<?php
session_start();
?>

<html>

<head>
  <title>Catalogo</title>
  <link rel="stylesheet" href="./css/default.css">

  <style type="text/css">
    h1 {
      text-align: center;
    }

    .confronta {
      width: 25%;
      display: inline-block;
      margin: 15px;
    }

    .confronta img {
      width: 70%;
      height: 30%;
      margin-bottom: 5px;
    }

    .confronta-section {
      width: 80%;
      margin: 0 auto;
      text-align: center;
    }

    a{
        text-decoration: none;
        color: white;
    }
  </style>
</head>

<body>
  <div class="box">
    <img src="immagini/LOGO.png">
    <?php
    include "session.php";
    include "navbar.php";
    ?>
  </div>
  <img src="immagini/kepp-calm.jpg" id="destra">
  <img src="immagini/kepp-calm.jpg" id="sinistra">
  <div class="confronta-section">
    <?php
    include "config.php";

    if(isset($_GET["cat"])){
      //check if cat is a number
      if (!is_numeric($_GET["cat"])) {
        echo "<script type='text/javascript'>location.href = './catalogo.php';</script>";
        exit();
      }
      $cat = intval($_GET["cat"]);
      //get category name
      $sql = "SELECT name FROM categories WHERE ID = ?";
      $stmt = $con->prepare($sql);
      $stmt->bind_param("i", $cat);
      $stmt->execute();
      $result = $stmt->get_result();
      if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "<h1>Catalogo " . $row['name'] . "</h1>";
      } else {
        echo "<script type='text/javascript'>location.href = './catalogo.php';</script>";
        exit();
      }

      //print type of cat
      $sql = "SELECT * FROM components WHERE categoryID = ?";
      $stmt = $con->prepare($sql);
      $stmt->bind_param("i", $cat);
      $stmt->execute();
      $result = $stmt->get_result();
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo "<div class='confronta'>";
          echo "<a href='./scheda_prodotto.php?id=" . $row['ID'] . "'>";
          echo "<img src='" . $row['defaultImage'] . "' alt='".$row['name']."'>";
          echo "<br>";
          echo $row['name'] . "</a>";
          echo "</div>";
        }
      }
    }else{
      echo "<h1>Catalogo Componenti PC</h1>";
      $sql = "SELECT * FROM categories";
      $result = $con->query($sql);
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo "<div class='confronta'>";
          echo "<a href='./catalogo.php?cat=" . $row['ID'] . "'>";
          echo "<img src='" . $row['defaultImage'] . "' alt='".$row['name']."'>";
          echo "<br>";
          echo $row['name'] . "</a>";
          echo "</div>";
        }
      }
    }
    ?>
</body>

</html>