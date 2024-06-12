<?php

namespace App\Jobs;

use App\Models\Post;
use App\Enums\PostStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PostScheduleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private Post $post)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('PostScheduleJob Started');
        $this->post->update([
            'status' => PostStatus::PUBLISHED,
            'published_at' => now(),
            'scheduled_for' => null,
        ]);
        Log::info('PostScheduleJob Ended');
    }
}
