<?php
require_once __DIR__ . '/../Controller/SolicitudController.php';
$controller = new SolicitudController();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$controller->detalle($id);
