<?php

require_once "functions.php";

header("Content-Type: application/json");

$response_data = array(
    "success" => false
);

if (!is_logged_in()) {
    $response_data["message"] = "Missing authentication";
    error_message_die($response_data);
}

setcookie("api_token", "", 0);

$response_data["success"] = true;
$response_data["message"] = "Successful logout";
echo json_encode($response_data);
