<?php
session_start();
include('database.php');

if(empty($_POST['email']) || empty($_POST['password'])){
    $_SESSION['mensagem'] = "Preencha todos os campos!";
    header('Location: tela_login.php');
    exit();
}

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = mysqli_prepare($conexao, "SELECT id_funcionario, nome, cargo, email, senha FROM funcionario WHERE email = ?");

if(!$stmt){
    die("Erro na query: " . mysqli_error($conexao));
}

mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if($row && password_verify($password, $row['senha'])){
    switch ($row['cargo']) {
        case 'Admin':
            $_SESSION['email'] = $row['email'];
            header('Location: ../pages/admin/painel_admin.php');
            exit();
            break;
        case 'Diretor':
            $_SESSION['email'] = $row['email'];
            header('Location: ../pages/painel_diretor.php');
            exit();
            break;
        case 'Coordenador':
            $_SESSION['email'] = $row['email'];
            header('Location: ../pages/painel_coordenador.php');
            exit();
            break;
        case 'DT':
            $_SESSION['email'] = $row['email'];
            header('Location: ../pages/painel_dt.php');
            exit();
            break;
    }
}else{
    $_SESSION['mensagem'] = "Login invalido";
    header('Location: ../pages/tela_login.php');
    exit();
}
?>