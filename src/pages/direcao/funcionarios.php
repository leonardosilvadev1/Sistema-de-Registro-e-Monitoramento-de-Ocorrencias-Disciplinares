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
    <link rel="stylesheet" href="../css/painel_diretor.css">
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
        Funcionários Cadastrados
    </h2>
    
    <div class="container mb-3">
        <input type="text" id="searchInput" class="form-control" placeholder="Pesquisar por nome ou qualquer informação do funcionário">
    </div>

    <div class="table-responsive-container">
        <table class="table table-striped">
            <tr>
                <th>Nome</th>
                <th>Cargo</th>
                <th>Email</th>
                <th>Série/Curso (DT)</th>
                <th>Ações</th>
            </tr>

            <?php
            include('../../backend/database.php');

            $query = "SELECT id_funcionario, nome, cargo, email, serie, curso FROM funcionario";
            $result = mysqli_query($conexao, $query);

            if (mysqli_num_rows($result) > 0) {
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nome']) ?></td>
                        <td><?= htmlspecialchars($row['cargo']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>

                        <td>
                            <?php
                            if ($row['cargo'] === 'DT') {
                                echo htmlspecialchars($row['serie']) . 'º Ano - ' . htmlspecialchars($row['curso']);
                            } else {
                                echo '-';
                            }
                            ?>
                        </td>

                        <td>
                            <button type="button"
                                    class="btn btn-warning btn-sm btn-editar-funcionario"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditarFuncionario"
                                    data-id="<?= $row['id_funcionario'] ?>"
                                    data-nome="<?= htmlspecialchars($row['nome'], ENT_QUOTES) ?>"
                                    data-cargo="<?= htmlspecialchars($row['cargo'], ENT_QUOTES) ?>"
                                    data-email="<?= htmlspecialchars($row['email'], ENT_QUOTES) ?>"
                                    data-serie="<?= htmlspecialchars($row['serie'] ?? '', ENT_QUOTES) ?>"
                                    data-curso="<?= htmlspecialchars($row['curso'] ?? '', ENT_QUOTES) ?>">
                                Editar
                            </button>

                            <a href="../../backend/deletar_func.php?id=<?= $row['id_funcionario'] ?>"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Tem certeza que deseja remover este funcionário?');">
                                Remover
                            </a>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='10'>Nenhum funcionário cadastrado!</td></tr>";
            }
            ?>
        </table>
    </div>

    <button name="adicionar" id="adicionar-funcionario">+</button>

    <!-- =================== MODAL DE CADASTRO (original) =================== -->
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

            <div class="mb-3 d-none" id="serie_wrapper">
                <label class="form-label">Série do Diretor de Turma</label>
                <select class="form-select" name="serie">
                    <option value="">Selecione</option>
                    <option value="1">1° Ano</option>
                    <option value="2">2° Ano</option>
                    <option value="3">3° Ano</option>
                </select>
            </div>

            <div class="mb-3 d-none" id="curso_wrapper">
                <label class="form-label">Curso do Diretor de Turma</label>
                <select class="form-select" name="curso">
                  <option value="">Selecione</option>
                  <option value="Enfermagem">Enfermagem</option>
                  <option value="Informática">Informática</option>
                  <option value="Desenvolvimento de Sistemas">Desenvolvimento de Sistemas</option>
                  <option value="Comercio">Comercio</option>
                  <option value="Administração">Administração</option>
                </select>
            </div>

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

    <!-- =================== MODAL DE EDIÇÃO (novo) =================== -->
    <div class="modal fade" id="modalEditarFuncionario" tabindex="-1" aria-labelledby="modalEditarFuncionarioLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <form action="../../backend/edit_funcionario.php" method="POST">
            <div class="modal-header" style="background-color: rgb(9, 105, 9); color: #fff;">
              <h5 class="modal-title" id="modalEditarFuncionarioLabel">Editar Funcionário</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
              <input type="hidden" name="id_funcionario" id="edit_func_id">

              <div class="mb-3">
                <label for="edit_func_nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="edit_func_nome" name="name" required>
              </div>

              <div class="mb-3">
                <label for="edit_func_cargo" class="form-label">Cargo</label>
                <select class="form-select" id="edit_func_cargo" name="cargo" required>
                  <option value="Admin">Admin</option>
                  <option value="Diretor">Diretor</option>
                  <option value="Coordenador">Coordenador</option>
                  <option value="DT">Diretor de Turma</option>
                </select>
              </div>

            <!-- Campos exclusivos para Diretor de Turma -->
              <div class="mb-3 d-none" id="edit_func_serie_wrapper">
                <label for="edit_func_serie" class="form-label">Série do Diretor de Turma</label>
                <select class="form-select" id="edit_func_serie" name="serie">
                  <option value="">Selecione</option>
                  <option value="1">1° Ano</option>
                  <option value="2">2° Ano</option>
                  <option value="3">3° Ano</option>
                </select>
              </div>

              <div class="mb-3 d-none" id="edit_func_curso_wrapper">
                <label for="edit_func_curso" class="form-label">Curso do Diretor de Turma</label>
                <select class="form-select" id="edit_func_curso" name="curso">
                  <option value="">Selecione</option>
                  <option value="Enfermagem">Enfermagem</option>
                  <option value="Informática">Informática</option>
                  <option value="Desenvolvimento de Sistemas">Desenvolvimento de Sistemas</option>
                  <option value="Comercio">Comercio</option>
                  <option value="Administração">Administração</option>
                </select>
              </div>

              <div class="mb-3">
                <label for="edit_func_email" class="form-label">E-mail</label>
                <input type="email" class="form-control" id="edit_func_email" name="email" required>
              </div>

              

              <div class="mb-3">
                <label for="edit_func_password" class="form-label">
                    Nova Senha <small class="text-muted">(deixe em branco para manter a atual)</small>
                </label>
                <input type="password" class="form-control" id="edit_func_password" name="password" autocomplete="new-password">
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
            if (e.key === 'Escape' && sidebar.classList.contains('active')) toggleMenu();
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

        // Modal de cadastro
        const botaoAbrir = document.getElementById('adicionar-funcionario');
        const modal = document.getElementById('form_modal');
        botaoAbrir.addEventListener('click', () => modal.showModal());

        // Mostra/esconde os campos Série e Curso conforme o cargo selecionado
        function toggleCamposDT() {
            const cargo = document.getElementById('edit_func_cargo').value;
            const isDT = cargo === 'DT';
            const serieWrapper = document.getElementById('edit_func_serie_wrapper');
            const cursoWrapper = document.getElementById('edit_func_curso_wrapper');
            const serieInput = document.getElementById('edit_func_serie');
            const cursoInput = document.getElementById('edit_func_curso');

            serieWrapper.classList.toggle('d-none', !isDT);
            cursoWrapper.classList.toggle('d-none', !isDT);
            serieInput.required = isDT;
            cursoInput.required = isDT;

            if (!isDT) {
                serieInput.value = '';
                cursoInput.value = '';
            }
        }

        function toggleCamposCadastro() {
            const cargo = document.getElementById('cargo').value;

            const serieWrapper = document.getElementById('serie_wrapper');
            const cursoWrapper = document.getElementById('curso_wrapper');

            if (cargo === 'DT') {
                serieWrapper.classList.remove('d-none');
                cursoWrapper.classList.remove('d-none');
            } else {
                serieWrapper.classList.add('d-none');
                cursoWrapper.classList.add('d-none');
            }
        }

        document.getElementById('cargo').addEventListener('change', toggleCamposCadastro);

        document.getElementById('edit_func_cargo').addEventListener('change', toggleCamposDT);


        // Modal de edição - preencher campos
        document.querySelectorAll('.btn-editar-funcionario').forEach(function (btn) {
            btn.addEventListener('click', function () {
                document.getElementById('edit_func_id').value       = this.dataset.id || '';
                document.getElementById('edit_func_nome').value     = this.dataset.nome || '';
                document.getElementById('edit_func_cargo').value    = this.dataset.cargo || '';
                document.getElementById('edit_func_email').value    = this.dataset.email || '';
                document.getElementById('edit_func_password').value = '';
                document.getElementById('edit_func_serie').value    = this.dataset.serie || '';
                document.getElementById('edit_func_curso').value    = this.dataset.curso || '';
                toggleCamposDT();
            }); 
        });
    </script>
</body>
</html>