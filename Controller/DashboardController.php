<?php
require_once __DIR__ . '/../Model/UsuarioModel.php';
require_once __DIR__ . '/../Model/InsumoModel.php';
require_once __DIR__ . '/../Model/ProveedorModel.php';
require_once __DIR__ . '/../Model/LoteModel.php';
require_once __DIR__ . '/../Model/MovimientoModel.php';
require_once __DIR__ . '/../Model/SolicitudModel.php';
require_once __DIR__ . '/../Model/AlertaModel.php';
require_once __DIR__ . '/../Model/DetalleSolicitudModel.php';

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

    // Dashboard genérico - respaldo
    public function inicio() {
        $rol = $_SESSION['user']['rol'] ?? null;
        if ($rol == 'gerente') {
            $this->gerencia();
            return;
        } elseif ($rol == 'bodega') {
            $this->bodega();
            return;
        } elseif (in_array($rol, ['cocinero', 'pastelero'])) {
            $this->cocina();
            return;
        }
        // fallback: dashboard genérico
        $this->dashboardGenerico();
    }

    private function dashboardGenerico() {
        $usuarios = $this->modelo_usuario->findAllView();
        $insumos = $this->modelo_insumo->findAllView();
        $proveedores = $this->modelo_proveedor->findAllView();
        $lotes = $this->modelo_lote->findAllView();
        $movimientos = $this->modelo_movimiento->findAllView();
        $solicitudes = $this->modelo_solicitud->findAllView();
        $alertas = $this->modelo_alerta->findAllView();
        $alertas_activas = $this->modelo_alerta->findActivasView();
        $semaforo = $this->modelo_insumo->SemaforoStock();
        $fefo = $this->modelo_insumo->InventarioFEFO();
        $mermas = $this->modelo_insumo->AnalisisMermas();
        $top_semaforo = array_slice($semaforo, 0, 5);
        $top_vencimiento = array_slice($fefo, 0, 5);
        $top_mermas = array_slice($mermas, 0, 5);
        require_once __DIR__ . '/../View/dashboard/index.php';
    }

    // Dashboard GERENTE: ve TODO
    public function gerencia() {
        $usuarios = $this->modelo_usuario->findAllView();
        $insumos = $this->modelo_insumo->findAllView();
        $proveedores = $this->modelo_proveedor->findAllView();
        $lotes = $this->modelo_lote->findAllView();
        $movimientos = $this->modelo_movimiento->findAllView();
        $solicitudes = $this->modelo_solicitud->findAllView();
        $alertas_activas = $this->modelo_alerta->findActivasView();
        $semaforo = $this->modelo_insumo->SemaforoStock();
        $fefo = $this->modelo_insumo->InventarioFEFO();
        $mermas = $this->modelo_insumo->AnalisisMermas();
        $pendientes = $this->modelo_solicitud->findPendientes();

        $total_usuarios = count($usuarios);
        $total_insumos = count($insumos);
        $total_proveedores = count($proveedores);
        $total_alertas = count($alertas_activas);
        $total_pendientes = count($pendientes);

        $top_semaforo = array_slice($semaforo, 0, 5);
        $top_vencimiento = array_slice($fefo, 0, 5);
        $top_mermas = array_slice($mermas, 0, 5);

        require_once __DIR__ . '/../View/dashboard/gerencia.php';
    }

    // Dashboard BODEGA: inventario, alertas, solicitudes pendientes
    public function bodega() {
        $insumos = $this->modelo_insumo->findAllView();
        $semaforo = $this->modelo_insumo->SemaforoStock();
        $fefo = $this->modelo_insumo->InventarioFEFO();
        $alertas_activas = $this->modelo_alerta->findActivasView();
        $solicitudes = $this->modelo_solicitud->findAllView();
        $pendientes = $this->modelo_solicitud->findPendientes();

        $total_insumos = count($insumos);
        $total_alertas = count($alertas_activas);
        $total_pendientes = count($pendientes);
        $total_lotes = count($fefo);

        $top_semaforo = array_slice($semaforo, 0, 5);
        $top_vencimiento = array_slice($fefo, 0, 5);

        require_once __DIR__ . '/../View/dashboard/bodega.php';
    }

    // Dashboard COCINA/PASTELERO: semáforo, mis solicitudes y crear nuevas
    public function cocina() {
        $userId = $_SESSION['user']['id'] ?? 0;
        $rol = $_SESSION['user']['rol'] ?? '';
        $semaforo = $this->modelo_insumo->SemaforoStock();
        $mis_solicitudes = $this->modelo_solicitud->findByUsuarioView($userId);
        $insumos = $this->modelo_insumo->findAllView();

        // Estados del semáforo para resumen rápido
        $criticos = array_filter($semaforo, function($i) {
            return strpos($i['eEstadoSemaforo'], 'Rojo') !== false;
        });
        $amarillos = array_filter($semaforo, function($i) {
            return strpos($i['eEstadoSemaforo'], 'Amarillo') !== false;
        });

        require_once __DIR__ . '/../View/dashboard/cocina.php';
    }
}