<?php

namespace App\Http\Controllers;

use App\Models\Contest;
use App\Models\Question;
use App\Traits\UtilsTrait;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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
        
        $question->DecodeParams();
        
        return view('admin.dashboard.detail-question', compact('question'));
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

    public function DetailQuestionPageForCoder($courseId, $questionId) {
        try {
            $question = Question::where("contest_id", $courseId)
            ->where("id", $questionId)
            ->first();
            if (!$question) {
                throw new Error("Question not found");
            }

            $question->DecodeParams();

            if (!$question->JoinedTheCourse()) {
                return view("coder.dashboard.detail-question", compact('question'));
            }   

            return view("coder.dashboard.editor", compact('question'));
        } catch (\Throwable $th) {
            Alert::error("Failed", $th->getMessage());
            return redirect()->back();
        };
    }

    public function SubmitSubmission(Request $request, $courseId, $questionId) {
        try {
            $submissionUrl = env("JUDGE_URL")."/submissions?base64_encoded=true&wait=false";

            $question = Question::where("id", $questionId)
            ->where("contest_id", $courseId)
            ->first();
            if(!$question) {
                throw new Error("Question not found");
            }

            $question->DecodeParams();
            $sc = $request->hiddenInput;

            foreach ($question->test_cases["params"] as $key => $params) {
                $args = '';
                foreach ($params as $key => $param) {
                    $args .= $param;
                }

                $sc .= "\n\n" . "echo solution(".$args.");";

                $payload = [
                    "language_id" => 68,
                    "compiler_options" => "",
                    "command_line_arguments" => "",
                    "redirect_stderr_to_stdout" => true,
                    "source_code" => base64_encode($sc),
                    "callback_url" => "http://".env("APP_URL")."/api/log"
                    // "callback_url" => "https://webhook.site/7bcb9e39-2fa5-4157-b752-63a22fcb8c24"
                ];

                $res = Http::post($submissionUrl, $payload);
                dd($payload,$res);
            }

        } catch (\Throwable $th) {
            dd($th->getMessage());
            Alert::error("Failed", $th->getMessage());
        } finally {
            return redirect()->back();
        }
    }
}
