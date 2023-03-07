<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DevicePurchaseRequest;
use App\Http\Requests\DeviceRegisterRequest;
use App\Services\DeviceService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DeviceController extends Controller
{
    protected DeviceService $deviceService;

    public function __construct(DeviceService $deviceService)
    {
        $this->deviceService = $deviceService;
    }

    public function register(DeviceRegisterRequest $request): Response
    {
        $response = $this->deviceService->register($request->all());
        return new Response($response->content(), $response->status());
    }

    public function purchase(DevicePurchaseRequest $request): Response
    {
        $response = $this->deviceService->purchase($request);
        return new Response($response->content(), $response->status());
    }
    public function checkSubscription(Request $request): Response
    {
        return new Response($this->deviceService->checkSubscription($request), 200);
    }
}
