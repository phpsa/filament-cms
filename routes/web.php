<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Middleware\ValidateSignature;
use Phpsa\FilamentCms\Http\Controllers\GlideController;
use Phpsa\FilamentCms\Http\Controllers\CmsMediaController;

Route::get('cms-media/glide/{media}', GlideController::class)
    ->middleware(ValidateSignature::class)
    ->name('filament-cms.cms-media.glide');


Route::domain(config("filament.domain"))
    ->middleware(config('filament.middleware.base') + config('filament.middleware.auth'))
    ->get('/filament-cms/media', CmsMediaController::class)->name('filament-cms.cms-media');
