<?php
session_start();
?>
<html>

<head>
  <title>Home page</title>
  <link rel="stylesheet" href="./css/default.css">

</head>

<body>
  <div class="box">
    <img src="immagini/LOGO.png">
    <ul>
      <li><a href="home_page.php" class="menu" id="selezionato">Home</a></li>
      <li><a href="configuratore.php" class="menu">Configuratore</a></li>
      <li><a href="catalogo.php" class="menu">Catalogo</a></li>
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

</body>

</html>