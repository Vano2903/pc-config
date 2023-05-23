<?php
  session_start();
  if (isset($_SESSION["logged"]) && ($_SESSION["logged"] == true)) {
    //già loggato
    header("Location: home_page.php");
    exit();
  }
  include "config.php";

  if (!isset($_POST["email"]) || !isset($_POST["password"])) {
    echo "ci sono dei campi mancanti, impossibile effettuare il login";
  }else{
    $username = $_POST["username"];
    filter_var($username, FILTER_SANITIZE_STRING);
    
    $email = $_POST["email"];
    filter_var($email, FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      echo "Email non valida";
      exit();
    }
    $password = $_POST["password"];
    $passmd5 = md5($password);
  
    // lettura della tabella utenti
    $sql = "SELECT * FROM users WHERE email=? AND password=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ss", $email, $passmd5);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        echo "Identificazione non riuscita: nome utente o password errati <br />";
        exit();
    }
    $row = $result->fetch_assoc();

    $_SESSION['logged'] = true;
    $_SESSION['ID'] = $row['ID'];
    header("Location: home_page.php");
  }
?>

<html>
  <head>
    <title>Login</title>
    <link rel="stylesheet" href="./css/default.css">
  </head>
  <body>
  
      <div class="box">
       <img src="immagini/LOGO.png">
        <ul>
          <li><a href="home_page.php" class="menu">Home</a></li>
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
      <form action="login.php" method="post" id="loginForm">
        <label for="username">Email:</label>
        <input type="text" id="email" name="email"><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password"><br>
        <input type="checkbox" onclick="showPassword()">Mostra password<br>
        <input type="submit" value="login">
      </form>
      <button onclick="window.location.href='register.php'">Registrati</button>
      <script type="text/javascript">
    function showPassword() {
      var passwordInput = document.getElementById("password");
        if (passwordInput.type === "password") {
          passwordInput.type = "text";
        } else {
          passwordInput.type = "password";
      }
    }
    </script>
   
  </body>
</html>