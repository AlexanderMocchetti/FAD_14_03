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
    $response_data["message"] = "Wrong and/or expired token";
	error_message_die($response_data, 403);
}

$request_data = get_json();

if ($request_data === null || !isset($request_data['id_item'], $request_data['quantity'])) {
    $response_data["message"] = "Missing entry data";
	error_message_die($response_data);
}

require_once "connection.php";

$id_item = $request_data['id_item'];
$quantity = $request_data['quantity'];

if ($quantity <= 0) {
    error_message_die();
}

$conn->begin_transaction();

try {
    $sql = "LOCK TABLES";
    $sql = "SELECT 1 FROM usersitems WHERE id_user = $id_user AND id_item = $id_item";
    $res = $conn->query($sql);

    if ($res->num_rows > 0) {
        $sql = "UPDATE usersitems SET quantity = quantity + $quantity WHERE id_user = $id_user AND id_item = $id_item";
    } else {
        $sql = "INSERT INTO usersitems (id_user, id_item, quantity) VALUES ($id_user, $id_item, $quantity)";
    }
    $conn->query($sql);

    $sql = "UNLOCK TABLES";
    $conn->query($sql);
    $conn->commit();
} catch (Exception $e) {
    $conn->rollback();
    $response_data["message"] = "DB failure ".$e->getMessage();
	error_message_die($response_data, 500);
}

$response_data["message"] = "Item successfully added";
echo json_encode($response_data);