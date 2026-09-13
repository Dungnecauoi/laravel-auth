<?php

namespace Duxbo\LaravelAuth\Console\Commands\Concerns;

use Illuminate\Filesystem\Filesystem;
use SplFileInfo;

trait CopiesFiles
{
    private function copyDirectory(Filesystem $files, string $from, string $to, bool $force): void
    {
        if (! $files->isDirectory($from)) {
            return;
        }

        foreach ($files->allFiles($from) as $file) {
            /** @var SplFileInfo $file */
            $relative = substr($file->getPathname(), strlen($from) + 1);
            $this->copyFile($files, $file->getPathname(), "{$to}/{$relative}", $force);
        }
    }

    private function copyFile(Filesystem $files, string $from, string $to, bool $force): void
    {
        if (! $files->exists($from)) {
            $this->components->twoColumnDetail($this->relative($to), '<fg=red>missing stub</>');

            return;
        }

        if ($files->exists($to) && ! $force) {
            $this->components->twoColumnDetail($this->relative($to), '<fg=yellow>SKIPPED (exists)</>');

            return;
        }

        $files->ensureDirectoryExists(dirname($to));
        $files->copy($from, $to);
        $this->components->twoColumnDetail($this->relative($to), '<fg=green>copied</>');
    }

    private function relative(string $path): string
    {
        return str_replace(base_path().'/', '', $path);
    }
}
