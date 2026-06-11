<?php
require_once __DIR__ . '/../Model/UsuarioModel.php';
require_once __DIR__ . '/../Model/InsumoModel.php';
require_once __DIR__ . '/../Model/ProveedorModel.php';
require_once __DIR__ . '/../Model/LoteModel.php';
require_once __DIR__ . '/../Model/MovimientoModel.php';
require_once __DIR__ . '/../Model/SolicitudModel.php';
require_once __DIR__ . '/../Model/AlertaModel.php';

class DashboardController {

    private $modelo_usuario;
    private $modelo_insumo;
    private $modelo_proveedor;
    private $modelo_lote;
    private $modelo_movimiento;
    private $modelo_solicitud;
    private $modelo_alerta;

    public function __construct() {
        $this->modelo_usuario = new UsuarioModel();
        $this->modelo_insumo = new InsumoModel();
        $this->modelo_proveedor = new ProveedorModel();
        $this->modelo_lote = new LoteModel();
        $this->modelo_movimiento = new MovimientoModel();
        $this->modelo_solicitud = new SolicitudModel();
        $this->modelo_alerta = new AlertaModel();
    }

    public function inicio() {
        $usuarios = $this->modelo_usuario->findAllView();
        $insumos = $this->modelo_insumo->findAllView();
        $proveedores = $this->modelo_proveedor->findAllView();
        $lotes = $this->modelo_lote->findAllView();
        $movimientos = $this->modelo_movimiento->findAllView();
        $solicitudes = $this->modelo_solicitud->findAllView();
        $alertas = $this->modelo_alerta->findAllView();
        $alertas_activas = $this->modelo_alerta->findActivas();
        $semaforo = $this->modelo_insumo->SemaforoStock();
        $fefo = $this->modelo_insumo->InventarioFEFO();
        $mermas = $this->modelo_insumo->AnalisisMermas();

        $top_semaforo = array_slice($semaforo, 0, 5);
        $top_vencimiento = array_slice($fefo, 0, 5);
        $top_mermas = array_slice($mermas, 0, 5);

        require_once __DIR__ . '/../View/dashboard/index.php';
    }
}
