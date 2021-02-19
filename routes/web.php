<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home.home');
})->name('home');

Route::resource('post', 'PostController');
Route::group(['prefix' => 'post'], function () {
    Route::post('search', 'PostController@search')->name('post.search');
});

Route::resource('category', 'CategoryController');
Route::group(['prefix' => 'category'], function () {
    Route::post('search', 'CategoryController@search')->name('category.search');
});

Route::resource('contact', 'ContactController');
Route::group(['prefix' => 'contact'], function () {
    Route::post('search', 'ContactController@search')->name('contact.search');
});
