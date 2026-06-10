<?php
$titulo = 'Editar usuario';
$usuario = $usuario ?? null;
ob_start();
?>
<div class="row mb-4">
    <div class="col-md-12">
        <h1>Editar usuario</h1>
        <p class="text-muted">Aquí puedes modificar los datos del usuario.</p>
    </div>
</div>
<?php if (!$usuario): ?>
    <div class="alert alert-warning">Usuario no encontrado.</div>
    <a class="btn btn-secondary" href="<?php echo BASE_URL; ?>/View/usuarios.php">Volver al listado</a>
<?php else: ?>
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr><th>ID</th><td><?php echo $usuario['nUsuarioID']; ?></td></tr>
                        <tr><th>Nombre</th><td><?php echo htmlspecialchars($usuario['cNombre']); ?></td></tr>
                        <tr><th>Nombre de usuario</th><td><?php echo htmlspecialchars($usuario['cNombreUsuario']); ?></td></tr>
                        <tr><th>Rol</th><td><?php echo htmlspecialchars($usuario['eRol']); ?></td></tr>
                        <tr><th>Estado</th><td><?php echo htmlspecialchars($usuario['eEstado']); ?></td></tr>
                        <tr><th>Correo</th><td><?php echo htmlspecialchars($usuario['cCorreo']); ?></td></tr>
                    </table>
                    <p class="text-muted">Sustituye este contenido por un formulario de edición real si lo necesitas.</p>
                    <a class="btn btn-secondary" href="<?php echo BASE_URL; ?>/View/usuarios.php">Volver al listado</a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../Public/plantilla.html';
