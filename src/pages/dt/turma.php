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

    <h2 style="color: rgb(9, 105, 9); text-align: center; margin-top: 20px;">
        Minha Turma - EEEP Manoel Mano
    </h2>

    <div class="container mb-3">
        <input type="text" id="searchInput" class="form-control" placeholder="Pesquisar por nome ou qualquer informação do aluno">
    </div>

    <div class="table-responsive-container">
        <table class="table table-striped">
            <tr>
                <th>Nome</th>
                <th>Matrícula</th>
                <th>Curso</th>
                <th>Série</th>
                <th>Telefone do Responsável</th>
            </tr>

            <?php
            include('../../backend/database.php');

            // Pega o e-mail do DT que está logado na sessão
            $email_logado = $_SESSION['email'];

            // Query corrigida trazendo os campos com os nomes exatos que o PHP espera
            $query = "SELECT 
            a.nome,
            a.matricula,
            a.curso,
            a.serie,
            a.telefone_responsavel
          FROM 
            aluno a
          INNER JOIN 
            funcionario f ON a.serie = f.serie AND a.curso = f.curso
          WHERE 
            f.cargo = 'DT' 
            AND f.email = '$email_logado'";

            $result = mysqli_query($conexao, $query);

            if (mysqli_num_rows($result) > 0) {
                while ($row = $result->fetch_assoc()) {
            ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nome']) ?></td>
                        <td><?= htmlspecialchars($row['matricula']) ?></td>
                        <td><?= htmlspecialchars($row['curso']) ?></td>
                        <td><?= htmlspecialchars($row['serie']) ?>ºAno</td>
                        <td><?= htmlspecialchars($row['telefone_responsavel']) ?></td>
                    </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='6' class='text-center'>Nenhum aluno cadastrado nesta turma!</td></tr>";
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

        function filterTable() {
            const input = document.getElementById("searchInput");
            const filter = input.value.toUpperCase();
            const table = document.querySelector(".table");
            const tr = table.getElementsByTagName("tr");

            for (let i = 1; i < tr.length; i++) {
                let found = false;
                const tds = tr[i].getElementsByTagName("td");
                for (let j = 0; j < tds.length - 1; j++) {
                    const td = tds[j];
                    if (td && td.textContent.toUpperCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                    }
                }
                tr[i].style.display = found ? "" : "none";
            }
        }
        document.getElementById("searchInput").addEventListener("keyup", filterTable);
    </script>
</body>

</html>