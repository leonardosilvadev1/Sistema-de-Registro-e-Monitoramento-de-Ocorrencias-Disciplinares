<?php
define('HOST', 'localhost');
define('USUARIO', 'root');
define('SENHA', '');
define('BD', 'sisrom');

$conexao = mysqli_connect(HOST, USUARIO, SENHA, BD);
if(!$conexao){
    die("Erro na conexão: " . mysqli_connect_error());
}
mysqli_set_charset($conexao, "utf8");
?>  