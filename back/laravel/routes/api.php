<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

///////// ROUTE FOR AUTH ///////

Route::post('register','App\Http\Controllers\UserController@register');
Route::post('login','App\Http\Controllers\UserController@login');
Route::get('profile','App\Http\Controllers\UserController@getAuthenticatedUser')->middleware('jwt.verify');
Route::post('logout', 'App\Http\Controllers\UserController@logout')->middleware('jwt.verify');

///////// ROUTE FOR ALIMENTS ///////////

Route::get('getAliments/{categorie}', 'App\Http\Controllers\AlimentController@index');

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::post('addAliment','App\Http\Controllers\AlimentController@add');
    Route::get('showAliment/{categorie}', 'App\Http\Controllers\AlimentController@show');
    Route::patch('updateAliment', 'App\Http\Controllers\AlimentController@update');
    Route::delete('deleteAliment', 'App\Http\Controllers\AlimentController@delete');
});
////////// ROUTE FOR CATEGORIES //////////////

Route::get('getCategories', 'App\Http\Controllers\CategoryController@index');

////////// ROUTE FOR USERS //////////////
Route::get('getUsers', 'App\Http\Controllers\UserController@index');

Route::group(['middleware' => ['jwt.verify']], function() {
Route::patch('updateUser', 'App\Http\Controllers\UserController@update');
Route::delete('deleteUser', 'App\Http\Controllers\UserController@delete');
});
