<?php
session_start();
include('database.php');

if (!isset($_GET['id'])) {
    die("ID do aluno não informado.");
}

$id = $_GET['id'];
$stmt = mysqli_prepare($conexao, "DELETE FROM aluno WHERE id_aluno = ?");
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['mensagem'] = "Removido com sucesso!";
    if($_SESSION['cargo'] == 'Admin'){
    header('Location: ../pages/admin/alunos.php');
    } elseif($_SESSION['cargo'] == 'Diretor'){
        header('Location: ../pages/direcao/alunos.php');
    } elseif($_SESSION['cargo'] == 'Coordenador'){
        header('Location: ../pages/coordenacao/alunos.php');
    }
    exit;
} else {
    $_SESSION['mensagem'] = "Erro ao remover aluno: " . mysqli_error($conexao);
    if($_SESSION['cargo'] == 'Admin'){
    header('Location: ../pages/admin/alunos.php');
    } elseif($_SESSION['cargo'] == 'Diretor'){
        header('Location: ../pages/direcao/alunos.php');
    } elseif($_SESSION['cargo'] == 'Coordenador'){
        header('Location: ../pages/coordenacao/alunos.php');
    }
}
?>