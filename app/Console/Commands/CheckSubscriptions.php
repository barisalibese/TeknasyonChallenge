<?php

namespace App\Console\Commands;

use App\Mocks\Google;
use App\Models\DeviceCredential;
use App\Models\Subscription;
use App\Services\CommandMethods\CheckSubscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CheckSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        CheckSubscription::initialize();
    }
}
