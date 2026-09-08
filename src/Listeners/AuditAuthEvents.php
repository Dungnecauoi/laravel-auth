<?php

namespace Duxbo\LaravelAuth\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Events\Dispatcher;
use Duxbo\LaravelAuth\Models\AuditLog;

/**
 * Rides entirely on Laravel's built-in auth events — no custom events are
 * dispatched for these cases, so any other package/listener already hooked
 * into the native events keeps working unmodified.
 */
class AuditAuthEvents
{
    public function handleLogin(Login $event): void
    {
        $this->log('login', $event->user);
    }

    public function handleLogout(Logout $event): void
    {
        $this->log('logout', $event->user);
    }

    public function handleFailed(Failed $event): void
    {
        $this->log('failed', $event->user, [
            'email' => $event->credentials['email'] ?? null,
        ]);
    }

    public function handleRegistered(Registered $event): void
    {
        $this->log('registered', $event->user);
    }

    public function handlePasswordReset(PasswordReset $event): void
    {
        $this->log('password_reset', $event->user);
    }

    public function handleVerified(Verified $event): void
    {
        $this->log('verified', $event->user);
    }

    protected function log(string $event, $user, array $meta = []): void
    {
        AuditLog::create([
            'user_id' => $user?->getAuthIdentifier(),
            'event' => $event,
            'ip_address' => request()->ip(),
            'user_agent' => substr((string) request()->userAgent(), 0, 255),
            'meta' => $meta ?: null,
            'created_at' => now(),
        ]);
    }

    public function subscribe(Dispatcher $events): array
    {
        return [
            Login::class => 'handleLogin',
            Logout::class => 'handleLogout',
            Failed::class => 'handleFailed',
            Registered::class => 'handleRegistered',
            PasswordReset::class => 'handlePasswordReset',
            Verified::class => 'handleVerified',
        ];
    }
}
