<?php

namespace App\Jobs;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private Subscription $subscription;

    /**
     * Create a new job instance.
     * @param $model
     */
    public function __construct($model)
    {
        $this->subscription=$model;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Subscription::pushEvents($this->subscription);
    }
}
