<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ConsultController;
use App\Http\Controllers\Api\ExpertController;
use App\Http\Controllers\Api\OpenningController;
use App\Http\Controllers\Api\FavouriteController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


//AUTH
Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function(){
    
    Route::get('profile',[UserController::class, 'profile']);
    Route::get('logout',[UserController::class, 'logout']);
    Route::post('profile',[UserController::class, 'update']);
    Route::delete('profile',[UserController::class, 'destroy']);
    
    //Categories
    Route::get('categories', [CategoryController::class, 'index']);
    
    //experts
    Route::get('experts', [ExpertController::class,'index']);
    Route::get('experts/{id}', [ExpertController::class,'show']);
    
    //services
    Route::post('services', [ServiceController::class, 'store']);
    Route::put('services/{id}', [ServiceController::class, 'update']);
    Route::get('services', [ServiceController::class, 'index']);
    Route::get('categories/{id}/services', [ServiceController::class, 'servicesByCatId']);
    Route::delete('services', [ServiceController::class, 'destroy']);

    //SERVICES UPDATE
    Route::get('services2', [ServiceController::class, 'getServices']);
    Route::post('services2', [ServiceController::class, 'submit']);
    
    //Opennings
    Route::get('experts/{id}/times', [OpenningController::class, 'index']);
    Route::get('experts/{id}/time', [OpenningController::class, 'timesByDate']);
    Route::post('add_time', [OpenningController::class, 'addTime']);
    Route::post('remove_time', [OpenningController::class, 'removeTime']);
    
    //consults 
    Route::post('consults', [ConsultController::class, 'store']);
    Route::get('taken_consults', [ConsultController::class, 'getTakenConsults']);
    Route::get('given_consults', [ConsultController::class, 'getGivenConsults']);
    Route::get('consults/{id}', [ConsultController::class, 'show']);
    Route::put('consults/{id}', [ConsultController::class, 'update']);
    Route::delete('consults/{id}', [ConsultController::class, 'destroy']);
    
    //favourites
    Route::get('favourites',[FavouriteController::class,'index']);
    Route::post('favourites',[FavouriteController::class,'store']);
    Route::delete('favourites/{id}',[FavouriteController::class,'destroy']);
    
});


