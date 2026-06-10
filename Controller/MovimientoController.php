<?php
require_once __DIR__ . '/../Entities/Movimiento.php';
require_once __DIR__ . '/../Model/MovimientoModel.php';

class MovimientoController {

    private $modelo_movimiento;

    public function __construct() {
        $this->modelo_movimiento = new MovimientoModel();
    }

    // GET
    public function inicio() {
        $movimientos = $this->modelo_movimiento->findAllView();
        require_once __DIR__ . '/../View/movimiento/lista.php';
    }

    public function detalle($id) {
        $movimiento = $this->modelo_movimiento->findByIdView($id);
        require_once __DIR__ . '/../View/movimiento/detalle.php';
    }

    public function nuevo() {
        require_once __DIR__ . '/../View/movimiento/nuevo.php';
    }

    // POST
    public function createMovimiento() {
        if (
            isset($_POST["nInsumoID"])   && !empty(trim($_POST["nInsumoID"])) &&
            isset($_POST["nLoteID"])     && !empty(trim($_POST["nLoteID"])) &&
            isset($_POST["nUsuarioID"])  && !empty(trim($_POST["nUsuarioID"])) &&
            isset($_POST["nproveedorID"]) && trim($_POST["nproveedorID"]) &&
            isset($_POST["nSolicitudID"]) && trim($_POST["nSolicitudID"])  &&
            isset($_POST["nCantidad"])    && !empty(trim($_POST["nCantidad"])) &&
            isset($_POST["eTipo"])        && !empty(trim($_POST["eTipo"])) &&
            isset($_POST["cMotivo"]) && !empty(trim($_POST["cMotivo"])) &&
            isset($_POST["dFecha"]) && !empty(trim($_POST["dFecha"]))

        ) {
            $nInsumoID    = trim($_POST["nInsumoID"]);
            $nLoteID      = trim($_POST["nLoteID"]);
            $nUsuarioID   = trim($_POST["nUsuarioID"]);
            $nproveedorID = trim($_POST["nproveedorID"]);
            $nSolicitudID =  trim($_POST["nSolicitudID"]);
            $nCantidad    = trim($_POST["nCantidad"]);
            $eTipo        = trim($_POST["eTipo"]);
            $cMotivo      = trim($_POST["cMotivo"]);
            $dFecha      = trim($_POST["dFecha"]);


            $movimiento = new Movimiento(
                null, $nInsumoID, $nLoteID, $nUsuarioID, $nproveedorID,
                $nSolicitudID, $nCantidad, $eTipo, $cMotivo, $dFecha
            );

            $rta = $this->modelo_movimiento->create($movimiento);

            if ($rta) {
                $_SESSION['msg']  = "Movimiento registrado correctamente";
                $_SESSION['tipo'] = "success";
            } else {
                $_SESSION['msg']  = "Error al registrar el movimiento";
                $_SESSION['tipo'] = "danger";
            }
            header("Location:" . BASE_URL . "movimiento/inicio");
        } else {
            echo "Los campos insumo, lote, usuario, cantidad y tipo son obligatorios";
        }
    }
}