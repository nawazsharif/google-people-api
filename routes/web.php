<?php

use App\Http\Controllers\Controller;
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

Route::get('/', function () {
    return view('welcome');
});
Route::group(['middleware' => 'web'], function () {
    Route::get('getAuthGoogle', [\App\Http\Controllers\PeopleApiContoller::class, 'index'])->name('get-contacts');
    Route::get('/cred', [\App\Http\Controllers\PeopleApiContoller::class, 'cred'])->name('cred');
    Route::get('/code', [\App\Http\Controllers\PeopleApiContoller::class, 'code'])->name('code');
});

//Route::get('/getAuthGoogle/', array('as' => 'get-contacts', 'uses' => 'Controller@getAuthGoogle'));

