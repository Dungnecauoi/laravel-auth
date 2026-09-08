<?php

namespace Duxbo\LaravelAuth\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Routing\Route;

/**
 * Dispatched by `laravel-auth:sync-permissions` for every registered route
 * that has neither a `can:`/`permission:` middleware nor a matching entry
 * in config('laravel-auth.permissions.ignored_routes'). Listen for this in
 * your app to, e.g., post a warning to Slack in CI.
 */
class RouteMissingPermission
{
    use Dispatchable;

    public function __construct(public Route $route)
    {
    }
}
