<?php
echo "<ul>";
//get name of current page
$currentPage = basename($_SERVER['PHP_SELF']);
if($currentPage == "home_page.php"){
    echo "<li><a href='home_page.php' class='menu' id='selezionato'>Home</a></li>";
}else{
    echo "<li><a href='home_page.php' class='menu'>Home</a></li>";
}

if($currentPage == "configuratore.php"){
    echo "<li><a href='configuratore.php' class='menu' id='selezionato'>Configuratore</a></li>";
}else{
    echo "<li><a href='configuratore.php' class='menu'>Configuratore</a></li>";
}

if($currentPage == "catalogo.php"){
    echo "<li><a href='catalogo.php' class='menu' id='selezionato'>Catalogo</a></li>";
}else{
    echo "<li><a href='catalogo.php' class='menu'>Catalogo</a></li>";
}

if($currentPage == "pagina_di_presentazione.php"){
    echo "<li><a href='pagina_di_presentazione.php' class='menu' id='selezionato'>Chi siamo</a></li>";
}else{
    echo "<li><a href='pagina_di_presentazione.php' class='menu'>Chi siamo</a></li>";
}

if (isUserLoggedIn()) {
    echo "<li><a href='user.php' class='menu'>User</a></li>";
}else{
    echo "<li><a href='login.php' class='menu'>Login</a></li>";
}
echo "</ul>";
?>