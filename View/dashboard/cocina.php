<?php
$titulo = 'Cocina — ' . APP_NAME;
$accion_actual = 'inicio';
$rol_label = ($rol == 'pastelero') ? 'Pastelero' : 'Cocinero';
$userId = $_SESSION['user']['id'] ?? 0;
$semaforo = $semaforo ?? [];
$mis_solicitudes = $mis_solicitudes ?? [];
$insumos = $insumos ?? [];
$criticos = $criticos ?? [];
$amarillos = $amarillos ?? [];
ob_start();
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="mb-1">👨‍🍳 Panel de <?php echo $rol_label; ?></h1>
        <p class="text-muted">Solicita materias primas según las necesidades de producción.</p>
    </div>
    <div class="col-md-4 text-end">
        <a href="<?php echo BASE_URL; ?>solicitud/nuevo" class="btn btn-lg btn-warning">
            <i class="ti ti-plus"></i> Nueva solicitud
        </a>
    </div>
</div>

<!-- Resumen rápido -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm p-3 text-center" style="border-left: 4px solid #E74C3C;">
            <h5 class="text-danger"><i class="ti ti-alert-circle"></i> Críticos</h5>
            <p class="display-6 text-danger mb-0"><?php echo count($criticos); ?></p>
            <small class="text-muted">Insumos bajo stock mínimo</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm p-3 text-center" style="border-left: 4px solid #F4B400;">
            <h5 class="text-warning"><i class="ti ti-clock"></i> Atención</h5>
            <p class="display-6 text-warning mb-0"><?php echo count($amarillos); ?></p>
            <small class="text-muted">Próximos a agotarse</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm p-3 text-center" style="border-left: 4px solid #3498DB;">
            <h5 class="text-info"><i class="ti ti-clipboard-list"></i> Mis solicitudes</h5>
            <p class="display-6 text-info mb-0"><?php echo count($mis_solicitudes); ?></p>
            <small class="text-muted">Registradas recientemente</small>
        </div>
    </div>
</div>

<!-- Semáforo de Stock -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title"><i class="ti ti-traffic-lights"></i> Estado de Insumos (Semáforo)</h5>
                <p class="text-muted">Disponibilidad actual de materias primas en bodega.</p>
                <?php if (empty($semaforo)): ?>
                    <div class="alert alert-info">No hay insumos registrados todavía.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Insumo</th>
                                    <th>Categoría</th>
                                    <th>Stock Actual</th>
                                    <th>Stock Mínimo</th>
                                    <th>Unidad</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($semaforo as $item): 
                                    $color = strpos($item['eEstadoSemaforo'], 'Rojo') !== false ? 'danger' : 
                                             (strpos($item['eEstadoSemaforo'], 'Amarillo') !== false ? 'warning' : 'success');
                                ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($item['cInsumo']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($item['cCategoria']); ?></td>
                                    <td><?php echo number_format($item['nStockActual'], 2); ?></td>
                                    <td><?php echo number_format($item['nStockMinimo'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($item['eUnidadMedida']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $color; ?>">
                                            <?php echo htmlspecialchars($item['eEstadoSemaforo']); ?>
                                        </span>
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

<!-- Mis solicitudes recientes -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><i class="ti ti-clipboard-list"></i> Mis Solicitudes Recientes</h5>
                <a href="<?php echo BASE_URL; ?>solicitud/nuevo" class="btn btn-primary btn-sm">
                    <i class="ti ti-plus"></i> Nueva solicitud
                </a>
            </div>
            <div class="card-body pt-0">
                <?php if (empty($mis_solicitudes)): ?>
                    <div class="alert alert-info">Aún no has creado ninguna solicitud. ¡Pide tus insumos ahora!</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Detalles</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                    <th>Motivo rechazo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($mis_solicitudes as $s): 
                                    $colorE = $s['eEstado'] == 'aprobada' ? 'success' : 
                                              ($s['eEstado'] == 'rechazada' ? 'danger' : 'warning');
                                ?>
                                <tr>
                                    <td><?php echo $s['nSolicitudID']; ?></td>
                                    <td><?php echo htmlspecialchars($s['cDetalles'] ?? 'Sin detalles'); ?></td>
                                    <td><span class="badge bg-<?php echo $colorE; ?>"><?php echo htmlspecialchars($s['eEstado']); ?></span></td>
                                    <td><?php echo htmlspecialchars($s['dFecha']); ?></td>
                                    <td><?php echo htmlspecialchars($s['cMotivoRechazo'] ?? '—'); ?></td>
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