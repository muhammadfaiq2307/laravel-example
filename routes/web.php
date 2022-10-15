<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OdooController;
use App\Http\Controllers\Profile\AddressController;

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

Route::get('/odoo',[OdooController::class, 'index']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');


Route::group(['prefix' => 'profile'], function(){
    Route::get('/', function(){
        return redirect('profile/address');
    });

    Route::group(['prefix' => 'address'], function(){
        Route::get('/', [AddressController::class, 'index']);
        Route::get('/all', [AddressController::class, 'getAll']);
        Route::post('/all-datatable', [AddressController::class, 'getAllDatatable']);
        Route::get('/create', [AddressController::class, 'create']);
        Route::get('/one/{id}', [AddressController::class, 'getOne']);                      //can be functioned
        Route::get('/detail/{id}', [AddressController::class, 'getUserAddressDetail']);     //can be functioned
        Route::post('/store', [AddressController::class, 'store']);                         //can be functioned
        Route::put('/{id}', [AddressController::class, 'update']);                          //can be functioned
        Route::delete('/{id}',[AddressController::class, 'destroy']);                       //can be functioned
    });
});
require __DIR__.'/auth.php';
