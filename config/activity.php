<?php

return [
    /*
    |--------------------------------------------------------------------------
    | User Activity Tracking Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration options for the user activity tracking
    | system. You can adjust these values based on your application's needs.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Session Timeout
    |--------------------------------------------------------------------------
    |
    | The number of minutes of inactivity after which a session is considered
    | expired. If a user is inactive for longer than this period, their
    | session will be automatically ended and a new session will start
    | when they make their next request.
    |
    | Default: 30 minutes
    |
    */
    'session_timeout_minutes' => env('ACTIVITY_SESSION_TIMEOUT_MINUTES', 30),

    /*
    |--------------------------------------------------------------------------
    | Maximum Session Duration
    |--------------------------------------------------------------------------
    |
    | The maximum duration (in hours) that a single session can last.
    | Sessions longer than this will be automatically ended.
    |
    | Default: 8 hours
    |
    */
    'max_session_duration_hours' => env('ACTIVITY_MAX_SESSION_DURATION_HOURS', 8),

    /*
    |--------------------------------------------------------------------------
    | Cleanup Settings
    |--------------------------------------------------------------------------
    |
    | Settings for cleaning up old activity records to maintain performance.
    |
    */
    'cleanup' => [
        'enabled' => env('ACTIVITY_CLEANUP_ENABLED', true),
        'keep_days' => env('ACTIVITY_CLEANUP_KEEP_DAYS', 90),
    ],
];
