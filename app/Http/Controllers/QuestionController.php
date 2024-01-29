<?php

namespace App\Http\Controllers;

use App\Jobs\SendSubmission;
use App\Models\Contest;
use App\Models\Question;
use App\Models\Submission;
use App\Traits\UtilsTrait;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function DetailQuestionPageForCoder(Request $request, $courseId, $questionId) {
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


            $coderSubmissions = Submission::get()
            ->where("coder_id", Auth::guard("coder")->user()->id)
            ->where("question_id", $questionId);

            $submissions = [];
            foreach ($coderSubmissions as $key => $submission) {
                if (!isset($submissions[$submission->batch_token])) {
                    $submissions[$submission->batch_token] = [];
                }

                $submission->DecodeParamsAndReturnValue();

                array_push($submissions[$submission->batch_token], $submission);
            }

            $submissions = array_reverse($submissions);
            $solution = '';
            $batchToken = $request->get("solution");

            if ($batchToken) {
                $submission = Submission::where("batch_token", $batchToken)->first();
                if($submission) {
                    $solution = $submission->source_code;
                }
            }

            return view("coder.dashboard.editor", compact('question', 'submissions', 'solution'));
        } catch (\Throwable $th) {
            Alert::error("Failed", $th->getMessage());
            return redirect()->back();
        };
    }

    public function SubmitSubmission(Request $request, $courseId, $questionId) {
        try {
            $batchToken = uuid_create();

            $question = Question::where("id", $questionId)
            ->where("contest_id", $courseId)
            ->first();
            if(!$question) {
                throw new Error("Question not found");
            }
            
            $question->DecodeParams();

            SendSubmission::dispatch([
                "question" => $question,
                "request" => $request->all(),
                "batchToken" => $batchToken,
                "questionId" => $questionId,
                "userId" => Auth::guard("coder")->user()->id 
            ])->onQueue("database");
        } catch (\Throwable $th) {
            Alert::error("Failed", $th->getMessage());
        } finally {
            return redirect()->back();
        }
    }
}