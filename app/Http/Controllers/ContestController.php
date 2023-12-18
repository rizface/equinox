<?php

namespace App\Http\Controllers;

use App\Models\Contest;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class ContestController extends Controller
{
    public function Dashboard() {
        $allCourses = Contest::orderBy("updated_at", "desc")
        ->get();
        $myCourses = Contest::where("admin_id", Auth::guard("admin")->user()->id)
        ->orderBy("updated_at", "desc")
        ->get();
        
        return view('admin.dashboard.contest', compact('allCourses','myCourses'));
    }

    public function CreateQuestionPage() {
        return view('admin.dashboard.create-question');
    }

    public function CreateQuestion(Request $request, $id) {
        try {
            $paramsLength = sizeof($request["input1"]);
            $indexInput = 1;
            while(true) {
                if(isset($request["input$indexInput"])) {
                    $indexInput++;
                } else {
                    $indexInput--;
                    break;
                }
            }

            $paramsAndReturnValue = [];

            for ($i=0; $i < $paramsLength; $i++) { 
                $paramAndReturnValue = [];

                for ($j=1; $j <= $indexInput; $j++) { 
                    $paramAndReturnValue["param$j"] = $request["input$j"][$i];
                }

                $paramAndReturnValue["return"] = $request["return"][$i];

                array_push($paramsAndReturnValue, $paramAndReturnValue);
            }

            Question::create([
                "contest_id" => $id,
                "title" => $request->title,
                "description" => $request["question-description"],
                "test_cases" => json_encode(["params" => $paramsAndReturnValue]),
            ]);

            Alert::success("Success", "Question successfully created");
        } catch (\Throwable $th) {
            dd($th->getMessage());
            Alert::error("Failed", "Failed create the question");
        } finally {
            return redirect(route('admin.createQuestionPage', ["id"=>$id]));
        }
    }

    public function CreateContest(Request $request) {
        try {
            $contest = Contest::create([
                "admin_id" => Auth::guard("admin")->user()->id,
                "title" => $request->title,
            ]); 

            Alert::success("Berhasi", "Kontes $contest->title Berhasil Dibuat");

            return redirect(route("admin.createQuestionPage", [
                "id" => $contest->id,
            ]));
        } catch (\Throwable $th) {
            dd($th->getMessage());
            Alert::error("Gagal", "Gagal membuat kontest");

            return redirect()->back();
        }
    }

    public function CreateContestPage() {
        return view('admin.dashboard.create-contest');
    }

    public function DetailContestPage($id) {
        $contest = Contest::where("id", $id)->first();
        return view('admin.dashboard.detail-contest', compact('contest'));
    }

    public function DetailQuestionPage($id, $questionId) {
        $question = Question::where("id", $questionId)->first();
        $question->test_cases = json_decode($question->test_cases);
        $numberOfParams = 0;

        foreach ($question->toArray()["test_cases"]->params[0] as $key => $value) {
            $numberOfParams++;
        }
        $numberOfParams-=1;

        return view('admin.dashboard.detail-question', compact('id', 'question', 'numberOfParams'));
    }
}
