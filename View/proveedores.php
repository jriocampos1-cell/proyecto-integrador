<?php
require_once __DIR__ . '/../Config/config.php';
try {
    $dsn = 'mysql:host=' . DB_SERVIDOR . ';dbname=' . DB_NOMBRE . ';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, DB_CLAVE, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $stmt = $pdo->query('SELECT nProveedorID, cNombre, cTelefono, cCorreo, eEstado FROM TProveedores ORDER BY cNombre');
    $proveedores = $stmt->fetchAll();
} catch (PDOException $e) {
    die('Error de base de datos: ' . $e->getMessage());
}
$titulo = 'Lista de proveedores';
ob_start();
?>
<div class="row mb-4">
    <div class="col-md-9">
        <h1>Proveedores</h1>
        <p class="text-muted">Lista de proveedores registrados.</p>
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
                            <th>Nombre</th>
                            <th>Teléfono</th>
                            <th>Correo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($proveedores)): ?>
                            <tr>
                                <td colspan="6" class="text-center">No hay proveedores registrados.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($proveedores as $proveedor): ?>
                                <tr>
                                    <td><?php echo $proveedor['nProveedorID']; ?></td>
                                    <td><?php echo htmlspecialchars($proveedor['cNombre']); ?></td>
                                    <td><?php echo htmlspecialchars($proveedor['cTelefono']); ?></td>
                                    <td><?php echo htmlspecialchars($proveedor['cCorreo']); ?></td>
                                    <td><?php echo htmlspecialchars($proveedor['eEstado']); ?></td>
                                    <td>
                                        <a class="btn btn-sm btn-info" href="<?php echo BASE_URL; ?>/View/proveedor_detalle.php?id=<?php echo $proveedor['nProveedorID']; ?>">Detalles</a>
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
require_once __DIR__ . '/../Public/plantilla.html';
