<?php
require_once __DIR__ . '/../Entities/Lote.php';
require_once __DIR__ . '/../Model/LoteModel.php';

class LoteController {

    private $modelo_lote;

    public function __construct() {
        $this->modelo_lote = new LoteModel();
    }

    // GET
    public function inicio() {
        $lotes = $this->modelo_lote->findAllView();
        require_once __DIR__ . '/../View/lote/lista.php';
    }

    public function nuevo() {
        require_once __DIR__ . '/../View/lote/nuevo.php';
    }

    public function detalle($id) {
        $lote = $this->modelo_lote->findByIdView($id);
        require_once __DIR__ . '/../View/lote/detalle.php';
    }

    public function porInsumo($insumoID) {
        $lista_lotes = $this->modelo_lote->findByInsumoId($insumoID);
        require_once __DIR__ . '/../View/lote/lista.php';
    }

    // POST
    public function registrarEntrada() {
        if (
            isset($_POST["insumo_id"])    && !empty(trim($_POST["insumo_id"])) &&
            isset($_POST["codigo_lote"])  && !empty(trim($_POST["codigo_lote"])) &&
            isset($_POST["cantidad"])     && !empty(trim($_POST["cantidad"])) &&
            isset($_POST["vencimiento"])  && !empty(trim($_POST["vencimiento"])) &&
            isset($_POST["usuario_id"])   && !empty(trim($_POST["usuario_id"])) &&
            isset($_POST["proveedor_id"]) && !empty(trim($_POST["proveedor_id"]))
        ) {
            $insumoID    = trim($_POST["insumo_id"]);
            $codigoLote  = trim($_POST["codigo_lote"]);
            $cantidad    = trim($_POST["cantidad"]);
            $vencimiento = trim($_POST["vencimiento"]);
            $usuarioID   = trim($_POST["usuario_id"]);
            $proveedorID = trim($_POST["proveedor_id"]);

            $rta = $this->modelo_lote->registrarEntradaPro(
                $insumoID, $codigoLote, $cantidad, $vencimiento, $usuarioID, $proveedorID
            );

            if ($rta) {
                $_SESSION['msg']  = "Entrada de lote registrada correctamente";
                $_SESSION['tipo'] = "success";
            } else {
                $_SESSION['msg']  = "Error al registrar la entrada";
                $_SESSION['tipo'] = "danger";
            }
            header("Location:" . BASE_URL . "lote/inicio");
        } else {
            echo "Todos los campos son obligatorios";
        }
    }
}