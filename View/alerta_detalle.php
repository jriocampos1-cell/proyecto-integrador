<?php
require_once __DIR__ . '/../Controller/AlertaController.php';
$controller = new AlertaController();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$controller->detalle($id);
