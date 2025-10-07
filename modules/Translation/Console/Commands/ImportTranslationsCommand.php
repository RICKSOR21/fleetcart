<?php

namespace Modules\Translation\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ImportTranslationsCommand extends Command
{
    protected $signature = 'translation:import {locale} {file}';

    protected $description = 'Import translations from JSON file';

    public function handle(): void
    {
        $this->info('Importing translations...');

        $this->importTranslations();

        $this->info('Translations imported successfully.');
    }

    private function importTranslations()
    {
        $fs = new Filesystem();
        $file = $this->argument('file');
        $locale = $this->argument('locale');
        $translations = json_decode(file_get_contents($file), true);

        foreach ($translations as $moduleName => $moduleFiles) {
            $moduleLangPath = base_path("modules/{$moduleName}/Resources/lang/${locale}");

            foreach ($moduleFiles as $fileName => $fileTranslations) {
                $moduleFilePath = "{$moduleLangPath}/{$fileName}.php";

                $original = require base_path("modules/{$moduleName}/Resources/lang/en/{$fileName}.php");
                $errors = $this->verifyTranslationStructure($original, $fileTranslations);

                if (!empty($errors)) {
                    dd($errors);
                }

                $phpContent = "<?php\n\nreturn " . self::arrayToPhpSyntax($fileTranslations) . ";\n";

                $fs->put($moduleFilePath, $phpContent);

                $this->info("Unpacked: {$moduleFilePath}");
            }
        }
    }

    public static function arrayToPhpSyntax($array, $indent = 0)
    {
        $spaces = str_repeat("    ", $indent);
        $output = "[\n";

        foreach ($array as $key => $value) {
            $output .= $spaces . "    " . var_export($key, true) . " => ";
            if (is_array($value)) {
                $output .= self::arrayToPhpSyntax($value, $indent + 1);
            } else {
                $output .= var_export($value, true);
            }
            $output .= ",\n";
        }

        $output .= $spaces . "]";
        return $output;
    }

    private function verifyTranslationStructure(array $original, array $translated, string $parentKey = ''): array
    {
        $errors = [];

        $originalKeys = array_keys($original);
        $translatedKeys = array_keys($translated);

        $missingKeys = array_diff($originalKeys, $translatedKeys);
        $extraKeys = array_diff($translatedKeys, $originalKeys);

        foreach ($missingKeys as $key) {
            $errors[] = "Missing key: " . ($parentKey ? "$parentKey.$key" : $key);
        }

        foreach ($extraKeys as $key) {
            $errors[] = "Extra key: " . ($parentKey ? "$parentKey.$key" : $key);
        }

        foreach ($original as $key => $value) {
            $fullKey = $parentKey ? "$parentKey.$key" : $key;

            if (is_array($value)) {
                if (!isset($translated[$key]) || !is_array($translated[$key])) {
                    $errors[] = "Structure mismatch for key: $fullKey";
                } else {
                    $errors = array_merge($errors, $this->verifyTranslationStructure($value, $translated[$key], $fullKey));
                }
            } else {
                if (!isset($translated[$key]) || !is_string($translated[$key])) {
                    $errors[] = "Value mismatch for key: $fullKey";
                } else {
                    $originalPlaceholders = $this->getPlaceholders($value);
                    $translatedPlaceholders = $this->getPlaceholders($translated[$key]);

                    $missingPlaceholders = array_diff($originalPlaceholders, $translatedPlaceholders);
                    $extraPlaceholders = array_diff($translatedPlaceholders, $originalPlaceholders);

                    foreach ($missingPlaceholders as $placeholder) {
                        $errors[] = "Missing placeholder '$placeholder' in key: $fullKey";
                    }

                    foreach ($extraPlaceholders as $placeholder) {
                        $errors[] = "Extra placeholder '$placeholder' in key: $fullKey";
                    }
                }
            }
        }

        return $errors;
    }

    private function getPlaceholders(string $text): array
    {
        preg_match_all('/:\w+/', $text, $matches);
        return $matches[0] ?? [];
    }
}
