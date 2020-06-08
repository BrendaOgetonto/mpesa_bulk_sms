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

Route::view('/bulksms', 'bulksms');
Route::post('/bulksms', 'BulkSmsController@sendSms');

Route::get('/billing', function () {
    return view('billing', ['amount' => '250']);
});
Route::post('/billing', 'MpesaController@stk_push');
Route::post('/register_url', 'MpesaController@register_url');


Route::get('/', function () {
    return view('welcome');
});
