<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\NewsController;

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

Route::get('/clear-cache', function () {
    Artisan::call('optimize:clear');
    return "Cache is cleared ... Check again";
});

Route::get('/optimize-app', function () {
    Artisan::call('event:cache');
    return "App is Optimized ... Check again";
});

Auth::routes();

Route::middleware(['auth'])->group(function() {

    Route::controller(NewsController::class)->group(function () {
        Route::get('/news', 'index')->name('news.index');
        Route::get('/news/{id}', 'show')->name('news.show');
        Route::post('/news', 'show')->name('news.store');
        Route::post('/news/{id}', 'update')->name('news.update');
        Route::post('/news/{id}/delete', 'destroy')->name('news.delete');
    });

});
