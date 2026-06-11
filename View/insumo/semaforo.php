$titulo = 'Semáforo de stock';
ob_start();
?>
<div class="row mb-4">
    <div class="col-md-9">
        <h1>Semáforo de stock</h1>
        <p class="text-muted">Vista de estado de stock con colores semafóricos.</p>
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
                            <th>Categoría</th>
                            <th>Stock actual</th>
                            <th>Stock mínimo</th>
                            <th>Unidad</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($semaforo)): ?>
                            <tr>
                                <td colspan="7" class="text-center">No hay datos de semáforo disponibles.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($semaforo as $fila): ?>
                                <tr>
                                    <td><?php echo $fila['nInsumoID']; ?></td>
                                    <td><?php echo htmlspecialchars($fila['cInsumo']); ?></td>
                                    <td><?php echo htmlspecialchars($fila['cCategoria']); ?></td>
                                    <td><?php echo number_format($fila['nStockActual'], 2); ?></td>
                                    <td><?php echo number_format($fila['nStockMinimo'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($fila['eUnidadMedida']); ?></td>
                                    <td><?php echo htmlspecialchars($fila['eEstadoSemaforo']); ?></td>
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
