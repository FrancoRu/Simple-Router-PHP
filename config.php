<?php

$folderPath = dirname($_SERVER['SCRIPT_NAME']);
$urlPath = $_SERVER['REQUEST_URI'];
$url = substr($urlPath, strlen(($folderPath)));

$urlWithoutParams = strstr($url, '?', true);
if ($urlWithoutParams === false) {
    // Si no se encuentra un signo de interrogación, la URL permanece igual
    $urlWithoutParams = $url;
}

define('URL', $urlWithoutParams);
