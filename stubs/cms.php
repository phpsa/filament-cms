<?php

use Illuminate\Support\Facades\Route;

Route::any('topics/{slug}', fn($slug) => view('dashboard'))->name('phpsa.filament.cms.resources.categories.resource');
Route::any('posts/{slug}', fn($slug) => view('dashboard'))->name('phpsa.filament.cms.resources.blog.posts.resource');
Route::any('{slug}', fn($slug) => view('dashboard'))->name('phpsa.filament.cms.resources.pages.resource');
