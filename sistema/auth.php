<?php
require 'config.php';
if (!isset($_SESSION['id_usuario'])) {
  header('Location: login.php'); exit;
}
if (time() > $_SESSION['expira']) {
  session_destroy();
  header('Location: login.php?expirou=1'); exit;
}
$_SESSION['expira'] = time() + 900;