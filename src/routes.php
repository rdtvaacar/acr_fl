<?php


Route::group(['middleware' => ['web']], function () {
    Route::group(['namespace' => 'Acr\Acr_fl\Controllers', 'prefix' => 'acr/acr_fl'], function () {
        Route::get('/upload', 'ImgControllers@upload');
        Route::get('/arsivle/indir', 'ImgControllers@arsivle_indir');
        Route::group(['middleware' => ['auth']], function () {
        });
    });
});