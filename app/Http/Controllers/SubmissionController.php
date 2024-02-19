<?php

namespace App\Http\Controllers;

use App\Models\AdminSubmission;
use App\Models\CoderCompleteCourse;
use App\Models\CoderSolvedQuestion;
use App\Models\Question;
use App\Models\Submission;
use App\Traits\UtilsTrait;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    use UtilsTrait;

    public function CheckAnswer(Request $request) {
        $submission = Submission::where("submission_token", $request["token"])->first();
        if (!$submission) {
            $this->log("Submission not found for token: " . $request["token"]);
            return;
        }

        try {
            // 3 mean no error occured in submission
            if ($request["status"]["id"] == 3) {
                $submission->DecodeParamsAndReturnValue();
                $submission->CheckAnswer($request["stdout"]);
                Submission::where("submission_token", $request["token"])->update([
                    "is_correct" => $submission->is_correct,
                    "result" => $submission->result,
                    "status" => $submission->status,
                ]);
            }

            // 11 mean error occured in submission
            if ($request["status"]["id"] == 11) {
                Submission::where("submission_token", $request["token"])->update([
                    "status" => $request["status"]["description"],
                    "is_correct" => false,
                    "message" => base64_decode($request->stdout),
                ]);
            }

            $this->checkIfAllTestCasesPassed($submission);
        } catch (\Throwable $th) {
            $this->log($th->getMessage());
        }
    }

    private function checkIfAllTestCasesPassed($submission) {
        $question = $submission->Question()->first();
        $question->DecodeParams();
        $numberOfTestCases = sizeof($question->test_cases["params"]);
        $numberOfCorrectTestCasesPerBatch = Submission::where([
            "batch_token" => $submission->batch_token,
            "question_id" => $submission->question_id,
            "is_correct" => true,
            "coder_id" => $submission->coder_id,
        ])->count();
        $allTestCasesPassed = $numberOfTestCases == $numberOfCorrectTestCasesPerBatch;

        if ($allTestCasesPassed) {
            $isCoderSolvedQuestion = CoderSolvedQuestion::where([
                "coder_id" => $submission->coder_id,
                "question_id" => $submission->question_id,
            ])->count() == 1;
            
            if ($isCoderSolvedQuestion) {
                return; // no need to re-insert
            }

            CoderSolvedQuestion::create([
                "coder_id" => $submission->coder_id,
                "question_id" => $submission->question_id,
            ]);

            $this->checkIfAllQuestionsIsSolved($question->contest_id, $submission->coder_id);
        }
    }

    private function checkIfAllQuestionsIsSolved($contest_id, $coder_id) {
        $questionIds = Question::where("contest_id", $contest_id)->pluck("id");
        $numberOfSolvedQuestions = CoderSolvedQuestion::whereIn("question_id", $questionIds)
        ->where("coder_id",$coder_id)
        ->count();
        $allQuestionsIsSolved = sizeof($questionIds) == $numberOfSolvedQuestions;

        if ($allQuestionsIsSolved) {
            $exists = CoderCompleteCourse::where("coder_id", $coder_id)
            ->where("course_id", $contest_id)
            ->count() == 1;
            if ($exists) {
                return; // no need to re-insert
            }

            CoderCompleteCourse::create([
                "coder_id" => $coder_id,
                "course_id" => $contest_id,
            ]);
        }
    }


    public function QuestionValidationCheckAnswer(Request $request) {
        $submission = AdminSubmission::where("submission_token", $request["token"])->first();
        if (!$submission) {
            $this->log("Submission not found for token: " . $request["token"]);
            return;
        }

        try {
            // 3 mean no error occured in submission
            if ($request["status"]["id"] == 3) {
                $submission->DecodeParamsAndReturnValue();
                // dd($submission);
                $submission->CheckAnswer($request["stdout"]);
                AdminSubmission::where("submission_token", $request["token"])->update([
                    "is_correct" => $submission->is_correct,
                    "result" => $submission->result,
                    "status" => $submission->status,
                ]);
            }

            // 11 mean error occured in submission
            if ($request["status"]["id"] == 11) {
                AdminSubmission::where("submission_token", $request["token"])->update([
                    "status" => $request["status"]["description"],
                    "is_correct" => false,
                    "message" => base64_decode($request->stdout),
                ]);
            }

            $this->questionValidationCheckIfAllTestCasesPassed($submission);
        } catch (\Throwable $th) {
            $this->log($th->getMessage());
        }
    }

    private function questionValidationCheckIfAllTestCasesPassed($submission) {
        $question = $submission->Question()->first();
        $question->DecodeParams();
        $numberOfTestCases = sizeof($question->test_cases["params"]);
        $numberOfCorrectTestCasesPerBatch = AdminSubmission::where([
            "batch_token" => $submission->batch_token,
            "question_id" => $submission->question_id,
            "is_correct" => true,
            "admin_id" => $submission->admin_id,
        ])->count();
        $allTestCasesPassed = $numberOfTestCases == $numberOfCorrectTestCasesPerBatch;

        if ($allTestCasesPassed) {
            Question::where("id", $submission->question_id)
            ->update(["is_valid" => true]);
        }
    }
}
