<?php
session_start();
include('database.php');

    if(empty($_POST['nome']) || empty($_POST['matricula']) || empty($_POST['curso']) || empty($_POST['serie']) || empty($_POST['tel_responsavel'])){
        $_SESSION['mensagem'] = "Preencha todos os campos!";
        if($_SESSION['cargo'] == 'Admin'){
            header('Location: ../pages/admin/alunos.php');
        } elseif($_SESSION['cargo'] == 'Diretor'){
            header('Location: ../pages/direcao/alunos.php');
        } elseif($_SESSION['cargo'] == 'Coordenador'){
            header('Location: ../pages/coordenacao/alunos.php');
        }
        exit();
    }

    $name = $_POST['nome'];
    $matricula = $_POST['matricula'];
    $curso = $_POST['curso'];
    $serie = $_POST['serie'];
    $tel_responsavel = $_POST['tel_responsavel'];

    $stmt = mysqli_prepare($conexao, "INSERT INTO aluno (nome, matricula, curso, serie, telefone_responsavel) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sssss", $name, $matricula, $curso, $serie, $tel_responsavel);

    if(mysqli_stmt_execute($stmt)){
        $_SESSION['mensagem'] = "Aluno cadastrado com sucesso!";
        if($_SESSION['cargo'] == 'Admin'){
            header('Location: ../pages/admin/alunos.php');
        } elseif($_SESSION['cargo'] == 'Diretor'){
            header('Location: ../pages/direcao/alunos.php');
        } elseif($_SESSION['cargo'] == 'Coordenador'){
            header('Location: ../pages/coordenacao/alunos.php');
        }
        exit();
    } else {
        $_SESSION['mensagem'] = "Erro ao cadastrar aluno!";
        if($_SESSION['cargo'] == 'Admin'){
            header('Location: ../pages/admin/alunos.php');
        } elseif($_SESSION['cargo'] == 'Diretor'){
            header('Location: ../pages/direcao/alunos.php');
        } elseif($_SESSION['cargo'] == 'Coordenador'){
            header('Location: ../pages/coordenacao/alunos.php');
        }
        exit();
    }
?>