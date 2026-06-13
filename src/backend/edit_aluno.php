<?php
session_start();
include('database.php');

function redirecionar_alunos() {
    if ($_SESSION['cargo'] == 'Admin') {
        header('Location: ../pages/admin/alunos.php');
    } elseif ($_SESSION['cargo'] == 'Diretor') {
        header('Location: ../pages/direcao/alunos.php');
    } elseif ($_SESSION['cargo'] == 'Coordenador') {
        header('Location: ../pages/coordenacao/alunos.php');
    }
    exit();
}

if (
    empty($_POST['id_aluno']) ||
    empty($_POST['nome']) ||
    empty($_POST['matricula']) ||
    empty($_POST['curso']) ||
    empty($_POST['serie']) ||
    empty($_POST['tel_responsavel'])
) {
    $_SESSION['mensagem'] = "Preencha todos os campos!";
    redirecionar_alunos();
}

$id              = (int) $_POST['id_aluno'];
$nome            = $_POST['nome'];
$matricula       = $_POST['matricula'];
$curso           = $_POST['curso'];
$serie           = $_POST['serie'];
$tel_responsavel = $_POST['tel_responsavel'];

$stmt = mysqli_prepare(
    $conexao,
    "UPDATE aluno
        SET nome = ?, matricula = ?, curso = ?, serie = ?, telefone_responsavel = ?
      WHERE id_aluno = ?"
);
mysqli_stmt_bind_param($stmt, "sssisi", $nome, $matricula, $curso, $serie, $tel_responsavel, $id);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['mensagem'] = "Aluno atualizado com sucesso!";
} else {
    $_SESSION['mensagem'] = "Erro ao atualizar aluno!";
}

redirecionar_alunos();
