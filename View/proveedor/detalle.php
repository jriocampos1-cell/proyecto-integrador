$titulo = 'Detalle de proveedor';
ob_start();
?>
<div class="row mb-4">
    <div class="col-md-12">
        <h1>Detalle de proveedor</h1>
        <p class="text-muted">Información completa del proveedor seleccionado.</p>
    </div>
</div>
<?php if (!$proveedor): ?>
    <div class="alert alert-warning">Proveedor no encontrado.</div>
    <a class="btn btn-secondary" href="<?php echo BASE_URL; ?>/View/proveedores.php">Volver</a>
<?php else: ?>
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr><th>ID</th><td><?php echo $proveedor['nProveedorID']; ?></td></tr>
                        <tr><th>Nombre</th><td><?php echo htmlspecialchars($proveedor['cNombre']); ?></td></tr>
                        <tr><th>Teléfono</th><td><?php echo htmlspecialchars($proveedor['cTelefono']); ?></td></tr>
                        <tr><th>Correo</th><td><?php echo htmlspecialchars($proveedor['cCorreo']); ?></td></tr>
                        <tr><th>Estado</th><td><?php echo htmlspecialchars($proveedor['eEstado']); ?></td></tr>
                    </table>
                    <a class="btn btn-secondary" href="<?php echo BASE_URL; ?>/View/proveedores.php">Volver a proveedores</a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../Public/plantilla.html';
