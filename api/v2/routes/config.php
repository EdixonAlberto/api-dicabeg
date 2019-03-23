<?php

/**
 * Setting routes
 */
$arrayPattern = [
    '/^\/([a-z]+)$/', // /route1
    '/^\/([a-z]+)\/([A-Z0-9-]{36})$/', // route1/id1
    '/^\/([a-z]+)\/([A-Z0-9-]*)\/([a-z]+)$/', // route1/id1/route2
    '/^\/([a-z]+)\/([A-Z0-9-]{36})\/([a-z]+)\/([A-Z0-9-]{36})$/' // /route1/id1/route2/id2
];

/**
 * Setting resources
 */

$arrayResources = [
    'users',
    'history',
    'referrals',
    'sessions',
    'videos'
];
