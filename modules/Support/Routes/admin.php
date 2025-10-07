<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

Route::get('sitemaps', [
    'as' => 'admin.sitemaps.create',
    'uses' => 'SitemapController@create',
]);


Route::post('sitemaps', [
    'as' => 'admin.sitemaps.store',
    'uses' => 'SitemapController@store',
]);


Route::get('clear-cache', function () {
    try {
        Artisan::call('optimize:clear');

        return redirect()->back()->with('success', trans('support::clear_cache.clear_cache_success'));
    } catch (\Exception $e) {
        Log::error('Cache clear failed: ' . $e->getMessage());

        return redirect()->back()->with('error', trans('support::clear_cache.clear_cache_error') . $e->getMessage());
    }
})->name('admin.clear_cache.all');
