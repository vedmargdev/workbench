<?php

namespace Workbench\OfflineTest\Observers;
use Workbench\OfflineTest\Models\OfflineTestMark;
use Illuminate\Support\Facades\Cache;

class OfflineTestMarksObserver
{
   /**
     * Handle the OfflineTestMark "created" event.
     */
    public function created(OfflineTestMark $offlineTestMark): void
    {
       $this->clearCache();
    }

    /**
     * Handle the OfflineTestMark "updated" event.
     */
    public function updated(OfflineTestMark $offlineTestMark): void
    {
       $this->clearCache();
    }

    /**
     * Handle the OfflineTestMark "deleted" event.
     */
    public function deleted(OfflineTestMark $offlineTestMark): void
    {
      $this->clearCache();
    }

    /**
     * Handle the OfflineTestMark "restored" event.
     */
    public function restored(OfflineTestMark $offlineTestMark): void
    {
      $this->clearCache();
    }

    /**
     * Handle the OfflineTestMark "force deleted" event.
     */
    public function forceDeleted(OfflineTestMark $offlineTestMark): void
    {
       $this->clearCache();
    }

    public function clearCache(){
        $prefix = env('CACHE_PREFIX');
        $id = getCurrentSessionId();
        $user_id = getUserId();
        $keys = [];
        
        $name = config('cache_keys')['user_cache']['USER_OFFLINE_TEST'];
        $keys[] = $prefix . $name . $user_id;

        $name = config('cache_keys')['user_cache']['USER_OFFLINE_TEST'];
        $keys[] = $prefix . $name . $user_id . "_$id";

        $name = config('cache_keys')['user_cache']['USER_OFFLINE_TEST_TRASHED'];
        $keys[] = $prefix . $name . $user_id;

        $name = config('cache_keys')['user_cache']['USER_OFFLINE_TEST_TRASHED'];
        $keys[] = $prefix . $name . $user_id . "_$id";

        $name = config('cache_keys')['user_cache']['USER_OFFLINE_TEST_MARKS'];
        $keys[] = $prefix . $name . $user_id;

        $name = config('cache_keys')['user_cache']['USER_OFFLINE_TEST_MARKS'];
        $keys[] = $prefix . $name . $user_id . "_$id";

        $name = config('cache_keys')['user_cache']['USER_OFFLINE_TEST_MARKS_TRASHED'];
        $keys[] = $prefix . $name . $user_id;

        $name = config('cache_keys')['user_cache']['USER_OFFLINE_TEST_MARKS_TRASHED'];
        $keys[] = $prefix . $name . $user_id . "_$id";

        foreach( $keys as $key ) {
            Cache::forget($key);
        }
    }
}
