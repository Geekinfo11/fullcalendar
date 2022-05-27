<?php

use App\Http\Controllers\CalendarController;
use Illuminate\Support\Facades\Auth;
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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//
Route::get('fullcalender', [CalendarController::class, 'index']);

// get resources
Route::get('fullcalender/resources', [CalendarController::class, 'getResources']);

// get resource by id
Route::get('fullcalender/resource/{id}', [CalendarController::class, 'getResourceById']);

Route::post('fullcalenderAjax', [CalendarController::class, 'ajax']);
