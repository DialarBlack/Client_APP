<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccesstokensController;
use App\Http\Controllers\ClientappController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/authenticate/{access}', [AuthController::class, 'authenticate'])->name('authenticate');

Route::get('/loginPage', [AuthController::class, 'showLoginPage'])->name('loginPage');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::get('/inscriptionPage', [AuthController::class, 'showInscriptionPage'])->name('inscriptionPage');
Route::post('/inscription', [AuthController::class, 'createUser'])->name('inscription');


Route::post('/client_app', [ClientappController::class, 'createClientApp']);
Route::get('/client_apps', [ClientappController::class, 'allClientApps']);
Route::get('/client_app/{id}', [ClientappController::class, 'showClientApp']);
Route::get('/client_app/{id}/edit', [ClientappController::class, 'editClientApp']);

Route::post('/access_tokens', [ClientappController::class, 'createaccess_tokens']);
Route::get('/access_tokens', [ClientappController::class, 'allaccess_tokens']);
Route::get('/access_tokens/{id}', [ClientappController::class, 'showaccess_tokens']);
Route::get('/access_tokens/{id}/edit', [ClientappController::class, 'editaccess_tokens']);