<?php

use App\Http\Controllers\SubmissionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::put("/check-submission",[SubmissionController::class, "CheckAnswer"]);
Route::put("/validate-question",[SubmissionController::class, "QuestionValidationCheckAnswer"]);


Route::post("/callback/onlyoffice", function(Request $request){
    Log::info("Callback from onlyoffice");
    Log::info($request->all());
    return response()->json(["status" => "ok"]);
});