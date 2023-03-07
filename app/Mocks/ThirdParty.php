<?php


namespace App\Mocks;


use App\Models\Subscription;
use Illuminate\Support\Facades\Http;

class ThirdParty implements Mock
{

    public static function fake($data): void
    {
        Http::fake(['random-url/push-event' => Http::response($data),

        ]);

    }
}
