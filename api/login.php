<?php

require_once "./functions.php";

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

if ($request_data === null || !isset($request_data["email"], $request_data["password"])) {
    $response_data["message"] = "Missing parameters";
	error_message_die($response_data);
}

require_once "./connection.php";

$email = $request_data["email"];
$password = $request_data["password"];

$password = md5($password);

$sql = "SELECT id FROM users WHERE email='$email' AND password_hash='$password'";

$result = $conn->query($sql);

$conn->close();

if ($result->num_rows === 0) {
    $response_data["message"] = "Incorrect email/password";
	error_message_die($response_data);
}

$secret_key = getenv("API_TOKEN_SECRET");

$row = $result->fetch_assoc();

$id = $row["id"];

$expiry_date = time() + (86400 * 30);

$cookie_name = "api_token";

$token = array(
    "id" => $id,
    "expiry_date" => $expiry_date,
    "signature" => hash_hmac("sha256", $id.$expiry_date, $secret_key)  
);

$token = base64_encode(json_encode($token));

setcookie($cookie_name, $token, $expiry_date);

$response_data["success"] = true;
$response_data["message"] = "Successful login";

echo json_encode($response_data);
