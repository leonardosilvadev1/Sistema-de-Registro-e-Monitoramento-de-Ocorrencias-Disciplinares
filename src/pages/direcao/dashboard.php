<?php
session_start();
/**@var mysqli $conexao */
    if(!isset($_SESSION['email']) || !isset($_SESSION['cargo'])){
        header('Location: ../tela_login.php');
        exit();
    }
?>