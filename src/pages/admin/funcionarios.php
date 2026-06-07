<?php
session_start();
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
    <title>Gerenciamento de Funcionários</title>
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
                <a href="painel_admin.php">🏠 Início</a>
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
        Funcionários Cadastrados
    </h2>
    
    <div class="container mb-3">
        <input type="text" id="searchInput" class="form-control" placeholder="Pesquisar por nome ou qualquer informação do funcionário">
    </div>

    <table class="table table-striped" style="margin: 20px auto; width: 90%; border-radius: 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
        <tr>
            <th>Nome</th>
            <th>Cargo</th>
            <th>Email</th>
            <th>Ações</th>
        </tr>

        <?php
        include('../../backend/database.php');

        $query = "SELECT id_funcionario, nome, cargo, email FROM funcionario";
        $result = mysqli_query($conexao, $query);

        $row = mysqli_num_rows($result);

        if ($row > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                    echo "<td>".$row['nome']."</td>";
                    echo "<td>".$row['cargo']."</td>";
                    echo "<td>".$row['email']."</td>";
                    echo "<td>
                        <a href='../../backend/editar_funcionario.php?id=".$row['id_funcionario']."' 
                            class='btn btn-primary btn-sm'>
                            Editar
                        </a>
                        <a href='../../backend/deletar_func.php?id=".$row['id_funcionario']."' 
                            class='btn btn-danger btn-sm'
                            onclick=\"return confirm('Tem certeza que deseja remover este funcionário?');\">
                            Remover
                        </a>
                    </td>";

                echo "</tr>";
            }
        }else {
            echo "<tr><td colspan='10'>Nenhum funcionário cadastrado!</td></tr>";
        }
        ?>
    </table>

    <button name="adicionar" id="adicionar-funcionario">+</button>
    <dialog id="form_modal">
        <form action="../../backend/cad_funcionario.php" method="POST">
            <label for="nome">Nome do Funcionário</label>
            <input type="text" id="nome" name="name" required autofocus>
            <br>

            <label for="cargo">Cargo</label>
            <select name="cargo" id="cargo" required>
                <option selected disabled>Selecione</option>
                <option value="Admin">Admin</option>
                <option value="Diretor">Diretor</option>
                <option value="Coordenador">Coordenador</option>
                <option value="DT">Diretor de Turma</option>
            </select>
            <br>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
            <br>

            <label for="password">Senha</label>
            <input type="password" id="password" name="password" required>
            <br>

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
        const botaoAbrir = document.getElementById('adicionar-funcionario');
        const modal = document.getElementById('form_modal');
        const formulario = document.getElementById('meuFormulario');

        //Abrir o modal
        botaoAbrir.addEventListener('click', () => {
            modal.showModal();
        });

        //Evitar que a página recarregue ao salvar para você ver o resultado no console
        formulario.addEventListener('submit', (event) => {
        event.preventDefault(); 
            
        alert("Funcionário cadastrado com sucesso!");
            
        modal.close();
        formulario.reset();
        });
    </script>
</body>
</html>