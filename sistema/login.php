<?php
require 'config.php';
$erro = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = trim($_POST['email']);
  $senha = $_POST['senha'];
  if ($email == '' || $senha == '') {
    $erro = 'Preencha e-mail e senha.';
  } else {
    $st = $pdo->prepare('SELECT * FROM usuarios WHERE email=? AND senha=SHA2(?,256)');
    $st->execute([$email, $senha]);
    $u = $st->fetch(PDO::FETCH_ASSOC);
    if ($u) {
      $_SESSION['id_usuario'] = $u['id_usuario'];
      $_SESSION['nome'] = $u['nome'];
      $_SESSION['expira'] = time() + 900;
      $pdo->prepare('UPDATE usuarios SET expiracao=? WHERE id_usuario=?')
          ->execute([date('Y-m-d H:i:s', $_SESSION['expira']), $u['id_usuario']]);
      header('Location: index.php');
      exit;
    }
    $erro = 'E-mail ou senha inválidos.';
  }
}
if (isset($_GET['expirou'])) $erro = 'Sessão expirada. Entre novamente.';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="login">
<form method="post">
  <h2>Locadora de Veículos</h2>
  <?php if ($erro) echo "<p class='erro'>$erro</p>"; ?>
  E-mail:
  <input type="email" name="email">
  Senha:
  <input type="password" name="senha">
  <button>Entrar</button>
</form>
</body>
</html>