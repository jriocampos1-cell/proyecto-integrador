<?php
require_once 'Entities/Insumo.php';
require_once 'Model/InsumoModel.php';

class InsumoController {

    private $modelo_insumo;

    public function __construct() {
        $this->modelo_insumo = new InsumoModel();
    }

    // GET
    public function inicio() {
        $lista_insumos = $this->modelo_insumo->findAll();
        require_once "View/insumo/lista.php";
    }

    public function nuevo() {
        require_once "View/insumo/nuevo.php";
    }

    public function detalle($id) {
        $insumo = $this->modelo_insumo->findById($id);
        require_once "View/insumo/detalle.php";
    }

    public function editar($id) {
        $insumo = $this->modelo_insumo->findById($id);
        require_once "View/insumo/editar.php";
    }

    public function semaforoStock() {
        $semaforo = $this->modelo_insumo->SemaforoStock();
        require_once "View/insumo/semaforo.php";
    }

    public function inventarioFEFO() {
        $inventario = $this->modelo_insumo->InventarioFEFO();
        require_once "View/insumo/fefo.php";
    }

    public function analisisMermas() {
        $mermas = $this->modelo_insumo->AnalisisMermas();
        require_once "View/insumo/mermas.php";
    }

    // POST
    public function createInsumo() {
        if (
            isset($_POST["cNombre"])    && !empty(trim($_POST["cNombre"])) &&
            isset($_POST["cCategoria"]) && !empty(trim($_POST["cCategoria"])) &&
            isset($_POST["eUnidad"])    && !empty(trim($_POST["eUnidad"])) &&
            isset($_POST["nStockMinimo"]) && !empty(trim($_POST["nStockMinimo"]))
        ) {
            $cNombre    = trim($_POST["cNombre"]);
            $cCategoria = trim($_POST["cCategoria"]);
            $eUnidad    = trim($_POST["eUnidad"]);
            $nStockMinimo  = trim($_POST["nStockMinimo"]);

            $insumo = new Insumo(null, $cNombre, $cCategoria, $eUnidad, $nStockMinimo);

            $rta = $this->modelo_insumo->create($insumo);

            if ($rta) {
                $_SESSION['msg']  = "Insumo registrado correctamente";
                $_SESSION['tipo'] = "success";
            } else {
                $_SESSION['msg']  = "Error al registrar el insumo";
                $_SESSION['tipo'] = "danger";
            }
            header("Location:" . BASE_URL . "insumo/inicio");
        } else {
            echo "Todos los campos son obligatorios";
        }
    }

    public function updateInsumo() {
        if (
            isset($_POST["nInsumoID"])  && !empty(trim($_POST["nInsumoID"])) &&
            isset($_POST["cNombre"])     && !empty(trim($_POST["cNombre"])) &&
            isset($_POST["cCategoria"])  && !empty(trim($_POST["cCategoria"])) &&
            isset($_POST["eUnidad"])     && !empty(trim($_POST["eUnidad"])) &&
            isset($_POST["nStockMinimo"])  && !empty(trim($_POST["nStockMinimo"]))
        ) {
            $id     = trim($_POST["nInsumoID"]);
            $cNombre    = trim($_POST["cNombre"]);
            $cCategoria = trim($_POST["cCategoria"]);
            $eUnidad    = trim($_POST["eUnidad"]);
            $nStockMinimo  = trim($_POST["nStockMinimo"]);

            $insumo = new Insumo($id, $cNombre, $cCategoria, $eUnidad, $nStockMinimo);

            $rta = $this->modelo_insumo->update($insumo);

            if ($rta) {
                $_SESSION['msg']  = "Insumo actualizado correctamente";
                $_SESSION['tipo'] = "success";
            } else {
                $_SESSION['msg']  = "Error al actualizar el insumo";
                $_SESSION['tipo'] = "danger";
            }
            header("Location:" . BASE_URL . "insumo/inicio");
        } else {
            echo "Todos los campos son obligatorios para actualizar";
        }
    }
}