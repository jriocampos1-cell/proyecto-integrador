<?php
$role = $_SESSION['user']['rol'] ?? null;
$userId = $_SESSION['user']['id'] ?? 0;
$titulo = (in_array($role, ['cocinero', 'pastelero'])) ? 'Mis Solicitudes — ' . APP_NAME : 'Gestión de Solicitudes — ' . APP_NAME;
$accion_actual = 'solicitudes';
$solicitudes = $solicitudes ?? [];
ob_start();
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="mb-1">
            <?php if (in_array($role, ['cocinero', 'pastelero'])): ?>
                <i class="ti ti-clipboard-list"></i> Mis Solicitudes
            <?php else: ?>
                <i class="ti ti-clipboard-check"></i> Gestión de Solicitudes
            <?php endif; ?>
        </h1>
        <p class="text-muted">
            <?php if (in_array($role, ['cocinero', 'pastelero'])): ?>
                Historial de tus solicitudes de materia prima.
            <?php else: ?>
                Revisa, aprueba o rechaza las solicitudes del personal.
            <?php endif; ?>
        </p>
    </div>
    <div class="col-md-4 text-end">
        <?php if (in_array($role, ['cocinero', 'pastelero'])): ?>
            <a href="<?php echo BASE_URL; ?>solicitud/nuevo" class="btn btn-lg btn-warning">
                <i class="ti ti-plus"></i> Nueva solicitud
            </a>
        <?php endif; ?>
    </div>
</div>

<?php if (!empty($_SESSION['msg'])): ?>
<div class="alert alert-<?php echo $_SESSION['tipo'] ?? 'info'; ?> alert-dismissible fade show">
    <?php echo $_SESSION['msg']; unset($_SESSION['msg']); unset($_SESSION['tipo']); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Filtros para gerente/bodega -->
<?php if (in_array($role, ['gerente', 'bodega'])): ?>
<div class="row mb-3">
    <div class="col-md-4">
        <select class="form-select" id="filtro-estado" onchange="filtrarTabla()">
            <option value="">Todos los estados</option>
            <option value="pendiente">Pendiente</option>
            <option value="aprobada">Aprobada</option>
            <option value="rechazada">Rechazada</option>
        </select>
    </div>
    <div class="col-md-5">
        <input type="text" class="form-control" id="filtro-texto" placeholder="Buscar por empleado..." oninput="filtrarTabla()">
    </div>
</div>
<?php endif; ?>

<div class="card shadow-sm" style="overflow:hidden">
    <div class="table-responsive">
        <table class="table table-striped table-hover mb-0" id="tabla-solicitudes">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <?php if (in_array($role, ['gerente', 'bodega'])): ?>
                        <th>Empleado</th>
                    <?php endif; ?>
                    <th>Estado</th>
                    <th>Motivo</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($solicitudes)): ?>
                    <tr>
                        <td colspan="<?php echo in_array($role, ['gerente', 'bodega']) ? '6' : '5'; ?>" class="text-center text-muted py-4">
                            <?php if (in_array($role, ['cocinero', 'pastelero'])): ?>
                                Aún no has creado solicitudes. <a href="<?php echo BASE_URL; ?>solicitud/nuevo" class="btn btn-sm btn-primary ms-2">Crear primera solicitud</a>
                            <?php else: ?>
                                No hay solicitudes registradas.
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($solicitudes as $s): 
                        $colorE = $s['eEstado'] == 'aprobada' ? 'success' : 
                                  ($s['eEstado'] == 'rechazada' ? 'danger' : 'warning');
                    ?>
                    <tr data-estado="<?php echo $s['eEstado']; ?>" data-usuario="<?php echo htmlspecialchars($s['cUsuario'] ?? ''); ?>">
                        <td><strong><?php echo $s['nSolicitudID']; ?></strong></td>
                        <?php if (in_array($role, ['gerente', 'bodega'])): ?>
                            <td><?php echo htmlspecialchars($s['cUsuario']); ?></td>
                        <?php endif; ?>
                        <td><span class="badge bg-<?php echo $colorE; ?>"><?php echo htmlspecialchars($s['eEstado']); ?></span></td>
                        <td><?php echo htmlspecialchars($s['cMotivoRechazo'] ?? '—'); ?></td>
                        <td><?php echo htmlspecialchars($s['dFecha']); ?></td>
                        <td>
                            <a href="<?php echo BASE_URL; ?>solicitud/detalle/<?php echo $s['nSolicitudID']; ?>" class="btn btn-sm btn-outline-primary">
                                <i class="ti ti-eye"></i> Ver
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function filtrarTabla() {
    const estado = document.getElementById('filtro-estado')?.value?.toLowerCase() || '';
    const texto = document.getElementById('filtro-texto')?.value?.toLowerCase() || '';
    document.querySelectorAll('#tabla-solicitudes tbody tr').forEach(row => {
        const rowEstado = row.dataset.estado || '';
        const rowText = row.dataset.usuario || '';
        const matchE = !estado || rowEstado === estado;
        const matchT = !texto || rowText.includes(texto);
        row.style.display = (matchE && matchT) ? '' : 'none';
    });
}
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../Public/plantilla.html';