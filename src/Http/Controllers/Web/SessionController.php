<?php

namespace Duxbo\LaravelAuth\Http\Controllers\Web;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SessionController
{
    public function destroy(Request $request, string $id): RedirectResponse
    {
        if (config('session.driver') === 'database') {
            DB::table(config('session.table', 'sessions'))
                ->where('id', $id)
                ->where('user_id', $request->user()->getKey())
                ->delete();
        }

        return back()->with('status', 'session-revoked');
    }

    public function destroyOthers(Request $request): RedirectResponse
    {
        // Invalidate "remember me" cookies on every other device...
        $request->user()->forceFill(['remember_token' => Str::random(60)])->save();

        // ...and drop every other active session row, if trackable.
        if (config('session.driver') === 'database') {
            DB::table(config('session.table', 'sessions'))
                ->where('user_id', $request->user()->getKey())
                ->where('id', '!=', $request->session()->getId())
                ->delete();
        }

        return back()->with('status', 'other-sessions-revoked');
    }
}
