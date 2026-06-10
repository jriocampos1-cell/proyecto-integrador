<?php
require_once __DIR__ . '/../Config/config.php';
try {
    $dsn = 'mysql:host=' . DB_SERVIDOR . ';dbname=' . DB_NOMBRE . ';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, DB_CLAVE, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $sql = 'SELECT l.nLoteID, i.cNombre AS cInsumo, l.cCodigoLote, l.nCantidadActual, l.dFechaIngreso, l.dVencimiento
            FROM TLotes l
            JOIN TInsumos i ON l.nInsumoID = i.nInsumoID
            ORDER BY l.dVencimiento ASC';
    $stmt = $pdo->query($sql);
    $lotes = $stmt->fetchAll();
} catch (PDOException $e) {
    die('Error de base de datos: ' . $e->getMessage());
}
$titulo = 'Inventario de lotes';
ob_start();
?>
<div class="row mb-4">
    <div class="col-md-9">
        <h1>Lotes</h1>
        <p class="text-muted">Listado de lotes con información de fecha y cantidad.</p>
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
                            <th>Código de lote</th>
                            <th>Cantidad</th>
                            <th>Ingreso</th>
                            <th>Vencimiento</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($lotes)): ?>
                            <tr>
                                <td colspan="7" class="text-center">No hay lotes registrados.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($lotes as $lote): ?>
                                <tr>
                                    <td><?php echo $lote['nLoteID']; ?></td>
                                    <td><?php echo htmlspecialchars($lote['cInsumo']); ?></td>
                                    <td><?php echo htmlspecialchars($lote['cCodigoLote']); ?></td>
                                    <td><?php echo number_format($lote['nCantidadActual'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($lote['dFechaIngreso']); ?></td>
                                    <td><?php echo htmlspecialchars($lote['dVencimiento']); ?></td>
                                    <td>
                                        <a class="btn btn-sm btn-info" href="<?php echo BASE_URL; ?>/View/lote_detalle.php?id=<?php echo $lote['nLoteID']; ?>">Detalles</a>
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
