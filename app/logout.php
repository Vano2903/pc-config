<?php 
session_start();
$_SESSION=array();
session_destroy();
echo "Disconnessione riuscita, sarai reindirizzato alla home tra 5 secondi";
echo "<script type='text/javascript'> setTimeout(function(){location.href = './home_page.php';}, 5000);</script>";
?>