<?php
require 'auth.php';
$msg = '';

if (isset($_POST['salvar'])) {
  $cli = (int)$_POST['id_cliente'];
  $vei = (int)$_POST['id_veiculo'];
  $data = $_POST['data_retirada'];

  $st = $pdo->prepare("SELECT COUNT(*) FROM veiculos WHERE id_veiculo=? AND status='disponivel'");
  $st->execute([$vei]);

  if (!$cli || !$vei || $data == '') {
    $msg = 'Selecione cliente, veículo e a data de retirada.';
  } elseif (!$st->fetchColumn()) {
    $msg = 'Este veículo não está disponível.';
  } else {
    $pdo->prepare("INSERT INTO locacoes (id_usuario,id_cliente,id_veiculo,data_retirada,status)
                   VALUES (?,?,?,?,'aberta')")
        ->execute([$_SESSION['id_usuario'], $cli, $vei, $data]);
    $pdo->prepare("UPDATE veiculos SET status='locado' WHERE id_veiculo=?")->execute([$vei]);
    $msg = 'Locação cadastrada!';
  }
}

if (isset($_GET['devolver'])) {
  $id = (int)$_GET['devolver'];
  $pdo->prepare("UPDATE veiculos SET status='disponivel'
                 WHERE id_veiculo=(SELECT id_veiculo FROM locacoes WHERE id_locacao=?)")
      ->execute([$id]);
  $pdo->prepare("UPDATE locacoes SET data_devolucao=CURDATE(), status='devolvida'
                 WHERE id_locacao=? AND status='aberta'")->execute([$id]);
  $msg = 'Devolução registrada!';
}

$clientes = $pdo->query('SELECT id_cliente, nome FROM clientes ORDER BY nome')->fetchAll(PDO::FETCH_ASSOC);
$veiculos = $pdo->query("SELECT id_veiculo, marca, modelo, placa FROM veiculos
                         WHERE status='disponivel' ORDER BY marca")->fetchAll(PDO::FETCH_ASSOC);

$lista = $pdo->query("SELECT l.id_locacao, l.data_retirada, l.data_devolucao, l.status,
                             c.nome AS cliente, v.marca, v.modelo, v.placa
                      FROM locacoes l
                      JOIN clientes c ON c.id_cliente = l.id_cliente
                      JOIN veiculos v ON v.id_veiculo = l.id_veiculo
                      ORDER BY l.data_retirada DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Locações</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<nav>
  Usuário: <?= htmlspecialchars($_SESSION['nome']) ?> |
  <a href="index.php">Início</a>
  <a href="logout.php">Sair</a>
</nav>

<h2>Locações</h2>
<?php if ($msg) echo "<p class='aviso'>$msg</p>"; ?>

<form method="post">
  <h3>Nova locação</h3>
  Cliente:
  <select name="id_cliente" required>
    <option value="">Selecione</option>
    <?php foreach ($clientes as $c): ?>
      <option value="<?= $c['id_cliente'] ?>"><?= htmlspecialchars($c['nome']) ?></option>
    <?php endforeach; ?>
  </select>
  Veículo:
  <select name="id_veiculo" required>
    <option value="">Selecione</option>
    <?php foreach ($veiculos as $v): ?>
      <option value="<?= $v['id_veiculo'] ?>">
        <?= htmlspecialchars($v['marca'].' '.$v['modelo'].' - '.$v['placa']) ?></option>
    <?php endforeach; ?>
  </select>
  Data de retirada:
  <input type="date" name="data_retirada" value="<?= date('Y-m-d') ?>" required>
  <button name="salvar">Cadastrar</button>
</form>

<table>
<tr><th>Retirada</th><th>Cliente</th><th>Veículo</th><th>Devolução</th><th>Status</th><th>Ação</th></tr>
<?php foreach ($lista as $l): ?>
<tr>
  <td><?= date('d/m/Y', strtotime($l['data_retirada'])) ?></td>
  <td><?= htmlspecialchars($l['cliente']) ?></td>
  <td><?= htmlspecialchars($l['marca'].' '.$l['modelo'].' ('.$l['placa'].')') ?></td>
  <td><?= $l['data_devolucao'] ? date('d/m/Y', strtotime($l['data_devolucao'])) : '-' ?></td>
  <td><?= $l['status'] ?></td>
  <td><?php if ($l['status'] == 'aberta'): ?>
      <a href="?devolver=<?= $l['id_locacao'] ?>">Devolver</a><?php endif; ?></td>
</tr>
<?php endforeach; ?>
</table>
</body>
</html>