<?php

$DEFAULT_API_BASE_URL = "https://api.meteomatics.com";
$USERNAME = "";
$PASSWORD = "";

function code_message($http_code) {
    switch ($http_code) {
        case 100: $text = 'Continue'; break;
        case 101: $text = 'Switching Protocols'; break;
        case 200: $text = 'OK'; break;
        case 201: $text = 'Created'; break;
        case 202: $text = 'Accepted'; break;
        case 203: $text = 'Non-Authoritative Information'; break;
        case 204: $text = 'No Content'; break;
        case 205: $text = 'Reset Content'; break;
        case 206: $text = 'Partial Content'; break;
        case 300: $text = 'Multiple Choices'; break;
        case 301: $text = 'Moved Permanently'; break;
        case 302: $text = 'Moved Temporarily'; break;
        case 303: $text = 'See Other'; break;
        case 304: $text = 'Not Modified'; break;
        case 305: $text = 'Use Proxy'; break;
        case 400: $text = 'Bad Request'; break;
        case 401: $text = 'Unauthorized'; break;
        case 402: $text = 'Payment Required'; break;
        case 403: $text = 'Forbidden'; break;
        case 404: $text = 'Not Found'; break;
        case 405: $text = 'Method Not Allowed'; break;
        case 406: $text = 'Not Acceptable'; break;
        case 407: $text = 'Proxy Authentication Required'; break;
        case 408: $text = 'Request Time-out'; break;
        case 409: $text = 'Conflict'; break;
        case 410: $text = 'Gone'; break;
        case 411: $text = 'Length Required'; break;
        case 412: $text = 'Precondition Failed'; break;
        case 413: $text = 'Request Entity Too Large'; break;
        case 414: $text = 'Request-URI Too Large'; break;
        case 415: $text = 'Unsupported Media Type'; break;
        case 500: $text = 'Internal Server Error'; break;
        case 501: $text = 'Not Implemented'; break;
        case 502: $text = 'Bad Gateway'; break;
        case 503: $text = 'Service Unavailable'; break;
        case 504: $text = 'Gateway Time-out'; break;
        case 505: $text = 'HTTP Version not supported'; break;
        default:
            exit('Unknown http status code "' . htmlentities($code) . '"');
        break;
    }
    return "$http_code $text";
}

function time_series_query_meteocache(DateTime $start_date, DateTime $end_date, $resolution, array $parameters,
                                       $model, $lat, $lon, $format) {

    global $DEFAULT_API_BASE_URL, $USERNAME, $PASSWORD;

    $start_date_str = $start_date->format(DateTime::ISO8601);
    $end_date_str = $end_date->format(DateTime::ISO8601);
    $parameters_str = implode($parameters, ",");

    $url = "{$DEFAULT_API_BASE_URL}/{$start_date_str}--{$end_date_str}:{$resolution}/{$parameters_str}/".
           "{$lat},{$lon}/{$format}?model={$model}";

    $curl_handle=curl_init();
    curl_setopt($curl_handle, CURLOPT_URL, $url);
    curl_setopt($curl_handle, CURLOPT_USERPWD, "{$USERNAME}:{$PASSWORD}");
    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Meteomatics PHP connector (curl)');
    $response = curl_exec($curl_handle);
    $http_code = (int)curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);
    curl_close($curl_handle);

    if($http_code != 200) {
        return code_message($http_code);
    }
    return $response;
}


