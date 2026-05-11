<?php
session_start();
include('database.php');

if($_SESSION['cargo'] == Admin){
    if(empty($_POST['name']) || empty($_POST['cargo']) || empty($_POST['email']) ||empty($_POST['password'])){
        $_SESSION['mensagem'] = "Preencha todos os campos";
        header('Location: ../pages/admin/funcionarios.php');
        exit();
    }

    $nome = $_POST['name'];
    $cargo = $_POST['cargo'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $senha_hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = mysqli_prepare($conexao, "INSERT INTO funcionario (nome, cargo, email, senha) VALUES (?, ?, ?, ?)");

    mysqli_stmt_bind_param($stmt, "ssss", $nome, $cargo, $email, $senha_hash);

    if(mysqli_stmt_execute($stmt)){
        $_SESSION['mensagem'] = "Cadastro realizado com sucesso!";
    }else{
        $_SESSION['mensagem'] = "Erro ao cadastrar!";
    }

    header('Location: ../pages/admin/funcionarios.php');
    exit();
}elseif($_SESSION['cargo'] == Diretor){
    if(empty($_POST['name']) || empty($_POST['cargo']) || empty($_POST['email']) ||empty($_POST['password'])){
        $_SESSION['mensagem'] = "Preencha todos os campos";
        header('Location: ../pages/direcao/funcionarios.php');
        exit();
    }

    $nome = $_POST['name'];
    $cargo = $_POST['cargo'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $senha_hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = mysqli_prepare($conexao, "INSERT INTO funcionario (nome, cargo, email, senha) VALUES (?, ?, ?, ?)");

    mysqli_stmt_bind_param($stmt, "ssss", $nome, $cargo, $email, $senha_hash);

    if(mysqli_stmt_execute($stmt)){
        $_SESSION['mensagem'] = "Cadastro realizado com sucesso!";
    }else{
        $_SESSION['mensagem'] = "Erro ao cadastrar!";
    }

    header('Location: ../pages/direcao/funcionarios.php');
    exit();
}elseif($_SESSION['cargo'] == Diretor){
    if(empty($_POST['name']) || empty($_POST['cargo']) || empty($_POST['email']) ||empty($_POST['password'])){
        $_SESSION['mensagem'] = "Preencha todos os campos";
        header('Location: ../pages/direcao/funcionarios.php');
        exit();
    }

    $nome = $_POST['name'];
    $cargo = $_POST['cargo'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $senha_hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = mysqli_prepare($conexao, "INSERT INTO funcionario (nome, cargo, email, senha) VALUES (?, ?, ?, ?)");

    mysqli_stmt_bind_param($stmt, "ssss", $nome, $cargo, $email, $senha_hash);

    if(mysqli_stmt_execute($stmt)){
        $_SESSION['mensagem'] = "Cadastro realizado com sucesso!";
    }else{
        $_SESSION['mensagem'] = "Erro ao cadastrar!";
    }

    header('Location: ../pages/direcao/funcionarios.php');
    exit();
}
?>