<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['id', 'uuId', 'appId', 'lang', 'os'];

    public function scopeCheckUid($query, $uId)
    {
        return $query->where('uuId', $uId)->exists();
    }
    public function subscription(){
        return $this->hasMany(Subscription::class,'device_id','id');
    }
}
