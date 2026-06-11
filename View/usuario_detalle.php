<?php
require_once __DIR__ . '/../Controller/UsuarioController.php';
$controller = new UsuarioController();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$controller->detalle($id);
