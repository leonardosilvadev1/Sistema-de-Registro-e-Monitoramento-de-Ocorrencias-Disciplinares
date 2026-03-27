<?php
define('HOST', 'localhost');
define('USUARIO', 'root');
define('SENHA', '');
define('BD', 'sisrom');

$conexao = mysqli_connect(HOST, USUARIO, SENHA, BD) or die('Não foi possível conectar');
?>