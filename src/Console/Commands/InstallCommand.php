<?php

namespace Duxbo\LaravelAuth\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
    protected $signature = 'laravel-auth:install
                            {--frontend= : blade, api, inertia or hybrid}
                            {--admin-email= : Seed a first super-admin user}
                            {--admin-password=}
                            {--admin-name=}
                            {--force : Run non-interactively, accepting defaults / skipping prompts}';

    protected $description = 'Install laravel-auth: publish config, migrate, sync route permissions, seed the first admin';

    public function handle(): int
    {
        $this->components->info('Installing laravel-auth...');

        $this->call('vendor:publish', ['--tag' => 'laravel-auth-config']);

        $this->selectFrontend();

        $this->components->task('Running migrations', fn () => $this->call('migrate') === 0);

        $this->components->task(
            'Syncing route permissions',
            fn () => $this->call('laravel-auth:sync-permissions', ['--seed' => true]) === 0
        );

        $this->seedAdmin();

        $this->components->info('laravel-auth installed. Next steps:');
        $this->components->bulletList([
            'Add the HasRoles and HasPermissions traits (and TwoFactorAuthenticatable if enabled) to your User model.',
            'Review config/laravel-auth.php for feature toggles.',
            in_array(config('laravel-auth.frontend'), ['inertia', 'hybrid'], true)
                ? 'Run npm install && npm run build for the Inertia frontend.'
                : 'Publish views with: php artisan vendor:publish --tag=laravel-auth-views',
        ]);

        return self::SUCCESS;
    }

    protected function selectFrontend(): void
    {
        $frontend = $this->option('frontend');

        if (! $frontend && ! $this->option('force')) {
            $frontend = $this->choice(
                'Which frontend flavor should laravel-auth use?',
                ['blade', 'api', 'inertia', 'hybrid'],
                0
            );
        }

        if ($frontend) {
            $this->setEnvValue('LARAVEL_AUTH_FRONTEND', $frontend);
            $this->components->info("Frontend flavor set to [{$frontend}] in .env");
        }
    }

    protected function seedAdmin(): void
    {
        $email = $this->option('admin-email')
            ?: ($this->option('force') ? null : $this->ask('Admin email (leave blank to skip)'));

        if (! $email) {
            return;
        }

        $password = $this->option('admin-password') ?: ($this->secret('Admin password') ?: Str::random(16));
        $name = $this->option('admin-name') ?: $this->ask('Admin name', 'Administrator');

        $userModel = config('laravel-auth.user_model');

        $user = $userModel::firstOrCreate(
            ['email' => $email],
            ['name' => $name, 'password' => Hash::make($password), 'email_verified_at' => now()]
        );

        if (method_exists($user, 'assignRole')) {
            $user->assignRole(...config('laravel-auth.permissions.super_admin_roles', ['super-admin']));
        }

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
