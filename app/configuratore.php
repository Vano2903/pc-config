<?php
session_start();
?>
<html>

<head>
  <title>Configuratore</title>
  <link rel="stylesheet" href="./css/default.css">

  <style type="text/css">
    .rettangolo {
      height: 200px;
      width: 200px;
      display: inline-block;
      margin: 20px;
      text-align: center;
    }

    .rettangolo img {
      height: 250px;
      width: 250px;
      padding-block-end: 40px;
    }

    .rettangolo p {
      text-align: center;
      font-weight: bold;
      margin-top: 10px;
    }

    .rettangolo select {
      text-align: center;
      background-color: white;
      width: 80%;
      margin: 10px auto;
    }

    .rettangolo span {
      text-align: center;
      display: none;
    }

    .container {
      max-width: 800px;
      margin: 0 auto;
      padding: 20px;
      background-color: #f5f5f5;
      font-family: Arial, sans-serif;
    }

    h2 {
      font-size: 24px;
      font-weight: bold;
      margin-top: 30px;
      margin-bottom: 10px;
    }

    label {
      display: block;
      font-size: 20px;
      font-weight: bold;
      margin-bottom: 5px;
      text-align: center;
    }

    select {
      display: block;
      width: 60%;
      padding: 10px;
      margin-left: 20%;
      border: 1px solid;
      border-radius: 5px;
      font-size: 16px;
      margin-bottom: 1%;
    }
  </style>
</head>

<body>
  <div class="box">
    <img src="immagini/LOGO.png">
    <ul>
      <li><a href="home_page.php" class="menu">Home</a></li>
      <li><a href="configuratore.php" class="menu" id="selezionato">Configuratore</a></li>
      <li><a href="catalogo.php" class="menu">Catalogo</a></li>
      <li><a href="pagina_di_presentazione.php" class="menu">Chi siamo</a></li>
      <?php
      include "session.php";
      if (isUserLoggedIn()) {
        echo "<li><a href='user.php' class='menu'>User</a></li>";
      }else{
        echo "<li><a href='login.php' class='menu'>Login</a></li>";
      }
      ?>    </ul>
  </div>
  <img src="immagini/kepp-calm.jpg" id="destra">
  <img src="immagini/kepp-calm.jpg" id="sinistra">
  <br/><br/><br/>
  <?php
  session_start();
  include "config.php";
  $sql = "SELECT * FROM categories";
  $result = $con->query($sql);
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      echo "<label for='" . $row['name'] . "'>" . $row['name'].": ";
      if ($row['isConfigurationRequired'] == 1){
        echo "<a style='color: red;'>*</a>";
      }
      echo "</label>";
      echo "<select id='" . $row['name'] . "'>";
      echo "<option value='0'>" . $row['name'] . "</option>";
      $sql2 = "SELECT * FROM components WHERE categoryID=" . $row['ID'];
      $result2 = $con->query($sql2);
      if ($result->num_rows > 0) {
        while ($row2 = $result2->fetch_assoc()) {
          echo "<option value='" . $row2['ID'] . "'>" . $row2['name'] . "</option>";
        }
      }
      echo "</select>";
    }
    
    if (isset($_SESSION['logged'])){
      //add to cart button only if logged in
      echo "<button style='
      display: block; 
      margin: 0 auto; 
      padding: 10px; 
      background-color: #4CAF50; 
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
    '>Aggiungi al carrello</button>";
    }
  }

  ?>
</body>

</html>