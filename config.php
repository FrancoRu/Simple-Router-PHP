<?php

$folderPath = dirname($_SERVER['SCRIPT_NAME']);
$urlPath = $_SERVER['REQUEST_URI'];
$url = substr($urlPath, strlen(($folderPath)));

$urlWithoutParams = strstr($url, '?', true);
if ($urlWithoutParams === false) {
    // If a question mark is not found, the URL remains unchanged
    $urlWithoutParams = $url;
}

define('URL', $urlWithoutParams);
