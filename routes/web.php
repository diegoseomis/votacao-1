<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AdminController, AuthController, GalleryController};
use App\Http\Middleware\EnsureAdmin;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [GalleryController::class, 'index'])->name('gallery');
Route::get('/exposicoes/{exhibition}', [GalleryController::class, 'show'])->name('gallery.show');
Route::post('/exposicoes/{exhibition}/votos', [GalleryController::class, 'vote'])->middleware('throttle:10,1')->name('gallery.vote');
Route::view('/login', 'auth.login')->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::prefix('admin')->name('admin.')->middleware(['auth', EnsureAdmin::class])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::post('/exposicoes', [AdminController::class, 'store'])->name('store');
    Route::get('/exposicoes/{exhibition}', [AdminController::class, 'show'])->name('show');
    Route::post('/exposicoes/{exhibition}/obras', [AdminController::class, 'upload'])->name('upload');
    Route::patch('/exposicoes/{exhibition}/status', [AdminController::class, 'status'])->name('status');
    Route::get('/exposicoes/{exhibition}/resultado.pdf', [AdminController::class, 'pdf'])->name('pdf');
});
