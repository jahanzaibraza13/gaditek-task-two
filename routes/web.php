<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/inbox', [App\Http\Controllers\HomeController::class, 'inbox'])->name('inbox');
Route::post('/email/save', [App\Http\Controllers\HomeController::class, 'saveEmail'])->name('save_email');
Route::get('/read/email/{id}', [App\Http\Controllers\HomeController::class, 'readEmail'])->name('read_email');

Route::post('/box', [App\Http\Controllers\BoxController::class, 'create'])->name('create_box');
Route::post('/email/{id}/box', [App\Http\Controllers\BoxController::class, 'emailBox'])->name('email_box');

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
