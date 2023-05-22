<html>
  <head>
    <title>Home page</title>
  <link rel="stylesheet" href="./css/default.css">
    <style type="text/css">
      .news-section{
        width: 80%;
        margin: 0 auto;
        text-align: center;
      }
      .news-box{
        width: 25%;
        display: inline-block;
        margin: 15px;
      }
      .news-box img{
        width: 90%;
        height: 30%;
        margin-bottom: 5px;
      }
      nav{
        text-decoration: line-through;
      }
    </style>
  </head>
  <body>
      <div class="box">
       <img src="immagini/LOGO.png">
        <ul>
          <li><a href="home_page.php" class="menu" id="selezionato">Home</a></li>
          <li><a href="Configuratore.html" class="menu">Configuratore</a></li>
          <li><a href="catalogo.php" class="menu">Catalogo</a></li>
          <li><a href="pagina_di_presentazione.html" class="menu">Chi siamo</a></li>
          <li><a href="login.php" class="menu">Login</a></li>
        </ul>
      </div>
      <img src="immagini/kepp-calm.jpg" id="destra">
      <img src="immagini/kepp-calm.jpg" id="sinistra">
      <div class="news-section">
      <h1>Notizie PC</h1>
      <?php
      include "config.php";
      $sql = "SELECT * FROM news";
      $result = $con->query($sql);
      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()){
          echo "<div class='news-box'>";
          echo "<a href='".$row['link']."'>";
          echo "<img src='".$row['image']."' alt='bruh'>";
          echo "<br>";
          echo $row['title']."</a>";
          echo "</div>";
        }
      }else{
        echo "<h2>Nulla di nuovo sotto il sole, vatti a leggere un libro...</h2>";
      }
      ?>
      <h1>Offerte</h1>
      <?php
      $sql = "SELECT c.* FROM offers JOIN components c ON offers.componentID = c.ID";
      $result = $con->query($sql);
      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()){
          echo "<div class='news-box'>";
          echo "<img src='".$row['image']."' alt='".$row['name']."'>";
          echo "<br>";
          echo $row['name'];
          echo "<br>";
          if ($row['discountPercentage'] == 0){
            echo "<div><p>Prezzo: " . $row['price'] . "</p></div>";
        }else{
            $price = $row['price'];
            $discount = $row['discountPercentage'];
            $discountPrice = round($price * (1 - $discount / 100), 2);
            echo "<div><p>Prezzo: <del>" . $price . "</del>    -" . $discount. "% <br>".$discountPrice."</p></div>";
        }
          echo "</div>";
        }
      }else{
        echo "<h2>Siamo molto tirchi quindi nessuna offerta >:(</h2>";
      }
      ?>
    </div>
  </body>
</html>
