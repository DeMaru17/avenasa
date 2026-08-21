<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\SetLocaleMiddleware;
use Illuminate\Support\Facades\Route;

// Root URL permanent redirect to default locale (/id) per SPEC-05 Section 4
Route::get('/', function () {
    return redirect('/id', 301);
});

// Localized public route group strictly matching /id or /en
Route::prefix('{locale}')
    ->where(['locale' => 'id|en'])
    ->middleware([SetLocaleMiddleware::class])
    ->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('home');
        Route::get('/about', [PageController::class, 'about'])->name('about');
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
        Route::get('/partners-clients', [PageController::class, 'partnersClients'])->name('partners-clients');
        Route::get('/contact', [ContactController::class, 'index'])->name('contact');
    });
