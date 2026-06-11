<?php
session_start();
include('database.php');

function redirecionar_ocorrencias() {
    if ($_SESSION['cargo'] == 'Admin') {
        header('Location: ../pages/admin/ocorrencias.php');
    } elseif ($_SESSION['cargo'] == 'Diretor') {
        header('Location: ../pages/direcao/ocorrencias.php');
    } elseif ($_SESSION['cargo'] == 'Coordenador') {
        header('Location: ../pages/coordenacao/ocorrencias.php');
    }
    exit();
}

if (
    empty($_POST['id_ocorrencia']) ||
    empty($_POST['fk_aluno_id']) ||
    empty($_POST['fk_funcionario_id']) ||
    empty($_POST['tipo']) ||
    empty($_POST['descricao']) ||
    empty($_POST['data'])
) {
    $_SESSION['mensagem'] = "Preencha todos os campos!";
    redirecionar_ocorrencias();
}

$id        = (int) $_POST['id_ocorrencia'];
$aluno     = (int) $_POST['fk_aluno_id'];
$func      = (int) $_POST['fk_funcionario_id'];
$tipo      = $_POST['tipo'];
$descricao = $_POST['descricao'];
$data      = $_POST['data'];

$stmt = mysqli_prepare(
    $conexao,
    "UPDATE ocorrencia
        SET data = ?, tipo_ocorrencia = ?, descricao = ?,
            fk_aluno_id_aluno = ?, fk_funcionario_id_funcionario = ?
      WHERE id_ocorrencia = ?"
);
mysqli_stmt_bind_param($stmt, "sssiii", $data, $tipo, $descricao, $aluno, $func, $id);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['mensagem'] = "Ocorrência atualizada com sucesso!";
} else {
    $_SESSION['mensagem'] = "Erro ao atualizar ocorrência!";
}

redirecionar_ocorrencias();
