<?php

namespace App\Traits;

use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Console\Output\ConsoleOutput;

trait UtilsTrait {
    public function ConstructParamsAndReturnValue(Request $request, String $paramKey) {
        if(!is_countable($request[$paramKey."1"])) {
            throw new Error("At least have 1 param");
        }

        $paramsLength = sizeof($request[$paramKey."1"]);
        $indexInput = 1;
        while(true) {
            if(isset($request["$paramKey$indexInput"])) {
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
                $paramAndReturnValue["param$j"] = $request["$paramKey$j"][$i];
            }

            $paramAndReturnValue["return"] = $request["return"][$i];

            array_push($paramsAndReturnValue, $paramAndReturnValue);
        }

        return $paramsAndReturnValue;
    }

    public function NullOrEmpty ($val): bool {
        if(!$val || $val == "") {
            return true;
        }
        
        return false;
    }

    public function log($data) {
        $log = new ConsoleOutput();
        $log->writeln(json_encode($data));
    }

    public function JudgePayload(int $langId, string $sc): Array {
        $payload = [
            "language_id" => $langId,
            "compiler_options" => "",
            "command_line_arguments" => "",
            "redirect_stderr_to_stdout" => true,
            "source_code" => $sc,
            "callback_url" => "https://webhook.site/7bcb9e39-2fa5-4157-b752-63a22fcb8c24"
            // "callback_url" => env("CALLBACK_URL") // TODO: REPLACE THIS WITH YOUR OWN CALLBACK URL
        ];

        return $payload;
    }

    public function SendToJudge(array $payload) {
        $result = [];
        $submissionUrl = env("JUDGE_URL")."/submissions?base64_encoded=true&wait=false";

        $res = Http::withHeaders([
            'content-type'=> 'application/json',
            'Content-Type'=> 'application/json',
            'X-RapidAPI-Key'=> env("X_RAPID_API_KEY"),
            'X-RapidAPI-Host'=> env("X_RAPID_API_HOST")
        ])->post($submissionUrl, $payload);
        
        if ($res->status() > 201) {
            $this->log($res->body());
            throw new Error("Failed send submission to judge");
        }

        $result["token"] = $res->json()["token"];

        return $result;
    }

    public function PHPBuilder(String $sc, Array $params): array {
        $args = "";
        $paramAt = 0;
        $pattern = '/\?>$/';
        $sc = preg_replace($pattern, '', $sc);
        $usedParams= [];
        $returnValues = [];
        
        foreach ($params as $key => $param) {
            if ($key == "return") {
                $returnValues[$key] = $param;
                continue;
            }

            $args .= $param;
    
            $usedParams[$key] = $param;

            if ($paramAt != sizeof($params) - 2) {
                $args .= ",";
            }

            $paramAt++;
        }

        $sc .= "\n\n" . "echo solution(".$args.");";
        
        return [
            "sc" => $sc,
            "params" => $usedParams,
            "returnValues" => $returnValues
        ];
    }
}