<?php
require_once __DIR__ . '/../Entities/Proveedor.php';
require_once __DIR__ . '/../Model/ProveedorModel.php';

class ProveedorController {

    private $modelo_proveedor;

    public function __construct() {
        $this->modelo_proveedor = new ProveedorModel();
    }

    // GET
    public function inicio() {
        $proveedores = $this->modelo_proveedor->findAllView();
        require_once __DIR__ . '/../View/proveedor/lista.php';
    }

    public function nuevo() {
        require_once __DIR__ . '/../View/proveedor/nuevo.php';
    }

    public function detalle($id) {
        $proveedor = $this->modelo_proveedor->findByIdView($id);
        require_once __DIR__ . '/../View/proveedor/detalle.php';
    }

    public function editar($id) {
        $proveedor = $this->modelo_proveedor->findByIdView($id);
        require_once __DIR__ . '/../View/proveedor/editar.php';
    }

    // POST
    public function createProveedor() {
        if (
            isset($_POST["cNombre"])   && !empty(trim($_POST["cNombre"])) &&
            isset($_POST["cTelefono"]) && !empty(trim($_POST["cTelefono"])) &&
            isset($_POST["cCorreo"])   && !empty(trim($_POST["cCorreo"])) &&
            isset($_POST["eEstado"])   && !empty(trim($_POST["eEstado"]))
        ) {
            $cNombre   = trim($_POST["cNombre"]);
            $cTelefono = trim($_POST["cTelefono"]);
            $cCorreo   = trim($_POST["cCorreo"]);
            $eEstado   = trim($_POST["eEstado"]);

            $proveedor = new Proveedor(null, $cNombre, $cTelefono, $cCorreo, $eEstado);

            $rta = $this->modelo_proveedor->create($proveedor);

            if ($rta) {
                $_SESSION['msg']  = "Proveedor registrado correctamente";
                $_SESSION['tipo'] = "success";
            } else {
                $_SESSION['msg']  = "Error al registrar el proveedor";
                $_SESSION['tipo'] = "danger";
            }
            header("Location:" . BASE_URL . "proveedor/inicio");
        } else {
            echo "Todos los campos son obligatorios";
        }
    }

    public function updateProveedor() {
        if (
            isset($_POST["nProveedorID"]) && !empty(trim($_POST["nProveedorID"])) &&
            isset($_POST["cNombre"])       && !empty(trim($_POST["cNombre"])) &&
            isset($_POST["cTelefono"])     && !empty(trim($_POST["cTelefono"])) &&
            isset($_POST["cCorreo"])       && !empty(trim($_POST["cCorreo"])) &&
            isset($_POST["eEstado"])       && !empty(trim($_POST["eEstado"]))
        ) {
            $id       = trim($_POST["nProveedorID"]);
            $cNombre   = trim($_POST["cNombre"]);
            $cTelefono = trim($_POST["cTelefono"]);
            $cCorreo   = trim($_POST["cCorreo"]);
            $eEstado   = trim($_POST["eEstado"]);

            $proveedor = new Proveedor($id, $cNombre, $cTelefono, $cCorreo, $eEstado);

            $rta = $this->modelo_proveedor->update($proveedor);

            if ($rta) {
                $_SESSION['msg']  = "Proveedor actualizado correctamente";
                $_SESSION['tipo'] = "success";
            } else {
                $_SESSION['msg']  = "Error al actualizar el proveedor";
                $_SESSION['tipo'] = "danger";
            }
            header("Location:" . BASE_URL . "proveedor/inicio");
        } else {
            echo "Todos los campos son obligatorios para actualizar";
        }
    }

    public function desactivarProveedor($id) {
        $rta = $this->modelo_proveedor->desactivar($id);

        if ($rta) {
            $_SESSION['msg']  = "Proveedor desactivado correctamente";
            $_SESSION['tipo'] = "success";
        } else {
            $_SESSION['msg']  = "Error al desactivar el proveedor";
            $_SESSION['tipo'] = "danger";
        }
        header("Location:" . BASE_URL . "proveedor/inicio");
    }
}