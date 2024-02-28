<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Question;
use App\Models\SuperAdmin;
use App\Models\Admin;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class SuperAdminController extends Controller
{
    public function LoginPage() {
        return view("superadmin.auth.login");
    }

    public function Login(Request $request) {
        try {
            if(Auth::guard("superadmin")->attempt(["username" => $request->username, "password" => $request->password])) {
                Auth::guard("superadmin")->login(SuperAdmin::where("username", $request->username)->first());

                return redirect(route('superadmin.index'));
            } else {
                throw new Error("wrong username or password");
            }
        } catch (\Throwable $th) {
            Alert::error("Failed", $th->getMessage());

            return redirect(route('superadmin.index'));
        }
    }

    public function Logout(Request $request) {
        Auth::guard("superadmin")->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route("superadmin.loginPage");
    }

    public function Index() {
        $reports = DB::table("questions")
        ->select(DB::raw("questions.id, questions.title, questions.is_valid, count(*) as total_reports"))
        ->join("question_reports", "questions.id", "=", "question_reports.question_id")
        ->groupBy("questions.id", "questions.title", "questions.is_valid")
        ->orderBy("total_reports", "desc")
        ->get();

        return view("superadmin.dashboard.question-report", compact("reports"));
    }

    public function Reports($questionId) {
        try {
            $question = Question::where("id", $questionId)->first();
            if (!$question) {
                throw new Error("Question not found");
            }
    
            $reports = $question->GetReports();
    
            return view("superadmin.dashboard.reports", compact("reports"));
        } catch (\Throwable $th) {
            Alert::error("Failed", $th->getMessage());
            return redirect(route('superadmin.index'));
        }
    }

    public function QuestionDetailPage($questionId) {
        try {
            $question = Question::where("id", $questionId)->first();
            if(!$question) {
                throw new Error("question not found");
            }

            $question->DecodeParams();
            
            return view('superadmin.dashboard.detail-question', compact('question'));
        } catch (\Throwable $th) {
            Alert::error("Failed", $th->getMessage());

            return redirect(route('superadmin.index'));
        }
    }

    public function InvalidateQuestion($questionId) {
        try {
            $question = Question::where("id", $questionId)->first();
            if(!$question) {
                throw new Error("Question not found");
            }

            if (!$question->is_valid) {
                return redirect()->back();
            }

            $question->Invalidate();

            Notification::create([
                "title" => "Question invalidated",
                "message" => "Question with title " . $question->title . " has been invalidated",
                "question_id" => $question->id,
                "from_id" => Auth::guard("superadmin")->user()->id,
                "to_id" => $question->Contest->admin_id
            ]);

            Alert::success("Success", "Question has been invalidated");

            return redirect()->back();
        } catch (\Throwable $th) {
            Alert::error("Failed", $th->getMessage());

            return redirect(route('superadmin.index'));
        }
    }

    public function ListInvalidAdmins() {
        $invalidAdmins = Admin::where("is_valid", false)->get();
        $validAdmins = Admin::where("is_valid", true)->get();

        return view("superadmin.dashboard.invalid-admins", compact("invalidAdmins", "validAdmins"));
    }

    public function ValidateAdmin($adminId) {
        try {
            $admin = Admin::where("id", $adminId)->first();

            if (!$admin) {
                throw new Error("Admin not found");
            }

            $admin->ValidateAdmin();

            Alert::success("Success", "Success validate admin");
        } catch (\Throwable $th) {
            Alert::error("Failed", $th->getMessage());
        } finally {
            return redirect(route('superadmin.invalidAdmins'));
        }
    }

    public function InvalidateAdmin($adminId) {
        try {
            $admin = Admin::where("id", $adminId)->first();

            if (!$admin) {
                throw new Error("Admin not found");
            }

            $admin->InvalidateAdmin();

            Alert::success("Success", "Success invalidate admin");
        } catch (\Throwable $th) {
            Alert::error("Failed", $th->getMessage());
        } finally {
            return redirect(route('superadmin.invalidAdmins'));
        }
    }
} 
