<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\Api\AuthenticatedController;
use App\Http\Controllers\System\UserController;
use App\Http\Controllers\System\AuthItemController;
use App\Http\Controllers\System\MainMenuController;
use App\Http\Controllers\System\AuthAssignmentController;
use App\Http\Controllers\System\RoleMainMenuController;
use App\Http\Controllers\Profile\AddressController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/authenticate', [AuthenticatedController::class, 'authenticate']);

Route::group(['prefix' => 'system'], function(){
    Route::get('/', function(){
        return redirect('system/users');
    });

    Route::group(['prefix' => 'users','middleware' => ['auth:sanctum']], function(){
        Route::get('/', [UserController::class, 'index']);
        Route::get('/all', [UserController::class, 'getAll']);
        Route::get('/one/{id}', [UserController::class, 'getOne']);
        Route::post('/store', [UserController::class, 'store']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}',[UserController::class, 'destroy']);
    });

    Route::group(['prefix' => 'auth-item','middleware' => ['auth:sanctum']], function(){
        Route::get('/all', [AuthItemController::class, 'getAll']);
        Route::get('/one/{id}', [AuthItemController::class, 'getOne']);
        Route::post('/store', [AuthItemController::class, 'store']);
        Route::put('/{id}', [AuthItemController::class, 'update']);
        Route::delete('/{id}',[AuthItemController::class, 'destroy']);
    });

    Route::group(['prefix' => 'main-menu','middleware' => ['auth:sanctum','access.role.menu:1']], function(){
        Route::get('/', [MainMenuController::class, 'index']);
        Route::get('/all', [MainMenuController::class, 'getAll']);
        Route::get('/one/{id}', [MainMenuController::class, 'getOne']);
        Route::post('/store', [MainMenuController::class, 'store']);
        Route::put('/{id}', [MainMenuController::class, 'update']);
        Route::delete('/{id}',[MainMenuController::class, 'destroy']);
    });

    Route::group(['prefix' => 'auth-assignment','middleware' => ['auth:sanctum']], function(){
        Route::get('/all', [AuthAssignmentController::class, 'getAll']);
        Route::get('/one/{id}', [AuthAssignmentController::class, 'getOne']);
        Route::post('/store', [AuthAssignmentController::class, 'store']);
        Route::put('/{id}', [AuthAssignmentController::class, 'update']);
        Route::delete('/{id}',[AuthAssignmentController::class, 'destroy']);
    });

    Route::group(['prefix' => 'role-main-menu','middleware' => ['auth:sanctum']], function(){
        Route::get('/all', [RoleMainMenuController::class, 'getAll']);
        Route::get('/one/{id}', [RoleMainMenuController::class, 'getOne']);
        Route::post('/store', [RoleMainMenuController::class, 'store']);
        Route::put('/{id}', [RoleMainMenuController::class, 'update']);
        Route::delete('/{id}',[RoleMainMenuController::class, 'destroy']);
    });
});

Route::group(['prefix' => 'profile'], function(){
    Route::get('/', function(){
        return redirect('profile/address');
    });

    Route::group(['prefix' => 'address','middleware' => ['auth:sanctum']], function(){
        Route::get('/', [AddressController::class, 'index']);
        Route::get('/all', [AddressController::class, 'getAll']);
        Route::get('/one/{id}', [AddressController::class, 'getOne']);
        Route::get('/detail/{id}', [AddressController::class, 'getUserAddressDetail']);
        Route::post('/store', [AddressController::class, 'store']);
        Route::put('/{id}', [AddressController::class, 'update']);
        Route::delete('/{id}',[AddressController::class, 'destroy']);
    });
});