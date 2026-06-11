$titulo = 'Detalle de solicitud';
ob_start();
?>
<div class="row mb-4">
    <div class="col-md-12">
        <h1>Detalle de solicitud</h1>
        <p class="text-muted">Información y detalle de la solicitud seleccionada.</p>
    </div>
</div>
<?php if (!$solicitud): ?>
    <div class="alert alert-warning">Solicitud no encontrada.</div>
    <a class="btn btn-secondary" href="<?php echo BASE_URL; ?>/View/solicitudes.php">Volver</a>
<?php else: ?>
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Solicitud #<?php echo $solicitud['nSolicitudID']; ?></h5>
                    <table class="table table-borderless">
                        <tr><th>Usuario</th><td><?php echo htmlspecialchars($solicitud['cUsuario'] ?? 'Sin usuario'); ?></td></tr>
                        <tr><th>Estado</th><td><?php echo htmlspecialchars($solicitud['eEstado']); ?></td></tr>
                        <tr><th>Motivo rechazo</th><td><?php echo htmlspecialchars($solicitud['cMotivoRechazo'] ?? '-'); ?></td></tr>
                        <tr><th>Fecha</th><td><?php echo htmlspecialchars($solicitud['dFecha']); ?></td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Detalle de insumos solicitados</h5>
                    <table class="table table-striped table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Insumo</th>
                                <th>Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($detalles)): ?>
                                <tr>
                                    <td colspan="3" class="text-center">No hay detalles registrados.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($detalles as $detalle): ?>
                                    <tr>
                                        <td><?php echo $detalle['nDetalleSolicitudID']; ?></td>
                                        <td><?php echo htmlspecialchars($detalle['cInsumo']); ?></td>
                                        <td><?php echo number_format($detalle['nCantidad'], 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <a class="btn btn-secondary" href="<?php echo BASE_URL; ?>/View/solicitudes.php">Volver a solicitudes</a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../Public/plantilla.html';
