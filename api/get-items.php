<?php

require_once "functions.php";

header("Content-Type: application/json");

$response_data = array(
    "success" => false
);

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    $response_data["message"] = "Wrong method, use GET";
    error_message_die($response_data, 405);    
}

if (!is_logged_in()) {
    $response_data["message"] = "Missing authentication";
    error_message_die($response_data, 401);
}

$token = $_COOKIE["api_token"];

$id_user = get_id_user_from_token($token);

if (!$id_user) {
    $response_data["message"] = "Wrong and/or expired token";
	error_message_die($response_data, 403);
}

require_once "connection.php";

$sql = "SELECT id_item as id, description as name, quantity
	FROM users JOIN usersitems ON users.id = id_user
	JOIN items ON items.id = id_item
	WHERE users.id = $id_user
	ORDER BY quantity DESC";

$res = $conn->query($sql);

$rows = $res->fetch_all(MYSQLI_ASSOC);

$response_data["items"] = $rows;
$response_data["success"] = true;
unset($response_data["message"]);
echo json_encode($response_data);
