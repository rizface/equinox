<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Traits\UtilsTrait;
use Error;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class QuestionController extends Controller
{
    use UtilsTrait;

    public function CreateQuestionPage() {
        return view('admin.dashboard.create-question');
    }

    public function CreateQuestion(Request $request, $id) {
        try {
            $paramsAndReturnValue = $this->ConstructParamsAndReturnValue($request,"input");
            
            Question::create([
                "contest_id" => $id,
                "title" => $request->title,
                "description" => $request["question-description"],
                "test_cases" => json_encode(["params" => $paramsAndReturnValue]),
            ]);

            Alert::success("Success", "Question successfully created");
        } catch (\Throwable $th) {
            Alert::error("Failed", "Failed create the question");
        } finally {
            return redirect(route('admin.createQuestionPage', ["id"=>$id]));
        }
    }

    public function DetailQuestionPage($id, $questionId) {
        $question = Question::where("id", $questionId)->first();
        
        // TODO: fix using method from model
        $question->test_cases = json_decode($question->test_cases);
        $numberOfParams = 0;

        foreach ($question->toArray()["test_cases"]->params[0] as $key => $value) {
            $numberOfParams++;
        }
        $numberOfParams-=1;

        return view('admin.dashboard.detail-question', compact('id', 'question', 'numberOfParams'));
    }

    public function DeleteQuestion($id, $questionId) {
        try {
            $question = Question::where("id", $questionId)->first();
            if(!$question) {
                throw new Error("Question not found");
            }

            if(!$question->Contest->ThisIsMyContest()) {
                throw new Error("Can't delete other admin's questions");
            }

            $question->delete();

            Alert::success("Success", "Success delete $question->title question");
        } catch (\Throwable $th) {
            Alert::error("Failed", $th->getMessage());
        } finally {
            return redirect()->back();
        }
    }

    public function UpdateQuestionPage($id, $questionId) {
        try {
            $question = Question::where("id", $questionId)->first();
            
            if(!$question) {
                throw new Error("Question not found");
            }

            if(!$question->Contest->ThisIsMyContest()) {
                throw new Error("Can't update other admin's questions");
            };

            $question->DecodeParams();

            return view("admin.dashboard.update-question-page", compact('question'));
        } catch (\Throwable $th) {
            Alert::error("Failed", $th->getMessage());
            return redirect()->back();
        }
    }

    public function UpdateQuestion(Request $request, $id, $questionId) {
        try {
            $question = Question::where("id", $questionId)->first();
            
            if(!$question) {
                throw new Error("Question not found");
            }

            if(!$question->Contest->ThisIsMyContest()) {
                throw new Error("Can't update other admin's questions");
            };

            $question->title = $request->title;
            $question->description = $request->description;
            $question->test_cases = json_encode([
                "params" => $this->ConstructParamsAndReturnValue($request, "param")
            ]);

            $question->Validate();
            $question->save();

            Alert::success("Success", "Successfully update question");
        } catch (\Throwable $th) {
            Alert::error("Failed", $th->getMessage());
        } finally {
            return redirect()->back();
        }
    }
}
