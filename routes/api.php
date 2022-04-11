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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/userDetails/{userID}/getProfilePictureMetadata', [App\Http\Controllers\ApiController::class, 'getProfilePictureMetadata']);
Route::get('/userDetails/{userID}', [App\Http\Controllers\ApiController::class, 'getUserDetails']);
Route::post('/login', [App\Http\Controllers\ApiController::class, 'checkIfCredentialsMatch']);
Route::post('/register', [App\Http\Controllers\ApiController::class, 'registerUser']);

Route::put('/userDetails/{userID}/updateUserDetails', [App\Http\Controllers\ApiController::class, 'updateUserDetails']);

