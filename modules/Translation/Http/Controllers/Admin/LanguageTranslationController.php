<?php

namespace Modules\Translation\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Modules\Translation\Entities\Translation;
use Modules\Translation\Http\Controllers\Requests\ImportTranslationRequest;

class LanguageTranslationController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function index(Request $request, string $locale)
    {
        $language = $locale;
        $locales = $locale && isset(supported_locales()[$locale])
            ? [$locale => supported_locales()[$locale]]
            : supported_locales();

        $translations = Translation::retrieve();

        $keys = array_keys($translations);

        return view('translation::admin.translations.index', compact('keys', 'locales', 'translations', 'language'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param string $key
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($locale, $key)
    {
        Translation::firstOrCreate(['key' => $key])
            ->translations()
            ->updateOrCreate(
                ['locale' => $locale],
                ['value' => request('value', '')]
            );

        return response()->json([
            'message' => trans('admin::messages.resource_updated', ['resource' => trans('translation::translations.translation')])
        ]);
    }

    public function import(ImportTranslationRequest $request, string $locale)
    {
        $file = $request->file('file');

        $translations = json_decode(file_get_contents($file), true);

        if (empty($translations)) {
            return redirect()->back()->with('error', __('Invalid or empty file.'));
        }

        $existingKeys = Translation::whereIn('key', array_keys($translations))
            ->pluck('id', 'key')
            ->toArray();

        $newTranslations = [];
        $updates = [];

        foreach ($translations as $key => $value) {
            if (isset($existingKeys[$key])) {
                $updates[] = [
                    'translation_id' => $existingKeys[$key],
                    'locale' => $locale,
                    'value' => $value ?? '',
                ];
            } else {
                $newTranslations[] = ['key' => $key];
            }
        }

        if (!empty($newTranslations)) {
            Translation::insert($newTranslations);

            $newKeys = Translation::whereIn('key', array_column($newTranslations, 'key'))
                ->pluck('id', 'key')
                ->toArray();
        }

        if (!empty($updates)) {
            DB::table('translation_translations')->upsert(
                array_map(fn($data) => [
                    'translation_id' => $data['translation_id'],
                    'locale' => $data['locale'],
                    'value' => $data['value'],
                ], $updates),
                ['translation_id', 'locale'],
                ['value']
            );
        }

        return redirect()->back()->with('success', __('Translations updated successfully.'));
    }

    public function export(string $locale)
    {
        $exports = [];
        $translations = Translation::retrieve();

        foreach ($translations as $key => $translation) {
            $exports[$key] = array_get($translation, $locale) ?? array_get($translation, 'en');
        }

        $fileName = $locale . '.json';
        $jsonContent = json_encode($exports, JSON_PRETTY_PRINT);

        return Response::make($jsonContent, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }
}
