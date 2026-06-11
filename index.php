<?php

require_once "Config/config.php";

session_start();

$url = (isset($_GET['url']) && $_GET['url'] != '')
    ? $_GET['url']
    : 'index';

$accion = (isset($_GET['accion']) && $_GET['accion'] != '')
    ? $_GET['accion']
    : 'inicio';

$id = (isset($_GET['id']) && $_GET['id'] != '')
    ? $_GET['id']
    : null;

$parts = explode('_', $url);
$controlador = '';
foreach ($parts as $part) {
    $controlador .= ucfirst($part);
}
$controlador .= 'Controller';
$ruta = "Controller/" . $controlador . ".php";

// Protección simple: si no hay sesión de usuario activa, forzar la vista de login
// Permitimos que se acceda a las acciones de registro/login sin autenticación
if (!isset($_SESSION['user'])) {
    $publicController = 'UsuarioController';
    $publicActions = ['login', 'authenticate', 'createUsuario'];
    if (!($controlador === $publicController && in_array($accion, $publicActions))) {
        // Forzamos a mostrar el login
        $controlador = $publicController;
        $ruta = "Controller/" . $controlador . ".php";
        $accion = 'login';
    }
}
if (file_exists($ruta)) {

    require_once $ruta;

    if (class_exists($controlador)) {
        $controller = new $controlador();

        if (method_exists($controller, $accion)) {
            if ($id) {
                $controller->$accion($id);
            } else {
                $controller->$accion();
            }
        } else {
            $error = "Accion no encontrada";
            require_once "View/Errors/404.php";
        }
    } else {
        $error = "Controlador no definido";
        require_once "View/Errors/404.php";
    }

} else {

    $error = "Controlador no encontrado";
    require_once "View/Errors/404.php";
}
