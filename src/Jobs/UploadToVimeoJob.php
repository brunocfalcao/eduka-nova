<?php

namespace Eduka\Nova\Jobs;

use Eduka\Nova\Actions\HandleVimeoUploadTask;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UploadToVimeoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private int $videoId)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $notificationRecipients = [env('ADMIN_EMAIL')];

        (new HandleVimeoUploadTask)->handle($this->videoId, $notificationRecipients);
    }
}
