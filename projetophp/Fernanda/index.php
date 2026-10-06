<?php
// filepath: c:\xampp\htdocs\projetophp\index.php

session_start();

require_once __DIR__ . '/model/versiculo.php';
require_once __DIR__ . '/model/usuario.php';
require_once __DIR__ . '/controller/VersiculoController.php';

$pdo = require __DIR__ . '/config/database.php';

$controller = new VersiculoController(new Versiculo($pdo), new Usuario($pdo));
$pagina = $_GET['pagina'] ?? 'home';
$controller->handle(is_string($pagina) ? $pagina : 'home');