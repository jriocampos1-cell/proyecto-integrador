<?php
$titulo = 'Gerencia — ' . APP_NAME;
$accion_actual = 'inicio';
$rol = $_SESSION['user']['rol'] ?? 'gerente';
$usuarios = $usuarios ?? [];
$insumos = $insumos ?? [];
$proveedores = $proveedores ?? [];
$lotes = $lotes ?? [];
$movimientos = $movimientos ?? [];
$solicitudes = $solicitudes ?? [];
$alertas_activas = $alertas_activas ?? [];
$pendientes = $pendientes ?? [];
$semaforo = $semaforo ?? [];
$fefo = $fefo ?? [];
$mermas = $mermas ?? [];
$top_semaforo = $top_semaforo ?? [];
$top_vencimiento = $top_vencimiento ?? [];
$top_mermas = $top_mermas ?? [];
$total_usuarios = $total_usuarios ?? 0;
$total_insumos = $total_insumos ?? 0;
$total_proveedores = $total_proveedores ?? 0;
$total_alertas = $total_alertas ?? 0;
$total_pendientes = $total_pendientes ?? 0;
ob_start();
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="mb-1">🏢 Panel de Gerencia</h1>
        <p class="text-muted">Control total del sistema: empleados, inventario, solicitudes y proveedores.</p>
    </div>
    <div class="col-md-4 text-end">
        <a href="<?php echo BASE_URL; ?>solicitud/inicio" class="btn btn-lg btn-warning">
            <i class="ti ti-clipboard-check"></i> Solicitudes 
            <?php if ($total_pendientes > 0): ?>
                <span class="badge bg-danger ms-1"><?php echo $total_pendientes; ?></span>
            <?php endif; ?>
        </a>
    </div>
</div>

<!-- KPIs generales -->
<div class="row mb-4">
    <div class="col-md-2">
        <div class="card shadow-sm p-3 text-center" style="border-left: 4px solid #8B5E3C;">
            <h6 class="text-dark"><i class="ti ti-users"></i> Usuarios</h6>
            <p class="display-6 mb-0" style="font-size:1.6rem;"><?php echo $total_usuarios; ?></p>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card shadow-sm p-3 text-center" style="border-left: 4px solid #27AE60;">
            <h6 class="text-success"><i class="ti ti-package"></i> Insumos</h6>
            <p class="display-6 mb-0" style="font-size:1.6rem;"><?php echo $total_insumos; ?></p>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card shadow-sm p-3 text-center" style="border-left: 4px solid #3498DB;">
            <h6 class="text-info"><i class="ti ti-truck"></i> Proveedores</h6>
            <p class="display-6 mb-0" style="font-size:1.6rem;"><?php echo $total_proveedores; ?></p>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card shadow-sm p-3 text-center" style="border-left: 4px solid #F4B400;">
            <h6 class="text-warning"><i class="ti ti-clipboard-check"></i> Pendientes</h6>
            <p class="display-6 mb-0" style="font-size:1.6rem;"><?php echo $total_pendientes; ?></p>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card shadow-sm p-3 text-center" style="border-left: 4px solid #E74C3C;">
            <h6 class="text-danger"><i class="ti ti-bell"></i> Alertas</h6>
            <p class="display-6 mb-0" style="font-size:1.6rem;"><?php echo $total_alertas; ?></p>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card shadow-sm p-3 text-center" style="border-left: 4px solid #E91E63;">
            <h6 class="text-muted"><i class="ti ti-box"></i> Lotes</h6>
            <p class="display-6 mb-0" style="font-size:1.6rem;"><?php echo count($lotes); ?></p>
        </div>
    </div>
</div>

<!-- Stock crítico + Pendientes -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title"><i class="ti ti-traffic-lights"></i> Semáforo de Stock</h5>
                <?php if (empty($top_semaforo)): ?>
                    <p class="text-muted">No hay datos disponibles.</p>
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
                <h5 class="card-title"><i class="ti ti-clipboard-check"></i> Solicitudes Pendientes (<?php echo count($pendientes); ?>)</h5>
                <?php if (empty($pendientes)): ?>
                    <div class="alert alert-success">No hay solicitudes pendientes.</div>
                <?php else: ?>
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
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Listado de usuarios (solo gerente) -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><i class="ti ti-users"></i> Empleados del Sistema</h5>
                <a href="<?php echo BASE_URL; ?>usuario/inicio" class="btn btn-outline-primary btn-sm">Gestionar usuarios</a>
            </div>
            <div class="card-body pt-0">
                <?php if (empty($usuarios)): ?>
                    <p class="text-muted">No hay usuarios registrados.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr><th>#</th><th>Nombre</th><th>Usuario</th><th>Rol</th><th>Correo</th><th>Estado</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($usuarios as $u): ?>
                                <tr>
                                    <td><?php echo $u['nUsuarioID']; ?></td>
                                    <td><strong><?php echo htmlspecialchars($u['cNombre']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($u['cNombreUsuario']); ?></td>
                                    <td>
                                        <span class="badge role-badge role-<?php echo $u['eRol']; ?>">
                                            <?php echo htmlspecialchars($u['eRol']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($u['cCorreo']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $u['eEstado'] == 'activo' ? 'success' : 'secondary'; ?>">
                                            <?php echo $u['eEstado']; ?>
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

<!-- FEFO + Mermas -->
<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title"><i class="ti ti-calendar-x"></i> Próximos Vencimientos (FEFO)</h5>
                <?php if (empty($top_vencimiento)): ?>
                    <p class="text-muted">No hay datos FEFO disponibles.</p>
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
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title"><i class="ti ti-trash"></i> Análisis de Mermas</h5>
                <?php if (empty($top_mermas)): ?>
                    <p class="text-muted">No hay datos de mermas disponibles.</p>
                <?php else: ?>
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr><th>Insumo</th><th>Comprado</th><th>Utilizado</th><th>Merma</th><th>Vencido</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($top_mermas as $fila): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($fila['cInsumo'] ?? '-'); ?></td>
                                <td><?php echo number_format($fila['nTotalComprado'] ?? 0, 2); ?></td>
                                <td><?php echo number_format($fila['nTotalUtilizado'] ?? 0, 2); ?></td>
                                <td><?php echo number_format($fila['nTotalMermas'] ?? 0, 2); ?></td>
                                <td><?php echo number_format($fila['nTotalVencido'] ?? 0, 2); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../Public/plantilla.html';