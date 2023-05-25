<?php
session_start();

include "session.php";
if (!isUserLoggedIn()) {
    http_response_code(401);
    echo "user not logged in";
    exit();
}

//get user id
$userID = $_SESSION['ID'];

//get component id
if (!isset($_GET['cid'])) {
    http_response_code(400);
    echo "component id not set";
    exit();
}

$componentID = $_GET['cid'];
include "config.php";

//check if component is in the cart
$sql = "SELECT * FROM cartContents WHERE userID=? AND componentID=?";
$stmt = $con->prepare($sql);
$stmt->bind_param("ii", $userID, $componentID);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    http_response_code(400);
    echo "component not in cart";
    exit();
}

//remove component from cart
$sql = "DELETE FROM cartContents WHERE userID=? AND componentID=?";
$stmt = $con->prepare($sql);
$stmt->bind_param("ii", $userID, $componentID);
$stmt->execute();
if ($con->affected_rows == 0) {
    http_response_code(500);
    echo "error removing component from cart";
    exit();
}

echo "component removed from cart";
?>