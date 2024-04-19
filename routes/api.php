<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

function makeCrudRouter(string $prefix,
                        string $crudController,
                        bool   $getList = true,
                        bool   $getOne = true,
                        bool   $create = true,
                        bool   $edit = true,
                        bool   $delete = true)
{
    Route::prefix($prefix)
        ->group(function () use ($crudController, $getList, $getOne, $create, $edit, $delete) {
            if ($getList) {
                Route::get('', [$crudController, 'getAll']);
            }

            if ($getOne) {
                Route::get('/{id}', [$crudController, 'get']);
            }

            if ($create) {
                Route::post('', [$crudController, 'create']);
            }

            if ($edit) {
                Route::put('/{id}', [$crudController, 'edit']);
            }

            if ($delete) {
                Route::delete('/{id}', [$crudController, 'delete']);
            }
        });
}

Route::middleware(\App\Http\Middleware\CorsMiddleware::class)->group(function () {
    Route::get('/assets/{id}', [\App\Http\Controllers\AssetsController::class, 'get']);

    makeCrudRouter('/products', \App\Http\Controllers\ProductsController::class,
        create: false, edit: false, delete: false);
    makeCrudRouter('/categories', \App\Http\Controllers\CategoriesController::class,
        create: false, edit: false, delete: false);
    makeCrudRouter('/companies', \App\Http\Controllers\CompaniesController::class,
        create: false, edit: false, delete: false);

    Route::prefix('/auth')
        ->group(function () {
            Route::post('/validate_token', [\App\Http\Controllers\AuthController::class, 'validateAccessToken']);
        });

    Route::prefix('/admin')->group(function () {
        Route::prefix('/auth')->group(function () {
            Route::post('/login', [\App\Http\Controllers\Admin\AdminAuthController::class, 'login']);
        });

        Route::middleware(\App\Http\Middleware\AdminAuthMiddleware::class)->group(function () {
            makeCrudRouter('/products', \App\Http\Controllers\ProductsController::class);
            makeCrudRouter('/categories', \App\Http\Controllers\CategoriesController::class);
            makeCrudRouter('/companies', \App\Http\Controllers\CompaniesController::class);
        });
    });
});
