<?php
session_start();
if(!isset($_SESSION['email'])){
	header("Location: login.html");
}
?>
<!DOCTYPE html>
<html>
<body>
	Identificazione utente riuscita! <br />
	Benvenuto nell'area riservata <br />
	premi su <a href="logout.php">Logout</a> per disconnetterti
</body>
</html>