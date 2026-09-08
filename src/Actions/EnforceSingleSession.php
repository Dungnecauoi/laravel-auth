<?php

namespace Duxbo\LaravelAuth\Actions;

use Illuminate\Support\Facades\DB;

/**
 * Called right after a login truly completes (no 2FA pending) in every
 * channel's session/token controller. Not baked into AttemptToAuthenticate
 * itself because "final login" happens at a different point per channel —
 * immediately for a no-2FA login, only after the 2FA challenge otherwise.
 */
class EnforceSingleSession
{
    public function forUser($user, ?string $exceptSessionId = null): void
    {
        if (! config('laravel-auth.features.single_session')) {
            return;
        }

        if (method_exists($user, 'tokens')) {
            $user->tokens()->delete();
        }

        if (config('session.driver') === 'database') {
            DB::table(config('session.table', 'sessions'))
                ->where('user_id', $user->getKey())
                ->when($exceptSessionId, fn ($query) => $query->where('id', '!=', $exceptSessionId))
                ->delete();
        }
    }
}
