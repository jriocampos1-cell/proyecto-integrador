<?php
require_once 'Entities/Solicitud.php';
require_once 'Model/SolicitudModel.php';

class SolicitudController {

    private $modelo_solicitud;

    public function __construct() {
        $this->modelo_solicitud = new SolicitudModel();
    }

    // GET
    public function inicio() {
        $lista_solicitudes = $this->modelo_solicitud->findAll();
        require_once "View/solicitud/lista.php";
    }

    public function nuevo() {
        require_once "View/solicitud/nuevo.php";
    }

    public function detalle($id) {
        $solicitud = $this->modelo_solicitud->findById($id);
        require_once "View/solicitud/detalle.php";
    }

    // POST
    public function createSolicitud() {
        if (
            isset($_POST["nUsuarioID"]) && !empty(trim($_POST["nUsuarioID"])) &&
            isset($_POST["eEstado"])     && !empty(trim($_POST["eEstado"]))
        ) {
            $nUsuarioID = trim($_POST["nUsuarioID"]);
            $eEstado    = trim($_POST["eEstado"]);

            $solicitud = new Solicitud(null, $nUsuarioID, $eEstado, null, null);

            $rta = $this->modelo_solicitud->create($solicitud);

            if ($rta) {
                $_SESSION['msg']  = "Solicitud creada correctamente";
                $_SESSION['tipo'] = "success";
            } else {
                $_SESSION['msg']  = "Error al crear la solicitud";
                $_SESSION['tipo'] = "danger";
            }
            header("Location:" . BASE_URL . "solicitud/inicio");
        } else {
            echo "Todos los campos son obligatorios";
        }
    }

    public function aprobarSolicitud() {
        if (
            isset($_POST["nSolicitudID"]) && !empty(trim($_POST["nSolicitudID"])) &&
            isset($_POST["nGerenteID"])   && !empty(trim($_POST["nGerenteID"]))
        ) {
            $nSolicitudID = trim($_POST["nSolicitudID"]);
            $nGerenteID   = trim($_POST["nGerenteID"]);

            $rta = $this->modelo_solicitud->aprobarFEFO($nSolicitudID, $nGerenteID);

            if ($rta) {
                $_SESSION['msg']  = "Solicitud aprobada y procesada correctamente (FEFO)";
                $_SESSION['tipo'] = "success";
            } else {
                $_SESSION['msg']  = "Error al aprobar la solicitud";
                $_SESSION['tipo'] = "danger";
            }
            header("Location:" . BASE_URL . "solicitud/inicio");
        } else {
            echo "Solicitud y gerente son obligatorios para aprobar";
        }
    }
}