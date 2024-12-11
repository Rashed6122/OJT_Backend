<?php

namespace App\Console\Commands;

use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;



class ForceDeleteOldPosts extends Command
{
    protected $signature = 'posts:force-delete';

    protected $description = 'Force delete soft deleted posts older than 30 days';

    public function handle()
    {
        $thirtyDaysAgo = Carbon::now()->subDays(30);

        $deletedPostsCount = Post::onlyTrashed()
            ->where('deleted_at', '<', $thirtyDaysAgo)
            ->forceDelete();

        $this->info("Force deleted {$deletedPostsCount} posts older than 30 days.");
        Log::info("Force deleted {$deletedPostsCount} posts older than 30 days."); 
    }
}