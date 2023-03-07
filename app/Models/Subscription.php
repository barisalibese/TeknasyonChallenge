<?php

namespace App\Models;

use App\Jobs\SendNotification;
use App\Mocks\ThirdParty;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = ['device_id', 'receipt', 'expire_date', 'status'];
    const RENEWED = 'renewed';
    const CANCELED = 'canceled';
    const STARTED = 'started';

    public static function boot()
    {
        parent::boot();
        self::updated(function (Subscription $model) {
            if ($model->wasChanged('status')) {
                self::pushEvents($model);
            }
        });
        self::created(function (Subscription $model) {
            if (Subscription::where('device_id', $model->device_id)->count() > 1) {
                self::pushEvents($model);
            }
        });
    }

    public function device(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Device::class, 'device_id', 'id');
    }

    public static function pushEvents($model, $sendLater = false): \Illuminate\Foundation\Bus\PendingDispatch|\Illuminate\Foundation\Bus\PendingClosureDispatch|bool
    {
        if ($sendLater) {
            return dispatch(new SendNotification($model))->delay(now()->addMinutes(1));
        }
        $device = $model->device()->select('uuId as deviceId', 'appId')->first()->toArray();
        $payload = [...$device, ...['event' => $model->status]];
        ThirdParty::fake($payload);
        $response = Http::post('random-url/push-event', $payload);
        if ($response->status() == 200 || $response->status() == 201) {
            return true;
        }
        return self::pushEvents($model, true);
    }
}
