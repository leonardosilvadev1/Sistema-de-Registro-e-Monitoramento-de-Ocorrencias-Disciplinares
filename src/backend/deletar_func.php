<?php
include('database.php');

if (!isset($_GET['id'])) {
    die("ID do funcionário não informado.");
}

$id = $_GET['id'];
$query = "DELETE FROM funcionario WHERE id_funcionario = $id";

if (mysqli_query($conexao, $query)) {
    $_SESSION['mensagem'] = "Removido com sucesso!";
    header("Location: ../pages/admin/funcionarios.php");
    exit;
} else {
    $_SESSION['mensagem'] = "Erro ao remover funcionário: " . mysqli_error($conexao);
}
?>