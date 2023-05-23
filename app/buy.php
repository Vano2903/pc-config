<?php
session_start();
include "session.php";
if (!isUserLoggedIn()) {
    http_response_code(401);
    echo "user not logged in";
    exit();
}

$cartID = $_GET['id'];
if (!isset($cartID)) {
    http_response_code(400);
    echo "Errore, cartID non settato";
    exit();
}

include "config.php";

$userID = $_SESSION['ID'];

$sql = "SELECT * FROM cart WHERE ID=?";
$stmp = $con->prepare($sql);
$stmp->bind_param("i", $cartID);
$stmp->execute();
$result = $stmp->get_result();
$row = $result->fetch_assoc();
if (!$row) {
    http_response_code(400);
    echo "Errore";
    exit();
}

if ($row['userID'] != $userID) {
    http_response_code(400);
    echo "Errore, non possiedi questo carrello";
    exit();
}

//! processa il carrello e paga ...

//set the cart status to paid
$sql = "UPDATE cart SET cartStatus='paid' WHERE ID=?";
$stmp = $con->prepare($sql);
$stmp->bind_param("i", $cartID);
$stmp->execute();

if ($con->affected_rows == 0) {
    http_response_code(500);
    echo "error updating cart status";
    exit();
}