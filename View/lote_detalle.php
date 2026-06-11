<?php
require_once __DIR__ . '/../Controller/LoteController.php';
$controller = new LoteController();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$controller->detalle($id);
