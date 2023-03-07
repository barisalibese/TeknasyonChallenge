<?php


namespace App\Services;


use App\Http\Requests\DevicePurchaseRequest;
use App\Mocks\Google;
use App\Models\Device;
use App\Models\DeviceCredential;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class DeviceService
{
    private int $deviceId;
    private string $deviceToken;

    public function register($data): Response
    {
        if (Device::checkUid($data['uId'])) {
            return new Response('uId Exists', 400);
        }
        if ($this->addDevice($data)) {
            if ($this->addDeviceCredential()) {
                return new Response(['token' => $this->deviceToken], 200);
            }
        }
        return new Response('Something Went Wrong When Trying To Register', 400);
    }

    private function addDevice($data): bool
    {
        try {
            $device = new Device();
            $device->uuId = $data['uId'];
            $device->appId = $data['appId'];
            $device->lang = $data['language'];
            $device->os = $data['operating-system'];
            $device->save();
            $this->deviceId = $device->id;
            return true;
        } catch (\Throwable $e) {
            info($e);
            return false;
        }
    }

    private function addDeviceCredential(): bool
    {
        try {
            $deviceCredential = new DeviceCredential();
            $deviceCredential->device_id = $this->deviceId;
            $deviceCredential->token = Hash::make(now());
            $deviceCredential->save();
            $this->deviceToken = $deviceCredential->token;
            return true;
        } catch (\Throwable $e) {
            info($e);
            return false;
        }
    }

    public function purchase(DevicePurchaseRequest $request): Response
    {
        Google::fake($request->all());
        $response = Http::get('www.google.com/purchase');
        if ($this->addSubscription($response->json(), $request->header('Client-Token'), $request->input('receipt'))) {
            return new Response($response->body(), $response->status());
        }
        return new Response(['errors' => 'receipt is already used'], 400);
    }

    private function addSubscription($data, $token, $receipt): bool
    {
        if (Subscription::where('receipt', $receipt)->exists()) {
            return false;
        }
        $subscription = new Subscription();
        $subscription->device_id = DeviceCredential::where('token', $token)->first()->device_id;
        $subscription->expire_date = $data['expire-date'];
        $subscription->status = $data['status'];
        $subscription->receipt = $receipt;
        $subscription->save();
        return true;
    }

    public function checkSubscription(Request $request)
    {
        return DeviceCredential::where('token', $request->header('Client-Token'))
            ->join('subscriptions','subscriptions.device_id','=','device_credentials.device_id')
            ->select('subscriptions.status','subscriptions.expire_date as expire-date')
            ->orderByDesc('subscriptions.id')->first();
    }
}
