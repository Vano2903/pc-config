<?php
    session_start();
    include "session.php";
    if (!isUserLoggedIn()) {
        header("Location: login.php");
        exit();
    }
    
    include "config.php";
    $userID = $_SESSION['ID'];
    $cartID = $_GET['id'];
    if (!isset($cartID)) {
        echo "Errore, cartID non settato";
        exit();
    }
    $sql = "SELECT * FROM cart WHERE ID=?";
    $stmp = $con->prepare($sql);
    $stmp->bind_param("i", $cartID);
    $stmp->execute();
    $result = $stmp->get_result();
    $row = $result->fetch_assoc();
    if (!$row) {
        echo "Errore";
        exit();
    }

    if ($row['userID'] != $userID) {
        echo "Errore, non possiedi questo carrello";
        exit();
    }
    
    //get configuration ID
    $confID = $row['configurationID'];

    //get components of the configuration
    $sql = "SELECT * FROM configContents WHERE configurationID=?";
    $stmp = $con->prepare($sql);
    $stmp->bind_param("i", $confID);
    $stmp->execute();
    $result = $stmp->get_result();
    $components = array();
    if($result->num_rows > 0){
        while ($row = $result->fetch_assoc()) {
            $componentID = $row['componentID'];
            $sql = "SELECT * FROM components WHERE ID=?";
            $stmp = $con->prepare($sql);
            $stmp->bind_param("i", $componentID);
            $stmp->execute();
            $result2 = $stmp->get_result();
            $row2 = $result2->fetch_assoc();
            $components[] = $row2;
        }
    }
?>
<html>

<head>
  <title>Carrello</title>
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
        <h1>Carrello</h1>
        <h2>Configurazione</h2>
        <!-- create a table with all the components -->
        <center>
        <table class="center">
            <tr>
                <th>Nome</th>
                <th>Prezzo</th>
                <th>Immagine</th>
            </tr>
            <?php
                foreach ($components as $component) {
                    echo "<tr>";
                    echo "<td>" . $component['name'] . "</td>";
                    if ($component['discountPercentage'] == 0){
                        echo "<td>" . $component['price'] . "</td>";
                    }else{
                        $price = $component['price'];
                        $discount = $component['discountPercentage'];
                        $discountPrice = round($price * (1 - $discount / 100), 2);
                        echo "<td><del>".$price."</del>    -" . $discount. "% <br>".$discountPrice."</td>";
                    }
                    echo "<td><img src='" . $component['defaultImage'] . "' alt='" . $component['name'] . "'></td>";
                    echo "</tr>";
                }
            ?>
        </table>
        <h2>Prezzo totale: <?php 
            $totalPrice = 0;
            foreach ($components as $component) {
                //check for discounts
                $price = $component['price'];
                if ($component['discountPercentage'] != 0){
                    $discount = $component['discountPercentage'];
                    $discountPrice = round($component['price'] * (1 - $discount / 100), 2);
                    $price = $discountPrice;
                }
                $totalPrice += $price ;
            }
            echo $totalPrice;
        ?>€</h2>
    </center>
    <?php
        $sql = "SELECT * FROM cart WHERE ID=?";
        $stmp = $con->prepare($sql);
        $stmp->bind_param("i", $cartID);
        $stmp->execute();
        $result = $stmp->get_result();
        $row = $result->fetch_assoc();
        $status = $row['cartStatus'];
        if ($status == "pending") {
            echo "<button onclick='buy(".$cartID.")'>Acquista</button>";
        }else if ($status=="failed"){
            echo "<h2>Acquisto fallito</h2>";
            echo "<p>contatta l'assistenza, il tuo carrello è id: $cartID, comunica questa informazione all'assistenza e sistemeremo il tuo problema</p>";
        }else{
            echo "<h2>Carrello già processato</h2>";
        }
    ?>
    </div>
    <script>
        function buy(cartID){
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    console.log("success");
                }else if (this.readyState == 4 && this.status == 400){
                    alert("Errore, contatta l'assistenza: "+this.responseText);
                }else if (this.readyState == 4 && this.status == 401){
                    console.log("not logged");
                    //redirect to login
                }
            };
            xhttp.open("GET", "buy.php?id="+cartID, true);
            xhttp.send();
        }
    </script>
</body>

</html>
