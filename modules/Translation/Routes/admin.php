<?php

use Illuminate\Support\Facades\Route;

Route::get('languages', [
    'uses' => 'LanguageController@index',
    'as' => 'admin.languages.index',
    'middleware' => 'can:admin.languages.index',
]);

Route::get('languages/add', [
    'uses' => 'LanguageController@add',
    'as' => 'admin.languages.add',
    'middleware' => 'can:admin.languages.add',
]);

Route::post('languages', [
    'uses' => 'LanguageController@store',
    'as' => 'admin.languages.store',
    'middleware' => 'can:admin.languages.add',
]);

Route::post('languages/make-default', [
    'uses' => 'LanguageController@makeDefault',
    'as' => 'admin.languages.make_default',
    'middleware' => 'can:admin.languages.add',
]);

Route::delete('languages/{locale}', [
    'uses' => 'LanguageController@destroy',
    'as' => 'admin.languages.destroy',
    'middleware' => 'can:admin.languages.index',
]);

Route::get('languages/{locale}/translations', [
    'uses' => 'LanguageTranslationController@index',
    'as' => 'admin.language.translations.index',
    'middleware' => 'can:admin.translations.index',
]);

Route::post('languages/{locale}/translations/import', [
    'uses' => 'LanguageTranslationController@import',
    'as' => 'admin.language.translations.import',
    'middleware' => 'can:admin.translations.index',
]);

Route::get('languages/{locale}/translations/export', [
    'uses' => 'LanguageTranslationController@export',
    'as' => 'admin.language.translations.export',
    'middleware' => 'can:admin.translations.index',
]);

Route::put('languages/{locale}/translations/{key}', [
    'uses' => 'LanguageTranslationController@update',
    'as' => 'admin.language.translations.update',
    'middleware' => 'can:admin.translations.edit',
]);

