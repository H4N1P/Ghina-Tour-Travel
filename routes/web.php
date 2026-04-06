<?php

use App\Http\Controllers\Admin\PaketController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::resource('paket', PaketController::class)->names([
        'index' => 'admin.paket.index',
        'create' => 'admin.paket.create',
        'store' => 'admin.paket.store',
        'show' => 'admin.paket.show',
        'edit' => 'admin.paket.edit',
        'update' => 'admin.paket.update',
        'destroy' => 'admin.paket.destroy',
    ]);
});


Route::get('/', function () {
    return view('welcome');
})->name('home');