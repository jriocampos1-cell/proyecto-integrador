<?php
$titulo = 'Bodega — ' . APP_NAME;
$accion_actual = 'inicio';
$rol = $_SESSION['user']['rol'] ?? 'bodega';
$insumos = $insumos ?? [];
$semaforo = $semaforo ?? [];
$fefo = $fefo ?? [];
$alertas_activas = $alertas_activas ?? [];
$solicitudes = $solicitudes ?? [];
$pendientes = $pendientes ?? [];
$top_semaforo = $top_semaforo ?? [];
$top_vencimiento = $top_vencimiento ?? [];
$total_insumos = $total_insumos ?? 0;
$total_alertas = $total_alertas ?? 0;
$total_pendientes = $total_pendientes ?? 0;
$total_lotes = $total_lotes ?? 0;
ob_start();
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="mb-1">📦 Panel de Bodega</h1>
        <p class="text-muted">Gestiona el inventario, recibe solicitudes y registra entradas/salidas.</p>
    </div>
    <div class="col-md-4 text-end">
        <a href="<?php echo BASE_URL; ?>solicitud/inicio" class="btn btn-lg btn-warning">
            <i class="ti ti-clipboard-check"></i> Ver solicitudes 
            <?php if ($total_pendientes > 0): ?>
                <span class="badge bg-danger ms-1"><?php echo $total_pendientes; ?></span>
            <?php endif; ?>
        </a>
    </div>
</div>

<!-- KPIs -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm p-3 text-center" style="border-left: 4px solid #27AE60;">
            <h5 class="text-success"><i class="ti ti-package"></i> Insumos</h5>
            <p class="display-6 text-success mb-0"><?php echo $total_insumos; ?></p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm p-3 text-center" style="border-left: 4px solid #F4B400;">
            <h5 class="text-warning"><i class="ti ti-clipboard-check"></i> Pendientes</h5>
            <p class="display-6 text-warning mb-0"><?php echo $total_pendientes; ?></p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm p-3 text-center" style="border-left: 4px solid #E74C3C;">
            <h5 class="text-danger"><i class="ti ti-bell"></i> Alertas</h5>
            <p class="display-6 text-danger mb-0"><?php echo $total_alertas; ?></p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm p-3 text-center" style="border-left: 4px solid #3498DB;">
            <h5 class="text-info"><i class="ti ti-box"></i> Lotes FEFO</h5>
            <p class="display-6 text-info mb-0"><?php echo $total_lotes; ?></p>
        </div>
    </div>
</div>

<!-- Semáforo + Vencimientos -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title"><i class="ti ti-traffic-lights"></i> Stock Crítico</h5>
                <?php if (empty($top_semaforo)): ?>
                    <p class="text-muted">No hay datos de semáforo.</p>
                <?php else: ?>
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr><th>Insumo</th><th>Stock</th><th>Mínimo</th><th>Estado</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($top_semaforo as $fila): 
                                $color = strpos($fila['eEstadoSemaforo'], 'Rojo') !== false ? 'danger' : 
                                         (strpos($fila['eEstadoSemaforo'], 'Amarillo') !== false ? 'warning' : 'success');
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($fila['cInsumo'] ?? '-'); ?></td>
                                <td><?php echo number_format($fila['nStockActual'] ?? 0, 2); ?></td>
                                <td><?php echo number_format($fila['nStockMinimo'] ?? 0, 2); ?></td>
                                <td><span class="badge bg-<?php echo $color; ?>"><?php echo htmlspecialchars($fila['eEstadoSemaforo'] ?? '-'); ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title"><i class="ti ti-calendar-x"></i> Próximos Vencimientos</h5>
                <?php if (empty($top_vencimiento)): ?>
                    <p class="text-muted">No hay lotes próximos a vencer.</p>
                <?php else: ?>
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr><th>Insumo</th><th>Lote</th><th>Cantidad</th><th>Vence</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($top_vencimiento as $fila): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($fila['cInsumo'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($fila['cCodigoLote'] ?? '-'); ?></td>
                                <td><?php echo number_format($fila['nCantidadActual'] ?? 0, 2); ?></td>
                                <td><?php echo htmlspecialchars($fila['dVencimiento'] ?? '-'); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Solicitudes pendientes -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><i class="ti ti-clipboard-check"></i> Solicitudes Pendientes (<?php echo count($pendientes); ?>)</h5>
                <a href="<?php echo BASE_URL; ?>solicitud/inicio" class="btn btn-primary btn-sm">Ver todas</a>
            </div>
            <div class="card-body pt-0">
                <?php if (empty($pendientes)): ?>
                    <div class="alert alert-success"><i class="ti ti-check"></i> No hay solicitudes pendientes.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr><th>#</th><th>Solicitante</th><th>Fecha</th><th>Acciones</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pendientes as $p): ?>
                                <tr>
                                    <td><?php echo $p['nSolicitudID']; ?></td>
                                    <td><?php echo htmlspecialchars($p['cUsuario']); ?></td>
                                    <td><?php echo htmlspecialchars($p['dFecha']); ?></td>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>solicitud/detalle/<?php echo $p['nSolicitudID']; ?>" class="btn btn-sm btn-primary">
                                            <i class="ti ti-eye"></i> Revisar
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Alertas activas -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title"><i class="ti ti-bell-ringing"></i> Alertas Activas</h5>
                <?php if (empty($alertas_activas)): ?>
                    <div class="alert alert-success">No hay alertas activas. Todo en orden.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr><th>Mensaje</th><th>Fecha</th><th>Estado</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($alertas_activas as $a): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($a['cMensaje']); ?></td>
                                    <td><?php echo htmlspecialchars($a['dFecha']); ?></td>
                                    <td><span class="badge bg-<?php echo $a['eEstado'] == 'activa' ? 'danger' : 'success'; ?>"><?php echo $a['eEstado']; ?></span></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../Public/plantilla.html';