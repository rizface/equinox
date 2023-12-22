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
    Route::get("/course", [ContestController::class, "CreateContestPage"])->name("admin.createContestPage");
    Route::post("/course", [ContestController::class, "CreateContest"])->name("admin.createContest");

    Route::get('/course/{id}', [ContestController::class, "DetailContestPage"])->name("admin.contestDetailPage");
    Route::get('/course/{id}/delete', [ContestController::class, "DeleteCourse"])->name("admin.deleteCourse");
    Route::get('/course/{id}/update', [ContestController::class, "UpdateCoursePage"])->name("admin.updateCoursePage");
    Route::post('/course/{id}/update', [ContestController::class, "UpdateCourse"])->name("admin.updateCourse");

    Route::get('/course/{id}/question', [ContestController::class, "CreateQuestionPage"])->name("admin.createQuestionPage");
    Route::post("/course/{id}/question", [ContestController::class, "CreateQuestion"])->name("admin.createQuestion");
    Route::get('/course/{id}/question/{questionId}', [ContestController::class, "DetailQuestionPage"])->name("admin.questionDetailPage");
    Route::get('/course/{id}/question/{questionId}/delete', [ContestController::class, "DeleteQuestion"])->name("admin.deleteQuestion");
    Route::get('/course/{id}/question/{questionId}/update', [ContestController::class, "UpdateQuestionPage"])->name("admin.updateQuestionPage");
});

Route::get('/', function () {
    $q = Question::first();
    return view('welcome', compact('q'));
});
