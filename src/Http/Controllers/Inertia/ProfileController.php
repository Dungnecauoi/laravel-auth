<?php

namespace Duxbo\LaravelAuth\Http\Controllers\Inertia;

use Duxbo\LaravelAuth\Actions\DeleteUser;
use Duxbo\LaravelAuth\Actions\UpdateUserProfileInformation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController
{
    public function edit(Request $request): Response
    {
        $user = $request->user();
        $twoFactorEnabled = config('laravel-auth.features.two_factor') && method_exists($user, 'twoFactorEnabled');

        return Inertia::render('Profile/Edit', [
            'user' => ['name' => $user->name, 'email' => $user->email],
            'emailVerified' => ! config('laravel-auth.features.email_verification')
                || ! method_exists($user, 'hasVerifiedEmail')
                || $user->hasVerifiedEmail(),
            'sessions' => $this->sessions($request),
            'twoFactorEnabled' => $twoFactorEnabled && $user->twoFactorEnabled(),
            'twoFactorPending' => $twoFactorEnabled && $user->two_factor_secret && ! $user->twoFactorEnabled(),
            'twoFactorQrCodeSvg' => ($twoFactorEnabled && $user->two_factor_secret && ! $user->twoFactorEnabled())
                ? $user->twoFactorQrCodeSvg()
                : null,
            'recoveryCodes' => $request->session()->get('recovery_codes'),
            'status' => $request->session()->get('success'),
            'twoFactorAvailable' => (bool) config('laravel-auth.features.two_factor'),
            'sessionManagementAvailable' => (bool) config('laravel-auth.features.session_management'),
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

    /** @return list<array{id: string, is_current_device: bool, ip_address: ?string, user_agent: ?string, last_active: string}> */
    private function sessions(Request $request): array
    {
        if (! config('laravel-auth.features.session_management') || config('session.driver') !== 'database') {
            return [];
        }

        return DB::table(config('session.table', 'sessions'))
            ->where('user_id', $request->user()->getKey())
            ->orderByDesc('last_activity')
            ->get()
            ->map(fn ($session) => [
                'id' => $session->id,
                'is_current_device' => $session->id === $request->session()->getId(),
                'ip_address' => $session->ip_address,
                'user_agent' => $session->user_agent,
                'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
            ])
            ->all();
    }
}
