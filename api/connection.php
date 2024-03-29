<?php

$hostname = "localhost";
$username = "alex";
$password = "porcona";
$db = "fad";

$conn = new mysqli($hostname, $username, $password, $db);

if ($conn->connect_error) {
    die("Database failure".$conn->connect_error);
}
