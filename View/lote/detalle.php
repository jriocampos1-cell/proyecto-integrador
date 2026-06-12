<?php
$titulo = 'Detalle de lote';
ob_start();
?>
<div class="row mb-4">
    <div class="col-md-12">
        <h1>Detalle de lote</h1>
        <p class="text-muted">Información completa del lote seleccionado.</p>
    </div>
</div>
<?php if (!$lote): ?>
    <div class="alert alert-warning">Lote no encontrado.</div>
    <a class="btn btn-secondary" href="<?php echo BASE_URL; ?>/View/lotes.php">Volver</a>
<?php else: ?>
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr><th>ID</th><td><?php echo $lote['nLoteID']; ?></td></tr>
                        <tr><th>Insumo</th><td><?php echo htmlspecialchars($lote['cInsumo']); ?></td></tr>
                        <tr><th>Código lote</th><td><?php echo htmlspecialchars($lote['cCodigoLote']); ?></td></tr>
                        <tr><th>Cantidad actual</th><td><?php echo number_format($lote['nCantidadActual'], 2); ?></td></tr>
                        <tr><th>Ingreso</th><td><?php echo htmlspecialchars($lote['dFechaIngreso']); ?></td></tr>
                        <tr><th>Vencimiento</th><td><?php echo htmlspecialchars($lote['dVencimiento']); ?></td></tr>
                    </table>
                    <a class="btn btn-secondary" href="<?php echo BASE_URL; ?>/View/lotes.php">Volver a lotes</a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../Public/plantilla.html';
