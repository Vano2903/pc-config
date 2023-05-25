<?php
  session_start();
  if (isset($_SESSION["logged"]) && ($_SESSION["logged"] == true)) {
    //già loggato
    header("Location: home_page.php");
    exit();
  }
  include "config.php";

  if (isset($_POST["email"]) && isset($_POST["password"])) {
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
    <style>
      form {
        margin-left: 40%;
        margin-right: 40%;
        margin-top: 10%;
        background-color: red;
        text-align: center;
        padding: 1%;
        border-radius: 5%;
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
      <form action="login.php" method="post" id="loginForm">
        <label for="username">Email:</label>
        <input type="text" id="email" name="email"><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password"><br>
        <input type="checkbox" onclick="showPassword()">Mostra password<br>
        <input type="submit" value="login">
        <button onclick="window.location.href='register.php'">Registrati</button>
      </form>
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