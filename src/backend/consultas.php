<?php
include('database.php');

//Total de alunos
$queryAlunos = "SELECT COUNT(*) AS total FROM aluno";
$resultadoAlunos = mysqli_query($conexao, $queryAlunos);
$totalAlunos = mysqli_fetch_assoc($resultadoAlunos)['total'] ?? 0;

//Total de ocorrências
$queryOcorrencias = "SELECT COUNT(*) AS total FROM ocorrencia";
$resultadoOcorrencias = mysqli_query($conexao, $queryOcorrencias);
$totalOcorrencias = mysqli_fetch_assoc($resultadoOcorrencias)['total'] ?? 0;

//Ocorrências por Tipo (Gráfico de Pizza)
$queryTipos = "SELECT tipo_ocorrencia, COUNT(*) as qtd FROM ocorrencia GROUP BY tipo_ocorrencia";
$resultadoTipos = mysqli_query($conexao, $queryTipos);
$tiposLabels = [];
$tiposDados = [];
while($row = mysqli_fetch_assoc($resultadoTipos)){
    $tiposLabels[] = $row['tipo_ocorrencia'];
    $tiposDados[] = $row['qtd'];
}

//Ocorrências por Curso (Gráfico de Barras)
$queryCursos = "SELECT a.curso, COUNT(o.id_ocorrencia) as qtd 
                FROM ocorrencia o 
                JOIN aluno a ON o.fk_aluno_id_aluno = a.id_aluno 
                GROUP BY a.curso";
$resultadoCursos = mysqli_query($conexao, $queryCursos);
$cursosLabels = [];
$cursosDados = [];
while($row = mysqli_fetch_assoc($resultadoCursos)){
    $cursosLabels[] = $row['curso'];
    $cursosDados[] = $row['qtd'];
}
?>