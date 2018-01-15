<?php


Route::group(['middleware' => ['web']], function () {


    Route::group(['namespace' => 'Acr\Acr_fl\Controllers', 'prefix' => 'acr/fl'], function () {
        Route::post('/upload', 'FlController@upload');
        Route::post('/file_header', 'FlController@file_header');
        Route::post('/download', 'FlController@download');
        Route::group(['middleware' => ['auth']], function () {
        });
    });

});