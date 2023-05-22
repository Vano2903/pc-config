<html>
  <head>
    <title>Login</title>
    <link rel="stylesheet" href="./css/default.css">
  </head>
  <body>
  <?php
    session_start();
    if(isset($_SESSION['email'])){
      echo "<script type='text/javascript'>location.href = './user.php';</script>";
      exit();
    }
  ?>
      <div class="box">
       <img src="immagini/LOGO.png">
        <ul>
          <li><a href="home_page.php" class="menu">Home</a></li>
          <li><a href="configuratore.php" class="menu">Configuratore</a></li>
          <li><a href="catalogo.php" class="menu">Catalogo</a></li>
          <li><a href="pagina_di_presentazione.html" class="menu">Chi siamo</a></li>
          <li><a href='login.php' class='menu'>Login</a></li>
        </ul>
      </div>
      <img src="immagini/kepp-calm.jpg" id="destra">
      <img src="immagini/kepp-calm.jpg" id="sinistra">
      <form id="loginForm">
        <label for="username">Email:</label>
        <input type="text" id="email" name="email"><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password"><br>
        <input type="checkbox" onclick="showPassword()">Mostra password<br>
        <a href="register.html"><input type="button" value="Registrati"></a><br>
        <input type="button" value="Login" onclick="submitForm()">
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
    <?php
    include "config.php";

    // acquisizione dati dal form HTML
    $email = $_POST["email"];
    $password = $_POST["password"];

    // protezione per SQL injection
    $email = stripslashes($email);
    $password = stripslashes($password);
    $email = mysql_real_escape_string($email);
    $password = mysql_real_escape_string($password);
    $passmd5 = md5($password);
    // lettura della tabella utenti
    $sql = "SELECT * FROM users WHERE email=? AND password=?"
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ss", $email, $passmd5);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        echo "Identificazione non riuscita: nome utente o password errati <br />";
        exit();
    }

    session_start();
    $_SESSION['logged'] = true;
    $_SESSION['ID'] = $result['ID'];
    header("Location: loginok.php");
    ?>
  </body>
</html>