<?php
require_once __DIR__ . '/../Config/config.php';
try {
    $dsn = 'mysql:host=' . DB_SERVIDOR . ';dbname=' . DB_NOMBRE . ';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, DB_CLAVE, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $stmt = $pdo->query('SELECT nInsumoID, cInsumo, nTotalComprado, nTotalUtilizado, nTotalMermas, nTotalVencido FROM v_AnalisisMermas ORDER BY cInsumo');
    $mermas = $stmt->fetchAll();
} catch (PDOException $e) {
    die('Error de base de datos: ' . $e->getMessage());
}
$titulo = 'Análisis de mermas';
ob_start();
?>
<div class="row mb-4">
    <div class="col-md-9">
        <h1>Análisis de mermas</h1>
        <p class="text-muted">Reporte de compras, uso, mermas y vencimientos por insumo.</p>
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
                            <th>Total comprado</th>
                            <th>Total utilizado</th>
                            <th>Total mermas</th>
                            <th>Total vencido</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($mermas)): ?>
                            <tr>
                                <td colspan="6" class="text-center">No hay datos de análisis de mermas.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($mermas as $fila): ?>
                                <tr>
                                    <td><?php echo $fila['nInsumoID']; ?></td>
                                    <td><?php echo htmlspecialchars($fila['cInsumo']); ?></td>
                                    <td><?php echo number_format($fila['nTotalComprado'], 2); ?></td>
                                    <td><?php echo number_format($fila['nTotalUtilizado'], 2); ?></td>
                                    <td><?php echo number_format($fila['nTotalMermas'], 2); ?></td>
                                    <td><?php echo number_format($fila['nTotalVencido'], 2); ?></td>
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
