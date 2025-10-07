<?php

use Illuminate\Support\Facades\Route;
use Modules\Importer\Http\Controllers\Admin\ImportController;

Route::get('import', [ImportController::class, 'index'])
    ->name('admin.importer.index')
    ->middleware('can:admin.importer.import');

Route::post('import', [ImportController::class, 'store'])
    ->name('admin.importer.import')
    ->middleware('can:admin.importer.import');
