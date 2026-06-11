<?php
$titulo = 'Nueva alerta';
ob_start();
?>
<div class="row mb-4">
    <div class="col-md-12">
        <h1>Nueva alerta</h1>
        <p class="text-muted">Aquí puedes crear una nueva alerta. Agrega tu formulario PHP si quieres guardar datos.</p>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <p>Este es un ejemplo de vista para crear una alerta en tu sistema. Sustituye por tu formulario PHP si quieres guardar datos.</p>
                <a class="btn btn-secondary" href="<?php echo BASE_URL; ?>/View/alertas.php">Volver al listado</a>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../Public/plantilla.html';
