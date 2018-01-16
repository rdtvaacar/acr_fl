<?php

Route::group(['middleware' => ['web']], function () {
    Route::group(['namespace' => 'Acr\Acr_fl\Controllers', 'prefix' => 'acr/fl'], function () {
        Route::get('/get_file/{acr_file_id}/{file_name}/{loc}', 'FlController@get_file');
        Route::post('/upload', 'FlController@upload');
        Route::post('/file/delete', 'FlController@file_delete');
        Route::get('/acr/acr_fl/', 'FlController@upload');
        Route::post('/file_header', 'FlController@file_header');
        Route::get('/download', 'FlController@download');
        Route::get('/config', 'FlController@config');
        Route::post('/config/update', 'FlController@config_update');
        Route::group(['middleware' => ['auth']], function () {

        });
    });
});