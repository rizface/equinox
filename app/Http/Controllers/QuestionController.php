<?php

namespace App\Http\Controllers;

use App\Jobs\SendSubmission;
use App\Models\AdminSubmission;
use App\Models\Contest;
use App\Models\Notification;
use App\Models\Question;
use App\Models\QuestionReport;
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
            // dd($paramsAndReturnValue);
            Question::create([
                "contest_id" => $id,
                "title" => $request->title,
                "description" => $request["question-description"],
                "test_cases" => json_encode(["params" => $paramsAndReturnValue]),
                "level" => $request->level
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
            $question->level = $request->level;

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

    public function ValidateQuestionForAdmin(Request $request, $courseId, $questionId) {
        try {
            $question = Question::where("contest_id", $courseId)
            ->where("id", $questionId)
            ->first();
            if (!$question) {
                throw new Error("Question not found");
            }

            if (!$question->Contest->ThisIsMyContest()) {
                throw new Error("Question not found");
            }

            $question->DecodeParamsForView();

            $adminSubmissions = AdminSubmission::get()
            ->where("admin_id", Auth::guard("admin")->user()->id)
            ->where("question_id", $questionId);

            $submissions = [];
            foreach ($adminSubmissions as $key => $submission) {
                if (!isset($submissions[$submission->batch_token])) {
                    $submissions[$submission->batch_token] = [];
                }

                $submission->DecodeParamsAndReturnValue();

                array_push($submissions[$submission->batch_token], $submission);
            }

            $solution = '';
            $batchToken = $request->get("solution");

            if ($batchToken) {
                $submission = AdminSubmission::where("batch_token", $batchToken)->first();
                if($submission) {
                    $solution = $submission->source_code;
                }
            }

            return view("admin.dashboard.validate-question", compact('question', 'submissions', 'solution'));
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
            $userId = null;
            $questionValidation = null;
            // dd($question);
            if ($request->questionvalidation !=  null) {
                $userId = Auth::guard("admin")->user()->id;
                $questionValidation = true;
            } else {
                $userId = Auth::guard("coder")->user()->id;
                $questionValidation = false;
            }

            SendSubmission::dispatch([
                "question" => $question,
                "request" => $request->all(),
                "batchToken" => $batchToken,
                "questionId" => $questionId,
                "userId" => $userId, 
                "questionValidation" => $questionValidation,
            ])->onQueue("database");
        } catch (\Throwable $th) {
            Alert::error("Failed", $th->getMessage());
        } finally {
            return redirect()->back();
        }
    }

    public function ViewSubmission(Request $request, $id, $questionId) {
        try {
            $question = Question::where("id", $questionId)->first();
            if (!$question) {
                throw new Error("Question not found");
            }

            $submissions = Submission::where("question_id", $questionId)
            ->groupBy("batch_token")
            ->select("batch_token")
            ->get();

            $solutionLang = 0;
            $solution = '';
            $batchToken = $request->get("batch_token");
            if ($batchToken) {
                $submission = Submission::where("batch_token", $batchToken)->first();
                if ($submission) {
                    $solution = $submission->source_code;
                    $solutionLang = $submission->lang_id;
                }
            }

            return view('admin.dashboard.editor', compact('submissions', 'solution', 'solutionLang'));
        } catch (\Throwable $th) {
            Alert::error("Failed", $th->getMessage());
            return redirect()->back();
        }
    }

    public function CreateReport(Request $request) {
        try {
            $question = Question::where("id", $request->question_id)->first();
            if(!$question) {
                throw new Error("Question not found");
            }

            QuestionReport::create([
                "title" => $request->title,
                "question_id" => $request->question_id,
                "coder_id" => Auth::guard("coder")->user()->id,
                "description" => $request->description
            ]);

            Notification::create([
                "from_id" => Auth::guard("coder")->user()->id,
                "to_id" => $question->Contest->admin_id,
                "question_id" =>  $request->question_id,
                "title" =>  "Question Report",
                "message" =>  $request->description
            ]);

            Alert::success("Success", "Report successfully submited");
        } catch (\Throwable $th) {
            $this->log($th->getMessage());
            Alert::error("Failed", "Failed create report");
        } finally {
            return redirect()->back();
        }
    }
}