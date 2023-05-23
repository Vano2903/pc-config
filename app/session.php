<?php
function isUserLoggedIn() {
    if (isset($_SESSION['ID']) && $_SESSION['ID'] > 0) {
        return true;
    }
    return false;
}
?>