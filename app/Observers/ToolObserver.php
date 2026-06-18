<?php

namespace App\Observers;

use App\Models\Tool;
use App\Jobs\SubmitIndexNowJob;

class ToolObserver
{
    /**
     * Handle the Tool "created" event.
     */
    public function created(Tool $tool): void
    {
        $this->dispatchJob($tool);
    }

    /**
     * Handle the Tool "updated" event.
     */
    public function updated(Tool $tool): void
    {
        $this->dispatchJob($tool);
    }

    /**
     * Handle the Tool "deleted" event.
     */
    public function deleted(Tool $tool): void
    {
        $this->dispatchJob($tool);
    }

    /**
     * Dispatch the IndexNow submission job.
     */
    protected function dispatchJob(Tool $tool): void
    {
        $url = url('/tools/' . $tool->page_name);
        SubmitIndexNowJob::dispatch($url);
    }
}
