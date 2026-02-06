<?php

if (!function_exists('deskslip_version')) {
    function deskslip_version()
    {
        return config('deskslip.version');
    }
}