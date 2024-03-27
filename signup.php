<?php

require_once __DIR__."/functions.php";

header("Content-Type: application/json");

$response_data = array(
    "success" => false
);

if (!is_request_valid()) {
    $response_data["message"] = "Invalid format";
	error_message_die($response_data);
}

if (is_logged_in()) {
    $response_data["message"] = "Already logged in";
	error_message_die($response_data);
}

$request_data = get_json();

if ($request_data === null || !isset($request_data['email'], $request_data['password'], $request_data['name'], $request_data['lastname'])) {
    $response_data["message"] = "Missing parameters";
	error_message_die($response_data);
}

require_once __DIR__."/connection.php";

$name = $request_data['name'];
$lastname = $request_data['lastname'];
$email = $request_data['email'];
$password = $request_data['password'];

$password = md5($password);

$conn->begin_transaction();

try {
    $sql = "LOCK TABLES";
    $conn->query($sql);
    if (is_user_existent($email, $conn)) {
        $conn->rollback();
        $response_data["message"] = "User already existent";
	    error_message_die($response_data);
    }
    $sql = "INSERT INTO users (name, lastname, email, password_hash) VALUES ('$name', '$lastname', '$email', '$password')";
    $sql = "UNLOCK TABLES";
    $conn->query($sql);
    $conn->commit();
} catch (Exception $e) {
    $conn->rollback();
    $response_data["message"] = "DB failure ".$e->getMessage();
	error_message_die($response_data, 500);
}

$response_data["success"] = true;
$response_data["message"] = "Successful signup"; 

echo json_encode($response_data);