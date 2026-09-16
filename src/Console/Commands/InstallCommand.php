<?php

namespace Duxbo\LaravelAuth\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Duxbo\LaravelAuth\Console\Commands\Concerns\CopiesFiles;
use Duxbo\LaravelAuth\Database\Seeders\AdminUserSeeder;

class InstallCommand extends Command
{
    use CopiesFiles;

    protected $signature = 'laravel-auth:install
                            {--stack= : blade, inertia-react, inertia-vue or headless}
                            {--ui= : UI library for the inertia-react stack (antd or shadcn) — mirrors react-kit:install --ui}
                            {--admin-email= : Seed a first super-admin user}
                            {--admin-password=}
                            {--admin-name=}
                            {--force : Run non-interactively, accepting defaults / skipping prompts}
                            {--views-force : When re-copying view/controller stubs, overwrite files that already exist}';

    protected $description = 'Install laravel-auth: pick a UI stack, publish config/views, migrate, sync route permissions, seed the first admin';

    protected const STACKS = ['blade', 'inertia-react', 'inertia-vue', 'headless'];

    public function handle(Filesystem $files): int
    {
        $this->components->info('Installing laravel-auth...');

        $this->call('vendor:publish', ['--tag' => 'laravel-auth-config']);

        $stack = $this->selectStack($files);

        // Sanctum v4 no longer auto-loads its own migrations (older versions
        // did) — without this, personal_access_tokens never exists and
        // anything touching $user->tokens() (API token issuance,
        // features.single_session's token revocation) fails outright.
        $this->call('vendor:publish', ['--tag' => 'sanctum-migrations']);

        $this->components->task('Running migrations', fn () => $this->call('migrate') === 0);

        $this->components->task(
            'Syncing route permissions',
            fn () => $this->call('laravel-auth:sync-permissions', ['--seed' => true]) === 0
        );

        $this->seedAdmin();

        $this->printNextSteps($stack);

        return self::SUCCESS;
    }

    /**
     * Mirrors the Breeze/Jetstream-style "which stack?" prompt. Only "blade"
     * and "headless" (API-only) are fully scaffolded today — inertia-react
     * and inertia-vue wire up the routing/config but still need their page
     * components built (a starter kit for each is planned).
     */
    protected function selectStack(Filesystem $files): string
    {
        $stack = $this->option('stack');

        if (! $stack && ! $this->option('force')) {
            $stack = $this->choice('Which UI stack should laravel-auth use?', self::STACKS, 0);
        }

        $stack = $stack ?: 'blade';

        if (! in_array($stack, self::STACKS, true)) {
            $this->components->error("Unknown stack [{$stack}]. Expected one of: ".implode(', ', self::STACKS));
            $stack = 'blade';
        }

        $frontend = match ($stack) {
            'inertia-react', 'inertia-vue' => 'inertia',
            'headless' => 'api',
            default => 'blade',
        };

        $this->setEnvValue('LARAVEL_AUTH_FRONTEND', $frontend);

        if (str_starts_with($stack, 'inertia-')) {
            $this->setEnvValue('LARAVEL_AUTH_INERTIA_STACK', Str::after($stack, 'inertia-'));
        }

        $this->components->info("Stack set to [{$stack}] (LARAVEL_AUTH_FRONTEND={$frontend} in .env)");

        if ($stack === 'blade') {
            if (! class_exists(\LaravelBladeKit\BladeKitServiceProvider::class)) {
                $this->components->warn(
                    'The blade stack uses duxbo/laravel-blade-kit\'s components/layout, which isn\'t installed. '
                    .'Run: composer require duxbo/laravel-blade-kit && php artisan blade-kit:install — then re-run this command.'
                );
            } else {
                $this->copyBladeStubs($files);
                $this->setEnvValue('LARAVEL_AUTH_REDIRECT_HOME', '/admin/dashboard');
            }
        }

        if ($stack === 'inertia-react') {
            if (! is_file(resource_path('js/Layouts/AdminLayout.tsx'))) {
                $this->components->warn(
                    'The inertia-react stack uses duxbo/laravel-react-kit\'s layouts/components, which aren\'t installed. '
                    .'Run: composer require duxbo/laravel-react-kit && php artisan react-kit:install — then re-run this command.'
                );
            } else {
                $this->copyInertiaReactStubs($files, $this->selectReactUiLibrary());
                $this->setEnvValue('LARAVEL_AUTH_REDIRECT_HOME', '/react/dashboard');
            }
        }

        return $stack;
    }

    /**
     * Mirrors react-kit:install's own --ui prompt exactly — this package
     * doesn't know which of the two variants react-kit was installed
     * with (both copy their Layouts to the same resource_path('js/Layouts')
     * destination, so the filesystem alone can't tell them apart), so it
     * has to ask again rather than guess.
     */
    protected function selectReactUiLibrary(): string
    {
        $ui = $this->option('ui');

        if (! $ui && ! $this->option('force')) {
            $ui = $this->choice('Which UI library is duxbo/laravel-react-kit installed with?', ['antd', 'shadcn'], 0);
        }

        return in_array($ui, ['antd', 'shadcn'], true) ? $ui : 'antd';
    }

    /**
     * This package owns its own UI: the views/controllers/routes below are
     * copied straight into the app, same as blade-kit copies its own
     * component stubs. They only *use* blade-kit's components/admin
     * layout — they don't live inside blade-kit anymore.
     */
    protected function copyBladeStubs(Filesystem $files): void
    {
        $stubs = dirname(__DIR__, 3).'/stubs/blade';
        $force = (bool) $this->option('views-force');

        $this->copyDirectory($files, "{$stubs}/app", app_path(), $force);
        $this->copyDirectory($files, "{$stubs}/resources/views", resource_path('views'), $force);
        $this->copyFile($files, "{$stubs}/routes/auth.php", base_path('routes/auth.php'), $force);
        $this->copyFile($files, "{$stubs}/routes/admin-auth.php", base_path('routes/admin-auth.php'), $force);

        $this->components->info(
            'Blade UI copied. Add to routes/web.php: require __DIR__.\'/auth.php\'; and require __DIR__.\'/admin-auth.php\';'
        );
    }

    /**
     * Same idea as copyBladeStubs(): this package owns its own UI, the
     * page components below are copied straight into the app, only
     * *using* react-kit's Layouts/components — they don't live inside
     * react-kit. routes/inertia.php itself stays package-owned (not
     * copied) since it doesn't render anything the app would need to
     * customize, only the .tsx pages do.
     */
    protected function copyInertiaReactStubs(Filesystem $files, string $ui): void
    {
        $stubs = dirname(__DIR__, 3)."/stubs/react-{$ui}";
        $force = (bool) $this->option('views-force');

        $this->copyDirectory($files, "{$stubs}/resources/js", resource_path('js'), $force);

        $this->components->info(
            "Inertia React UI ({$ui}) copied. Add to routes/web.php: require __DIR__.'/../vendor/duxbo/laravel-auth/routes/inertia.php'; "
            ."or simply leave it — it's loaded automatically based on config('laravel-auth.frontend')."
        );
    }

    protected function printNextSteps(string $stack): void
    {
        $steps = [
            'Add the HasRoles and HasPermissions traits (and TwoFactorAuthenticatable if enabled) to your User model.',
            'Review config/laravel-auth.php for feature toggles.',
        ];

        $steps[] = match ($stack) {
            'blade' => 'UI (routes/auth.php, routes/admin-auth.php, resources/views/admin/{auth,profile,users,roles,permissions}) has been copied into your app, using duxbo/laravel-blade-kit\'s components/layout — require both route files from routes/web.php.',
            'inertia-react' => 'UI (resources/js/Pages/Auth/{Login,Register,ForgotPassword,ResetPassword,TwoFactorChallenge,VerifyEmail}.tsx) has been copied into your app, using duxbo/laravel-react-kit\'s Layouts/components — routes/inertia.php loads automatically, nothing to require.',
            'inertia-vue' => 'Inertia routes/controllers are wired up, but the Vue page components (Auth/Login, Auth/Register, ...) aren\'t scaffolded yet — a starter kit for this stack is coming; build them yourself against routes/inertia.php in the meantime.',
            'headless' => 'No views to worry about — /api/login, /api/register etc. return JSON. Point your SPA/mobile client at them.',
        };

        $this->components->info('laravel-auth installed. Next steps:');
        $this->components->bulletList($steps);
    }

    /**
     * Delegates the actual creation to AdminUserSeeder so `db:seed
     * --class=...` and this command are guaranteed to do the same thing —
     * only where the email/password/name come from differs here (CLI
     * options, falling back to already-set config/.env, falling back to an
     * interactive prompt unless --force).
     */
    protected function seedAdmin(): void
    {
        $email = $this->option('admin-email') ?: config('laravel-auth.admin.email');

        if (! $email && ! $this->option('force')) {
            $email = $this->ask('Admin email (leave blank to skip)');
        }

        if (! $email) {
            return;
        }

        $password = $this->option('admin-password')
            ?: config('laravel-auth.admin.password')
            ?: ($this->option('force') ? null : $this->secret('Admin password'));

        $name = $this->option('admin-name')
            ?: config('laravel-auth.admin.name')
            ?: ($this->option('force') ? 'Administrator' : $this->ask('Admin name', 'Administrator'));

        config([
            'laravel-auth.admin.email' => $email,
            'laravel-auth.admin.password' => $password,
            'laravel-auth.admin.name' => $name,
        ]);

        $this->call('db:seed', ['--class' => AdminUserSeeder::class, '--force' => true]);

        $this->components->info("Admin user ready: {$email}");
    }

    protected function setEnvValue(string $key, string $value): void
    {
        $path = base_path('.env');

        if (! file_exists($path)) {
            return;
        }

        $contents = file_get_contents($path);

        $contents = preg_match("/^{$key}=/m", $contents)
            ? preg_replace("/^{$key}=.*/m", "{$key}={$value}", $contents)
            : rtrim($contents)."\n{$key}={$value}\n";

        file_put_contents($path, $contents);
    }
}
