<?php

namespace Modules\Translation\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Modules\Support\Locale;
use Modules\Translation\Http\Controllers\Requests\AddLanguageRequest;

class LanguageController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|object
     */
    public function index(Request $request)
    {
        $languages = supported_locales();
        $languages = collect($languages)
            ->map(function ($locale, $key) {
                return [
                    'key' => $key,
                    'name' => $locale['name'],
                    'is_default' => $key === setting('default_locale'),
                ];
            })
            ->values()
            ->all();

        if ($request->wantsJson()) {
            return response()->json($languages);
        }

        return view("translation::admin.languages.index", compact('languages'));
    }

    public function add()
    {
        $locales = collect(Locale::all())
            ->except(array_keys(supported_locales()));

        return view("translation::admin.languages.add", compact('locales'));
    }

    public function store(AddLanguageRequest $request)
    {
        $supportedLocales = setting('supported_locales');

        array_push($supportedLocales, $request->input('language'));

        setting(['supported_locales' => $supportedLocales]);

        return redirect()->route('admin.languages.index');
    }

    public function makeDefault(Request $request)
    {
        if (!in_array($request->input('language'), array_keys(supported_locales()))) {
            return response()->json([
                'message' => 'Language is not supported',
            ], 400);
        }

        setting(['default_locale' => $request->input('language')]);

        $languages = collect(supported_locales())
            ->map(function ($locale, $key) use ($request) {
                return [
                    'key' => $key,
                    'name' => $locale['name'],
                    'is_default' => $key === $request->input('language'),
                ];
            })
            ->values()
            ->all();

        return response()->json($languages);
    }

    public function destroy($locale)
    {
        if ($locale == setting('default_locale')) {
            return response()->json([
                'message' => "You can't delete the default language",
            ], 400);
        }

        $newLocales = array_values(array_filter(setting('supported_locales'), function ($value) use ($locale) {
            return $value !== $locale;
        }));

        setting(['supported_locales' => $newLocales]);

        $languages = collect(supported_locales())
            ->reject(function ($language, $key) use ($locale) {
                return $key === $locale;
            })
            ->map(function ($language, $key) {
                return [
                    'key' => $key,
                    'name' => $language['name'],
                    'is_default' => $key === setting('default_locale'),
                ];
            })
            ->values()
            ->all();

        return response()->json($languages);
    }
}
