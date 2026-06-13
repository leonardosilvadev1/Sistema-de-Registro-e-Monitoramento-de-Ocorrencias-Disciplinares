<?php
session_start();
if (!isset($_SESSION['email']) || !isset($_SESSION['cargo'])) {
    header('Location: ../tela_login.php');
    exit();
}
include('../../backend/database.php');

// Listas para popular os selects do modal de edição
$alunos_modal = mysqli_query($conexao, "SELECT id_aluno, nome FROM aluno ORDER BY nome");
$funcs_modal  = mysqli_query($conexao, "SELECT id_funcionario, nome FROM funcionario ORDER BY nome");
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Ocorrências</title>
    <link rel="stylesheet" href="../css/painel_coordenador.css">
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

    <div class="table-responsive-container">
        <table class="table table-striped">
            <tr>
                <th>Data</th>
                <th>Tipo</th>
                <th>Descrição</th>
                <th>Aluno</th>
                <th>Funcionário</th>
                <th>Ações</th>
            </tr>

            <?php
            $query = "SELECT o.id_ocorrencia, o.data, o.tipo_ocorrencia, o.descricao,
                             o.fk_aluno_id_aluno, o.fk_funcionario_id_funcionario,
                             a.nome AS nome_aluno, f.nome AS nome_funcionario
                      FROM ocorrencia o
                      INNER JOIN aluno a ON o.fk_aluno_id_aluno = a.id_aluno
                      INNER JOIN funcionario f ON o.fk_funcionario_id_funcionario = f.id_funcionario
                      ORDER BY o.data DESC";

            $result = mysqli_query($conexao, $query);

            if (mysqli_num_rows($result) > 0) {
                while ($row = $result->fetch_assoc()) {
            ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($row['data'])) ?></td>
                        <td><?= htmlspecialchars($row['tipo_ocorrencia']) ?></td>
                        <td><?= htmlspecialchars($row['descricao']) ?></td>
                        <td><?= htmlspecialchars($row['nome_aluno']) ?></td>
                        <td><?= htmlspecialchars($row['nome_funcionario']) ?></td>
                        <td>
                            <button type="button"
                                class="btn btn-warning btn-sm btn-editar-ocorrencia"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEditarOcorrencia"
                                data-id="<?= $row['id_ocorrencia'] ?>"
                                data-data="<?= $row['data'] ?>"
                                data-tipo="<?= htmlspecialchars($row['tipo_ocorrencia'], ENT_QUOTES) ?>"
                                data-descricao="<?= htmlspecialchars($row['descricao'], ENT_QUOTES) ?>"
                                data-aluno="<?= $row['fk_aluno_id_aluno'] ?>"
                                data-funcionario="<?= $row['fk_funcionario_id_funcionario'] ?>">
                                Editar
                            </button>
                            <a href="../../backend/deletar_ocorrencia.php?id=<?= $row['id_ocorrencia'] ?>"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Tem certeza que deseja remover esta ocorrência?');">
                                Remover
                            </a>
                        </td>
                    </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='6' class='text-center'>Nenhuma ocorrência cadastrada!</td></tr>";
            }
            ?>
        </table>
    </div>

    <a href="tela_cad_ocorrencia.php"><button name="adicionar" id="adicionar-ocorrencia">+</button></a>

    <!-- =================== MODAL DE EDIÇÃO =================== -->
    <div class="modal fade" id="modalEditarOcorrencia" tabindex="-1" aria-labelledby="modalEditarOcorrenciaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="../../backend/edit_ocorrencia.php" method="POST">
                    <div class="modal-header" style="background-color: rgb(9, 105, 9); color: #fff;">
                        <h5 class="modal-title" id="modalEditarOcorrenciaLabel">Editar Ocorrência</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_ocorrencia" id="edit_oc_id">

                        <div class="mb-3">
                            <label for="edit_oc_data" class="form-label">Data</label>
                            <input type="date" class="form-control" id="edit_oc_data" name="data" required>
                        </div>

                        <div class="mb-3">
                            <label for="edit_oc_tipo" class="form-label">Tipo</label>
                            <select class="form-select" id="edit_oc_tipo" name="tipo" required>
                                <option value="" disabled selected>Selecione o tipo</option>
                                <option value="Indisciplina">Indisciplina</option>
                                <option value="Saúde (Especifique na descrição)">Saúde (Especifique na descrição)</option>
                                <option value="Descumprimento de Regras">Descumprimento de Regras</option>
                                <option value="Danos ao Patrimônio">Danos ao Patrimônio</option>
                                <option value="Violência (Física ou verbal)">Violência (Física ou verbal)</option>
                                <option value="Bullying ou Cyberbullying">Bullying ou Cyberbullying</option>
                                <option value="Preconceito ou discriminação">Preconceito ou discriminação</option>
                                <option value="Sem o material didático">Sem o material didático</option>
                                <option value="Outro (Especifique na descrição)">Outro (Especifique na descrição)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="edit_oc_descricao" class="form-label">Descrição</label>
                            <textarea class="form-control" id="edit_oc_descricao" name="descricao" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="edit_oc_aluno" class="form-label">Aluno</label>
                            <select class="form-select" id="edit_oc_aluno" name="fk_aluno_id" required>
                                <option value="">Selecione...</option>
                                <?php while ($a = mysqli_fetch_assoc($alunos_modal)): ?>
                                    <option value="<?= $a['id_aluno'] ?>"><?= htmlspecialchars($a['nome']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="edit_oc_func" class="form-label">Funcionário</label>
                            <select class="form-select" id="edit_oc_func" name="fk_funcionario_id" required>
                                <option value="">Selecione...</option>
                                <?php while ($f = mysqli_fetch_assoc($funcs_modal)): ?>
                                    <option value="<?= $f['id_funcionario'] ?>"><?= htmlspecialchars($f['nome']) ?></option>
                                <?php endwhile; ?>
                            </select>
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

        // Modal de edição - preencher campos
        document.querySelectorAll('.btn-editar-ocorrencia').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.getElementById('edit_oc_id').value = this.dataset.id || '';
                document.getElementById('edit_oc_data').value = this.dataset.data || '';
                document.getElementById('edit_oc_tipo').value = this.dataset.tipo || '';
                document.getElementById('edit_oc_descricao').value = this.dataset.descricao || '';
                document.getElementById('edit_oc_aluno').value = this.dataset.aluno || '';
                document.getElementById('edit_oc_func').value = this.dataset.funcionario || '';
            });
        });
    </script>
</body>

</html>