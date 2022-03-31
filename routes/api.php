<?php

use App\Http\Controllers\PeopleApiContoller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['prefix' => 'v1'], function () {

    Route::resource('people', PeopleApiContoller::class);
//    Route::post('contact/show', [PeopleApiContoller::class,'show']);
//    Route::get('contacts', [PeopleApiContoller::class,'index']);
//    Route::post('contacts/update', [PeopleApiContoller::class,'update']);
//
////    Route::get('contacts', [PeopleApiContoller::class,'index']);
//    Route::post('contacts/distroy/{id}', [PeopleApiContoller::class,'distroy']);
});

