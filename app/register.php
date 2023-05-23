<?php
  session_start();
  if (isset($_SESSION["logged"]) && ($_SESSION["logged"] == true)) {
    //già loggato
    header("Location: home_page.php");
    exit();
  }

  include "config.php";
  if (!isset($_POST["username"]) || !isset($_POST["email"]) || !isset($_POST["password"])) {
    echo "ricevuto nulla. quit";
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

    //check if user exists
    $sql = "SELECT * FROM users WHERE email=?";
    $stmp = $con->prepare($sql);
    $stmp->bind_param("s", $email);
    $stmp->execute();
    $result = $stmp->get_result();
    $row = $result->fetch_assoc();
    if ($row) {
      echo "Email già registrata";
      exit();
    }

    //register user
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmp = $con->prepare($sql);
    echo $email;
    $stmp->bind_param("sss", $username, $email, $passmd5);
    $stmp->execute();
    // $result = $stmp->get_result();
    $id = $stmp->insert_id;
    if ($id) {
      $_SESSION['logged'] = true;
      $_SESSION['ID'] = $id;
      header("Location: loginok.php");
      echo "Registrazione avvenuta con successo";
      //redirect to user page
    } else {
      echo "Errore nella registrazione";
    }
  }
?>
<html>

<head>
  <title>Register</title>
  <link rel="stylesheet" href="./css/default.css">

  <style type="text/css">
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
    <ul>
      <li><a href="home_page.php" class="menu">Home</a></li>
      <li><a href="Configuratore.php" class="menu">Configuratore</a></li>
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
  <form action="register.php" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username"><br><br>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email"><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password1" name="password"><br><br>
    <label for="password">Conferma password:</label>
    <input type="password" id="password2" name="password2"><br><br>
    <input type="checkbox" onclick="showPassword()">Mostra password<br><br>
    <input type="submit" value="Registrati">
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