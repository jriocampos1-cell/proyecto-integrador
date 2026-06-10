<?php
require_once __DIR__ . '/../Config/config.php';
try {
    $dsn = 'mysql:host=' . DB_SERVIDOR . ';dbname=' . DB_NOMBRE . ';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, DB_CLAVE, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $sql = 'SELECT m.nMovimientoID, i.cNombre AS cInsumo, m.eTipo, m.eMotivoSalida, m.nCantidad, u.cNombre AS cUsuario, p.cNombre AS cProveedor, s.eEstado AS eEstadoSolicitud, m.dFecha
            FROM TMovimientos m
            LEFT JOIN TInsumos i ON m.nInsumoID = i.nInsumoID
            LEFT JOIN TUsuarios u ON m.nUsuarioID = u.nUsuarioID
            LEFT JOIN TProveedores p ON m.nProveedorID = p.nProveedorID
            LEFT JOIN TSolicitud s ON m.nSolicitudID = s.nSolicitudID
            ORDER BY m.dFecha DESC';
    $stmt = $pdo->query($sql);
    $movimientos = $stmt->fetchAll();
} catch (PDOException $e) {
    die('Error de base de datos: ' . $e->getMessage());
}
$titulo = 'Registro de movimientos';
ob_start();
?>
<div class="row mb-4">
    <div class="col-md-9">
        <h1>Movimientos</h1>
        <p class="text-muted">Historial de entradas y salidas de inventario.</p>
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
                            <th>Tipo</th>
                            <th>Motivo</th>
                            <th>Cantidad</th>
                            <th>Usuario</th>
                            <th>Proveedor</th>
                            <th>Solicitud</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($movimientos)): ?>
                            <tr>
                                <td colspan="10" class="text-center">No hay movimientos registrados.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($movimientos as $movimiento): ?>
                                <tr>
                                    <td><?php echo $movimiento['nMovimientoID']; ?></td>
                                    <td><?php echo htmlspecialchars($movimiento['cInsumo']); ?></td>
                                    <td><?php echo htmlspecialchars($movimiento['eTipo']); ?></td>
                                    <td><?php echo htmlspecialchars($movimiento['eMotivoSalida']); ?></td>
                                    <td><?php echo number_format($movimiento['nCantidad'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($movimiento['cUsuario']); ?></td>
                                    <td><?php echo htmlspecialchars($movimiento['cProveedor'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($movimiento['eEstadoSolicitud'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($movimiento['dFecha']); ?></td>
                                    <td>
                                        <a class="btn btn-sm btn-info" href="<?php echo BASE_URL; ?>/View/movimiento_detalle.php?id=<?php echo $movimiento['nMovimientoID']; ?>">Detalles</a>
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
