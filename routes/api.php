<?php

use App\Http\Controllers\EntryController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PadletController;
use App\Http\Controllers\AuthController;

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

Route::post('/auth/login', [AuthController::class, 'login']);

//registrieren der Controller-Methoden //von web.php, um präfix api zu erhalten
Route::get('/', [PadletController::class, 'index']); //default-Route definieren
Route::get('/padlets', [PadletController::class, 'index']); //get Padlets
Route::get('/entries', [EntryController::class, 'index']); //get Entries
//Route::get('/padlets/{title}', [PadletController::class, 'findByTitle']);
Route::get('/padlets/{id}', [PadletController::class, 'findById']); //find Padlets by Id
Route::get('/entries/{id}', [EntryController::class, 'findById']); //find Entries by Id
Route::get('/padlets/search/{searchTerm}', [PadletController::class, 'findBySearchTerm']);
Route::get('/entries/search/{searchTerm}', [EntryController::class, 'findBySearchTerm']);
Route::get('/users', [UserController::class, 'index']); //get User

Route::group(['middleware' => ['api', 'auth.jwt', 'auth.admin']], function() {
    Route::post('padlets', [PadletController::class, 'save']);
    Route::post('entries', [EntryController::class, 'save']);
    Route::put('padlets/{id}', [PadletController::class, 'update']);
    Route::put('entries/{id}', [EntryController::class, 'update']);
    Route::delete('padlets/{id}', [PadletController::class,'delete']);
    Route::delete('entries/{id}', [EntryController::class,'delete']);
    Route::post('auth/logout', [AuthController::class, 'logout']);
});
