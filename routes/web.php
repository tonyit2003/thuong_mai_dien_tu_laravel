<?php

use App\Http\Controllers\Ajax\AttributeController as AjaxAttributeController;
use App\Http\Controllers\Ajax\DashboardController as AjaxDashboardController;
use App\Http\Controllers\Ajax\LocationController;
use App\Http\Controllers\Ajax\MenuController as AjaxMenuController;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\GenerateController;
use App\Http\Controllers\Backend\LanguageController;
use App\Http\Controllers\Backend\PermissionController;
use App\Http\Controllers\Backend\PostCatalogueController;
use App\Http\Controllers\Backend\PostController;
use App\Http\Controllers\Backend\UserCatalogueController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Middleware\AuthenticateMiddleware;
use App\Http\Middleware\LoginMiddleware;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\ProductCatalogueController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\AttributeCatalogueController;
use App\Http\Controllers\Backend\AttributeController;
use App\Http\Controllers\Backend\MenuController;
use App\Http\Controllers\Backend\SystemController;

// @@use-controller@@

Route::get('/', function () {
    return view('welcome');
});

// middleware: xử lý các request trước khi chuyển đến các route hoặc controller.
Route::group(['middleware' => [AuthenticateMiddleware::class, SetLocale::class]], function () {
    // DASHBOARD
    Route::get('dashboard/index', [DashboardController::class, 'index'])->name('dashboard.index');

    // USER
    Route::group(['prefix' => 'user'], function () {
        Route::get('index', [UserController::class, 'index'])->name('user.index');
        Route::get('create', [UserController::class, 'create'])->name('user.create');
        Route::post('store', [UserController::class, 'store'])->name('user.store');
        Route::get('{id}/edit', [UserController::class, 'edit'])->name('user.edit')->where(['id' => '[0-9]+']);
        Route::post('{id}/update', [UserController::class, 'update'])->name('user.update')->where(['id' => '[0-9]+']);
        Route::get('{id}/delete', [UserController::class, 'delete'])->name('user.delete')->where(['id' => '[0-9]+']);
        Route::post('{id}/destroy', [UserController::class, 'destroy'])->name('user.destroy')->where(['id' => '[0-9]+']);
    });

    // USER CATALOGUE
    Route::group(['prefix' => 'user/catalogue'], function () {
        Route::get('index', [UserCatalogueController::class, 'index'])->name('user.catalogue.index');
        Route::get('create', [UserCatalogueController::class, 'create'])->name('user.catalogue.create');
        Route::post('store', [UserCatalogueController::class, 'store'])->name('user.catalogue.store');
        Route::get('{id}/edit', [UserCatalogueController::class, 'edit'])->name('user.catalogue.edit')->where(['id' => '[0-9]+']);
        Route::post('{id}/update', [UserCatalogueController::class, 'update'])->name('user.catalogue.update')->where(['id' => '[0-9]+']);
        Route::get('{id}/delete', [UserCatalogueController::class, 'delete'])->name('user.catalogue.delete')->where(['id' => '[0-9]+']);
        Route::post('{id}/destroy', [UserCatalogueController::class, 'destroy'])->name('user.catalogue.destroy')->where(['id' => '[0-9]+']);
        Route::get('permission', [UserCatalogueController::class, 'permission'])->name('user.catalogue.permission');
        Route::post('updatePermission', [UserCatalogueController::class, 'updatePermission'])->name('user.catalogue.updatePermission');
    });

    // POST
    Route::group(['prefix' => 'post'], function () {
        Route::get('index', [PostController::class, 'index'])->name('post.index');
        Route::get('create', [PostController::class, 'create'])->name('post.create');
        Route::post('store', [PostController::class, 'store'])->name('post.store');
        Route::get('{id}/edit', [PostController::class, 'edit'])->name('post.edit')->where(['id' => '[0-9]+']);
        Route::post('{id}/update', [PostController::class, 'update'])->name('post.update')->where(['id' => '[0-9]+']);
        Route::get('{id}/delete', [PostController::class, 'delete'])->name('post.delete')->where(['id' => '[0-9]+']);
        Route::post('{id}/destroy', [PostController::class, 'destroy'])->name('post.destroy')->where(['id' => '[0-9]+']);
    });

    // POST CATALOGUE
    Route::group(['prefix' => 'post/catalogue'], function () {
        Route::get('index', [PostCatalogueController::class, 'index'])->name('post.catalogue.index');
        Route::get('create', [PostCatalogueController::class, 'create'])->name('post.catalogue.create');
        Route::post('store', [PostCatalogueController::class, 'store'])->name('post.catalogue.store');
        Route::get('{id}/edit', [PostCatalogueController::class, 'edit'])->name('post.catalogue.edit')->where(['id' => '[0-9]+']);
        Route::post('{id}/update', [PostCatalogueController::class, 'update'])->name('post.catalogue.update')->where(['id' => '[0-9]+']);
        Route::get('{id}/delete', [PostCatalogueController::class, 'delete'])->name('post.catalogue.delete')->where(['id' => '[0-9]+']);
        Route::post('{id}/destroy', [PostCatalogueController::class, 'destroy'])->name('post.catalogue.destroy')->where(['id' => '[0-9]+']);
    });

    // MENU
    Route::group(['prefix' => 'menu'], function () {
        Route::get('index', [MenuController::class, 'index'])->name('menu.index');
        Route::get('create', [MenuController::class, 'create'])->name('menu.create');
        Route::post('store', [MenuController::class, 'store'])->name('menu.store');
        Route::get('{id}/edit', [MenuController::class, 'edit'])->name('menu.edit')->where(['id' => '[0-9]+']);
        Route::get('{id}editMenu', [MenuController::class, 'editMenu'])->name('menu.editMenu')->where(['id' => '[0-9]+']);
        Route::post('{id}/update', [MenuController::class, 'update'])->name('menu.update')->where(['id' => '[0-9]+']);
        Route::get('{id}/delete', [MenuController::class, 'delete'])->name('menu.delete')->where(['id' => '[0-9]+']);
        Route::post('{id}/destroy', [MenuController::class, 'destroy'])->name('menu.destroy')->where(['id' => '[0-9]+']);
        Route::get('{id}/children', [MenuController::class, 'children'])->name('menu.children')->where(['id' => '[0-9]+']);
        Route::post('{id}/saveChildren', [MenuController::class, 'saveChildren'])->name('menu.save.children')->where(['id' => '[0-9]+']);
    });

    // LANGUAGE
    Route::group(['prefix' => 'language'], function () {
        Route::get('index', [LanguageController::class, 'index'])->name('language.index');
        Route::get('create', [LanguageController::class, 'create'])->name('language.create');
        Route::post('store', [LanguageController::class, 'store'])->name('language.store');
        Route::get('{id}/edit', [LanguageController::class, 'edit'])->name('language.edit')->where(['id' => '[0-9]+']);
        Route::post('{id}/update', [LanguageController::class, 'update'])->name('language.update')->where(['id' => '[0-9]+']);
        Route::get('{id}/delete', [LanguageController::class, 'delete'])->name('language.delete')->where(['id' => '[0-9]+']);
        Route::post('{id}/destroy', [LanguageController::class, 'destroy'])->name('language.destroy')->where(['id' => '[0-9]+']);
        Route::get('{id}/switch', [LanguageController::class, 'switchBackendLanguage'])->name('language.switch')->where(['id' => '[0-9]+']);
        Route::get('{id}/{languageId}/{model}/translate', [LanguageController::class, 'translate'])->name('language.translate')->where(['id' => '[0-9]+', 'languageId' => '[0-9]+',]);
        Route::post('storeTranslate', [LanguageController::class, 'storeTranslate'])->name('language.storeTranslate');
    });

    // PERMISSION
    Route::group(['prefix' => 'permission'], function () {
        Route::get('index', [PermissionController::class, 'index'])->name('permission.index');
        Route::get('create', [PermissionController::class, 'create'])->name('permission.create');
        Route::post('store', [PermissionController::class, 'store'])->name('permission.store');
        Route::get('{id}/edit', [PermissionController::class, 'edit'])->name('permission.edit')->where(['id' => '[0-9]+']);
        Route::post('{id}/update', [PermissionController::class, 'update'])->name('permission.update')->where(['id' => '[0-9]+']);
        Route::get('{id}/delete', [PermissionController::class, 'delete'])->name('permission.delete')->where(['id' => '[0-9]+']);
        Route::post('{id}/destroy', [PermissionController::class, 'destroy'])->name('permission.destroy')->where(['id' => '[0-9]+']);
    });

    // GENERATE
    Route::group(['prefix' => 'generate'], function () {
        Route::get('index', [GenerateController::class, 'index'])->name('generate.index');
        Route::get('create', [GenerateController::class, 'create'])->name('generate.create');
        Route::post('store', [GenerateController::class, 'store'])->name('generate.store');
        Route::get('{id}/edit', [GenerateController::class, 'edit'])->name('generate.edit')->where(['id' => '[0-9]+']);
        Route::post('{id}/update', [GenerateController::class, 'update'])->name('generate.update')->where(['id' => '[0-9]+']);
        Route::get('{id}/delete', [GenerateController::class, 'delete'])->name('generate.delete')->where(['id' => '[0-9]+']);
        Route::post('{id}/destroy', [GenerateController::class, 'destroy'])->name('generate.destroy')->where(['id' => '[0-9]+']);
    });

    // SYSTEM
    Route::group(['prefix' => 'system'], function () {
        Route::get('index', [SystemController::class, 'index'])->name('system.index');
        Route::post('store', [SystemController::class, 'store'])->name('system.store');
        Route::get('{languageId}/translate', [SystemController::class, 'translate'])->name('system.translate')->where(['languageId' => '[0-9]+']);
        Route::post('{languageId}/saveTranslate', [SystemController::class, 'saveTranslate'])->name('system.save.translate')->where(['languageId' => '[0-9]+']);
    });

    // PRODUCT CATALOGUE
    Route::group(['prefix' => 'product/catalogue'], function () {
        Route::get('index', [ProductCatalogueController::class, 'index'])->name('product.catalogue.index');
        Route::get('create', [ProductCatalogueController::class, 'create'])->name('product.catalogue.create');
        Route::post('store', [ProductCatalogueController::class, 'store'])->name('product.catalogue.store');
        Route::get('{id}/edit', [ProductCatalogueController::class, 'edit'])->name('product.catalogue.edit')->where(['id' => '[0-9]+']);
        Route::post('{id}/update', [ProductCatalogueController::class, 'update'])->name('product.catalogue.update')->where(['id' => '[0-9]+']);
        Route::get('{id}/delete', [ProductCatalogueController::class, 'delete'])->name('product.catalogue.delete')->where(['id' => '[0-9]+']);
        Route::post('{id}/destroy', [ProductCatalogueController::class, 'destroy'])->name('product.catalogue.destroy')->where(['id' => '[0-9]+']);
    });

    // PRODUCT
    Route::group(['prefix' => 'product'], function () {
        Route::get('index', [ProductController::class, 'index'])->name('product.index');
        Route::get('create', [ProductController::class, 'create'])->name('product.create');
        Route::post('store', [ProductController::class, 'store'])->name('product.store');
        Route::get('{id}/edit', [ProductController::class, 'edit'])->name('product.edit')->where(['id' => '[0-9]+']);
        Route::post('{id}/update', [ProductController::class, 'update'])->name('product.update')->where(['id' => '[0-9]+']);
        Route::get('{id}/delete', [ProductController::class, 'delete'])->name('product.delete')->where(['id' => '[0-9]+']);
        Route::post('{id}/destroy', [ProductController::class, 'destroy'])->name('product.destroy')->where(['id' => '[0-9]+']);
    });

    // ATTRIBUTE CATALOGUE
    Route::group(['prefix' => 'attribute/catalogue'], function () {
        Route::get('index', [AttributeCatalogueController::class, 'index'])->name('attribute.catalogue.index');
        Route::get('create', [AttributeCatalogueController::class, 'create'])->name('attribute.catalogue.create');
        Route::post('store', [AttributeCatalogueController::class, 'store'])->name('attribute.catalogue.store');
        Route::get('{id}/edit', [AttributeCatalogueController::class, 'edit'])->name('attribute.catalogue.edit')->where(['id' => '[0-9]+']);
        Route::post('{id}/update', [AttributeCatalogueController::class, 'update'])->name('attribute.catalogue.update')->where(['id' => '[0-9]+']);
        Route::get('{id}/delete', [AttributeCatalogueController::class, 'delete'])->name('attribute.catalogue.delete')->where(['id' => '[0-9]+']);
        Route::post('{id}/destroy', [AttributeCatalogueController::class, 'destroy'])->name('attribute.catalogue.destroy')->where(['id' => '[0-9]+']);
    });

    // ATTRIBUTE
    Route::group(['prefix' => 'attribute'], function () {
        Route::get('index', [AttributeController::class, 'index'])->name('attribute.index');
        Route::get('create', [AttributeController::class, 'create'])->name('attribute.create');
        Route::post('store', [AttributeController::class, 'store'])->name('attribute.store');
        Route::get('{id}/edit', [AttributeController::class, 'edit'])->name('attribute.edit')->where(['id' => '[0-9]+']);
        Route::post('{id}/update', [AttributeController::class, 'update'])->name('attribute.update')->where(['id' => '[0-9]+']);
        Route::get('{id}/delete', [AttributeController::class, 'delete'])->name('attribute.delete')->where(['id' => '[0-9]+']);
        Route::post('{id}/destroy', [AttributeController::class, 'destroy'])->name('attribute.destroy')->where(['id' => '[0-9]+']);
    });

    // @@new-module@@

    // AJAX
    Route::get('ajax/location/getLocation', [LocationController::class, 'getLocation'])->name('ajax.location.index');
    Route::post('ajax/dashboard/changeStatus', [AjaxDashboardController::class, 'changeStatus'])->name('ajax.dashboard.changeStatus');
    Route::post('ajax/dashboard/changeStatusAll', [AjaxDashboardController::class, 'changeStatusAll'])->name('ajax.dashboard.changeStatusAll');
    Route::get('ajax/dashboard/getMenu', [AjaxDashboardController::class, 'getMenu'])->name('ajax.dashboard.getMenu');
    Route::get('ajax/attribute/getAttribute', [AjaxAttributeController::class, 'getAttribute'])->name('ajax.attribute.getAttribute');
    Route::get('ajax/attribute/loadAttribute', [AjaxAttributeController::class, 'loadAttribute'])->name('ajax.attribute.loadAttribute');
    Route::post('ajax/menu/createCatalogue', [AjaxMenuController::class, 'createCatalogue'])->name('ajax.menu.createCatalogue');
    Route::post('ajax/menu/drag', [AjaxMenuController::class, 'drag'])->name('ajax.menu.drag');
});

// LOGIN - LOGOUT DASHBOARD
Route::get('admin', [AuthController::class, 'index'])->name('auth.admin')->middleware(LoginMiddleware::class);
Route::post('login', [AuthController::class, 'login'])->name('auth.login');
Route::get('logout', [AuthController::class, 'logout'])->name('auth.logout');
