<?php
session_start();
if(!isset($_SESSION['email'])){
        header('Location: ../tela_login.php');
        exit();
    }
?>