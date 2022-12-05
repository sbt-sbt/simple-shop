<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/note', function (Request $request) {
    return $request->user();
});

Route::post('/new-note', function (Request $request) {
    return \Modules\Note\Entities\NoteModel::create([
        "title"=>"test",
        "text"=>"author text",
    ]);
});
