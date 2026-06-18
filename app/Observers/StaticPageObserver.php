<?php

namespace App\Observers;

use App\Models\StaticPage;
use App\Jobs\SubmitIndexNowJob;

class StaticPageObserver
{
    /**
     * Handle the StaticPage "created" event.
     */
    public function created(StaticPage $staticPage): void
    {
        $this->dispatchJob($staticPage);
    }

    /**
     * Handle the StaticPage "updated" event.
     */
    public function updated(StaticPage $staticPage): void
    {
        $this->dispatchJob($staticPage);
    }

    /**
     * Handle the StaticPage "deleted" event.
     */
    public function deleted(StaticPage $staticPage): void
    {
        $this->dispatchJob($staticPage);
    }

    /**
     * Dispatch the IndexNow submission job.
     */
    protected function dispatchJob(StaticPage $staticPage): void
    {
        $url = match ($staticPage->page_name) {
            'home' => url('/'),
            'tools' => url('/tools'),
            default => url('/' . $staticPage->page_name),
        };

        SubmitIndexNowJob::dispatch($url);
    }
}
