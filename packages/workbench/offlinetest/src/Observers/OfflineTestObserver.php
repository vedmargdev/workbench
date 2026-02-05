<?php

namespace Workbench\OfflineTest\Observers;

use Workbench\OfflineTest\Models\OfflineTest;
use Workbench\OfflineTest\Observers\OfflineTestMarksObserver;
use Illuminate\Support\Facades\Cache;

class OfflineTestObserver
{
     /**
     * Handle the OfflineTest "created" event.
     */
    public function created(OfflineTest $offlineTest): void
    {
        $this->clearCache();
    }

    /**
     * Handle the OfflineTest "updated" event.
     */
    public function updated(OfflineTest $offlineTest): void
    {
        $this->clearCache();
    }

    /**
     * Handle the OfflineTest "deleted" event.
     */
    public function deleted(OfflineTest $offlineTest): void
    {
        $this->clearCache();
    }

    /**
     * Handle the OfflineTest "restored" event.
     */
    public function restored(OfflineTest $offlineTest): void
    {
        $this->clearCache();
    }

    /**
     * Handle the OfflineTest "force deleted" event.
     */
    public function forceDeleted(OfflineTest $offlineTest): void
    {
        $this->clearCache();
    }

    public function clearCache() {

        $ob = new OfflineTestMarksObserver();
        $ob->clearCache();
    }
}
