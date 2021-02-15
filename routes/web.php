<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home.home');
})->name('home');

Route::resource('post', 'PostController');

Route::group(['prefix' => 'post'], function () {
    Route::post('search', 'PostController@search')->name('post.search');
});

Route::get('category', function () {
    return view('category.category');
})->name('category');
