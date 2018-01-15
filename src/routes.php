<?php


Route::group(['middleware' => ['web']], function () {


    Route::group(['namespace' => 'Acr\Acr_fl\Controllers', 'prefix' => 'acr/fl'], function () {
        Route::get('/get_file/{acr_file_id}/{file_name}', 'FlController@get_file');
        Route::post('/upload', 'FlController@upload');
        Route::get('/acr/acr_fl/', 'FlController@upload');
        Route::post('/file_header', 'FlController@file_header');
        Route::post('/download', 'FlController@download');
        Route::group(['middleware' => ['auth']], function () {
        });
    });

});