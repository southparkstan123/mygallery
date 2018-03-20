<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'api/v1', 'middleware' => ['cors']], function () {
    Route::get('list_gallery','GalleryController@getGalleryList');
    Route::post('getWeather','MainController@getWeather');
    Route::group(['prefix' => 'gallery'], function () {
        Route::get('/','GalleryController@listGalleries');
        Route::get('{id}','GalleryController@getGalleryByID');
        Route::post('create','GalleryController@createGallery');
        Route::delete('delete/{id}', 'GalleryController@deleteGalleryByID');
        Route::post('update/{id}', 'GalleryController@updateGalleryByID');
    });
    Route::group(['prefix' => 'images'], function () {
        Route::get('{id}','GalleryController@getGalleryWithImages');
        Route::post('create','ImageController@createImages');
        Route::delete('delete/{id}', 'ImageController@deleteImageByID');
        Route::post('update/{id}', 'ImageController@updateImageByID');  
        Route::post('publishAll', 'ImageController@publishAllImages');  
    });
    Route::group(['prefix' => 'user'], function(){
        Route::post('signup','Auth\RegisterController@create');
        Route::post('checkUser','Auth\LoginController@isUserValidated');
    });
});

Route::any('{all}', function () {
    return view('index');
})
->where(['all' => '.*'])->middleware('cors');

Auth::routes();