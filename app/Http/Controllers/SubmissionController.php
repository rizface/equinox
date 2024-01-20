<?php

namespace App\Http\Controllers;

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
                $submission->CheckAnswer($request["result"]);
                Submission::where("submission_token", $request["token"])->update([
                    "is_correct" => $submission->is_correct,
                    "result" => $submission->result,
                    "status" => $submission->status,
                ]);
                
                return;
            }

            // 11 mean error occured in submission
            if ($request["status"]["id"] == 11) {
                Submission::where("submission_token", $request["token"])->update([
                    "status" => $request["status"]["description"],
                    "is_correct" => false,
                    "message" => base64_decode($request->stdout),
                ]);
                
                return;
            }
        } catch (\Throwable $th) {
            $this->log($th->getMessage());
        }
    }
}
