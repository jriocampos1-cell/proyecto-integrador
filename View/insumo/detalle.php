<?php
$titulo = 'Detalle de insumo';
ob_start();
?>
<div class="row mb-4">
    <div class="col-md-12">
        <h1>Detalle de insumo</h1>
        <p class="text-muted">Información completa del insumo seleccionado.</p>
    </div>
</div>
<?php if (!$insumo): ?>
    <div class="alert alert-warning">Insumo no encontrado.</div>
    <a class="btn btn-secondary" href="<?php echo BASE_URL; ?>/View/insumos.php">Volver</a>
<?php else: ?>
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr><th>ID</th><td><?php echo $insumo['nInsumoID']; ?></td></tr>
                        <tr><th>Nombre</th><td><?php echo htmlspecialchars($insumo['cNombre']); ?></td></tr>
                        <tr><th>Categoría</th><td><?php echo htmlspecialchars($insumo['cCategoria']); ?></td></tr>
                        <tr><th>Unidad</th><td><?php echo htmlspecialchars($insumo['eUnidadMedida']); ?></td></tr>
                        <tr><th>Stock actual</th><td><?php echo number_format($insumo['nStockActual'], 2); ?></td></tr>
                        <tr><th>Stock mínimo</th><td><?php echo number_format($insumo['nStockMinimo'], 2); ?></td></tr>
                    </table>
                    <a class="btn btn-secondary" href="<?php echo BASE_URL; ?>/View/insumos.php">Volver a insumos</a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../Public/plantilla.html';
