<?php
session_start();
include('database.php');

function redirecionar_tela_cad() {
    if ($_SESSION['cargo'] == 'Admin') {
        header('Location: ../pages/admin/tela_cad_ocorrencia.php');
    } elseif ($_SESSION['cargo'] == 'Diretor') {
        header('Location: ../pages/direcao/tela_cad_ocorrencia.php');
    } elseif ($_SESSION['cargo'] == 'Coordenador') {
        header('Location: ../pages/coordenacao/tela_cad_ocorrencia.php');
    }
    exit();
}

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
    empty($_POST['fk_aluno_id']) ||
    empty($_POST['fk_funcionario_id']) ||
    empty($_POST['tipo']) ||
    empty($_POST['data'])
) {
    $_SESSION['mensagem'] = "Preencha todos os campos!";
    redirecionar_tela_cad();
}

$aluno     = (int) $_POST['fk_aluno_id'];
$func      = (int) $_POST['fk_funcionario_id'];
$tipo      = $_POST['tipo'];
$descricao = $_POST['descricao'];
$data      = $_POST['data'];

$stmt = mysqli_prepare($conexao, "
    INSERT INTO ocorrencia
    (data, tipo_ocorrencia, descricao, fk_aluno_id_aluno, fk_funcionario_id_funcionario)
    VALUES (?, ?, ?, ?, ?)
");
mysqli_stmt_bind_param($stmt, "sssii", $data, $tipo, $descricao, $aluno, $func);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['mensagem'] = "Ocorrência cadastrada com sucesso!";
} else {
    $_SESSION['mensagem'] = "Erro ao cadastrar!";
}

redirecionar_ocorrencias();
