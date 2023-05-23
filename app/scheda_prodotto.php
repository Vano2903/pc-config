<?php
session_start();
?>

<html>

<head>
  <title>Scheda prodotto</title>
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

    .link{
        /* text-decoration: none; */
        color: rgb(0, 180, 251);
    }
  </style>
</head>

<body>
  <div class="box">
    <img src="immagini/LOGO.png">
    <ul>
      <li><a href="home_page.php" class="menu">Home</a></li>
      <li><a href="configuratore.php" class="menu">Configuratore</a></li>
      <li><a href="catalogo.php" class="menu" id="selezionato">Catalogo</a></li>
      <li><a href="pagina_di_presentazione.php" class="menu">Chi siamo</a></li>
      <?php
      include "session.php";
      if (isUserLoggedIn()) {
        echo "<li><a href='user.php' class='menu'>User</a></li>";
      }else{
        echo "<li><a href='login.php' class='menu'>Login</a></li>";
      }
      ?>
    </ul>
  </div>
  <img src="immagini/kepp-calm.jpg" id="destra">
  <img src="immagini/kepp-calm.jpg" id="sinistra">
  <div class="confronta-section">
    <?php
    include "config.php";

    if(!isset($_GET["id"])){
        echo "<script type='text/javascript'>location.href = './catalogo.php';</script>";
        exit();
    }

    if (!is_numeric($_GET["id"])) {
        echo "<script type='text/javascript'>location.href = './catalogo.php';</script>";
        exit();
    }
    
    $id = intval($_GET["id"]);
    //get category name
    $sql = "SELECT c.*, b.name as brandName FROM components c JOIN brands b ON c.brandID = b.ID WHERE c.ID = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        echo "<script type='text/javascript'>location.href = './catalogo.php';</script>";
        exit();
    }
    $row = $result->fetch_assoc();
    echo "<h1>" . $row['name'] . "</h1><br><hr><br>";
    echo "<div><img src='" . $row['defaultImage'] . "' alt='".$row['name']."'></div>";
    echo "<br>";
    echo "<div><p>Marca: " . $row['brandName'] . "</p></div>";
    echo "<div><p>Descrizione: " . $row['description'] . "</p></div>";
    if($row['availability'] == 1){
        if ($row['discountPercentage'] == 0){
            echo "<div><p>Prezzo: " . $row['price'] . "</p></div>";
        }else{
            $price = $row['price'];
            $discount = $row['discountPercentage'];
            $discountPrice = round($price * (1 - $discount / 100), 2);
            echo "<div><p>Prezzo: <del>" . $price . "</del>    -" . $discount. "% <br>".$discountPrice."</p></div>";
        }
        if (isset($row['reviewUrl'])){
            echo "<div><p><a class='link' href='".$row['reviewUrl']."'>Scopri di più</a></p></div>";
        }
    }else{
        echo "<div><p>Non disponibile</p></div>";
    }
    echo "<br>";
    //get components info
    $sql = "select * from componentsInfo where componentID=".$id.";";
    $result = $con->query($sql);
    if ($result->num_rows > 0) {
        echo "<div><p>Specifiche tecniche:</p></div>";
        echo "<div><ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>" . $row['infoKey'] . ": " . $row['infoValue'] . "</li>";
        }
        echo "</ul></div>";
    }else{
        echo "<div><p>Non ci sono specifiche tecniche per questo componente</p></div>";
    }
    ?>
</body>

</html>