<?php
require 'auth.php';
$msg = '';

if (isset($_POST['salvar'])) {
  $d = [trim($_POST['marca']), trim($_POST['modelo']),
        strtoupper(trim($_POST['placa'])), (int)$_POST['ano'], $_POST['status']];
  if ($d[0] == '' || $d[1] == '' || $d[2] == '') {
    $msg = 'Preencha marca, modelo e placa.';
  } else {
    if ($_POST['id'] != '') {
      $d[] = $_POST['id'];
      $pdo->prepare('UPDATE veiculos SET marca=?, modelo=?, placa=?, ano=?, status=?
                     WHERE id_veiculo=?')->execute($d);
      $msg = 'Veículo atualizado!';
    } else {
      $pdo->prepare('INSERT INTO veiculos (marca,modelo,placa,ano,status)
                     VALUES (?,?,?,?,?)')->execute($d);
      $msg = 'Veículo cadastrado!';
    }
  }
}

$v = ['id_veiculo'=>'','marca'=>'','modelo'=>'','placa'=>'','ano'=>'','status'=>'disponivel'];
if (isset($_GET['editar'])) {
  $st = $pdo->prepare('SELECT * FROM veiculos WHERE id_veiculo=?');
  $st->execute([$_GET['editar']]);
  $v = $st->fetch(PDO::FETCH_ASSOC) ?: $v;
}

$lista = $pdo->query('SELECT * FROM veiculos ORDER BY marca, modelo')->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Veículos</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<nav>
  Usuário: <?= htmlspecialchars($_SESSION['nome']) ?> |
  <a href="index.php">Início</a>
  <a href="logout.php">Sair</a>
</nav>

<h2>Veículos</h2>
<?php if ($msg) echo "<p class='aviso'>$msg</p>"; ?>

<form method="post">
  <h3><?= $v['id_veiculo'] ? 'Editar veículo' : 'Novo veículo' ?></h3>
  <input type="hidden" name="id" value="<?= $v['id_veiculo'] ?>">
  Marca: <input name="marca" required value="<?= htmlspecialchars($v['marca']) ?>">
  Modelo: <input name="modelo" required value="<?= htmlspecialchars($v['modelo']) ?>">
  Placa: <input name="placa" maxlength="8" required value="<?= htmlspecialchars($v['placa']) ?>">
  Ano: <input type="number" name="ano" value="<?= htmlspecialchars($v['ano']) ?>">
  Status:
  <select name="status">
    <?php foreach (['disponivel','locado','manutencao'] as $s): ?>
      <option value="<?= $s ?>" <?= $v['status'] == $s ? 'selected' : '' ?>><?= $s ?></option>
    <?php endforeach; ?>
  </select>
  <button name="salvar">Salvar</button>
  <a href="veiculos.php">Novo / Cancelar</a>
</form>

<table>
<tr><th>Marca</th><th>Modelo</th><th>Placa</th><th>Ano</th><th>Status</th><th>Ação</th></tr>
<?php foreach ($lista as $x): ?>
<tr>
  <td><?= htmlspecialchars($x['marca']) ?></td>
  <td><?= htmlspecialchars($x['modelo']) ?></td>
  <td><?= htmlspecialchars($x['placa']) ?></td>
  <td><?= $x['ano'] ?></td>
  <td><?= $x['status'] ?></td>
  <td><a href="?editar=<?= $x['id_veiculo'] ?>">Editar</a></td>
</tr>
<?php endforeach; ?>
</table>
</body>
</html>