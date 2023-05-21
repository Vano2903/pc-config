<?php
      $host="locahost";
      $username="root";
      $password="root";
      $db_nome="sito";
      mysqli_connect($host,$username,$password)
        or die('Connessione non riuscita: '.mysql_error());
      mysql_select-db($db_nome)
        or die('Accesso al database non riucito: '.mysql_error());
      $username=$_POST["username"];
      $email=$_POST["email"];
      $password=$_POST["password"];
      $email=stripslashes($email);
      $password=stripslashes($password);
      $email=mysql_real_escape_string($email);
      $password=mysql_real_escape_string($password);
      $passmd5=md5($password);
      $sql="SELECT * FROM utente WHERE Email='$email' AND Password='$passmd5'";
      $result=mysql_query($sql);
      $conta=mysql_num_rows($result);
      if($conta==1){
        session_start();
        $_SESSION['email']=$email;
        $_SESSION['password']=$passmd5;
        header("Location: loginok.php");
      }
      else{
        echo "Identificazione non riuscita:nome utente o password errati <br />";
        echo "Torna a pagina di <a href=\"login.html\">login</a> ";
      }
    ?>