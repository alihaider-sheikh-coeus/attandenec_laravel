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

use Illuminate\Support\Facades\Route;


Route::get('/login', function () {
    return view('login');
})->name('login');


Route::middleware(['auth'])->group(function () {

    Route::get('/markattandenceView', 'AttandenceController@markattandenceView')->name('markattandence');
    Route::view('/dashboard', 'HR.dashboard')->name('hr_dashboard');
    Route::view('/report', 'HR.report')->name('hr_report');
    Route::get('/daily_report', 'AttandenceController@generatedailyReport')->name('hr_daily_report');
    Route::post('/markAttandence', 'AttandenceController@markAttandence')->name('markAttandence');
    Route::post('/report','AttandenceController@generateReport');

    Route::prefix('user')->group(function () {
        Route::get('/add', 'HrController@createUser')->name('add');
        Route::post('/store', 'HrController@store')->name('store');
        Route::get('/show', 'HrController@showallUser')->name('index_page');
        Route::get('/edit/{id}', 'HrController@edit')->where('id', '[0-9]+')->name('edit_user');
        Route::post('/update/{id}','HrController@updateUser')->name('update_user');
        Route::get('/delete/{id}','HrController@deleteUser')->where('id', '[0-9]+');

    });

});

Route::post('/loginandverify', 'UserController@login');
Route::get('/logout', 'UserController@logout')->name('logout');



