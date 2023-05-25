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

//create new order
$sql = "INSERT INTO orders (userID, status) VALUES (?, 'pending')";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
if ($con->affected_rows == 0) {
    http_response_code(500);
    echo "error creating order";
    exit();
}  

//get the order id
$orderID = $con->insert_id;

//get all cart components
$sql = "SELECT * FROM cartContents WHERE userID=?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    //remove the order
    $sql = "DELETE FROM orders WHERE ID=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $orderID);
    $stmt->execute();
    if ($con->affected_rows == 0) {
        http_response_code(500);
        echo "error: cart is emtpy: error deleting order";
        exit();
    }
    http_response_code(400);
    echo "cart is empty";
    exit();
}

//for each component in the cart move it in the orderContent table with the right quantity
while ($row = $result->fetch_assoc()) {
    $componentID = $row['componentID'];
    $quantity = $row['quantity'];

    $sql = "INSERT INTO ordersContents (orderID, componentID, quantity) VALUES (?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("iii", $orderID, $componentID, $quantity);
    $stmt->execute();
    if ($con->affected_rows == 0) {
        //set the order status to failed
        $sql = "UPDATE orders SET orderStatus='failed' WHERE ID=?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $orderID);
        $stmt->execute();
        if ($con->affected_rows == 0) {
            http_response_code(500);
            echo "error adding component to order: error updating order status";
            exit();
        }
        http_response_code(500);
        echo "error adding component to order";
        exit();
    }
}

//empty the cartContents table for the user
$sql = "DELETE FROM cartContents WHERE userID=?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();

if ($con->affected_rows == 0) {
    //set the order status to failed
    $sql = "UPDATE orders SET orderStatus='failed' WHERE ID=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $orderID);
    $stmt->execute();
    if ($con->affected_rows == 0) {
        http_response_code(500);
        echo "error emptying cart: error updating order status";
        exit();
    }
    http_response_code(500);
    echo "error emptying cart";
    exit();
}

//return the order id
echo $orderID;

?>