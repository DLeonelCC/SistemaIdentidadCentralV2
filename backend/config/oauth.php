<?php

return [
    'authorize_url' => env('PASSPORT_AUTHORIZE_URL', '/oauth/authorize'),
    'approve_url'   => env('PASSPORT_APPROVE_URL', '/oauth/approve'),
    'deny_url'      => env('PASSPORT_DENY_URL', '/oauth/deny'),
];