<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Candidate;

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

Route::get('/dashboard', 'CandidateController@index');
Route::post('/candidate', 'CandidateController@store');
Route::post('/update-candidate/{id}', 'CandidateController@update');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
