<?php

namespace App\Jobs;

use App\Models\AdminSubmission;
use App\Models\Submission;
use App\Traits\UtilsTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class SendSubmission implements ShouldQueue
{
    use utilsTrait, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public $data)
    {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $question = $this->data["question"];
        $request = $this->data["request"];
        $batchToken = $this->data["batchToken"];
        $questionId = $this->data["questionId"];
        $userId = $this->data["userId"];
        $questionValidation = $this->data["questionValidation"];

        $this->log($question);
        foreach ($question["test_cases"]["params"] as $key => $params) {
            $sc = "";
            $usedParams= [];
            $returnValues = [];
            $this->log($request["lang"]);
            if ($request["lang"] == "68") {
                $scProps = $this->PHPBuilder($request["hiddenInput"], $params);
                $sc = $scProps["sc"];
                $usedParams = $scProps["params"];
                $returnValues = $scProps["returnValues"];
            }

            $sc = base64_encode($sc);
            $payload = $this->JudgePayload($request["lang"], $sc);
            $result = $this->SendToJudge($payload);
            $submission = [
                "batch_token" => $batchToken,
                "submission_token" => $result["token"],
                "question_id" => $questionId,
                "lang_id" => $request["lang"],
                "source_code" => $sc,
                "params" => json_encode($usedParams),
                "expected_return_values" => json_encode($returnValues),
                "status" => "pending",
                "result" => null,
                "correct" => null,
            ];

            if (!$questionValidation) {
                $submission["coder_id"] = $userId;
                Submission::create($submission);
            } else {
                $submission["admin_id"] = $userId;
                AdminSubmission::create($submission);
            }
        }
    }
}
