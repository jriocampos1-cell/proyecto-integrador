<?php
$titulo = 'Detalle de solicitud';
ob_start();
?>
<div class="row mb-4">
    <div class="col-md-12">
        <h1>Detalle de solicitud</h1>
        <p class="text-muted">Lista de detalles por solicitud.</p>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>ID Solicitud</th>
                            <th>Insumo</th>
                            <th>Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($lista_detalles)): ?>
                            <tr>
                                <td colspan="4" class="text-center">No hay detalles registrados.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($lista_detalles as $detalle): ?>
                                <tr>
                                    <td><?php echo $detalle['nDetalleSolicitudID']; ?></td>
                                    <td><?php echo $detalle['nSolicitudID']; ?></td>
                                    <td><?php echo $detalle['nInsumoID']; ?></td>
                                    <td><?php echo $detalle['nCantidad']; ?></td>
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
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../Public/plantilla.html';
