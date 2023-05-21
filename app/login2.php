<?php
include "db.php";

session_start();
if(!isset($_SESSION["uid"])){
    header("location:index.php");
}