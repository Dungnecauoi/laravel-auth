<?php

namespace Duxbo\LaravelAuth\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Duxbo\LaravelAuth\Database\Seeders\AdminUserSeeder;

class InstallCommand extends Command
{
    protected $signature = 'laravel-auth:install
                            {--stack= : blade, inertia-react, inertia-vue or headless}
                            {--admin-email= : Seed a first super-admin user}
                            {--admin-password=}
                            {--admin-name=}
                            {--force : Run non-interactively, accepting defaults / skipping prompts}';

    protected $description = 'Install laravel-auth: pick a UI stack, publish config/views, migrate, sync route permissions, seed the first admin';

    protected const STACKS = ['blade', 'inertia-react', 'inertia-vue', 'headless'];

    public function handle(): int
    {
        $this->components->info('Installing laravel-auth...');

        $this->call('vendor:publish', ['--tag' => 'laravel-auth-config']);

        $stack = $this->selectStack();

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
    protected function selectStack(): string
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
            // "Render the views out" immediately — a Blade stack means real,
            // editable files under resources/views, not just whatever the
            // package quietly loads from vendor/ via loadViewsFrom().
            $this->call('vendor:publish', ['--tag' => 'laravel-auth-views', '--force' => true]);
        }

        return $stack;
    }

    protected function printNextSteps(string $stack): void
    {
        $steps = [
            'Add the HasRoles and HasPermissions traits (and TwoFactorAuthenticatable if enabled) to your User model.',
            'Review config/laravel-auth.php for feature toggles.',
        ];

        $steps[] = match ($stack) {
            'blade' => 'Blade views were published to resources/views/vendor/laravel-auth — edit them directly.',
            'inertia-react', 'inertia-vue' => 'Inertia routes/controllers are wired up, but the '
                .($stack === 'inertia-react' ? 'React' : 'Vue')
                .' page components (Auth/Login, Auth/Register, ...) aren\'t scaffolded yet — a starter kit for this stack is coming; build them yourself against routes/inertia.php in the meantime.',
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
