<?php
require_once __DIR__ . '/../Config/config.php';
try {
    $dsn = 'mysql:host=' . DB_SERVIDOR . ';dbname=' . DB_NOMBRE . ';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, DB_CLAVE, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $stmt = $pdo->query('SELECT nInsumoID, cNombre, cCategoria, eUnidadMedida, nStockActual, nStockMinimo FROM TInsumos ORDER BY cCategoria, cNombre');
    $insumos = $stmt->fetchAll();
} catch (PDOException $e) {
    die('Error de base de datos: ' . $e->getMessage());
}
$titulo = 'Lista de insumos';
ob_start();
?>
<div class="row mb-4">
    <div class="col-md-9">
        <h1>Insumos</h1>
        <p class="text-muted">Listado completo de insumos y su stock actual.</p>
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
                            <th>Categoría</th>
                            <th>Unidad</th>
                            <th>Stock actual</th>
                            <th>Stock mínimo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($insumos)): ?>
                            <tr>
                                <td colspan="7" class="text-center">No hay insumos registrados.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($insumos as $insumo): ?>
                                <tr>
                                    <td><?php echo $insumo['nInsumoID']; ?></td>
                                    <td><?php echo htmlspecialchars($insumo['cNombre']); ?></td>
                                    <td><?php echo htmlspecialchars($insumo['cCategoria']); ?></td>
                                    <td><?php echo htmlspecialchars($insumo['eUnidadMedida']); ?></td>
                                    <td><?php echo number_format($insumo['nStockActual'], 2); ?></td>
                                    <td><?php echo number_format($insumo['nStockMinimo'], 2); ?></td>
                                    <td>
                                        <a class="btn btn-sm btn-info" href="<?php echo BASE_URL; ?>/View/insumo_detalle.php?id=<?php echo $insumo['nInsumoID']; ?>">Detalles</a>
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
