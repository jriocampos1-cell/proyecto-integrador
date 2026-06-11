<?php
$titulo = 'Nuevo proveedor';
ob_start();
?>
<div class="row mb-4">
    <div class="col-md-12">
        <h1>Nuevo proveedor</h1>
        <p class="text-muted">Aquí puedes crear un nuevo proveedor. Agrega tu formulario PHP si quieres guardar datos.</p>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <p>Este es un ejemplo de vista para registrar un proveedor. Sustituye por tu formulario PHP si quieres guardar datos.</p>
                <a class="btn btn-secondary" href="<?php echo BASE_URL; ?>/View/proveedores.php">Volver al listado</a>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../Public/plantilla.html';
