<?php
// Redirigir al front controller para usar la nueva vista en View/solicitud/detalle.php
$id = $_GET['id'] ?? 0;
header('Location: ' . BASE_URL . 'solicitud/detalle/' . $id);
exit();