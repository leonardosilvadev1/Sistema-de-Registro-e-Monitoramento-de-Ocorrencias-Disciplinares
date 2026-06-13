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
    <title>Gerenciamento de Alunos</title>
    <link rel="stylesheet" href="../css/painel_diretor.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
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

    <div class="table-responsive-container">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Matrícula</th>
                    <th>Curso</th>
                    <th>Série</th>
                    <th>Telefone do Responsável</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php
            include('../../backend/database.php');

            $query = "SELECT id_aluno, nome, matricula, curso, serie, telefone_responsavel FROM aluno WHERE serie BETWEEN 1 AND 3";
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
                        <td>
                            <button type="button"
                                    class="btn btn-warning btn-sm btn-editar-aluno"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditarAluno"
                                    data-id="<?= $row['id_aluno'] ?>"
                                    data-nome="<?= htmlspecialchars($row['nome'], ENT_QUOTES) ?>"
                                    data-matricula="<?= htmlspecialchars($row['matricula'], ENT_QUOTES) ?>"
                                    data-curso="<?= htmlspecialchars($row['curso'], ENT_QUOTES) ?>"
                                    data-serie="<?= htmlspecialchars($row['serie'], ENT_QUOTES) ?>"
                                    data-tel="<?= htmlspecialchars($row['telefone_responsavel'], ENT_QUOTES) ?>">
                                Editar
                            </button>
                            <a href="../../backend/deletar_aluno.php?id=<?= $row['id_aluno'] ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Tem certeza que deseja remover este aluno?');">
                               Remover
                            </a>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='6'>Nenhum aluno cadastrado!</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>

    <button name="adicionar" id="adicionar-aluno">+</button>

    <dialog id="form_modal">
        <form action="../../backend/cad_aluno.php" method="POST" id="formCadastroAluno">
            <label for="nome">Nome do Aluno</label>
            <input type="text" id="nome" name="nome" required autofocus>
            <br>

            <label for="matricula">Número da Matrícula</label>
            <input type="text" id="matricula" name="matricula" required>
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
                <option value="1">1° Ano</option>
                <option value="2">2° Ano</option>
                <option value="3">3° Ano</option>
            </select>
            <br>

            <label for="telefone_responsavel">Telefone do Responsável</label>
            <input type="text" id="telefone_responsavel" name="tel_responsavel" required>

            <button class="button_modal" type="submit">Salvar</button>
            <button class="button_modal" type="button" onclick="this.closest('dialog').close()">Cancelar</button>
        </form>
    </dialog>

    <div class="modal fade" id="modalEditarAluno" tabindex="-1" aria-labelledby="modalEditarAlunoLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <form action="../../backend/edit_aluno.php" method="POST">
            <div class="modal-header" style="background-color: rgb(9, 105, 9); color: #fff;">
              <h5 class="modal-title" id="modalEditarAlunoLabel">Editar Aluno</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
              <input type="hidden" name="id_aluno" id="edit_aluno_id">

              <div class="mb-3">
                <label for="edit_aluno_nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="edit_aluno_nome" name="nome" required>
              </div>

              <div class="mb-3">
                <label for="edit_aluno_matricula" class="form-label">Matrícula</label>
                <input type="text" class="form-control" id="edit_aluno_matricula" name="matricula" required>
              </div>

              <div class="mb-3">
                <label for="edit_aluno_curso" class="form-label">Curso</label>
                <select class="form-select" id="edit_aluno_curso" name="curso" required>
                  <option value="Enfermagem">Enfermagem</option>
                  <option value="Informática">Informática</option>
                  <option value="DS">Desenvolvimento de Sistemas</option>
                  <option value="Adm">Administração</option>
                  <option value="Comércio">Comércio</option>
                </select>
              </div>

              <div class="mb-3">
                <label for="edit_aluno_serie" class="form-label">Série</label>
                <select class="form-select" id="edit_aluno_serie" name="serie" required>
                  <option value="1">1° Ano</option>
                  <option value="2">2° Ano</option>
                  <option value="3">3° Ano</option>
                </select>
              </div>

              <div class="mb-3">
                <label for="edit_aluno_tel" class="form-label">Telefone do Responsável</label>
                <input type="text" class="form-control" id="edit_aluno_tel" name="tel_responsavel" required>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-success">Salvar Alterações</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

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

        botaoAbrir.addEventListener('click', () => {
            modal.showModal();
        });

        // Modal de Edição - preencher campos
        document.querySelectorAll('.btn-editar-aluno').forEach(function (btn) {
            btn.addEventListener('click', function () {
                document.getElementById('edit_aluno_id').value        = this.dataset.id || '';
                document.getElementById('edit_aluno_nome').value      = this.dataset.nome || '';
                document.getElementById('edit_aluno_matricula').value = this.dataset.matricula || '';
                document.getElementById('edit_aluno_curso').value     = this.dataset.curso || '';
                document.getElementById('edit_aluno_serie').value     = this.dataset.serie || '';
                document.getElementById('edit_aluno_tel').value       = this.dataset.tel || '';
            });
        });
    </script>
</body>
</html>