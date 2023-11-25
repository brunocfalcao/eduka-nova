<?php

namespace Eduka\Nova\Jobs;

use Eduka\Nova\Tasks\HandleBackblazeUploadTask;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UploadToBackblazeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private int $videoStorageId)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $notificationRecipients = [env('ADMIN_EMAIL')];

        (new HandleBackblazeUploadTask)->handle($this->videoStorageId, $notificationRecipients);
    }
}
