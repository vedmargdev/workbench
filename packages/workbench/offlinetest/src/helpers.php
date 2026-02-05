<?php

use Illuminate\Support\Facades\Cache;
use Workbench\OfflineTest\Models\OfflineTest;
use Workbench\OfflineTest\Models\OfflineTestMark;


if (!function_exists('asset_url')) {
    function asset_url($path = '')
    {
        return asset($path);
    }
}

if (!function_exists('_template')) {
    function _template(string $view): string
    {
        return "offlinetest::{$view}";
    }
}

if (!function_exists('_user_app')) {
    function _user_app()
    {
        return 'offlinetest::template.user.app.app';
    }
}

function asset_url( $url = '' ) {
		$aws_url = config('app.aws_url');
		$aws_url .= '/' . $url;
		return $aws_url;
	}


	function cdn_url( $url = '' ) {
		$cloud_front_url = config('app.cloud_front_url');
		$cloud_front_url .= '/' . $url;
		return $cloud_front_url;
	}

if (!function_exists('getUserId')) {
    function getUserId()
    {
        return Auth::id();
    }
}   
if (!function_exists('getCurrentSession')) {
    function getCurrentSession()
    {
      
        return session('current_session');
    }
}

if (!function_exists('getCurrentSessionId')) {
    function getCurrentSessionId()
    {
        return session('current_session_id');
    }
}

if (!function_exists('getOfflineTest')) {
    function getOfflineTest()
    {
        $prefix  = env('CACHE_PREFIX', '');
        $user_id = getUserId();
        $session = getCurrentSession();
        $id      = getCurrentSessionId();

        $name = config('cache_keys.user_cache.USER_OFFLINE_TEST');
        $key  = $prefix . $name . $user_id . "_$id";

        return Cache::rememberForever($key, function () use ($user_id, $session) {
            return OfflineTest::with([
                'created_by_user:id,uuid,first_name,last_name,name'
            ])
            ->where('user_id', $user_id)
            ->where('session', $session)
            ->orderBy('id', 'ASC')
            ->get();
        });
    }
}

if (!function_exists('getOfflineTestTrashed')) {
    function getOfflineTestTrashed()
    {
        $prefix  = env('CACHE_PREFIX', '');
        $user_id = getUserId();
        $session = getCurrentSession();
        $id      = getCurrentSessionId();

        $name = config('cache_keys.user_cache.USER_OFFLINE_TEST_TRASHED');
        $key  = $prefix . $name . $user_id . "_$id";

        return Cache::rememberForever($key, function () use ($user_id, $session) {
            return OfflineTest::onlyTrashed()
                ->where('user_id', $user_id)
                ->where('session', $session)
                ->orderBy('id', 'ASC')
                ->get();
        });
    }
}

if (!function_exists('getOfflineTestMarks')) {
    function getOfflineTestMarks()
    {
        $prefix  = env('CACHE_PREFIX', '');
        $user_id = getUserId();
        $session = getCurrentSession();
        $id      = getCurrentSessionId();

        $name = config('cache_keys.user_cache.USER_OFFLINE_TEST_MARKS');
        $key  = $prefix . $name . $user_id . "_$id";

        return Cache::rememberForever($key, function () use ($user_id, $session) {
            return OfflineTestMark::with([
                'created_by_user:id,uuid,first_name,last_name,name'
            ])
            ->where('user_id', $user_id)
            ->where('session', $session)
            ->orderBy('id', 'ASC')
            ->get();
        });
    }
}

if (!function_exists('getOfflineTestMarksTrashed')) {
    function getOfflineTestMarksTrashed()
    {
        $prefix  = env('CACHE_PREFIX', '');
        $user_id = getUserId();
        $session = getCurrentSession();
        $id      = getCurrentSessionId();

        $name = config('cache_keys.user_cache.USER_OFFLINE_TEST_MARKS_TRASHED');
        $key  = $prefix . $name . $user_id . "_$id";

        return Cache::rememberForever($key, function () use ($user_id, $session) {
            return OfflineTestMark::onlyTrashed()
                ->where('user_id', $user_id)
                ->where('session', $session)
                ->orderBy('id', 'ASC')
                ->get();
        });
    }
}
