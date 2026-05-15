<?php
session_start();
/**@var mysqli $conexao */
    if(!isset($_SESSION['email']) || !isset($_SESSION['cargo'])){
        header('Location: ../tela_login.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Ocorrências</title>
    <link rel="stylesheet" href="../css/painel_admin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="shortcut icon" href="../../assets/images/Logo Projeto Sem Fundo.png" type="image/x-icon">
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
                <a href="painel_coordenador.php">🏠 Início</a>
                <a href="dashboard.php">📊 Dashboard</a>
                <a href="ocorrencias.php">📝 Ocorrências</a>
                <a href="alunos.php">🎓 Alunos</a>
            </nav>

            <div class="sidebar-footer">
                <a href="../../backend/logout.php"><button class="logout-btn">Sair</button></a>
            </div>
        </aside>
    </div>

    <h2 style="color: rgb(9, 105, 9); text-align: center; margin-top: 20px;">
        Ocorrências Registradas
    </h2>
    
    <div class="container mb-3">
        <input type="text" id="searchInput" class="form-control" placeholder="Pesquisar por nome ou qualquer informação do funcionário">
    </div>

    <table class="table table-striped" style="margin: 20px auto; width: 90%; border-radius: 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
    <tr>
        <th>Data</th>
        <th>Tipo</th>
        <th>Descrição</th>
        <th>Aluno</th>
        <th>Funcionário</th>
        <th>Ações</th>
    </tr>

    <?php
    include('../../backend/database.php');
    $query = "SELECT o.id_ocorrencia, o.data, o.tipo_ocorrencia, o.descricao, a.nome AS nome_aluno, f.nome AS nome_funcionario 
              FROM ocorrencia o 
              INNER JOIN aluno a ON o.fk_aluno_id_aluno = a.id_aluno 
              INNER JOIN funcionario f ON o.fk_funcionario_id_funcionario = f.id_funcionario
              ORDER BY o.data DESC";
    
    $result = mysqli_query($conexao, $query);

    if (mysqli_num_rows($result) > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
                echo "<td>".date('d/m/Y', strtotime($row['data']))."</td>";
                echo "<td>".$row['tipo_ocorrencia']."</td>";
                echo "<td>".$row['descricao']."</td>";
                echo "<td>".$row['nome_aluno']."</td>";
                echo "<td>".$row['nome_funcionario']."</td>";
                echo "<td>
                    <a href='../../backend/deletar_ocorrencia.php?id=".$row['id_ocorrencia']."' 
                        class='btn btn-danger btn-sm'
                        onclick=\"return confirm('Tem certeza que deseja remover esta ocorrência?');\">
                        Remover
                    </a>
                </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6' class='text-center'>Nenhuma ocorrência cadastrada!</td></tr>";
    }
    ?>
</table>

    <a href="tela_cad_ocorrencia.php"><button name="adicionar" id="adicionar-ocorrencia">+</button></a>

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

        // Abrir/Fechar ao clicar no botão
        menuBtn.addEventListener('click', toggleMenu);
        overlay.addEventListener('click', toggleMenu);
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && sidebar.classList.contains('active')) {
                toggleMenu();
            }
        });

        // Função de busca
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
                    if (td) {
                        if (td.textContent.toUpperCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                        }
                    }
                }

            if (found) {
                tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
        document.getElementById("searchInput").addEventListener("keyup", filterTable);
    </script>
</body>
</html>