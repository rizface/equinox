<?php

namespace App\Traits;

use Error;
use Illuminate\Http\Request;
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

    public function NullOrEmpty ($val) {
        if(!$val || $val == "") {
            return true;
        }
        
        return false;
    }

    public function log($data) {
        $log = new ConsoleOutput();
        $log->writeln(json_encode($data));
    }

    public function PHPBuilder(String $sc, Array $params): String {
        $args = "";
        $paramAt = 0;
        $pattern = '/\?>$/';
        $sc = preg_replace($pattern, '', $sc);

        foreach ($params as $key => $param) {
            if ($key == "return") {
                continue;
            }

            $args .= $param;

            if ($paramAt != sizeof($params) - 2) {
                $args .= ",";
            }

            $paramAt++;
        }

        $sc .= "\n\n" . "echo solution(".$args.");";
        
        return $sc;
    }
}