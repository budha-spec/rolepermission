<?php

use Illuminate\Support\Facades\Route;
use Budhaspec\Rolepermission\Http\Controllers\ModuleController;
use Budhaspec\Rolepermission\Http\Controllers\RoleController;


Route::middleware(['web', 'auth'])->group(function () {
    Route::resource('modules', ModuleController::class);
    Route::resource('roles', RoleController::class);
    Route::put('roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.permissions.update');
    Route::get('users', [ModuleController::class, 'listUsers'])->name('user.list');
    Route::get('user/{user}/edit', [ModuleController::class, 'editUser'])->name('user.edit');
    Route::post('user/{user}/update', [ModuleController::class, 'updateUser'])->name('user.update');
    Route::post('modules/child-update', [ModuleController::class, 'childUpdate'])->name('module.child-update');
    Route::post('modules/delete-child', [ModuleController::class, 'deleteChild'])->name('module.delete-child');

    // Load Assets //
    Route::get('rolepermission-assets/{type}/{file}', function ($type, $file) {
        $path = __DIR__ . "/../resources/assets/{$type}/{$file}";
        if (!File::exists($path)) {
            abort(404);
        }
        $mime = $type === 'js' ? 'application/javascript' : 'text/css';
        return Response::make(File::get($path), 200)->header("Content-Type", $mime);
    })->where('type', 'js|css');

    Route::get('access-assets/webfonts/{file}', function ($file) {
        $path = __DIR__ . '/../resources/assets/webfonts/' . $file;

        if (!file_exists($path)) {
            abort(404);
        }

        $mime = match (pathinfo($file, PATHINFO_EXTENSION)) {
            'woff2' => 'font/woff2',
            'woff'  => 'font/woff',
            'ttf'   => 'font/ttf',
            default => 'application/octet-stream',
        };

        return response()->file($path, ['Content-Type' => $mime]);
    })->where('file', '.*');
});