<?php
require 'auth.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Início</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<nav>
  <a href="index.php">Início</a>
  <a href="clientes.php">Clientes</a>
  <a href="veiculos.php">Veículos</a>
  <a href="locacoes.php">Locações</a>
  <a href="logout.php">Sair</a>
</nav>
<h2>Bem-vindo, <?= htmlspecialchars($_SESSION['nome']) ?>!</h2>
<p>Use o menu acima para acessar os recursos do sistema.</p>
</body>
</html>