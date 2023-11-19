<?php

namespace Eduka\Nova\Commands;

use Eduka\Cube\Models\VideoStorage;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DiskFileCleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eduka-nova:disk-cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command cleans up all the video files from the temporary server (local) disk.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $videoFiles = VideoStorage::whereNotNull('vimeo_id')->whereNotNull('backblaze_id')->get();

        foreach ($videoFiles as $videoFile) {
            try {
                Storage::delete($videoFile->path_on_disk);

                Log::info(sprintf("Video file ( from path '%s' ) for video id %s deleted.", $videoFile->path_on_disk, $videoFile->video_id));
            } catch (Exception $e) {
                Log::error('could_not_delete_file_during_automatic_cleanup', [
                    'file_path' => $videoFile->path_on_disk,
                    'video_id' => $videoFile->video_id,
                    'exception_message' => $e->getMessage(),
                ]);
            }
        }
    }
}
