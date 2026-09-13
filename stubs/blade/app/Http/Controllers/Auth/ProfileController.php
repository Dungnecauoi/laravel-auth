<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Duxbo\LaravelAuth\Actions\DeleteUser;
use Duxbo\LaravelAuth\Actions\UpdateUserProfileInformation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $sessions = collect();

        if (config('laravel-auth.features.session_management') && config('session.driver') === 'database') {
            $sessions = DB::table(config('session.table', 'sessions'))
                ->where('user_id', $request->user()->getKey())
                ->orderByDesc('last_activity')
                ->get()
                ->map(fn ($session) => (object) [
                    'id' => $session->id,
                    'is_current_device' => $session->id === $request->session()->getId(),
                    'ip_address' => $session->ip_address,
                    'user_agent' => $session->user_agent,
                    'last_active' => \Illuminate\Support\Carbon::createFromTimestamp($session->last_activity),
                ]);
        }

        return view('admin.profile.edit', [
            'user' => $request->user(),
            'sessions' => $sessions,
        ]);
    }

    public function update(Request $request, UpdateUserProfileInformation $updater): RedirectResponse
    {
        $updater->update($request->user(), $request->all());

        return back()->with('success', __('laravel-auth::laravel-auth.status.profile-updated'));
    }

    public function destroy(Request $request, DeleteUser $deleter): RedirectResponse
    {
        $request->validate(['password' => ['required', 'current_password:'.config('laravel-auth.guard', 'web')]]);

        $user = $request->user();

        Auth::guard(config('laravel-auth.guard'))->logout();
        $deleter->delete($user);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
