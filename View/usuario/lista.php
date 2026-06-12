<?php
$titulo = 'Lista de usuarios';
ob_start();
?>
<div class="row mb-4">
    <div class="col-md-9">
        <h1>Lista de usuarios</h1>
        <p class="text-muted">Listado de empleados registrados en el sistema.</p>
    </div>
    <div class="col-md-3 text-end align-self-center">
        <?php if (($_SESSION['user']['rol'] ?? '') === 'gerente'): ?>
        <a class="btn btn-warning fw-bold" href="<?php echo BASE_URL; ?>usuario/nuevo">
            <i class="ti ti-user-plus"></i> Nuevo usuario
        </a>
        <?php endif; ?>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Usuario</th>
                            <th scope="col">Rol</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Correo</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($usuarios)): ?>
                            <tr>
                                <td colspan="7" class="text-center">No hay usuarios registrados.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <th scope="row"><?php echo $usuario['nUsuarioID']; ?></th>
                                    <td><?php echo htmlspecialchars($usuario['cNombre']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['cNombreUsuario']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['eRol']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['eEstado']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['cCorreo']); ?></td>
                                    <td>
                                        <a class="btn btn-sm btn-info" href="<?php echo BASE_URL; ?>/View/usuario_detalle.php?id=<?php echo $usuario['nUsuarioID']; ?>">Detalles</a>
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
