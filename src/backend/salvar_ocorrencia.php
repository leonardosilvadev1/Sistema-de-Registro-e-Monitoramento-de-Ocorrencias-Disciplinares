<?php
session_start();
include('database.php');

if(
    empty($_POST['fk_aluno_id']) ||
    empty($_POST['fk_funcionario_id']) ||
    empty($_POST['tipo']) ||
    empty($_POST['descricao']) ||
    empty($_POST['data'])
){
    $_SESSION['mensagem'] = "Preencha todos os campos!";
    header('Location: tela_ocorrencia.php');
    exit();
}

$aluno = $_POST['fk_aluno_id'];
$func = $_POST['fk_funcionario_id'];
$tipo = $_POST['tipo'];
$descricao = $_POST['descricao'];
$data = $_POST['data'];

$stmt = mysqli_prepare($conexao, "
    INSERT INTO ocorrencia 
    (data, tipo_ocorrencia, descricao, fk_aluno_id_aluno, fk_funcionario_id_funcionario)
    VALUES (?, ?, ?, ?, ?)
");

mysqli_stmt_bind_param($stmt, "sssii", $data, $tipo, $descricao, $aluno, $func);

if(mysqli_stmt_execute($stmt)){
    $_SESSION['mensagem'] = "Ocorrência cadastrada com sucesso!";
}else{
    $_SESSION['mensagem'] = "Erro ao cadastrar!";
}

header('Location: ../pages/admin/ocorrencias.php');
exit();
?>