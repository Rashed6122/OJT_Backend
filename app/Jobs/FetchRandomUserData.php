<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class FetchRandomUserData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public function handle()
    {
        $response = Http::get('https://randomuser.me/api/');
        if ($response->successful()) {
            Log::info('Random User Data:', $response->json()['results']);
        } else {
            Log::error('Error fetching random user data:', ['status_code' => $response->status()]);
        }
    }
}