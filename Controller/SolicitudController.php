<?php
require_once __DIR__ . '/../Entities/Solicitud.php';
require_once __DIR__ . '/../Model/SolicitudModel.php';
require_once __DIR__ . '/../Model/DetalleSolicitudModel.php';
require_once __DIR__ . '/../Model/InsumoModel.php';

class SolicitudController {

    private $modelo_solicitud;

    public function __construct() {
        $this->modelo_solicitud = new SolicitudModel();
    }

    // GET
    public function inicio() {
        $role = $_SESSION['user']['rol'] ?? null;
        $userId = $_SESSION['user']['id'] ?? 0;

        if (in_array($role, ['cocinero', 'pastelero'])) {
            // Ver solo sus solicitudes
            $solicitudes = $this->modelo_solicitud->findByUsuarioView($userId);
        } else {
            // Gerente y bodega ven todas
            $solicitudes = $this->modelo_solicitud->findAllView();
        }
        require_once __DIR__ . '/../View/solicitud/lista.php';
    }

    public function nuevo() {
        // Solo cocinero o pastelero pueden crear solicitudes
        $role = $_SESSION['user']['rol'] ?? null;
        if (!in_array($role, ['cocinero', 'pastelero'])) {
            $_SESSION['msg'] = 'No tienes permisos para solicitar materias primas';
            $_SESSION['tipo'] = 'danger';
            header('Location: ' . BASE_URL . 'dashboard/inicio');
            exit();
        }
        $insumoModel = new InsumoModel();
        $insumos = $insumoModel->findAllView();
        require_once __DIR__ . '/../View/solicitud/nuevo.php';
    }

    public function detalle($id) {
        $solicitud = $this->modelo_solicitud->findByIdView($id);
        $detalleModel = new DetalleSolicitudModel();
        $detalles = $detalleModel->findBySolicitudIdView($id);
        $insumoModel = new InsumoModel();
        $insumos = $insumoModel->findAllView();
        require_once __DIR__ . '/../View/solicitud/detalle.php';
    }

    // POST: crea solicitud con múltiples insumos
    public function createCompleta() {
        $role = $_SESSION['user']['rol'] ?? null;
        if (!in_array($role, ['cocinero', 'pastelero'])) {
            $_SESSION['msg'] = 'No tienes permisos para crear solicitudes';
            $_SESSION['tipo'] = 'danger';
            header('Location:' . BASE_URL . 'dashboard/inicio');
            exit();
        }

        $nUsuarioID = $_POST['nUsuarioID'] ?? null;
        $insumo_ids = $_POST['insumo_id'] ?? [];
        $cantidades = $_POST['cantidad'] ?? [];
        $unidades = $_POST['unidad'] ?? [];

        if (!$nUsuarioID || empty($insumo_ids) || empty($cantidades)) {
            $_SESSION['msg'] = 'Debe seleccionar al menos un insumo con cantidad.';
            $_SESSION['tipo'] = 'danger';
            header('Location: ' . BASE_URL . 'solicitud/nuevo');
            exit();
        }

        // Crear la solicitud
        $solicitud = new Solicitud(null, $nUsuarioID, 'pendiente', null, null);
        $solicitudID = $this->modelo_solicitud->create($solicitud);

        if ($solicitudID) {
            $detalleModel = new DetalleSolicitudModel();
            $errores = 0;
            for ($i = 0; $i < count($insumo_ids); $i++) {
                if (!empty($insumo_ids[$i]) && !empty($cantidades[$i]) && $cantidades[$i] > 0) {
                    $r = $detalleModel->createSimple($solicitudID, $insumo_ids[$i], $cantidades[$i]);
                    if (!$r) $errores++;
                }
            }
            if ($errores == 0) {
                $_SESSION['msg'] = 'Solicitud #' . $solicitudID . ' creada correctamente con ' . count($insumo_ids) . ' insumo(s).';
                $_SESSION['tipo'] = 'success';
            } else {
                $_SESSION['msg'] = 'Solicitud creada pero algunos detalles fallaron.';
                $_SESSION['tipo'] = 'warning';
            }
        } else {
            $_SESSION['msg'] = 'Error al crear la solicitud';
            $_SESSION['tipo'] = 'danger';
        }
        header('Location: ' . BASE_URL . 'solicitud/inicio');
        exit();
    }

    // POST: crear solicitud simple (compatibilidad)
    public function createSolicitud() {
        if (
            isset($_POST["nUsuarioID"]) && !empty(trim($_POST["nUsuarioID"])) &&
            isset($_POST["eEstado"])     && !empty(trim($_POST["eEstado"]))
        ) {
            $role = $_SESSION['user']['rol'] ?? null;
            if (!in_array($role, ['cocinero', 'pastelero'])) {
                $_SESSION['msg'] = 'No tienes permisos para crear solicitudes';
                $_SESSION['tipo'] = 'danger';
                header('Location:' . BASE_URL . 'dashboard/inicio');
                exit();
            }
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
            // Solo gerente, bodega o administrador pueden aprobar
            $role = $_SESSION['user']['rol'] ?? null;
            if (!in_array($role, ['gerente', 'bodega'])) {
                $_SESSION['msg'] = 'No tienes permisos para aprobar solicitudes';
                $_SESSION['tipo'] = 'danger';
                header('Location:' . BASE_URL . 'dashboard/inicio');
                exit();
            }
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

    // POST: rechazar solicitud
    public function rechazarSolicitud() {
        if (
            isset($_POST["nSolicitudID"]) && !empty(trim($_POST["nSolicitudID"])) &&
            isset($_POST["cMotivoRechazo"]) && !empty(trim($_POST["cMotivoRechazo"]))
        ) {
            $role = $_SESSION['user']['rol'] ?? null;
            if (!in_array($role, ['gerente', 'bodega'])) {
                $_SESSION['msg'] = 'No tienes permisos para rechazar solicitudes';
                $_SESSION['tipo'] = 'danger';
                header('Location:' . BASE_URL . 'dashboard/inicio');
                exit();
            }
            $nSolicitudID = trim($_POST["nSolicitudID"]);
            $cMotivo = trim($_POST["cMotivoRechazo"]);

            $rta = $this->modelo_solicitud->rechazar($nSolicitudID, $cMotivo);

            if ($rta) {
                $_SESSION['msg'] = 'Solicitud #' . $nSolicitudID . ' rechazada.';
                $_SESSION['tipo'] = 'warning';
            } else {
                $_SESSION['msg'] = 'Error al rechazar la solicitud';
                $_SESSION['tipo'] = 'danger';
            }
            header('Location: ' . BASE_URL . 'solicitud/inicio');
            exit();
        } else {
            echo "Solicitud y motivo son obligatorios para rechazar";
        }
    }
}
