<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CoderController;
use App\Http\Controllers\ContestController;
use App\Http\Controllers\QuestionController;
use App\Http\Middleware\AdminAuthMiddleware;
use App\Http\Middleware\AdminGuestMiddleware;
use App\Http\Middleware\CoderAuth;
use App\Http\Middleware\CoderGuest;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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
    Route::middleware([AdminGuestMiddleware::class])->group(function() {
        Route::get('/login', [AdminController::class,"LoginPage"])->name("admin.loginPage");
        Route::get('/register', [AdminController::class, "RegisterPage"])->name("admin.registerPage");
        Route::post("/register", [AdminController::class, "Register"])->name("admin.register");
        Route::post("/login", [AdminController::class, "Login"])->name("admin.login");  
    });
    
    Route::middleware([AdminAuthMiddleware::class])->group(function() {
        Route::get('/', [ContestController::class, "Dashboard"])->name("admin.dashboard");
        Route::get("/course", [ContestController::class, "CreateContestPage"])->name("admin.createContestPage");
        Route::post("/course", [ContestController::class, "CreateContest"])->name("admin.createContest");

        Route::get('/course/{id}', [ContestController::class, "DetailContestPage"])->name("admin.contestDetailPage");
        Route::get('/course/{id}/delete', [ContestController::class, "DeleteCourse"])->name("admin.deleteCourse");
        Route::get('/course/{id}/update', [ContestController::class, "UpdateCoursePage"])->name("admin.updateCoursePage");
        Route::post('/course/{id}/update', [ContestController::class, "UpdateCourse"])->name("admin.updateCourse");

        Route::get('/course/{id}/question', [QuestionController::class, "CreateQuestionPage"])->name("admin.createQuestionPage");
        Route::post("/course/{id}/question", [QuestionController::class, "CreateQuestion"])->name("admin.createQuestion");
        Route::get('/course/{id}/question/{questionId}', [QuestionController::class, "DetailQuestionPage"])->name("admin.questionDetailPage");
        Route::get('/course/{id}/question/{questionId}/validate', [QuestionController::class, "ValidateQuestionForAdmin"])->name("admin.validateQuestionPage");
        Route::get('/course/{id}/question/{questionId}/delete', [QuestionController::class, "DeleteQuestion"])->name("admin.deleteQuestion");
        Route::get('/course/{id}/question/{questionId}/update', [QuestionController::class, "UpdateQuestionPage"])->name("admin.updateQuestionPage");
        Route::post('/course/{id}/question/{questionId}/update', [QuestionController::class, "UpdateQuestion"])->name("admin.updateQuestion");
        Route::get('/course/{id}/question/{questionId}/submissions', [QuestionController::class, "ViewSubmission"])->name("admin.viewSubmission");
        Route::post("/courses/{courseId}/questions/{questionId}/submission", [QuestionController::class, "SubmitSubmission"])->name("admin.submitSubmission");

        Route::get("/logout", [AdminController::class, "logout"])->name("admin.logout");
    });
});

Route::prefix("/coder")->group(function() {
    Route::middleware([CoderGuest::class])->group(function() {
        Route::get("/register", [CoderController::class, "RegisterPage"])->name("coder.registerPage");
        Route::post("/register", [CoderController::class, "Register"])->name("coder.register"); 
        Route::get("/login", [CoderController::class, "LoginPage"])->name("coder.loginPage");
        Route::post("/login", [CoderController::class, "Login"])->name("coder.login");  
    });

    Route::middleware([CoderAuth::class])->group(function() {
        Route::get("/profile", [CoderController::class, "ProfilePage"])->name("coder.profile");
        Route::post("/profile", [CoderController::class, "UpdateProfile"])->name("coder.profile.update");

        Route::get("/courses", [ContestController::class, "AvailableCoursesForCoders"])->name("coder.courses");
        Route::get("/courses/{id}", [ContestController::class, "DetailCoursePageForCoder"])->name("coder.detailCourse");
        
        Route::get("/courses/{courseId}/questions/{questionId}", [QuestionController::class, "DetailQuestionPageForCoder"])->name("coder.detailQuestion");
        Route::post("/courses/{courseId}/questions/{questionId}/submission", [QuestionController::class, "SubmitSubmission"])->name("coder.submitSubmission");
        Route::get("/courses/{courseId}/coder/{coderId}/join", [ContestController::class, "CoderJoinCourse"])->name("coder.joinCourse");

        Route::get("/logout", [CoderController::class, "Logout"])->name("coder.logout");
    });
});

Route::get('/', function () {
    $q = Question::first();
    return view('welcome', compact('q'));
});
