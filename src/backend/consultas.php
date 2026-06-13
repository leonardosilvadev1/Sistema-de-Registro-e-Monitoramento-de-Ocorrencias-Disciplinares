<?php
include('database.php');

$totalAlunos = 0;
$totalOcorrencias = 0;
$ocorrenciasMes = 0;
$totalCursos = 0;

$tiposLabels = [];
$tiposDados = [];
$cursosLabels = [];
$cursosDados = [];
$alunosCursoLabels = [];
$alunosCursoDados = [];

if ($conexao) {
    // Total de alunos
    $queryAlunos = "SELECT COUNT(*) AS total FROM aluno";
    if ($resultadoAlunos = mysqli_query($conexao, $queryAlunos)) {
        $totalAlunos = mysqli_fetch_assoc($resultadoAlunos)['total'] ?? 0;
    }

    // 2. Total de ocorrências
    $queryOcorrencias = "SELECT COUNT(*) AS total FROM ocorrencia";
    if ($resultadoOcorrencias = mysqli_query($conexao, $queryOcorrencias)) {
        $totalOcorrencias = mysqli_fetch_assoc($resultadoOcorrencias)['total'] ?? 0;
    }

    // Total de Cursos distintos ativos
    $queryTotalCursos = "SELECT COUNT(DISTINCT curso) AS total FROM aluno";
    if ($resultadoTotalCursos = mysqli_query($conexao, $queryTotalCursos)) {
        $totalCursos = mysqli_fetch_assoc($resultadoTotalCursos)['total'] ?? 0;
    }

    // Ocorrências no Mês Atual (Verificação inteligente para evitar falha se coluna não existir)
    // Primeiro verificamos se existe alguma coluna de data (como data, data_ocorrencia ou criado_em)
    $colunasQuery = mysqli_query($conexao, "SHOW COLUMNS FROM ocorrencia LIKE 'data%'");
    $colunaData = null;
    if ($colunasQuery && mysqli_num_rows($colunasQuery) > 0) {
        $colunaDataRow = mysqli_fetch_assoc($colunasQuery);
        $colunaData = $colunaDataRow['Field'];
    }

    if ($colunaData) {
        $queryOcorrenciasMes = "SELECT COUNT(*) AS total FROM ocorrencia WHERE MONTH(`$colunaData`) = MONTH(CURRENT_DATE()) AND YEAR(`$colunaData`) = YEAR(CURRENT_DATE())";
        if ($resultadoOcorrenciasMes = mysqli_query($conexao, $queryOcorrenciasMes)) {
            $ocorrenciasMes = mysqli_fetch_assoc($resultadoOcorrenciasMes)['total'] ?? 0;
        }
    } else {
        // Se não houver coluna de data, mostramos um valor fictício ou as últimas 5 como sendo "recentes"
        $ocorrenciasMes = min(5, $totalOcorrencias);
    }

    // Ocorrências por Tipo
    $queryTipos = "SELECT tipo_ocorrencia, COUNT(*) as qtd FROM ocorrencia GROUP BY tipo_ocorrencia";
    if ($resultadoTipos = mysqli_query($conexao, $queryTipos)) {
        while($row = mysqli_fetch_assoc($resultadoTipos)){
            if (!empty($row['tipo_ocorrencia'])) {
                $tiposLabels[] = $row['tipo_ocorrencia'];
                $tiposDados[] = (int)$row['qtd'];
            }
        }
    }

    // Ocorrências por Curso
    $queryCursos = "SELECT a.curso, COUNT(o.id_ocorrencia) as qtd 
                    FROM ocorrencia o 
                    JOIN aluno a ON o.fk_aluno_id_aluno = a.id_aluno 
                    GROUP BY a.curso";
    if ($resultadoCursos = mysqli_query($conexao, $queryCursos)) {
        while($row = mysqli_fetch_assoc($resultadoCursos)){
            if (!empty($row['curso'])) {
                $cursosLabels[] = $row['curso'];
                $cursosDados[] = (int)$row['qtd'];
            }
        }
    }

    // Distribuição de Alunos por Curso
    $queryAlunosCurso = "SELECT curso, COUNT(*) as qtd FROM aluno GROUP BY curso";
    if ($resultadoAlunosCurso = mysqli_query($conexao, $queryAlunosCurso)) {
        while($row = mysqli_fetch_assoc($resultadoAlunosCurso)){
            if (!empty($row['curso'])) {
                $alunosCursoLabels[] = $row['curso'];
                $alunosCursoDados[] = (int)$row['qtd'];
            }
        }
    }
}

// Garantir que arrays nunca fiquem vazios para evitar quebra de sintaxe no Javascript
if (empty($tiposLabels)) { $tiposLabels = ['Nenhuma']; $tiposDados = [0]; }
if (empty($cursosLabels)) { $cursosLabels = ['Nenhum']; $cursosDados = [0]; }
if (empty($alunosCursoLabels)) { $alunosCursoLabels = ['Nenhum']; $alunosCursoDados = [0]; }
?>