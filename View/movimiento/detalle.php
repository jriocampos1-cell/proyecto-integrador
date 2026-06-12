<?php
$titulo = 'Detalle de movimiento';
ob_start();
?>
<div class="row mb-4">
    <div class="col-md-12">
        <h1>Detalle de movimiento</h1>
        <p class="text-muted">Información completa del movimiento seleccionado.</p>
    </div>
</div>
<?php if (!$movimiento): ?>
    <div class="alert alert-warning">Movimiento no encontrado.</div>
    <a class="btn btn-secondary" href="<?php echo BASE_URL; ?>/View/movimientos.php">Volver</a>
<?php else: ?>
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr><th>ID</th><td><?php echo $movimiento['nMovimientoID']; ?></td></tr>
                        <tr><th>Insumo</th><td><?php echo htmlspecialchars($movimiento['cInsumo'] ?? ''); ?></td></tr>
                        <tr><th>Tipo</th><td><?php echo htmlspecialchars($movimiento['eTipo']); ?></td></tr>
                        <tr><th>Motivo</th><td><?php echo htmlspecialchars($movimiento['eMotivoSalida'] ?? '-'); ?></td></tr>
                        <tr><th>Cantidad</th><td><?php echo number_format($movimiento['nCantidad'], 2); ?></td></tr>
                        <tr><th>Usuario</th><td><?php echo htmlspecialchars($movimiento['cUsuario'] ?? ''); ?></td></tr>
                        <tr><th>Proveedor</th><td><?php echo htmlspecialchars($movimiento['cProveedor'] ?? '-'); ?></td></tr>
                        <tr><th>Solicitud</th><td><?php echo htmlspecialchars($movimiento['eSolicitud'] ?? '-'); ?></td></tr>
                        <tr><th>Fecha</th><td><?php echo htmlspecialchars($movimiento['dFecha']); ?></td></tr>
                    </table>
                    <a class="btn btn-secondary" href="<?php echo BASE_URL; ?>/View/movimientos.php">Volver a movimientos</a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../Public/plantilla.html';
