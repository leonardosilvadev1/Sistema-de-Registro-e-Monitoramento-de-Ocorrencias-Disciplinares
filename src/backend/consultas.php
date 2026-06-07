<?php
include('database.php');

//Total de alunos
$totalAlunos = "SELECT COUNT(*) AS total FROM alunos";
$resultadoAlunos = mysqli_query($conexao, $totalAlunos);
$totalAlunos = mysqli_fetch_assoc($resultadoAlunos)['total'];

//Total de ocorrências
$totalOcorrencias = "SELECT COUNT(*) AS total FROM ocorrencia";
$resultadoOcorrencias = mysqli_query($conexao, $totalOcorrencias);
$totalOcorrencias = mysqli_fetch_assoc($resultadoOcorrencias)['total'];
?>