<?php

use Illuminate\Support\Facades\Route;

Route::get('compare', 'CompareController@index')->name('compare.index');
Route::get('compare/list', 'CompareController@list')->name('compare.list');
Route::get('compare/products', 'CompareController@products')->name('compare.products');
Route::post('compare', 'CompareController@store')->name('compare.store');
Route::delete('compare/{productId}', 'CompareController@destroy')->name('compare.destroy');

Route::get('compare/related-products', 'CompareRelatedProductController@index')->name('compare.related_products.index');
