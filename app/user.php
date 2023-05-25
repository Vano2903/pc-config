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
    $sql = "SELECT * FROM cart WHERE userID=?";
    $stmp = $con->prepare($sql);
    $stmp->bind_param("i", $id);
    $stmp->execute();
    $result = $stmp->get_result();
    $carts = array();
    if($result->num_rows > 0){
        while ($row = $result->fetch_assoc()) {
            $carts[] = $row;
        }
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
        <h2>I tuoi carrelli:</h2>
        <?php 
        if (count($carts) == 0) {
            echo "<h3>Non hai carrelli</h3>";
        }else{
            echo "<ul>";
            foreach ($carts as $cart) { ?>
                <li>
                    <a href="cart.php?id=<?php echo $cart['ID']; ?>">Carrello <?php
                      echo $cart['ID'];
                      echo " (".$cart["cartStatus"].")";
                    ?></a>
                </li>
            <?php 
            } 
        }
        ?>
        </ul>
        <button onclick="location.href = 'logout.php';" style="background-color: #e83131; color: white; border: none; padding: 10px; border-radius: 5px;">Logout</button>
      </div>

</body>

</html>
