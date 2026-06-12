<?php
require_once __DIR__ . '/../Entities/Usuario.php';
require_once __DIR__ . '/../Model/UsuarioModel.php';

class UsuarioController {

    private $modelo_usuario;

    public function __construct() {
        $this->modelo_usuario = new UsuarioModel();
    }

    // GET
    public function inicio() {
        $usuarios = $this->modelo_usuario->findAllView();
        require_once __DIR__ . '/../View/usuario/lista.php';
    }

    public function login() {
        require_once __DIR__ . '/../View/usuario/login.php';
    }

    // POST: procesa autenticación
    public function authenticate() {
        if (isset($_POST['cNombre']) && isset($_POST['cCedula'])) {
            $nombre = trim($_POST['cNombre']);
            $cedula = trim($_POST['cCedula']);
            // Primero intenta autenticar por nombre de persona, luego por username (compatibilidad)
            $usuario = $this->modelo_usuario->authenticate($nombre, $cedula);
            if (!$usuario) {
                $usuario = $this->modelo_usuario->authenticateByUsername($nombre, $cedula);
            }
            if ($usuario) {
                // Iniciar sesión y guardar datos esenciales
                $_SESSION['user'] = [
                    'id' => $usuario->getNUsuarioID(),
                    'nombre' => $usuario->getCNombre(),
                    'usuario' => $usuario->getCNombreUsuario(),
                    'rol' => $usuario->getERol()
                ];
                // Redirigir al dashboard según el rol
                $rol = $usuario->getERol();
                switch ($rol) {
                    case 'gerente':
                        header('Location: ' . BASE_URL . 'dashboard/gerencia');
                        break;
                    case 'bodega':
                        header('Location: ' . BASE_URL . 'dashboard/bodega');
                        break;
                    case 'cocinero':
                    case 'pastelero':
                        header('Location: ' . BASE_URL . 'dashboard/cocina');
                        break;
                    default:
                        header('Location: ' . BASE_URL . 'dashboard/inicio');
                }
                exit();
            } else {
                $_SESSION['msg'] = 'Nombre o cédula inválidos';
                $_SESSION['tipo'] = 'danger';
                header('Location: ' . BASE_URL . 'usuario/login');
                exit();
            }
        } else {
            echo 'Datos incompletos';
        }
    }

    public function logout() {
        unset($_SESSION['user']);
        session_destroy();
        header('Location: ' . BASE_URL . 'usuario/login');
        exit();
    }

    public function nuevo() {
        // Solo gerente puede registrar nuevos usuarios
        $rol = $_SESSION['user']['rol'] ?? '';
        if ($rol !== 'gerente') {
            $_SESSION['msg'] = 'No tienes permisos para crear usuarios.';
            $_SESSION['tipo'] = 'danger';
            header('Location: ' . BASE_URL . 'dashboard/inicio');
            exit();
        }
        require_once __DIR__ . '/../View/usuario/nuevo.php';
    }

    public function detalle($id) {
        $usuario = $this->modelo_usuario->findByIdView($id);
        require_once __DIR__ . '/../View/usuario/detalle.php';
    }

    public function editar($id) {
        $usuario = $this->modelo_usuario->findByIdView($id);
        require_once __DIR__ . '/../View/usuario/editar.php';
    }

    // POST
    public function createUsuario() {
        if (
            isset($_POST["cNombre"])         && !empty(trim($_POST["cNombre"])) &&
            isset($_POST["cNombreUsuario"]) && !empty(trim($_POST["cNombreUsuario"])) &&
            isset($_POST["cContraseñaUsuario"])     && !empty(trim($_POST["cContraseñaUsuario"])) &&
            isset($_POST["eRol"])            && !empty(trim($_POST["eRol"])) &&
            isset($_POST["cCorreo"])         && !empty(trim($_POST["cCorreo"]))
        ) {
            $cNombre     = trim($_POST["cNombre"]);
            $cNombreUsuario = trim($_POST["cNombreUsuario"]);
            $cContraseñaUsuario    = trim($_POST["cContraseñaUsuario"]);
            $eRol           = trim($_POST["eRol"]);
            $cCorreo        = trim($_POST["cCorreo"]);

            $usuario = new Usuario(null, $cNombre, $cNombreUsuario, $cContraseñaUsuario, $eRol, 'activo', $cCorreo);

            $rta = $this->modelo_usuario->create($usuario);

            if ($rta) {
                $_SESSION['msg']  = "Usuario registrado correctamente";
                $_SESSION['tipo'] = "success";
            } else {
                $_SESSION['msg']  = "Error al registrar el usuario";
                $_SESSION['tipo'] = "danger";
            }
            header("Location:" . BASE_URL . "usuario/inicio");
        } else {
            echo "Todos los campos son obligatorios";
        }
    }

    public function updateUsuario() {
        if (
            isset($_POST["nUsuarioID"])     && !empty(trim($_POST["nUsuarioID"])) &&
            isset($_POST["cNombre"])         && !empty(trim($_POST["cNombre"])) &&
            isset($_POST["cNombreUsuario"]) && !empty(trim($_POST["cNombreUsuario"])) &&
            isset($_POST["cContraseñaUsuario"])     && !empty(trim($_POST["cContraseñaUsuario"])) &&
            isset($_POST["eRol"])            && !empty(trim($_POST["eRol"])) &&
            isset($_POST["eEstado"])         && !empty(trim($_POST["eEstado"])) &&
            isset($_POST["cCorreo"])         && !empty(trim($_POST["cCorreo"]))
        ) {
            $id            = trim($_POST["nUsuarioID"]);
            $cNombre        = trim($_POST["cNombre"]);
            $cNombreUsuario = trim($_POST["cNombreUsuario"]);
            $cContraseñaUsuario    = trim($_POST["cContraseñaUsuario"]);
            $eRol           = trim($_POST["eRol"]);
            $eEstado        = trim($_POST["eEstado"]);
            $cCorreo        = trim($_POST["cCorreo"]);

            $usuario = new Usuario($id, $cNombre, $cNombreUsuario, $cContraseñaUsuario, $eRol, $eEstado, $cCorreo);

            $rta = $this->modelo_usuario->update($usuario);

            if ($rta) {
                $_SESSION['msg']  = "Usuario actualizado correctamente";
                $_SESSION['tipo'] = "success";
            } else {
                $_SESSION['msg']  = "Error al actualizar el usuario";
                $_SESSION['tipo'] = "danger";
            }
            header("Location:" . BASE_URL . "usuario/inicio");
        } else {
            echo "Todos los campos son obligatorios para actualizar";
        }
    }

    public function desactivarUsuario($id) {
        $rta = $this->modelo_usuario->desactivar($id);

        if ($rta) {
            $_SESSION['msg']  = "Usuario desactivado correctamente";
            $_SESSION['tipo'] = "success";
        } else {
            $_SESSION['msg']  = "Error al desactivar el usuario";
            $_SESSION['tipo'] = "danger";
        }
        header("Location:" . BASE_URL . "usuario/inicio");
    }
}