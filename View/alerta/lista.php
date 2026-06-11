$titulo = 'Alertas';
ob_start();
?>
<div class="row mb-4">
    <div class="col-md-9">
        <h1>Alertas</h1>
        <p class="text-muted">Alertas activas y atendidas del sistema.</p>
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
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($alertas)): ?>
                            <tr>
                                <td colspan="8" class="text-center">No hay alertas registradas.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($alertas as $alerta): ?>
                                <tr>
                                    <td><?php echo $alerta['nAlertaID']; ?></td>
                                    <td><?php echo htmlspecialchars($alerta['cInsumo'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($alerta['cCodigoLote'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($alerta['eTipo']); ?></td>
                                    <td><?php echo htmlspecialchars($alerta['cMensaje']); ?></td>
                                    <td><?php echo htmlspecialchars($alerta['eEstado']); ?></td>
                                    <td><?php echo htmlspecialchars($alerta['dFecha']); ?></td>
                                    <td>
                                        <a class="btn btn-sm btn-info" href="<?php echo BASE_URL; ?>/View/alerta_detalle.php?id=<?php echo $alerta['nAlertaID']; ?>">Detalles</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../Public/plantilla.html';
