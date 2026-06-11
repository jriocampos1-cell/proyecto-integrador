<?php
require_once __DIR__ . '/../Controller/InsumoController.php';
$controller = new InsumoController();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$controller->detalle($id);
