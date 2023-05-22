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
          <li><a href="Configuratore.html" class="menu">Configuratore</a></li>
          <li><a href="catalogo.php" class="menu">Catalogo</a></li>
          <li><a href="pagina_di_presentazione.html" class="menu">Chi siamo</a></li>
          <li><a href="login.php" class="menu">Login</a></li>
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
    
    $host="localhost";
    $username="root";
    $password="root";
    $db_nome="pcbuilder";
    mysql_connect($host, $username, $password) or die('Impossibile connettersi al
    server: ' . mysql_error());
    mysql_select_db($db_nome) or die ('Accesso al database non riuscito: '
    . mysql_error());
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
    $sql="SELECT * FROM $tab_nome WHERE Email='$email' AND Password='$passmd5'";
    $result=mysql_query($sql);
    $conta=mysql_num_rows($result);
    if($conta==1){
    session_start();
    $_SESSION['email'] = $email;
    $_SESSION['password'] = $passmd5;
    header("Location: loginok.php");
    }
    else {
    echo "Identificazione non riuscita: nome utente o password errati <br />";
    echo "Torna a pagina di <a href=\"login.html\">login</a>";
    }
    ?>
  </body>
</html>