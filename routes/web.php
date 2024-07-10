<?php

use App\Http\Controllers\Ajax\DashboardController as AjaxDashboardController;
use App\Http\Controllers\Ajax\LocationController;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\LanguageController;
use App\Http\Controllers\Backend\PostCatalogueController;
use App\Http\Controllers\Backend\PostController;
use App\Http\Controllers\Backend\UserCatalogueController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Middleware\AuthenticateMiddleware;
use App\Http\Middleware\LoginMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// DASHBOARD
Route::get('dashboard/index', [DashboardController::class, 'index'])->name('dashboard.index')->middleware(AuthenticateMiddleware::class);

// LOGIN - LOGOUT DASHBOARD
Route::get('admin', [AuthController::class, 'index'])->name('auth.admin')->middleware(LoginMiddleware::class);
Route::post('login', [AuthController::class, 'login'])->name('auth.login');
Route::get('logout', [AuthController::class, 'logout'])->name('auth.logout');

// USER
Route::group(['prefix' => 'user'], function () {
    Route::get('index', [UserController::class, 'index'])->name('user.index')->middleware(AuthenticateMiddleware::class);
    Route::get('create', [UserController::class, 'create'])->name('user.create')->middleware(AuthenticateMiddleware::class);
    Route::post('store', [UserController::class, 'store'])->name('user.store')->middleware(AuthenticateMiddleware::class);
    Route::get('{id}/edit', [UserController::class, 'edit'])->name('user.edit')->where(['id' => '[0-9]+'])->middleware(AuthenticateMiddleware::class);
    Route::post('{id}/update', [UserController::class, 'update'])->name('user.update')->where(['id' => '[0-9]+'])->middleware(AuthenticateMiddleware::class);
    Route::get('{id}/delete', [UserController::class, 'delete'])->name('user.delete')->where(['id' => '[0-9]+'])->middleware(AuthenticateMiddleware::class);
    Route::post('{id}/destroy', [UserController::class, 'destroy'])->name('user.destroy')->where(['id' => '[0-9]+'])->middleware(AuthenticateMiddleware::class);
});

// USER CATALOGUE
Route::group(['prefix' => 'user/catalogue'], function () {
    Route::get('index', [UserCatalogueController::class, 'index'])->name('user.catalogue.index')->middleware(AuthenticateMiddleware::class);
    Route::get('create', [UserCatalogueController::class, 'create'])->name('user.catalogue.create')->middleware(AuthenticateMiddleware::class);
    Route::post('store', [UserCatalogueController::class, 'store'])->name('user.catalogue.store')->middleware(AuthenticateMiddleware::class);
    Route::get('{id}/edit', [UserCatalogueController::class, 'edit'])->name('user.catalogue.edit')->where(['id' => '[0-9]+'])->middleware(AuthenticateMiddleware::class);
    Route::post('{id}/update', [UserCatalogueController::class, 'update'])->name('user.catalogue.update')->where(['id' => '[0-9]+'])->middleware(AuthenticateMiddleware::class);
    Route::get('{id}/delete', [UserCatalogueController::class, 'delete'])->name('user.catalogue.delete')->where(['id' => '[0-9]+'])->middleware(AuthenticateMiddleware::class);
    Route::post('{id}/destroy', [UserCatalogueController::class, 'destroy'])->name('user.catalogue.destroy')->where(['id' => '[0-9]+'])->middleware(AuthenticateMiddleware::class);
});

// POST
Route::group(['prefix' => 'post'], function () {
    Route::get('index', [PostController::class, 'index'])->name('post.index')->middleware(AuthenticateMiddleware::class);
    Route::get('create', [PostController::class, 'create'])->name('post.create')->middleware(AuthenticateMiddleware::class);
    Route::post('store', [PostController::class, 'store'])->name('post.store')->middleware(AuthenticateMiddleware::class);
    Route::get('{id}/edit', [PostController::class, 'edit'])->name('post.edit')->where(['id' => '[0-9]+'])->middleware(AuthenticateMiddleware::class);
    Route::post('{id}/update', [PostController::class, 'update'])->name('post.update')->where(['id' => '[0-9]+'])->middleware(AuthenticateMiddleware::class);
    Route::get('{id}/delete', [PostController::class, 'delete'])->name('post.delete')->where(['id' => '[0-9]+'])->middleware(AuthenticateMiddleware::class);
    Route::post('{id}/destroy', [PostController::class, 'destroy'])->name('post.destroy')->where(['id' => '[0-9]+'])->middleware(AuthenticateMiddleware::class);
});

// POST CATALOGUE
Route::group(['prefix' => 'post/catalogue'], function () {
    Route::get('index', [PostCatalogueController::class, 'index'])->name('post.catalogue.index')->middleware(AuthenticateMiddleware::class);
    Route::get('create', [PostCatalogueController::class, 'create'])->name('post.catalogue.create')->middleware(AuthenticateMiddleware::class);
    Route::post('store', [PostCatalogueController::class, 'store'])->name('post.catalogue.store')->middleware(AuthenticateMiddleware::class);
    Route::get('{id}/edit', [PostCatalogueController::class, 'edit'])->name('post.catalogue.edit')->where(['id' => '[0-9]+'])->middleware(AuthenticateMiddleware::class);
    Route::post('{id}/update', [PostCatalogueController::class, 'update'])->name('post.catalogue.update')->where(['id' => '[0-9]+'])->middleware(AuthenticateMiddleware::class);
    Route::get('{id}/delete', [PostCatalogueController::class, 'delete'])->name('post.catalogue.delete')->where(['id' => '[0-9]+'])->middleware(AuthenticateMiddleware::class);
    Route::post('{id}/destroy', [PostCatalogueController::class, 'destroy'])->name('post.catalogue.destroy')->where(['id' => '[0-9]+'])->middleware(AuthenticateMiddleware::class);
});

// LANGUAGE
Route::group(['prefix' => 'language'], function () {
    Route::get('index', [LanguageController::class, 'index'])->name('language.index')->middleware(AuthenticateMiddleware::class);
    Route::get('create', [LanguageController::class, 'create'])->name('language.create')->middleware(AuthenticateMiddleware::class);
    Route::post('store', [LanguageController::class, 'store'])->name('language.store')->middleware(AuthenticateMiddleware::class);
    Route::get('{id}/edit', [LanguageController::class, 'edit'])->name('language.edit')->where(['id' => '[0-9]+'])->middleware(AuthenticateMiddleware::class);
    Route::post('{id}/update', [LanguageController::class, 'update'])->name('language.update')->where(['id' => '[0-9]+'])->middleware(AuthenticateMiddleware::class);
    Route::get('{id}/delete', [LanguageController::class, 'delete'])->name('language.delete')->where(['id' => '[0-9]+'])->middleware(AuthenticateMiddleware::class);
    Route::post('{id}/destroy', [LanguageController::class, 'destroy'])->name('language.destroy')->where(['id' => '[0-9]+'])->middleware(AuthenticateMiddleware::class);
});

// AJAX
Route::get('ajax/location/getLocation', [LocationController::class, 'getLocation'])->name('ajax.location.index')->middleware(AuthenticateMiddleware::class);
Route::post('ajax/dashboard/changeStatus', [AjaxDashboardController::class, 'changeStatus'])->name('ajax.dashboard.changeStatus')->middleware(AuthenticateMiddleware::class);
Route::post('ajax/dashboard/changeStatusAll', [AjaxDashboardController::class, 'changeStatusAll'])->name('ajax.dashboard.changeStatusAll')->middleware(AuthenticateMiddleware::class);
