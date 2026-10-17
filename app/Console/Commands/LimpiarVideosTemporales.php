<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class LimpiarVideosTemporales extends Command
{
    protected $signature = 'videos:limpiar-temporales';
    protected $description = 'Borra videos temporales abandonados (más de 24 horas)';

    public function handle(): int
    {
        $dir = storage_path('app/tmp_videos');
        $borrados = 0;
        foreach (glob($dir . '/*.*') ?: [] as $f) {
            if (is_file($f) && basename($f) !== '.gitignore' && filemtime($f) < time() - 86400) {
                @unlink($f);
                $borrados++;
            }
        }
        $this->info("Videos temporales eliminados: $borrados");
        return self::SUCCESS;
    }
}
