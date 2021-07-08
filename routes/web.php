<?php

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
Route::prefix('user')->group(function () {
    Route::get('/add', 'UserController@createUser')->name('add');
    Route::post('/store', 'UserController@store')->name('store');
    Route::get('/show', 'UserController@showallUser')->name('index_page');
    Route::get('/edit/{id}', 'UserController@edit')->where('id', '[0-9]+');
    Route::post('/update/{user}','UserController@updateUser')->name('update_user');
    Route::get('/delete/{id}','UserController@deleteUser')->where('id', '[0-9]+');
});

//Auth::routes();
Route::get('/login', 'UserController@loginView')->name('login');
Route::get('/markattandenceView', 'AttandenceController@markattandenceView')->name('markattandence');
Route::view('/dashboard', 'HR.dashboard')->name('hr_dashboard');
Route::view('/report', 'HR.report')->name('hr_report');

Route::post('/loginandverify', 'UserController@login');
Route::get('/logout', 'UserController@logout')->name('logout');


Route::post('/markAttandence', 'AttandenceController@markAttandence')->name('markAttandence');
Route::post('/report','AttandenceController@generateReport');
