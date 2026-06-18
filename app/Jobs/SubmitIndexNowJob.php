<?php

namespace App\Jobs;

use App\Services\IndexNowService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SubmitIndexNowJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Create a new job instance.
     *
     * @param array|string $urls
     */
    public function __construct(
        protected array|string $urls
    ) {}

    /**
     * Execute the job.
     */
    public function handle(IndexNowService $indexNowService): void
    {
        $urls = is_array($this->urls) ? $this->urls : [$this->urls];
        
        $indexNowService->submitUrls($urls);
    }
}
