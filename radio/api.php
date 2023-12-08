<?php
include "functions.php";
include 'app/app.php';

// turn on error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// parse the domain and add the http protocol
$baseurl = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
// get the document root
$doc_root = $_SERVER['DOCUMENT_ROOT'];

// this file will return a JSON object containing the request data
$episode = $_GET['episode'];

$app = new RadioApp( "sonic-twist-radio" );

// print the request data as json with proper headers
header('Content-Type: application/json');

if($episode=="all"){
    $episodes = $app->get_episodes();
    $episodes_json = array();
    foreach($episodes as $episode){
        $episodes_json[] = $episode->data;
    }
    // reverse the order of the episodes
    //$episodes_json = array_reverse($episodes_json);
    echo json_encode($episodes_json);
}
else{
    $episode = $app->get_episode($episode);
    echo $episode->to_json();
}



exit;