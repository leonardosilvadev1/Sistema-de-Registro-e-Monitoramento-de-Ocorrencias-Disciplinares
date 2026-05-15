<?php
session_start();
include('../database.php');
/**@var mysqli $conexao */
    if(!isset($_SESSION['email']) || !isset($_SESSION['cargo'])){
        header('Location: ../tela_login.php');
        exit();
    }

// dados vindos do buscar.php
$alunos = $_SESSION['alunos'] ?? [];
$funcionarios = $_SESSION['funcionarios'] ?? [];

$alunoSelecionado = $_SESSION['aluno_nome'] ?? '';
$alunoId = $_SESSION['aluno_id'] ?? '';

$funcSelecionado = $_SESSION['func_nome'] ?? '';
$funcId = $_SESSION['func_id'] ?? '';

// limpa resultados (pra não duplicar)
unset($_SESSION['alunos'], $_SESSION['funcionarios']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Ocorrência</title>
    <link rel="shortcut icon" href="../../assets/images/Logo Projeto Sem Fundo.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/ocorrencia.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>

<div class="ide-container">
    <aside class="ide-sidebar">
        <div class="panel-section">
            <div class="panel-header">Buscar Aluno</div>
            <div class="panel-content">
                <form method="POST" action="../../backend/buscar.php" class="search-box">
                    <input type="text" name="aluno" placeholder="Nome do aluno..." required>
                    <button type="submit" name="buscar_aluno" class="btn-search">🔍</button>
                </form>

                <ul class="result-list">
                <?php foreach($alunos as $a): ?>
                    <li class="result-item">
                        <form method="POST" action="../../backend/buscar.php">
                            <input type="hidden" name="aluno_id" value="<?php echo $a['id_aluno']; ?>">
                            <input type="hidden" name="aluno_nome" value="<?php echo $a['nome']; ?>">
                            <button type="submit" name="aluno_escolhido" class="btn-select-item">
                                <?php echo $a['nome']; ?>
                            </button>
                        </form>
                    </li>
                <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="panel-section">
            <div class="panel-header">Buscar Funcionário</div>
            <div class="panel-content">
                <form method="POST" action="../../backend/buscar.php" class="search-box">
                    <input type="text" name="funcionario" placeholder="Nome do funcionário..." required>
                    <button type="submit" name="buscar_funcionario" class="btn-search">🔍</button>
                </form>

                <ul class="result-list">
                <?php foreach($funcionarios as $f): ?>
                    <li class="result-item">
                        <form method="POST" action="../../backend/buscar.php">
                            <input type="hidden" name="func_id" value="<?php echo $f['id_funcionario']; ?>">
                            <input type="hidden" name="func_nome" value="<?php echo $f['nome']; ?>">
                            <button type="submit" name="func_escolhido" class="btn-select-item">
                                <?php echo $f['nome']; ?>
                            </button>
                        </form>
                    </li>
                <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <a href="ocorrencias.php"><button style="margin-left: 15px;" type="submit" class="btn-submit">Voltar</button></a>
    </aside>

    <main class="ide-editor">
        <div class="form-card">
            <h2 class="editor-title">Nova Ocorrência</h2>

            <?php if(isset($_SESSION['mensagem'])): ?>
                <div class="alert alert-orange">
                    <?php 
                        echo $_SESSION['mensagem']; 
                        unset($_SESSION['mensagem']);
                    ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="../../backend/salvar_ocorrencia.php" class="main-form-grid">
                
                <div class="form-group">
                    <label>Aluno</label>
                    <input type="text" value="<?php echo $alunoSelecionado; ?>" placeholder="Selecione na lista lateral" disabled>
                    <input type="hidden" name="fk_aluno_id" value="<?php echo $alunoId; ?>">
                </div>

                <div class="form-group">
                    <label>Funcionário (Ex: Diretor, Coordenador)</label>
                    <input type="text" value="<?php echo $funcSelecionado; ?>" placeholder="Selecione na lista lateral" disabled>
                    <input type="hidden" name="fk_funcionario_id" value="<?php echo $funcId; ?>">
                </div>

                <div class="form-group">
                    <label>Tipo de Ocorrência</label>
                    <input type="text" name="tipo" placeholder="Ex: Disciplinar, Médica..." required>
                </div>

                <div class="form-group">
                    <label>Data do Evento</label>
                    <input type="date" name="data" required>
                </div>

                <div class="form-group full-width">
                    <label>Descrição Detalhada</label>
                    <textarea name="descricao" rows="6" placeholder="Descreva os detalhes da ocorrência aqui" style="resize: vertical;"></textarea>
                </div>
                
                <div class="full-width" style="text-align: right;">
                    <button type="submit" class="btn-submit">Cadastrar</button>
                </div>
            </form>
        </div>
    </main>
</div>
</body>
</html>