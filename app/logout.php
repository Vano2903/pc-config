<?php 
session_start();
$_SESSION=arrey();
session_destroy();
echo "Disconnessione riuscita"
?>