<?php

date_default_timezone_set('UTC');

require_once("php_query_api.php");

$start_date = new DateTime('2017-05-12 00:00:00');
$end_date   = new DateTime('2017-05-15 00:00:00');
$parameters = ["t_2m:C","d_2m:C"];
$resolution = "PT15M";
$model      = "ecmwf-ifs";
$format     = "csv";
$lat        = 50.123; //TODO list
$lon        = 10.843; //TODO list



$response = time_series_query_meteocache($start_date, $end_date, $resolution, $parameters, $model, $lat, $lon, $format);
$lines = explode("\n", $response);

$data = array();
$headers = null;

foreach ($lines as $line) {
    $line_data = explode(";", $line);
    if ($headers == null) {
        $headers = $line_data;
    }

    if (count($headers) == count($line_data)) {
        $data[] = array_combine($headers, $line_data);
    }
}


?>