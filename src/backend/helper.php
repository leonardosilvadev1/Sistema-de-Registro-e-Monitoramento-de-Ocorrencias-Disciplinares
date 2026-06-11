<h2>Buscar Aluno</h2>

<form method="POST" action="../../backend/buscar.php">
    <input type="text" name="aluno" placeholder="Digite o nome">
    <button type="submit" name="buscar_aluno">Buscar</button>
</form>

<ul>
<?php foreach($alunos as $a): ?>
    <li>
        <form method="POST" action="../../backend/buscar.php">
            <input type="hidden" name="aluno_id" value="<?php echo $a['id_aluno']; ?>">
            <input type="hidden" name="aluno_nome" value="<?php echo $a['nome']; ?>">
            <button type="submit" name="aluno_escolhido">
                <?php echo $a['nome']; ?>
            </button>
        </form>
    </li>
<?php endforeach; ?>
</ul>

<hr>

<h2>Buscar Funcionário</h2>

<form method="POST" action="../../backend/buscar.php">
    <input type="text" name="funcionario" placeholder="Digite o nome">
    <button type="submit" name="buscar_funcionario">Buscar</button>
</form>

<ul>
<?php foreach($funcionarios as $f): ?>
    <li>
        <form method="POST" action="../../backend/buscar.php">
            <input type="hidden" name="func_id" value="<?php echo $f['id_funcionario']; ?>">
            <input type="hidden" name="func_nome" value="<?php echo $f['nome']; ?>">
            <button type="submit" name="func_escolhido">
                <?php echo $f['nome']; ?>
            </button>
        </form>
    </li>
<?php endforeach; ?>
</ul>

<hr>

<h2>Criar Ocorrência</h2>

<?php
if(isset($_SESSION['mensagem'])){
    echo $_SESSION['mensagem'];
    unset($_SESSION['mensagem']);
}
?>

<form method="POST" action="../../backend/salvar_ocorrencia.php">

    <input type="text" value="<?php echo $alunoSelecionado; ?>" placeholder="Aluno" disabled>
    <input type="hidden" name="fk_aluno_id" value="<?php echo $alunoId; ?>">

    <br><br>

    <input type="text" value="<?php echo $funcSelecionado; ?>" placeholder="Funcionário" disabled>
    <input type="hidden" name="fk_funcionario_id" value="<?php echo $funcId; ?>">

    <br><br>

    <input type="text" name="tipo" placeholder="Tipo">
    <br><br>

    <input type="text" name="descricao" placeholder="Descrição">
    <br><br>

    <input type="date" name="data">
    <br><br>

    <button type="submit">Cadastrar</button>

</form>

<?php
echo password_hash("12345", PASSWORD_DEFAULT);
?>