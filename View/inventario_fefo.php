<?php
require_once __DIR__ . '/../Config/config.php';
try {
    $dsn = 'mysql:host=' . DB_SERVIDOR . ';dbname=' . DB_NOMBRE . ';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, DB_CLAVE, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $stmt = $pdo->query('SELECT nLoteID, cInsumo, cCodigoLote, nCantidadActual, eUnidadMedida, dVencimiento, nDiasParaVencer FROM v_InventarioFEFO');
    $inventario = $stmt->fetchAll();
} catch (PDOException $e) {
    die('Error de base de datos: ' . $e->getMessage());
}
$titulo = 'Inventario FEFO';
ob_start();
?>
<div class="row mb-4">
    <div class="col-md-9">
        <h1>Inventario FEFO</h1>
        <p class="text-muted">Lista FEFO de lotes en orden de vencimiento.</p>
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
                            <th>Código lote</th>
                            <th>Cantidad</th>
                            <th>Unidad</th>
                            <th>Vencimiento</th>
                            <th>Días para vencer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($inventario)): ?>
                            <tr>
                                <td colspan="7" class="text-center">No hay lotes FEFO disponibles.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($inventario as $fila): ?>
                                <tr>
                                    <td><?php echo $fila['nLoteID']; ?></td>
                                    <td><?php echo htmlspecialchars($fila['cInsumo']); ?></td>
                                    <td><?php echo htmlspecialchars($fila['cCodigoLote']); ?></td>
                                    <td><?php echo number_format($fila['nCantidadActual'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($fila['eUnidadMedida']); ?></td>
                                    <td><?php echo htmlspecialchars($fila['dVencimiento']); ?></td>
                                    <td><?php echo htmlspecialchars($fila['nDiasParaVencer']); ?></td>
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
