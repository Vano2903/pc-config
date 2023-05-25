<?php
    session_start();
    include "session.php";
    if (!isUserLoggedIn()) {
        header("Location: login.php");
        exit();
    }
    
    include "config.php";
    $id = $_SESSION['ID'];
    $sql = "SELECT * FROM users WHERE ID=?";
    $stmp = $con->prepare($sql);
    $stmp->bind_param("i", $id);
    $stmp->execute();
    $result = $stmp->get_result();
    $row = $result->fetch_assoc();
    if (!$row) {
        echo "Errore";
        exit();
    }
    $username = $row["username"];
    $email = $row["email"];

    //get all orders
    $sql = "SELECT * FROM orders WHERE userID=? ORDER BY status DESC, createdAt";
    $stmp = $con->prepare($sql);
    $stmp->bind_param("i", $id);
    $stmp->execute();
    $result = $stmp->get_result();
    $orders = array();
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
?>
<html>

<head>
  <title>User</title>
  <link rel="stylesheet" href="./css/default.css">

  <style type="text/css">
    .box {
      width: 100%;
      display: inline-flex;
      flex-wrap: nowrap;
      justify-content: space-evenly;
      background-color: black;
      font-weight: bold;
    }

    li {
      display: inline;
      margin: 6%;
    }

    ul {
      width: 100%;
      margin-top: 6%;
    }

    img {
      width: 12%;
      height: 12%;
    }

    a {
      text-decoration: none;
    }

    .menu {
      color: red;
    }

    .menu:hover {
      color: orangered;
    }

    #selezionato {
      color: orange;
    }

    .center {
      text-align: center;
    }
  </style>
</head>

<body>
    <div class="box">
        <img src="immagini/LOGO.png">
        <?php
          include "navbar.php";
        ?>
    </div>
    <div class="center">
        <h1>Benvenuto <?php echo $username; ?></h1>
        <h2>Informazioni personali</h2>
        <p>Username: <?php echo $username; ?></p>
        <p>Email: <?php echo $email; ?></p>
        <br>
        <button onclick="location.href = 'logout.php';" style="background-color: #e83131; color: white; border: none; padding: 10px; border-radius: 5px;">Logout</button>
        <br>
        <h2><a href="cart.php">Il mio carrello</a></h2>

        <h2>I tuoi ordini:</h2>
        <?php 
        if (count($orders) == 0) {
            echo "<h3>Non hai ordini</h3>";
        }else{
            echo "<ul>";
            foreach ($orders as $order) {
                $orderID = $order["ID"];
                $status = $order["status"];
                echo "<li><a href='order.php?orderID=$orderID'>Ordine $orderID</a> - $status</li>";
            }
        }
        ?>
        </ul>
      </div>

</body>

</html>
