<?php
$titulo = 'Dashboard';
$usuarios = $usuarios ?? [];
$insumos = $insumos ?? [];
$proveedores = $proveedores ?? [];
$lotes = $lotes ?? [];
$movimientos = $movimientos ?? [];
$solicitudes = $solicitudes ?? [];
$alertas_activas = $alertas_activas ?? [];
$top_semaforo = $top_semaforo ?? [];
$top_vencimiento = $top_vencimiento ?? [];
$top_mermas = $top_mermas ?? [];
ob_start();
?>
<div class="row mb-4">
    <div class="col-md-9">
        <h1>Dashboard</h1>
        <p class="text-muted">Resumen general del inventario y alertas en el sistema.</p>
    </div>
</div>
<div class="row gy-3">
    <div class="col-md-4">
        <div class="card shadow-sm p-3">
            <h5>Usuarios</h5>
            <p class="display-6 mb-0"><?php echo count($usuarios); ?></p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm p-3">
            <h5>Insumos</h5>
            <p class="display-6 mb-0"><?php echo count($insumos); ?></p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm p-3">
            <h5>Proveedores</h5>
            <p class="display-6 mb-0"><?php echo count($proveedores); ?></p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm p-3">
            <h5>Lotes</h5>
            <p class="display-6 mb-0"><?php echo count($lotes); ?></p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm p-3">
            <h5>Movimientos</h5>
            <p class="display-6 mb-0"><?php echo count($movimientos); ?></p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm p-3">
            <h5>Solicitudes</h5>
            <p class="display-6 mb-0"><?php echo count($solicitudes); ?></p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm p-3">
            <h5>Alertas activas</h5>
            <p class="display-6 mb-0"><?php echo count($alertas_activas); ?></p>
        </div>
    </div>
</div>
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Stock crítico y semáforo</h5>
                <p class="text-muted">Primeros insumos con estado de semáforo registrado.</p>
                <table class="table table-sm table-striped mt-3">
                    <thead>
                        <tr>
                            <th>Insumo</th>
                            <th>Stock</th>
                            <th>Mínimo</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($top_semaforo)): ?>
                            <tr><td colspan="4" class="text-center">No hay datos de semáforo disponibles.</td></tr>
                        <?php else: ?>
                            <?php foreach ($top_semaforo as $fila): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($fila['cInsumo'] ?? '-'); ?></td>
                                    <td><?php echo number_format($fila['nStockActual'] ?? 0, 2); ?></td>
                                    <td><?php echo number_format($fila['nStockMinimo'] ?? 0, 2); ?></td>
                                    <td><?php echo htmlspecialchars($fila['eEstadoSemaforo'] ?? '-'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Próximo vencimiento (FEFO)</h5>
                <p class="text-muted">Lotes ordenados por fecha de vencimiento.</p>
                <table class="table table-sm table-striped mt-3">
                    <thead>
                        <tr>
                            <th>Insumo</th>
                            <th>Lote</th>
                            <th>Cantidad</th>
                            <th>Vence</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($top_vencimiento)): ?>
                            <tr><td colspan="4" class="text-center">No hay datos FEFO disponibles.</td></tr>
                        <?php else: ?>
                            <?php foreach ($top_vencimiento as $fila): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($fila['cInsumo'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($fila['cCodigoLote'] ?? '-'); ?></td>
                                    <td><?php echo number_format($fila['nCantidadActual'] ?? 0, 2); ?></td>
                                    <td><?php echo htmlspecialchars($fila['dVencimiento'] ?? '-'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Análisis de mermas</h5>
                <p class="text-muted">Resumen de mermas por insumo.</p>
                <table class="table table-sm table-striped mt-3">
                    <thead>
                        <tr>
                            <th>Insumo</th>
                            <th>Comprado</th>
                            <th>Utilizado</th>
                            <th>Mermas</th>
                            <th>Vencido</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($top_mermas)): ?>
                            <tr><td colspan="5" class="text-center">No hay datos de mermas disponibles.</td></tr>
                        <?php else: ?>
                            <?php foreach ($top_mermas as $fila): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($fila['cInsumo'] ?? '-'); ?></td>
                                    <td><?php echo number_format($fila['nTotalComprado'] ?? 0, 2); ?></td>
                                    <td><?php echo number_format($fila['nTotalUtilizado'] ?? 0, 2); ?></td>
                                    <td><?php echo number_format($fila['nTotalMermas'] ?? 0, 2); ?></td>
                                    <td><?php echo number_format($fila['nTotalVencido'] ?? 0, 2); ?></td>
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
