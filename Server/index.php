<?php

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch($path) {
    case "/packages.json":
        attemptRegularDownload('packages.json', time());
        break;
    case "/package1":
        $ts = time();
        redirect("/package1/download?timestamp=" . $ts);
        break;
    case "/package2":
        $ts = time();
        redirect("/package2/download?timestamp=" . $ts);
        break;
    case "/package1/download":
        $ts = !empty($_GET['timestamp']) ? (int)$_GET['timestamp'] : null;
        attemptSlowDownload("package1.zip", $ts);
        break;
    case "/package2/download":
        $ts = !empty($_GET['timestamp']) ? (int)$_GET['timestamp'] : null;
        attemptRegularDownload("package2.zip", $ts);
        break;
}

function redirect($to)
{
    header('Location: ' . $to, true, 302);
}

function attemptRegularDownload($filename, $timestamp)
{
    if(empty($timestamp) || ($timestamp < (time() - 5))) {
        header('HTTP/1.0 403 Unauthorized');
        echo 'Timestamp Expired';
        return;
    }

    header('Content-type: application/zip');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    readfile($filename);
}

function attemptSlowDownload($filename, $timestamp)
{
    if(empty($timestamp) || ($timestamp < (time() - 5))) {
        header('HTTP/1.0 403 Unauthorized');
        echo 'Timestamp Expired';
        return;
    }

    set_time_limit(0);

    $filesize = filesize($filename);

    header('Content-Type: application/octet-stream');
    header('Content-Description: file transfer');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Length: '. $filesize);

    $open = fopen($filename, 'rb');
    while( !feof($open) ){
        echo fread($open, 64);
        usleep(200);
    }
    fclose($open);
}
