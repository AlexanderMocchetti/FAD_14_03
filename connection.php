<?php

$hostname = "localhost";
$username = "root";
$password = "";
$db = "fad";

$conn = new mysqli($hostname, $username, $password, $db);

if ($conn->connect_error) {
    die("Database failure".$conn->connect_error);
}