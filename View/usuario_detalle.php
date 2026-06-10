<?php
require_once __DIR__ . '/../Config/config.php';

try {
    $dsn = 'mysql:host=' . DB_SERVIDOR . ';dbname=' . DB_NOMBRE . ';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, DB_CLAVE, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $stmt = $pdo->prepare('SELECT nUsuarioID, cNombre, cNombreUsuario, eRol, eEstado, cCorreo FROM TUsuarios WHERE nUsuarioID = :id');
    $stmt->execute([':id' => $id]);
    $usuario = $stmt->fetch();
} catch (PDOException $e) {
    die('Error de base de datos: ' . $e->getMessage());
}

$titulo = 'Detalle de usuario';
ob_start();
?>
<div class="row mb-4">
    <div class="col-md-12">
        <h1>Detalle de usuario</h1>
        <p class="text-muted">Información detallada del usuario seleccionado.</p>
    </div>
</div>
<?php if (!$usuario): ?>
    <div class="alert alert-warning">Usuario no encontrado.</div>
    <a class="btn btn-secondary" href="<?php echo BASE_URL; ?>/View/usuarios.php">Volver al listado</a>
<?php else: ?>
    <div class="row">
        <div class="col-md-8">
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
                    <a class="btn btn-secondary" href="<?php echo BASE_URL; ?>/View/usuarios.php">Volver al listado</a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../Public/plantilla.html';
