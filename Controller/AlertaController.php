<?php
require_once __DIR__ . '/../Entities/Alerta.php';
require_once __DIR__ . '/../Model/AlertaModel.php';

class AlertaController {

    private $modelo_alerta;

    public function __construct() {
        $this->modelo_alerta = new AlertaModel();
    }

    // GET
    public function detalle($id) {
        $alerta = $this->modelo_alerta->findByIdView($id);
        require_once __DIR__ . '/../View/alerta/detalle.php';
    }

    public function inicio() {
        $alertas = $this->modelo_alerta->findAllView();
        require_once __DIR__ . '/../View/alerta/lista.php';
    }

    public function activas() {
        $lista_alertas = $this->modelo_alerta->findActivas();
        require_once __DIR__ . '/../View/alerta/activas.php';
    }

    public function nuevo() {
        require_once __DIR__ . '/../View/alerta/nuevo.php';
    }

    // POST
    public function createAlerta() {
        if (
            isset($_POST["nInsumoID"])  && !empty(trim($_POST["nInsumoID"])) &&
            isset($_POST["nLoteID"])    && !empty(trim($_POST["nLoteID"])) &&
            isset($_POST["eTipo"])       && !empty(trim($_POST["eTipo"])) &&
            isset($_POST["cMensaje"])    && !empty(trim($_POST["cMensaje"])) &&
            isset($_POST["eEstado"])     && !empty(trim($_POST["eEstado"]))
        ) {
            $nInsumoID = trim($_POST["nInsumoID"]);
            $nLoteID   = trim($_POST["nLoteID"]);
            $eTipo     = trim($_POST["eTipo"]);
            $cMensaje  = trim($_POST["cMensaje"]);
            $eEstado   = trim($_POST["eEstado"]);

            $alerta = new Alerta(null, $nInsumoID, $nLoteID, $eTipo, $cMensaje, $eEstado);

            $rta = $this->modelo_alerta->create($alerta);

            if ($rta) {
                $_SESSION['msg']  = "Alerta registrada correctamente";
                $_SESSION['tipo'] = "success";
            } else {
                $_SESSION['msg']  = "Error al registrar la alerta";
                $_SESSION['tipo'] = "danger";
            }
            header("Location:" . BASE_URL . "alerta/inicio");
        } else {
            echo "Todos los campos son obligatorios";
        }
    }

    public function atenderAlerta($id) {
        $rta = $this->modelo_alerta->atenderAlerta($id);

        if ($rta) {
            $_SESSION['msg']  = "Alerta marcada como atendida";
            $_SESSION['tipo'] = "success";
        } else {
            $_SESSION['msg']  = "Error al atender la alerta";
            $_SESSION['tipo'] = "danger";
        }
        header("Location:" . BASE_URL . "alerta/inicio");
    }
}