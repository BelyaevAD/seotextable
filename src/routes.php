<?php

use Illuminate\Support\Facades\Route;
use Belyaevad\SeoTextable\SeoTextableController;


Route::group([
    'middleware' => ['doNotCacheResponse', 'throttle:999,1'],
    'prefix'     => config('seotextable.url_prefix'),
    'as'         => 'api.seo-textable.',
], function () {
    Route::get('/List/{limit?}', [SeoTextableController::class, 'readList'])
        ->name('readList');
    Route::get('/StartUpdate', [SeoTextableController::class, 'StartUpdate'])
        ->name('StartUpdate');
    Route::get('/DeleteData', [SeoTextableController::class, 'DeleteData'])
        ->name('DeleteData');
    Route::get('/GetStat', [SeoTextableController::class, 'GetStat'])
        ->name('GetStat');
    Route::post('/HasRead', [SeoTextableController::class, 'HasRead'])
        ->name('HasRead');
    Route::post('/SetLinks', [SeoTextableController::class, 'SetLinks'])
        ->name('SetLinks');
});
