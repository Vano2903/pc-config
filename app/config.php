<?php

$DATABASE_HOST="db";
$DATABASE_USER="root";
$DATABASE_PASS="root";
$DATABASE_NAME="pcbuilder";

$con = new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

if ($con->connection_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>