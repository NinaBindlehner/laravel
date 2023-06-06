<?php

use App\Models\Padlet;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PadletController;

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
/*Array an View welcome.blade.php übergeben*/
/*Route::get('/', function () {
    $books = [
        'Herr der Ringe',
        'Harry Potter',
        'Laravel Einführung'
    ];
    return view('welcome',compact('books'));
});*/


/*Route::get('/', [PadletController::class, 'index']); //default-Route definieren
Route::get('/padlets', [PadletController::class, 'index']);
Route::get('/padlets/{padlet}', [PadletController::class, 'show']);*/

/*
Route::get('/', function () {
    $padlets = DB::table('padlets')->get();
    //return $padlets;
    return view('welcome',compact('padlets'));
});*/
