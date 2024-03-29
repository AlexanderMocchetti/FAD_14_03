<?php

require_once "functions.php";

header("Content-Type: application/json");

$response_data = array(
    "success" => false
);

if (!is_request_valid()) {
    $response_data["message"] = "Invalid format";
	error_message_die($response_data);
}

if (!is_logged_in()) {
    $response_data["message"] = "Missing authentication";
	error_message_die($response_data, 401);
}

$token = $_COOKIE["api_token"];

$id_user = get_id_user_from_token($token);

if (!$id_user) {
    $response_data["message"] = "Wrong or expired token";
	error_message_die($response_data, 403);
}

$request_data = get_json();

if ($request_data === null || !isset($request_data['id_item'])) {
    $response_data["message"] = "Missing entry data";
	error_message_die($response_data);
}

$id_item = $request_data['id_item'];

require_once "connection.php";

$sql = "DELETE FROM usersitems WHERE id_user = $id_user AND id_item = $id_item";
$conn->query($sql);

$response_data["success"] = true;
$response_data['message'] = 'Successful removal';

echo json_encode($response_data);
