<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/registro', [UserController::class, 'mostrarFormularioRegistro'])->name('formularioRegistro');
route::post('/logout', [UserController::class, 'logout'])->name('logOut');


Route::get('/bienvenido', [UserController::class, 'mostrarBienvenida'])->name('bienvenido');

Route::post('/registro', [UserController::class, 'creaUser'])->name('registro');
Route::get('/verificar-codigo', [UserController::class, 'mostrarFormularioVerificacion'])->name('verificarCodigo');
Route::post('/verificar-codigo', [UserController::class, 'verificarCodigo']);


Route::get('/login', [UserController::class, 'mostrarFormularioLogin'])->name('login.form');
Route::post('/login', [UserController::class, 'inicioSesion'])->name('login.submit');
