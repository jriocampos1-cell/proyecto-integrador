$titulo = 'Solicitudes';
ob_start();
?>
<div class="row mb-4">
    <div class="col-md-9">
        <h1>Solicitudes</h1>
        <p class="text-muted">Solicitudes realizadas por usuarios.</p>
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
                            <th>Usuario</th>
                            <th>Estado</th>
                            <th>Motivo rechazo</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($solicitudes)): ?>
                            <tr>
                                <td colspan="6" class="text-center">No hay solicitudes registradas.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($solicitudes as $solicitud): ?>
                                <tr>
                                    <td><?php echo $solicitud['nSolicitudID']; ?></td>
                                    <td><?php echo htmlspecialchars($solicitud['cUsuario'] ?? 'Sin usuario'); ?></td>
                                    <td><?php echo htmlspecialchars($solicitud['eEstado']); ?></td>
                                    <td><?php echo htmlspecialchars($solicitud['cMotivoRechazo'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($solicitud['dFecha']); ?></td>
                                    <td>
                                        <a class="btn btn-sm btn-info" href="<?php echo BASE_URL; ?>/View/solicitud_detalle.php?id=<?php echo $solicitud['nSolicitudID']; ?>">Detalle</a>
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
