<?php
require_once __DIR__ . '/../Controller/ProveedorController.php';
$controller = new ProveedorController();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$controller->detalle($id);
