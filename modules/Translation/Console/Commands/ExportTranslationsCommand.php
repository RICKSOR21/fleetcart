<?php

namespace Modules\Translation\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ExportTranslationsCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'translation:export {locale}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import translations from JSON file';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->info('Exporting translations...');

        $this->exportTranslations();

        $this->info('Translations exported successfully.');
    }

    /**
     * Import translations from JSON file.
     *
     * @return void
     */
    private function exportTranslations()
    {
        $locale = $this->argument('locale');
        $fs = new Filesystem();
        $translations = [];

        foreach (glob(base_path('modules/*/Resources/lang/' . $locale)) as $moduleLangPath) {
            $moduleName = basename(dirname($moduleLangPath, 3));

            foreach ($fs->files($moduleLangPath) as $file) {
                $fileName = pathinfo($file, PATHINFO_FILENAME);
                $translations[$moduleName][$fileName] = require $file;
            }
        }

        // Ensure storage directory exists
        $path = storage_path("packed_languages");
        if (!$fs->exists($path)) {
            $fs->makeDirectory($path, 0755, true, true);
        }

        // Write to JSON file
        $outputPath = $path . "/{$locale}.json";
        $fs->put($outputPath, json_encode($translations, JSON_PRETTY_PRINT));
    }
}
