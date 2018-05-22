<?php

$DEFAULT_API_BASE_URL = "https://api.meteomatics.com";
$USERNAME = "";
$PASSWORD = "";


function time_series_query_meteocache(DateTime $start_date, DateTime $end_date, $resolution, array $parameters,
                                       $model, $lat, $lon, $format) {

    global $DEFAULT_API_BASE_URL, $USERNAME, $PASSWORD;

    $start_date_str = $start_date->format(DateTime::ISO8601);
    $end_date_str = $end_date->format(DateTime::ISO8601);
    $parameters_str = implode($parameters, ",");

    $url = "{$DEFAULT_API_BASE_URL}/{$start_date_str}--{$end_date_str}:{$resolution}/{$parameters_str}/".
           "{$lat},{$lon}/{$format}?model={$model}";

    $auth = base64_encode("{$USERNAME}:{$PASSWORD}");
    $context = stream_context_create(['http' => ['header' => "Authorization: Basic $auth"]]);
    $response = file_get_contents($url, false, $context );
    return $response;
}


