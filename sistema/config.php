<?php
session_start();
$pdo = new PDO(
  'mysql:host=127.0.0.1;port=3307;dbname=locadora_db;charset=utf8mb4',
  'root', ''
);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
define('CHAVE', 'chave123');