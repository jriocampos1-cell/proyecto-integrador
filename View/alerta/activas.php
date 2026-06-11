<?php
$titulo = 'Alertas activas';
ob_start();
?>
<div class="row mb-4">
    <div class="col-md-12">
        <h1>Alertas activas</h1>
        <p class="text-muted">Listado de alertas que todavía están activas.</p>
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
                            <th>Insumo</th>
                            <th>Lote</th>
                            <th>Tipo</th>
                            <th>Mensaje</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($lista_alertas)): ?>
                            <tr>
                                <td colspan="6" class="text-center">No hay alertas activas.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($lista_alertas as $alerta): ?>
                                <tr>
                                    <td><?php echo $alerta['nAlertaID']; ?></td>
                                    <td><?php echo htmlspecialchars($alerta['cInsumo'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($alerta['cCodigoLote'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($alerta['eTipo']); ?></td>
                                    <td><?php echo htmlspecialchars($alerta['cMensaje']); ?></td>
                                    <td><?php echo htmlspecialchars($alerta['dFecha']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                <a class="btn btn-secondary" href="<?php echo BASE_URL; ?>/View/alertas.php">Volver a todas las alertas</a>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../Public/plantilla.html';
