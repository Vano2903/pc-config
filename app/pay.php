<?php
// get user id from session
// create a new order with status pending in the database for the user
// get all cart components from database cartContents.userID 
// for each component in the cart move it in the orderContent table with the right quantity
// empty the cartContents table for the user
// return the order id

session_start();
include "session.php";
if (!isUserLoggedIn()) {
    http_response_code(401);
    echo "user not logged in";
    exit();
}

include "config.php";

$userID = $_SESSION['ID'];

//get order id
if (!isset($_GET['oid'])) {
    http_response_code(400);
    echo "order id not set";
    exit();
}

//check if the user is the owner of the order
$orderID = $_GET['oid'];
$sql = "SELECT * FROM orders WHERE ID=? AND userID=?";
$stmt = $con->prepare($sql);
$stmt->bind_param("ii", $orderID, $userID);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    http_response_code(400);
    echo "order not found";
    exit();
}

//! FASE DI PAGAMENTO FINTA

//update the order status to paid
$sql = "UPDATE orders SET status='paid' WHERE ID=?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $orderID);
$stmt->execute();
if ($con->affected_rows == 0) {
    http_response_code(500);
    echo "error updating order status";
    exit();
}

echo "ok";
?>