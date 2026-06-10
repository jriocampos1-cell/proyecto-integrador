<?php
require_once 'Entities/DetalleSolicitud.php';
require_once 'Model/DetalleSolicitudModel.php';

class DetalleSolicitudController {

    private $modelo_detalle;

    public function __construct() {
        $this->modelo_detalle = new DetalleSolicitudModel();
    }

    // GET
    public function porSolicitud($nSolicitudID) {
        $lista_detalles = $this->modelo_detalle->findBySolicitudId($nSolicitudID);
        require_once "View/detalle_solicitud/lista.php";
    }

    public function nuevo() {
        require_once "View/detalle_solicitud/nuevo.php";
    }

    // POST
    public function createDetalle() {
        if (
            isset($_POST["nSolicitudID"]) && !empty(trim($_POST["nSolicitudID"])) &&
            isset($_POST["nInsumoID"])    && !empty(trim($_POST["nInsumoID"])) &&
            isset($_POST["nCantidad"])     && !empty(trim($_POST["nCantidad"]))
        ) {
            $nSolicitudID = trim($_POST["nSolicitudID"]);
            $nInsumoID    = trim($_POST["nInsumoID"]);
            $nCantidad    = trim($_POST["nCantidad"]);

            $detalle = new DetalleSolicitud(null, $nSolicitudID, $nInsumoID, $nCantidad);

            $rta = $this->modelo_detalle->create($detalle);

            if ($rta) {
                $_SESSION['msg']  = "Detalle agregado correctamente";
                $_SESSION['tipo'] = "success";
            } else {
                $_SESSION['msg']  = "Error al agregar el detalle";
                $_SESSION['tipo'] = "danger";
            }
            header("Location:" . BASE_URL . "detalle_solicitud/porSolicitud/" );
        } else {
            echo "Todos los campos son obligatorios";
        }
    }
}