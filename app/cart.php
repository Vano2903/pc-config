<?php
    session_start();
    include "session.php";
    if (!isUserLoggedIn()) {
        header("Location: login.php");
        exit();
    }
    
    include "config.php";
    $userID = $_SESSION['ID'];

    //get all components from the cartContents table
    $sql = "SELECT * FROM cartContents WHERE userID=?";
    $stmp = $con->prepare($sql);
    $stmp->bind_param("i", $userID);
    $stmp->execute();
    $result = $stmp->get_result();
    $components = array();
    if($result->num_rows > 0){
        while ($row = $result->fetch_assoc()) {
            $sql = "SELECT * FROM components WHERE ID=?";
            $stmp = $con->prepare($sql);
            $stmp->bind_param("i", $row['componentID']);
            $stmp->execute();
            $result2 = $stmp->get_result();
            $component = $result2->fetch_assoc();
            $component['quantity'] = $row['quantity'];
            $components[] = $component;
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
        <!-- create a table with all the components -->
        <center>
            <?php
                if (count($components) == 0){
                    echo "<h3>Il carrello è vuoto</h3>";
                }else{
                    ?>
        <table class="center">
            <tr>
                <th>Nome</th>
                <th>Prezzo</th>
                <th>Quantità</th>
                <th>Immagine</th>
                <th></th>
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
                    echo "<td>" . $component['quantity'] . "</td>";
                    echo "<td><img src='" . $component['image'] . "' alt='" . $component['name'] . "'></td>";
                    echo "<td><button onclick='removeFromCart(".$component['ID'].")'>Rimuovi dal carrello</button></td>";
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
                $totalPrice += $price*$component['quantity'] ;
            }
            echo $totalPrice;
        ?>€</h2>
    </center>
    <button onclick='buy()'>Acquista</button>
    <?php
        }
    ?>
    </div>
    <script>
        function buy(){
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    console.log("success");
                    location.reload();
                }else if (this.readyState == 4 && (this.status == 400 || this.status == 500)){
                    alert("Errore, contatta l'assistenza: "+this.responseText);
                }else if (this.readyState == 4 && this.status == 401){
                    console.log("not logged");
                    //redirect to login
                }
            };
            xhttp.open("GET", "buy.php", true);
            xhttp.send();
        }

        function removeFromCart(componentID){
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    location.reload();
                }else if (this.readyState == 4 && this.status == 400){
                    alert("Errore: "+this.responseText);
                }else if (this.readyState == 4 && this.status == 500){
                    alert("Errore, contatta l'assistenza: "+this.responseText);
                }else if (this.readyState == 4 && this.status == 401){
                    console.log("not logged");
                    //redirect to login
                }else if(this.readyState == 4){
                    console.log(this.responseText)
                }
            };
            xhttp.open("GET", "removeFromCart.php?cid="+componentID, true);
            xhttp.send();

        }
    </script>
</body>

</html>
