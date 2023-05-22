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
      <li><a href="configuratore.php" class="menu">Configuratore</a></li>
      <li><a href="catalogo.php" class="menu">Catalogo</a></li>
      <li><a href="pagina_di_presentazione.html" class="menu">Chi siamo</a></li>
      <li><a href="login.php" class="menu">Login</a></li>
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
    <input type="password" id="password1" name="password1"><br><br>
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