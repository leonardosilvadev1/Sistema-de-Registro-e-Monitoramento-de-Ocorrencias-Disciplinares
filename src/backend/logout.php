<?php
    session_start();
    session_destroy();
    header('Location: ../pages/tela_login.php');
    exit();
?>