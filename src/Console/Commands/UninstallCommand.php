<?php

namespace Duxbo\LaravelAuth\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

/**
 * Reverses `laravel-auth:install` as far as a package safely can on its own:
 * rolls back only its own migrations, and optionally removes published
 * config/views/lang. It can't safely edit your User model or composer.json
 * for you — those steps are printed at the end.
 */
class UninstallCommand extends Command
{
    protected $signature = 'laravel-auth:uninstall
                            {--purge : Also delete published config/views/lang files}
                            {--force : Skip the confirmation prompt}';

    protected $description = 'Roll back laravel-auth: drop its tables/columns and optionally remove published files';

    public function handle(): int
    {
        if (! $this->option('force') && ! $this->confirm(
            'This will DROP the roles/permissions/audit_log tables and the 2FA/ban columns on your users table. Continue?'
        )) {
            $this->components->warn('Aborted.');

            return self::FAILURE;
        }

        $this->components->task('Rolling back laravel-auth migrations', function () {
            $this->rollbackOwnMigrations();

            return true;
        });

        if ($this->option('purge')) {
            $this->purgePublishedFiles();
        }

        $this->components->info('laravel-auth data removed. Remaining manual steps:');
        $this->components->bulletList([
            'Remove HasRoles / HasPermissions / TwoFactorAuthenticatable from your User model.',
            'Remove LARAVEL_AUTH_* entries from your .env file.',
            'Run: composer remove duxbo/laravel-auth',
        ]);

        return self::SUCCESS;
    }

    /**
     * Deliberately does NOT use `migrate:rollback --path=...`:
     *
     * 1. `--path` must be relative to base_path(), but this package is
     *    commonly installed via a composer path repository (a symlink) —
     *    realpath() resolves straight through that symlink to a directory
     *    outside base_path() entirely, so no relative path can even
     *    express it without --realpath.
     * 2. More fundamentally, a plain `migrate:rollback` only rolls back
     *    the LATEST batch. If anything else migrated after this package
     *    did (a host-app migration, another package's — even Sanctum's,
     *    if it wasn't published+run until later), our own migrations
     *    aren't in that batch at all and never get touched, regardless of
     *    --path.
     *
     * Calling down() on each of our own migration files directly sidesteps
     * both problems — it doesn't care what batch anything is in.
     */
    protected function rollbackOwnMigrations(): void
    {
        $files = glob(__DIR__.'/../../../database/migrations/*.php');
        rsort($files);

        $table = config('database.migrations', 'migrations');
        $table = is_array($table) ? ($table['table'] ?? 'migrations') : $table;

        foreach ($files as $file) {
            $migration = require $file;

            if (is_object($migration) && method_exists($migration, 'down')) {
                $migration->down();
            }

            DB::table($table)->where('migration', basename($file, '.php'))->delete();
        }
    }

    protected function purgePublishedFiles(): void
    {
        $paths = [
            config_path('laravel-auth.php'),
            resource_path('views/vendor/laravel-auth'),
            $this->laravel->langPath('vendor/laravel-auth'),
        ];

        foreach ($paths as $path) {
            if (! File::exists($path)) {
                continue;
            }

            File::isDirectory($path) ? File::deleteDirectory($path) : File::delete($path);
            $this->components->info("Removed {$path}");
        }
    }
}
