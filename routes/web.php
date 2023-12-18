<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ContestController;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::prefix("/admin")->group(function() {
    Route::get('/login', [AdminController::class,"LoginPage"])->name("admin.loginPage");
    Route::get('/register', [AdminController::class, "RegisterPage"])->name("admin.registerPage");
    Route::post("/register", [AdminController::class, "Register"])->name("admin.register");
    Route::post("/login", [AdminController::class, "Login"])->name("admin.login");

    Route::get('/', [ContestController::class, "Dashboard"])->name("admin.dashboard");
    Route::get("/contest", [ContestController::class, "CreateContestPage"])->name("admin.createContestPage");
    Route::post("/contest", [ContestController::class, "CreateContest"])->name("admin.createContest");
    Route::get('/contest/{id}', [ContestController::class, "DetailContestPage"])->name("admin.contestDetailPage");
    Route::get('/contest/{id}/question', [ContestController::class, "CreateQuestionPage"])->name("admin.createQuestionPage");
    Route::post("/contest/{id}/question", [ContestController::class, "CreateQuestion"])->name("admin.createQuestion");
    Route::get('/contest/{id}/question/{questionId}', [ContestController::class, "DetailQuestionPage"])->name("admin.questionDetailPage");
});

Route::get('/', function () {
    $q = Question::first();
    return view('welcome', compact('q'));
});
