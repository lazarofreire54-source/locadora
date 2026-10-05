<?php
require 'auth.php';
$msg = '';

if (isset($_POST['salvar'])) {
  $nome = trim($_POST['nome']);
  $cpf = preg_replace('/\D/', '', $_POST['cpf']);
  if ($nome == '' || strlen($cpf) != 11) {
    $msg = 'Informe o nome e um CPF com 11 números.';
  } else {
    $pdo->prepare('INSERT INTO clientes (nome,cpf,telefone,email)
                   VALUES (?, AES_ENCRYPT(?,?), ?, ?)')
        ->execute([$nome, $cpf, CHAVE, $_POST['telefone'], $_POST['email']]);
    $msg = 'Cliente cadastrado!';
  }
}

if (isset($_GET['excluir'])) {
  try {
    $pdo->prepare('DELETE FROM clientes WHERE id_cliente=?')->execute([$_GET['excluir']]);
    $msg = 'Cliente excluído.';
  } catch (PDOException $e) {
    $msg = 'Não é possível excluir: o cliente tem locações.';
  }
}

$busca = trim($_GET['busca'] ?? '');
$sql = 'SELECT id_cliente, nome, CAST(AES_DECRYPT(cpf,?) AS CHAR) AS cpf, telefone, email
        FROM clientes';
$params = [CHAVE];
if ($busca != '') {
  $sql .= ' WHERE nome LIKE ? OR email LIKE ? OR telefone LIKE ?';
  array_push($params, "%$busca%", "%$busca%", "%$busca%");
}
$st = $pdo->prepare($sql . ' ORDER BY nome');
$st->execute($params);
$lista = $st->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Clientes</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<nav>
  Usuário: <?= htmlspecialchars($_SESSION['nome']) ?> |
  <a href="index.php">Início</a>
  <a href="logout.php">Sair</a>
</nav>

<h2>Clientes</h2>
<?php if ($msg) echo "<p class='aviso'>$msg</p>"; ?>

<form method="get">
  <input name="busca" placeholder="Buscar nome, e-mail ou telefone"
         value="<?= htmlspecialchars($busca) ?>">
  <button>Buscar</button>
  <a href="clientes.php">Limpar</a>
</form>

<form method="post">
  <h3>Novo cliente</h3>
  Nome: <input name="nome" required>
  CPF: <input name="cpf" maxlength="14" required>
  Telefone: <input name="telefone">
  E-mail: <input type="email" name="email">
  <button name="salvar">Salvar</button>
</form>

<table>
<tr><th>Nome</th><th>CPF</th><th>Telefone</th><th>E-mail</th><th>Ação</th></tr>
<?php foreach ($lista as $c): ?>
<tr>
  <td><?= htmlspecialchars($c['nome']) ?></td>
  <td><?= substr($c['cpf'],0,3) . '.***.***-' . substr($c['cpf'],-2) ?></td>
  <td><?= htmlspecialchars($c['telefone']) ?></td>
  <td><?= htmlspecialchars($c['email']) ?></td>
  <td><a href="?excluir=<?= $c['id_cliente'] ?>"
         onclick="return confirm('Excluir?')">Excluir</a></td>
</tr>
<?php endforeach; ?>
</table>
</body>
</html>