<?php
session_start();
if (!isset($_SESSION['email']) || !isset($_SESSION['cargo'])) {
    header('Location: ../tela_login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Diretor de Turma</title>
    <link rel="shortcut icon" href="../../assets/images/Logo Projeto Sem Fundo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/painel_dt.css">
</head>

<body>
    <div style="background-color: green;">
        <div class="home" style="display: flex; justify-content: center; align-items: center;">
            <img src="../../assets/images/Logo Projeto Sem Fundo.png" style="height: 100px; width: 100px;" alt="Logo">
            <h2 style="color: white; margin-left: 10px;">SIS-ROM</h2>
        </div>

        <button id="menuBtn">☰</button>

        <div id="overlay"></div>
        <aside id="sidebar">
            <div class="sidebar-header">
                Menu Principal
            </div>

            <nav class="sidebar-nav">
                <a href="painel_dt.php">🏠 Início</a>
                <a href="turma.php">👥 Minha Turma</a>
                <a href="ocorrencias.php">📝 Ocorrências</a>
            </nav>

            <div class="sidebar-footer">
                <a href="../../backend/logout.php"><button class="logout-btn">Sair</button></a>
            </div>
        </aside>
    </div>

    <div class="table-responsive-container">
        <table class="table table-striped">
            <tr>
                <th>Data</th>
                <th>Tipo</th>
                <th>Descrição</th>
                <th>Aluno</th>
                <th>Funcionário</th>
            </tr>

            <?php
            include('../../backend/database.php');

            // Pega o e-mail do DT logado na sessão
            $email_logado = $_SESSION['email'];

            $query = "SELECT 
            o.data,
            o.tipo_ocorrencia,
            o.descricao,
            a.nome AS nome_aluno,
            a.matricula,
            f_reg.nome AS registrado_por
          FROM 
            ocorrencia o
          INNER JOIN 
            aluno a ON o.fk_aluno_id_aluno = a.id_aluno
          INNER JOIN 
            funcionario f_dt ON a.serie = f_dt.serie AND a.curso = f_dt.curso
          LEFT JOIN 
            funcionario f_reg ON o.fk_funcionario_id_funcionario = f_reg.id_funcionario
          WHERE 
            f_dt.cargo = 'DT' 
            AND f_dt.email = '$email_logado'
          ORDER BY 
            o.data DESC";

            $result = mysqli_query($conexao, $query);

            if (mysqli_num_rows($result) > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Converte a data do padrão do banco (AAAA-MM-DD) para o padrão brasileiro (DD/MM/AAAA)
                    $data_formatada = date('d/m/Y', strtotime($row['data']));
            ?>
                    <tr>
                        <td><?= $data_formatada ?></td>
                        <td><?= htmlspecialchars($row['nome_aluno']) ?> (<?= htmlspecialchars($row['matricula']) ?>)</td>
                        <td><?= htmlspecialchars($row['tipo_ocorrencia']) ?></td>
                        <td><?= htmlspecialchars($row['descricao']) ?></td>
                        <td><?= htmlspecialchars($row['registrado_por']) ?></td>
                    </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='5' class='text-center'>Nenhuma ocorrência registrada para esta turma!</td></tr>";
            }
            ?>
        </table>
    </div>

    <script>
        const menuBtn = document.getElementById('menuBtn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');

        function toggleMenu() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');

            if (sidebar.classList.contains('active')) {
                menuBtn.textContent = '✕';
                menuBtn.style.backgroundColor = '#dc3545';
                menuBtn.style.color = 'rgb(255, 255, 255)';
            } else {
                menuBtn.textContent = '☰';
                menuBtn.style.backgroundColor = 'rgb(4, 168, 4)';
                menuBtn.style.color = 'rgb(255, 255, 255)';
            }
        }

        menuBtn.addEventListener('click', toggleMenu);
        overlay.addEventListener('click', toggleMenu);
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && sidebar.classList.contains('active')) {
                toggleMenu();
            }
        });
    </script>
</body>

</html>