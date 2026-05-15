<?php
session_start();
/**@var mysqli $conexao */
    if(!isset($_SESSION['email']) || !isset($_SESSION['cargo'])){
        header('Location: ../tela_login.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Alunos</title>
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
                <a href="painel_diretor.php">🏠 Início</a>
                <a href="funcionarios.php">👥 Gerenciamento de Funcionários</a>
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
        Alunos Cadastrados
    </h2>
    
    <div class="container mb-3">
        <input type="text" id="searchInput" class="form-control" placeholder="Pesquisar por nome ou qualquer informação do aluno">
    </div>

    <table class="table table-striped" style="margin: 20px auto; width: 90%; border-radius: 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
        <tr>
            <th>Nome</th>
            <th>Matrícula</th>
            <th>Curso</th>
            <th>Série</th>
            <th>Telefone do Responsável</th>
            <th>Ações</th>
        </tr>

        <?php
        include('../../backend/database.php');

        $query = "SELECT id_aluno, nome, matricula, curso, serie, telefone_responsavel FROM aluno";
        $result = mysqli_query($conexao, $query);

        $row = mysqli_num_rows($result);

        if ($row > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                    echo "<td>".$row['nome']."</td>";
                    echo "<td>".$row['matricula']."</td>";
                    echo "<td>".$row['curso']."</td>";
                    echo "<td>".$row['serie']. "° Ano" ."</td>";
                    echo "<td>".$row['telefone_responsavel']."</td>";
                    echo "<td>
                        <a href='../../backend/deletar_aluno.php?id=".$row['id_aluno']."' 
                            class='btn btn-danger btn-sm'
                            onclick=\"return confirm('Tem certeza que deseja remover este aluno?');\">
                            Remover
                        </a>
                    </td>";

                echo "</tr>";
            }
        }else {
            echo "<tr><td colspan='10'>Nenhum aluno cadastrado!</td></tr>";
        }
        ?>
    </table>

    <button name="adicionar" id="adicionar-aluno">+</button>
    <dialog id="form_modal">
        <form action="../../backend/cad_aluno.php" method="POST">
            <label for="nome">Nome do Aluno</label>
            <input type="text" id="nome" name="nome" required autofocus>
            <br>

            <label for="matricula">Número da Matrícula</label>
            <input type="text" id="matricula" name="matricula" required autofocus>
            <br>

            <label for="curso">Curso</label>
            <select name="curso" id="curso" required>
                <option selected disabled>Selecione</option>
                <option value="Enfermagem">Enfermagem</option>
                <option value="Informática">Informática</option>
                <option value="DS">Desenvolvimento de Sistemas</option>
                <option value="Adm">Administração</option>
                <option value="Comércio">Comércio</option>
            </select>
            <br>

            <label for="serie">Série/Ano</label>
            <select name="serie" id="serie" required>
                <option selected disabled>Selecione</option>
                <option value="1° Ano">1° Ano</option>
                <option value="2° Ano">2° Ano</option>
                <option value="3° Ano">3° Ano</option>
            </select>
            <br>

            <label for="telefone_responsavel">Telefone do Responsável</label>
            <input type="text" id="telefone_responsavel" name="tel_responsavel" required autofocus>

            <button class="button_modal" type="submit">Salvar</button>
            <button class="button_modal" type="button" onclick="this.closest('dialog').close()">Cancelar</button>
        </form>
    </dialog>


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

        // Modal de Cadastro
        const botaoAbrir = document.getElementById('adicionar-aluno');
        const modal = document.getElementById('form_modal');
        const formulario = document.getElementById('meuFormulario');

        //Abrir o modal
        botaoAbrir.addEventListener('click', () => {
            modal.showModal();
        });

        //Evitar que a página recarregue ao salvar para você ver o resultado no console
        formulario.addEventListener('submit', (event) => {
        event.preventDefault(); 
            
        alert("Aluno cadastrado com sucesso!");
            
        modal.close();
        formulario.reset();
        });
    </script>
</body>
</html>