<?php
//ADD COMPONENT TO CART
//get user id (from session)
//get component id (from GET compid=...)
//check if the component is already in the cart, if so increment the quantity
//if not, add the component to the cart with quantity 1

//ADD CONFIGURATION TO CART
//get configuration uuid (from GET confid=...)
//get all components of the configuration
//do the same as add component to cart

session_start();
include "session.php";
if (!isUserLoggedIn()) {
    http_response_code(401);
    echo "user not logged in";
    exit();
}
$userID = $_SESSION['ID'];

include "config.php";

if (!isset($_GET['compid']) && !isset($_GET['confid'])) {
    http_response_code(400);
    echo "component or configuration id not set";
    exit();
}

if (isset($_GET['compid'])){
    //ADD COMPONENT
    $componentID = $_GET['compid'];

    $status = addComponentToCart($con, $userID, $componentID, 1);
    if ($status==1){
        http_response_code(500);
        echo "error incrementing quantity";
        exit();
    }else if ($status==2){
        http_response_code(500);
        echo "error adding component to cart";
        exit();
    }

    echo "component added to cart";
}else{
    //ADD CONFIGURATION
    //get the configuration uuid
    $uuid = $_GET['confid'];

    //get configuration id
    $sql = "SELECT * FROM configs WHERE uuid=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $uuid);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        http_response_code(400);
        echo "configuration not found";
        exit();
    }
    $row = $result->fetch_assoc();
    $confID = $row['ID'];

    //get all components of the configuration
    $sql = "SELECT * FROM configContents WHERE configID=" . $confID;
    $result = $con->query($sql);
    if ($result->num_rows == 0) {
        http_response_code(400);
        echo "configuration is empty";
        exit();
    }
    //add all components to the cart
    while ($row = $result->fetch_assoc()) {
        $status = addComponentToCart($con, $userID, $row['componentID'], $row['quantity']);
        if ($status==1){
            http_response_code(500);
            echo "error incrementing quantity";
            exit();
        }else if ($status==2){
            http_response_code(500);
            echo "error adding component to cart";
            exit();
        }
    }

    echo "configuration added to cart";
}

//0 => success
//1 => error incrementing quantity
//2 => error adding component to cart
function addComponentToCart($con, $uid, $cid, $quantity){
    //check if the component is already in the cart
    $sql = "SELECT * FROM cartContents WHERE userID=? AND componentID=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ii", $uid, $cid);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        //increment the quantity
        $sql = "UPDATE cartContents SET quantity=quantity+1 WHERE userID=? AND componentID=?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ii", $uid, $cid);
        $stmt->execute();
        if ($con->affected_rows == 0) {
            return 1;
        }
    } else {
        //add the component to the cart
        $sql = "INSERT INTO cartContents (userID, componentID, quantity) VALUES (?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("iii", $uid, $cid, $quantity);
        $stmt->execute();
        if ($con->affected_rows == 0) {
            return 2;
        }
    }
    return 0;
}
?>