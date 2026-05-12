<?php
session_start();
include('database.php');

if (!isset($_GET['id'])) {
    die("ID do aluno não informado.");
}

$id = $_GET['id'];
$query = "DELETE FROM aluno WHERE id_aluno = $id";

if (mysqli_query($conexao, $query)) {
    $_SESSION['mensagem'] = "Removido com sucesso!";
    header("Location: ../pages/admin/alunos.php");
    exit;
} else {
    $_SESSION['mensagem'] = "Erro ao remover aluno: " . mysqli_error($conexao);
}
?>