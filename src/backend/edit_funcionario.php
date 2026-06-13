<?php
session_start();
include('database.php');

function redirecionar_funcionarios() {
    if ($_SESSION['cargo'] == 'Admin') {
        header('Location: ../pages/admin/funcionarios.php');
    } elseif ($_SESSION['cargo'] == 'Diretor') {
        header('Location: ../pages/direcao/funcionarios.php');
    }
    exit();
}

if (
    empty($_POST['id_funcionario']) ||
    empty($_POST['name']) ||
    empty($_POST['cargo']) ||
    empty($_POST['email'])
) {
    $_SESSION['mensagem'] = "Preencha todos os campos!";
    redirecionar_funcionarios();
}

$id    = (int) $_POST['id_funcionario'];
$nome  = $_POST['name'];
$cargo = $_POST['cargo'];
$email = $_POST['email'];
$senha = $_POST['password'] ?? '';

// Campos exclusivos para Diretor de Turma
if ($cargo === 'DT') {
    $serie = !empty($_POST['serie']) ? (int) $_POST['serie'] : null;
    $curso = $_POST['curso'] ?? null;
    if (empty($serie) || empty($curso)) {
        $_SESSION['mensagem'] = "Para Diretor de Turma, informe série e curso!";
        redirecionar_funcionarios();
    }
} else {
    $serie = null;
    $curso = null;
}

if (!empty($senha)) {
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    $stmt = mysqli_prepare(
        $conexao,
        "UPDATE funcionario
            SET nome = ?, cargo = ?, email = ?, senha = ?, serie = ?, curso = ?
          WHERE id_funcionario = ?"
    );
    mysqli_stmt_bind_param($stmt, "ssssisi", $nome, $cargo, $email, $senha_hash, $serie, $curso, $id);
} else {
    $stmt = mysqli_prepare(
        $conexao,
        "UPDATE funcionario
            SET nome = ?, cargo = ?, email = ?, serie = ?, curso = ?
          WHERE id_funcionario = ?"
    );
    mysqli_stmt_bind_param($stmt, "sssisi", $nome, $cargo, $email, $serie, $curso, $id);
}

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['mensagem'] = "Funcionário atualizado com sucesso!";
} else {
    $_SESSION['mensagem'] = "Erro ao atualizar funcionário!";
}

redirecionar_funcionarios();
