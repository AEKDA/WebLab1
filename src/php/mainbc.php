<?php

session_start();

class HitData
{
    public $hit;
    public $x;
    public $y;
    public $r;
    public $postTime;
    public $execTime;

    public function __construct(
        $hit_arg,
        $x_arg,
        $y_arg,
        $r_arg,
        $postTime_arg,
        $execTime_arg
    ) {
        $this->hit = $hit_arg;
        $this->x = $x_arg;
        $this->y = $y_arg;
        $this->r = $r_arg;
        $this->postTime = $postTime_arg;
        $this->execTime = $execTime_arg;
    }
}


function validateX($number)
{
    if (is_numeric($number) && $number >= -4.0 && $number <= 4.0) {
        return $number;
    } else {
        return null;
    }
}

function validateY($number)
{
    if (is_numeric($number) && $number > -3.0 && $number < 5.0) {
        return $number;
    } else {
        return null;
    }
}

function validateR($number)
{
    if (is_numeric($number) && $number >= 1 && $number <= 5) {
        return $number;
    } else {
        return null;
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $start_time = microtime(true);

    $rValue = validateR($_POST["r-value"] ?? null);
    $xValue = validateX($_POST["x-value"] ?? null);
    $yValue = validateY($_POST["y-value"] ?? null);


    if ($rValue === null || $xValue === null || $yValue === null) {
        http_response_code(400);
        echo json_encode("Invalid parameter values");
        return;
    }

    $hit = "MISS";

    if ($xValue >= 0) {
        if ($yValue <= 0) {
            if ($xValue <= $rValue && $yValue >= -$rValue) {
                $hit = "HIT";
            }
        } else {
            if ($yValue <= -0.5 * $xValue + 0.5 * $rValue) {
                $hit = "HIT";
            }
        }
    } else {
        if ($yValue <= 0) {
            if ($yValue * $yValue + $xValue * $xValue <= $rValue * $rValue) {
                $hit = "HIT";
            }
        }
    }

    $execution_time = (microtime(true) - $start_time);
    $execution_time_formatted = number_format($execution_time, 12);

    $current_time = time();

    $_SESSION['hits'][] = new HitData($hit, $xValue, $yValue, $rValue, $current_time, $execution_time_formatted);

    $data = [
        'x' => $xValue,
        'y' => $yValue,
        'r' => $rValue,
        'hit' => $hit,
        'time' => $current_time,
        'execution_time' => $execution_time_formatted
    ];

    $ans = json_encode($data);

    header('Content-Type: application/json');

    echo $ans;
} else if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_SESSION['hits']) && is_array($_SESSION['hits'])) {
        $outData = array();
        foreach ($_SESSION['hits'] as $val) {
            $x = $val->x;
            $y = $val->y;
            $r = $val->r;
            $hit = $val->hit;
            $postTime = $val->postTime;
            $execTime = $val->execTime;

            $rowData = [
                'x' => $x,
                'y' => $y,
                'r' => $r,
                'hit' => $hit,
                'time' => $postTime,
                'execution_time' => $execTime
            ];

            $outData[] = $rowData;
        }


        $jsonData = json_encode($outData);

        header('Content-Type: application/json');

        echo $jsonData;
    }
} else {
    http_response_code(405);
    echo 'Method must be post';
    return;
}
