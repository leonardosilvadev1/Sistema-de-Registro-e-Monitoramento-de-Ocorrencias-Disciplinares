<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Tela de Login</title>
  <link rel="stylesheet" href="style.css">
  <link rel="shortcut icon" href="../assets/images/Logo Projeto Sem Fundo.png" type="image/x-icon">
</head>
<body>
  <div class="left">
    <img src="../assets/images/Logo Projeto Sem Fundo.png" style="height: 200px; width: 200px;" alt="Logo da Escola" class="logo">
    <h1>EEEP Manoel Mano</h1>
    <h2>CRATEÚS - CE</h2>
    <p><strong>Bem vindo!!!</strong><br>
    Acesse sua conta no Sistema de Registro e<br>
    Monitoramento de Ocorrências Disciplinares</p>
  </div>

  <div class="right">
    <div class="login-box">
      <h2>Login</h2>
      <form action="../backend/login.php" method="POST">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Digite seu email" required autofocus>

        <label for="password">Senha</label>
        <input type="password" id="password" name="password" placeholder="Digite sua senha" required>

        <a href="#">Esqueceu sua senha?</a>
        <button type="submit">Entrar</button>
      </form>
    </div>
  </div>
</body>
</html>