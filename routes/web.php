<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AutenticacaoMiddleware;
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

Route::get('/login/{erro?}', 'LoginController@index')->name('viewLogin');
Route::post('/autenticar', 'LoginController@autenticar')->name('autenticar');

Route::middleware(AutenticacaoMiddleware::class)->get('/home', 'HomeController@index')->name('app.home');
Route::middleware(AutenticacaoMiddleware::class)->get('/tipos', 'TiposController@index')->name('app.tipos');


Route::get('/', function () {
    return view('login');
});