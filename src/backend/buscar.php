<?php
session_start();
include('database.php');

if(isset($_POST['buscar_aluno'])){
    $nome = $_POST['aluno'];

    $stmt = mysqli_prepare($conexao, "SELECT id_aluno, nome FROM aluno WHERE nome LIKE ? AND serie BETWEEN 1 AND 3");
    $like = $nome . "%";

    mysqli_stmt_bind_param($stmt, "s", $like);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $alunos = [];
    while($row = mysqli_fetch_assoc($result)){
        $alunos[] = $row;
    }

    $_SESSION['alunos'] = $alunos;
}

if(isset($_POST['buscar_funcionario'])){
    $nome = $_POST['funcionario'];

    $stmt = mysqli_prepare($conexao, "SELECT id_funcionario, nome FROM funcionario WHERE nome LIKE ?");
    $like = $nome . "%";

    mysqli_stmt_bind_param($stmt, "s", $like);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $funcionarios = [];
    while($row = mysqli_fetch_assoc($result)){
        $funcionarios[] = $row;
    }

    $_SESSION['funcionarios'] = $funcionarios;
}

if(isset($_POST['aluno_escolhido'])){
    $_SESSION['aluno_nome'] = $_POST['aluno_nome'];
    $_SESSION['aluno_id'] = $_POST['aluno_id'];
}

if(isset($_POST['func_escolhido'])){
    $_SESSION['func_nome'] = $_POST['func_nome'];
    $_SESSION['func_id'] = $_POST['func_id'];
}

if($_SESSION['cargo'] == 'Admin'){
    header('Location: ../pages/admin/tela_cad_ocorrencia.php');
} elseif($_SESSION['cargo'] == 'Diretor'){
    header('Location: ../pages/direcao/tela_cad_ocorrencia.php');
} elseif($_SESSION['cargo'] == 'Coordenador'){
    header('Location: ../pages/coordenacao/tela_cad_ocorrencia.php');
}
exit();
?>