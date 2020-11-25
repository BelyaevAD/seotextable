<?php
use Illuminate\Support\Facades\Route;
use Belyaevad\SeoTextable\SeoTextableController;


Route::group(['middleware' => ['doNotCacheResponse', 'throttle:999,1'], 'prefix' => '/api/seo-textable', 'as' => 'api.seo-morph.'], function () {
    Route::get('/List/{limit?}', [SeoTextableController::class, 'readList'])->name('readList');
    Route::post('/HasRead', [SeoTextableController::class, 'HasRead'])->name('HasRead');
    Route::post('/SetLinks', [SeoTextableController::class, 'SetLinks'])->name('SetLinks');
});
