<?php
session_start();
include "session.php";
if (!isUserLoggedIn()) {
  http_response_code(401);
  echo "user not logged in";
  exit();
}
$userID = $_SESSION['ID'];

include "config.php";

//get the configuration ID
$uuid = $_SESSION['config-id'];
if (!isset($uuid)) {
    http_response_code(400);
    echo "config id not found in session";
    exit();
}

$sql = "SELECT * FROM configurations WHERE uuid='" . $uuid . "'";
$result = $con->query($sql);
$confID = $result->fetch_assoc()['ID'];

//check if the configuration is already in the cart
$sql = "SELECT * FROM cart WHERE userID=" . $userID . " AND configurationID=" . $confID;
$result = $con->query($sql);
if ($result->num_rows > 0) {
    http_response_code(400);
    echo "configuration already in cart";
    exit();
}

//add the configuration to the cart
$sql = "INSERT INTO cart (userID, configurationID, cartStatus) VALUES (" . $userID . ", " . $confID . ",'pending')";
$con->query($sql);

if ($con->affected_rows == 0) {
    http_response_code(500);
    echo "error adding configuration to cart";
    exit();
}

echo "configuration added to cart";
?>