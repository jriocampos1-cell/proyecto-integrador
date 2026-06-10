$titulo = 'Detalle de alerta';
ob_start();
?>
<div class="row mb-4">
    <div class="col-md-12">
        <h1>Detalle de alerta</h1>
        <p class="text-muted">Información completa de la alerta seleccionada.</p>
    </div>
</div>
<?php if (!$alerta): ?>
    <div class="alert alert-warning">Alerta no encontrada.</div>
    <a class="btn btn-secondary" href="<?php echo BASE_URL; ?>/View/alertas.php">Volver</a>
<?php else: ?>
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr><th>ID</th><td><?php echo $alerta['nAlertaID']; ?></td></tr>
                        <tr><th>Insumo</th><td><?php echo htmlspecialchars($alerta['cInsumo'] ?? '-'); ?></td></tr>
                        <tr><th>Lote</th><td><?php echo htmlspecialchars($alerta['cCodigoLote'] ?? '-'); ?></td></tr>
                        <tr><th>Tipo</th><td><?php echo htmlspecialchars($alerta['eTipo']); ?></td></tr>
                        <tr><th>Mensaje</th><td><?php echo htmlspecialchars($alerta['cMensaje']); ?></td></tr>
                        <tr><th>Estado</th><td><?php echo htmlspecialchars($alerta['eEstado']); ?></td></tr>
                        <tr><th>Fecha</th><td><?php echo htmlspecialchars($alerta['dFecha']); ?></td></tr>
                    </table>
                    <a class="btn btn-secondary" href="<?php echo BASE_URL; ?>/View/alertas.php">Volver a alertas</a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../Public/plantilla.html';
