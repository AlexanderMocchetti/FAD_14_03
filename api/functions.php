<?php

function is_request_valid() {
    $request_method = $_SERVER['REQUEST_METHOD'] ?? "";
    $content_type = $_SERVER['CONTENT_TYPE'] ?? "";
    return (
        $request_method === "POST" &&
        $content_type === "application/json"
    );
}

function is_logged_in(){
    return isset($_COOKIE["api_token"]);
}

function get_json(){
    $json_string = file_get_contents("php://input");
    $json_array = json_decode($json_string, true);
    return $json_array;
}

function error_message_die(array $message = null, int $status_code = 400){
    http_response_code($status_code);
    echo json_encode($message);
    die;
}

function is_user_existent(string $email, mysqli $conn){
    $sql = "SELECT id FROM users WHERE email = '$email'";
    $result = $conn->query($sql);
    return $result->num_rows > 0;
}

function get_id_user_from_token(string $token){
    $payload = json_decode(base64_decode($token), true);
    $id = $payload['id'];
    $expiry_date = $payload['expiry_date'];
    $token_signature = $payload['signature'];

    if (time() >= $expiry_date) {
        return false;
    }

    $secret_key = getenv("API_TOKEN_SECRET");

    $signature = hash_hmac("sha256", $id.$expiry_date, $secret_key);

    if ($signature !== $token_signature) {
        return false;
    }

    return $id;
}
