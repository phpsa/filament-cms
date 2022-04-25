[![Latest Version on Packagist](https://img.shields.io/packagist/v/phpsa/filament-cms.svg?style=flat-square)](https://packagist.org/packages/phpsa/filament-cms)
[![Semantic Release](https://github.com/phpsa/filament-cms/actions/workflows/release.yml/badge.svg)](https://github.com/phpsa/filament-cms/actions/workflows/release.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/phpsa/filament-cms.svg?style=flat-square)](https://packagist.org/packages/phpsa/filament-cms)

# Filament CMS


## Installation


You can install the package via composer:

```bash
composer require phpsa/filament-cms
```

```
php artisan filament-cms:install
```

## Routes

```
Route::any('/topics', [CmsCategoriesController::class, 'index'])->name('phpsa.filament.cms.resources.categories.resource');
Route::any('topics/{page:slug}', [CmsCategoriesController::class, 'show'])->name('phpsa.filament.cms.resources.categories.resource.show');
Route::any('posts/{page:slug}', [CmsBlogPostController::class, 'show'])->name('phpsa.filament.cms.resources.blog.post.resource');
Route::any('topics/{page:slug}/posts/{post:slug}', [CmsBlogPostController::class, 'showWithTopic'])->name('phpsa.filament.cms.resources.topic.blog.post.resource');
Route::any('{page:slug}', CmsPageController::class)->name('phpsa.filament.cms.resources.pages.resource');
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Phpsa](https://github.com/phpsa)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
