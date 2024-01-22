<?php

namespace App\Http\Controllers;

use App\Models\CoderSolvedQuestion;
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
        }
    }
}
