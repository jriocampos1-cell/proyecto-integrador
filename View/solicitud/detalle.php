<?php
$role = $_SESSION['user']['rol'] ?? null;
$userId = $_SESSION['user']['id'] ?? 0;
$titulo = 'Solicitud #' . ($solicitud['nSolicitudID'] ?? '?') . ' — ' . APP_NAME;
$accion_actual = 'solicitudes';
$solicitud = $solicitud ?? null;
$detalles = $detalles ?? [];
ob_start();
?>

<?php if (!$solicitud): ?>
    <div class="alert alert-danger">Solicitud no encontrada.</div>
    <?php $content = ob_get_clean(); require_once __DIR__ . '/../../Public/plantilla.html'; return; ?>
<?php endif; ?>

<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="mb-1"><i class="ti ti-file-invoice"></i> Solicitud #<?php echo $solicitud['nSolicitudID']; ?></h1>
        <p class="text-muted">
            <?php 
                $colorE = $solicitud['eEstado'] == 'aprobada' ? 'success' : 
                          ($solicitud['eEstado'] == 'rechazada' ? 'danger' : 'warning');
            ?>
            Estado: <span class="badge bg-<?php echo $colorE; ?> fs-6"><?php echo htmlspecialchars($solicitud['eEstado']); ?></span>
            | Solicitante: <strong><?php echo htmlspecialchars($solicitud['cUsuario']); ?></strong>
            | Fecha: <?php echo htmlspecialchars($solicitud['dFecha']); ?>
        </p>
    </div>
    <div class="col-md-4 text-end">
        <a href="<?php echo BASE_URL; ?>solicitud/inicio" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left"></i> Volver
        </a>
    </div>
</div>

<?php if (!empty($_SESSION['msg'])): ?>
<div class="alert alert-<?php echo $_SESSION['tipo'] ?? 'info'; ?> alert-dismissible fade show">
    <?php echo $_SESSION['msg']; unset($_SESSION['msg']); unset($_SESSION['tipo']); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="row">
    <div class="col-md-8">
        <!-- Detalles de la solicitud -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title"><i class="ti ti-list-details"></i> Insumos solicitados</h5>
                <?php if (empty($detalles)): ?>
                    <p class="text-muted">No hay detalles registrados para esta solicitud.</p>
                <?php else: ?>
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr>
                                <th>Insumo</th>
                                <th>Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($detalles as $d): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($d['cInsumo']); ?></strong></td>
                                <td><?php echo number_format($d['nCantidad'], 2); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>

        <!-- Motivo de rechazo si existe -->
        <?php if ($solicitud['eEstado'] == 'rechazada' && !empty($solicitud['cMotivoRechazo'])): ?>
        <div class="card shadow-sm mb-4 border-danger">
            <div class="card-body">
                <h5 class="card-title text-danger"><i class="ti ti-x"></i> Motivo de rechazo</h5>
                <p class="mb-0"><?php echo htmlspecialchars($solicitud['cMotivoRechazo']); ?></p>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="col-md-4">
        <!-- Acciones para gerente/bodega -->
        <?php if (in_array($role, ['gerente', 'bodega']) && $solicitud['eEstado'] == 'pendiente'): ?>
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h5 class="card-title"><i class="ti ti-tool"></i> Acciones</h5>
                
                <!-- Aprobar -->
                <form method="post" action="<?php echo BASE_URL; ?>solicitud/aprobarSolicitud" class="mb-3">
                    <input type="hidden" name="nSolicitudID" value="<?php echo $solicitud['nSolicitudID']; ?>">
                    <input type="hidden" name="nGerenteID" value="<?php echo $userId; ?>">
                    <button type="submit" class="btn btn-success w-100" onclick="return confirm('¿Está seguro de APROBAR esta solicitud? Se descontará del inventario usando FEFO.')">
                        <i class="ti ti-check"></i> Aprobar solicitud
                    </button>
                </form>

                <!-- Rechazar -->
                <form method="post" action="<?php echo BASE_URL; ?>solicitud/rechazarSolicitud">
                    <input type="hidden" name="nSolicitudID" value="<?php echo $solicitud['nSolicitudID']; ?>">
                    <div class="mb-2">
                        <label class="form-label">Motivo del rechazo *</label>
                        <textarea name="cMotivoRechazo" class="form-control" rows="2" required placeholder="Ej: Stock insuficiente, no hay proveedor disponible..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger w-100" onclick="return confirm('¿Está seguro de RECHAZAR esta solicitud?')">
                        <i class="ti ti-x"></i> Rechazar solicitud
                    </button>
                </form>
            </div>
        </div>
        <?php endif; ?>

        <!-- Información adicional -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="card-title"><i class="ti ti-info-circle"></i> Información</h6>
                <ul class="list-unstyled mb-0 small">
                    <li class="mb-1"><strong>ID Solicitud:</strong> #<?php echo $solicitud['nSolicitudID']; ?></li>
                    <li class="mb-1"><strong>Solicitante:</strong> <?php echo htmlspecialchars($solicitud['cUsuario']); ?></li>
                    <li class="mb-1"><strong>Fecha:</strong> <?php echo htmlspecialchars($solicitud['dFecha']); ?></li>
                    <li class="mb-1"><strong>Estado:</strong> <?php echo htmlspecialchars($solicitud['eEstado']); ?></li>
                    <?php if ($solicitud['eEstado'] == 'rechazada'): ?>
                        <li><strong>Motivo:</strong> <?php echo htmlspecialchars($solicitud['cMotivoRechazo'] ?? 'No especificado'); ?></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../Public/plantilla.html';