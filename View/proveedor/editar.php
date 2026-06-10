<?php
$titulo = 'Editar proveedor';
ob_start();
?>
<div class="row mb-4">
    <div class="col-md-12">
        <h1>Editar proveedor</h1>
        <p class="text-muted">Aquí puedes modificar los datos del proveedor.</p>
    </div>
</div>
<?php if (!$proveedor): ?>
    <div class="alert alert-warning">Proveedor no encontrado.</div>
    <a class="btn btn-secondary" href="<?php echo BASE_URL; ?>/View/proveedores.php">Volver al listado</a>
<?php else: ?>
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr><th>ID</th><td><?php echo $proveedor['nProveedorID']; ?></td></tr>
                        <tr><th>Nombre</th><td><?php echo htmlspecialchars($proveedor['cNombre']); ?></td></tr>
                        <tr><th>Teléfono</th><td><?php echo htmlspecialchars($proveedor['cTelefono']); ?></td></tr>
                        <tr><th>Correo</th><td><?php echo htmlspecialchars($proveedor['cCorreo']); ?></td></tr>
                        <tr><th>Estado</th><td><?php echo htmlspecialchars($proveedor['eEstado']); ?></td></tr>
                    </table>
                    <p class="text-muted">Sustituye este contenido por un formulario de edición real si lo necesitas.</p>
                    <a class="btn btn-secondary" href="<?php echo BASE_URL; ?>/View/proveedores.php">Volver al listado</a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../Public/plantilla.html';
