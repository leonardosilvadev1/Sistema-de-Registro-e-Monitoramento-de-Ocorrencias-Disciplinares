<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Admin</title>
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="../assets/images/Logo Projeto Sem Fundo.png" type="image/x-icon">
</head>
<body class="main">
    <header>
        <nav>
            <div class="home">
                <img src="../assets/images/Logo Projeto Sem Fundo.png" style="height: 80px; width: 80px;" alt="Logo">
                <h3 style="color: rgb(23, 157, 35);">Gerenciamento de Ocorrências</h3>
            </div>
            <ul>
                <a href="ocorrencias.php">
                    <li>Ocorrências</li>
                </a>
                <a href="alunos.php">
                    <li>Alunos</li>
                </a>
                <a href="../backend/logout.php">
                    <li style="color: rgb(182, 0, 0);">Sair</li>
                </a>
            </ul>
        </nav>
    </header>
</body>
</html>