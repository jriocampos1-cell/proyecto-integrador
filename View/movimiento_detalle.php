<?php
require_once __DIR__ . '/../Controller/MovimientoController.php';
$controller = new MovimientoController();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$controller->detalle($id);
