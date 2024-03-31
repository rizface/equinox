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
                $p = $request["$paramKey$j"][$i];
                if (!$p) {
                    throw new Error("Cannot have empty param or return value");
                }

                $decodedParam = json_decode($p);

                if (($decodedParam !== null)) {
                    $paramAndReturnValue["param$j"] = $decodedParam;
                    continue;
                } 

                $paramAndReturnValue["param$j"] = $p;
            }

            $returnValue =  $request["return"][$i];
            if (!$returnValue) {
                throw new Error("Cannot have empty param or return value");
            }

            $decodedReturn = json_decode($returnValue);
            if ($decodedReturn !== null) {
                $paramAndReturnValue["return"] = $decodedReturn;
            } else {
                $paramAndReturnValue["return"] = $returnValue;
            }

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

    public function JudgePayload(int $langId, string $sc, $questionValidation): Array {
        $path = "/api/check-submission";

        if($questionValidation) {
            $path = "/api/validate-question";
        } 

        $payload = [
            "language_id" => $langId,
            "compiler_options" => "",
            "command_line_arguments" => "",
            "redirect_stderr_to_stdout" => true,
            "source_code" => $sc,
            // "callback_url" => "https://webhook.site/7855225c-6d32-4f54-88b5-2e43af9dcaef"
            "callback_url" => env("CALLBACK_URL").$path
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
        $paramIsArray = false;
        $this->log($params);
        foreach ($params as $key => $param) {
            if ($key == "return") {
                $returnValues[$key] = $param;
                continue;
            }


            $this->log($params);
            $this->log(is_bool($param));
            $this->log(is_string($param));

            if (is_array($param)) {
                $param = json_encode($param);
                $paramIsArray = true;
                $usedParams[$key] = $param;
            } else if (is_string($param)) {
                $usedParams[$key] = $param;
                $param = "'".$param."'";
            } else if (is_bool($param)) {
                $param = $param ? "true" : "false";
                $usedParams[$key] = $param;
            } else {
                $usedParams[$key] = $param;
            }

            $args .= $param;

            if ($paramAt != sizeof($params) - 2) {
                $args .= ",";
            }

            $paramAt++;
        }
        $this->log("args: ".$args);
        $solution = "solution(".$args.")";
        $this->log($solution);
        if ($paramIsArray) {
            $solution = "json_encode(".$solution.")";
        }
        $this->log($solution);
        $sc .= "\n\n" . "echo $solution;";
        
        return [
            "sc" => $sc,
            "params" => $usedParams,
            "returnValues" => $returnValues
        ];
    }
}