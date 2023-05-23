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
  <script>
    function updateConfig(obj){
      //console log the updated value
      let val = obj.value;
      if (val == 0){
        return
      }
      
      //send get request to update_config.php
      let xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          //console.log(this.responseText);
        }
      };
      xhttp.open("GET", "update_config.php?cid=" + val, true);
      xhttp.send();
      console.log("done");
    }
  </script>
  <div class="box">
    <img src="immagini/LOGO.png">
    <?php
      include "session.php";
      include "navbar.php";
    ?>
  </div>
  <img src="immagini/kepp-calm.jpg" id="destra">
  <img src="immagini/kepp-calm.jpg" id="sinistra">
  <br/><br/><br/>
    <?php
    include "config.php";
    echo $_SESSION['config-id']."<br/>";
    echo "id:".$_SESSION['ID'];
    $sql = "SELECT * FROM categories";
    $result = $con->query($sql);
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        echo "<label for='" . $row['name'] . "'>" . $row['name'].": ";
        if ($row['isConfigurationRequired'] == 1){
          echo "<a style='color: red;'>*</a>";
        }
        echo "</label>";
        echo "<select id='" . $row['name'] . "' onchange='updateConfig(this)'>";
        echo "<option selected value='0'>" . $row['name'] . "</option>";
        $sql2 = "SELECT * FROM components WHERE categoryID=" . $row['ID'];
        $result2 = $con->query($sql2);
        if ($result->num_rows > 0) {
          while ($row2 = $result2->fetch_assoc()) {
            echo "<option id=c".$row2['ID']." value='" . $row2['ID'] . "'>" . $row2['name'] . "</option>";
          }
        }
        echo "</select>";
      }
    }else{
      echo "<h1><b>Nessuna categoria trovata, contattare l'amministratore del sito.</b></h1>";
    }

    if(isset($_SESSION['config-id'])){
      $uuid = $_SESSION['config-id'];
      echo "<script>console.log('".$uuid."');</script>";
      $sql = "SELECT * FROM configurations WHERE uuid='" . $uuid."'";
      $result = $con->query($sql);
      $confID = $result->fetch_assoc()['ID'];
      echo "<script>console.log('".$confID."');</script>";
      //get all components of the configuration
      //set all matching options to selected
      $sql="SELECT * FROM configContents WHERE configurationID=" . $confID;
      $result = $con->query($sql);
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo "<script>document.getElementById('c".$row['componentID']."').selected=true;</script>";
        }
      }
    }else{
      $_SESSION['config-id']=uniqid();
      $uuid = $_SESSION['config-id'];
      $sql = "INSERT INTO configurations (uuid) VALUES ('" . $uuid . "')";
      $con->query($sql);
      $confID = $con->insert_id;
      
    }

    //add to cart button only if logged in
    //when pressed, redirect to add_to_cart.php
    if(isUserLoggedIn()){
      echo "<button onclick='addToCart()' style='
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
  ?>
  <br>
  <button style='
      display: block; 
      margin: 0 auto; 
      padding: 10px; 
      background-color: #ff9800;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
      ' onclick="cleanConfig()">Pulisi la configurazione</button>
  <script>
    function addToCart(){
      //get request to add_to_cart.php
      let xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          //redirect to user.php
          // window.location.href = "user.php";
          console.log("ok")
        }else if(this.readyState == 4 && this.status == 401){
          //redirect to login.php
          // window.location.href = "login.php";
          console.log("not auth")
        }else if(this.readyState == 4 && this.status == 400){
          //redirect to user.php
          alert("error nel aggiungere al carrello: " + this.responseText);
        }
      };
      xhttp.open("GET", "add_to_cart.php", true);
      xhttp.send();
      console.log("request sent");
    }

    function cleanConfig(){
      let selects = document.getElementsByTagName("select");
      for (let i = 0; i < selects.length; i++){
        selects[i].selectedIndex = 0;
      }
    }
  </script>
</body>

</html>