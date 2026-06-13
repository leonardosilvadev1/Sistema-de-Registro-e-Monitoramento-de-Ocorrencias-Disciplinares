<?php
session_start();
include('database.php');

if (!isset($_GET['id'])) {
    die("ID da Ocorrencia não informado.");
}

$id = $_GET['id'];
$stmt = mysqli_prepare($conexao, "DELETE FROM ocorrencia WHERE id_ocorrencia = ?");
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['mensagem'] = "Removido com sucesso!";
    if($_SESSION['cargo'] == 'Admin'){
    header('Location: ../pages/admin/ocorrencias.php');
    } elseif($_SESSION['cargo'] == 'Diretor'){
        header('Location: ../pages/direcao/ocorrencias.php');
    } elseif($_SESSION['cargo'] == 'Coordenador'){
        header('Location: ../pages/coordenacao/ocorrencias.php');
    }
    exit;
} else {
    $_SESSION['mensagem'] = "Erro ao remover ocorrência: " . mysqli_error($conexao);
    if($_SESSION['cargo'] == 'Admin'){
    header('Location: ../pages/admin/ocorrencias.php');
    } elseif($_SESSION['cargo'] == 'Diretor'){
        header('Location: ../pages/direcao/ocorrencias.php');
    } elseif($_SESSION['cargo'] == 'Coordenador'){
        header('Location: ../pages/coordenacao/ocorrencias.php');
    }
}
?>