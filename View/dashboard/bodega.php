<?php
$titulo = 'Bodega — ' . APP_NAME;
$accion_actual = 'inicio';
$rol = $_SESSION['user']['rol'] ?? 'bodega';
$insumos = $insumos ?? [];
$semaforo = $semaforo ?? [];
$fefo = $fefo ?? [];
$alertas_activas = $alertas_activas ?? [];
$solicitudes = $solicitudes ?? [];
$pendientes = $pendientes ?? [];
$top_semaforo = $top_semaforo ?? [];
$top_vencimiento = $top_vencimiento ?? [];
$total_insumos = $total_insumos ?? 0;
$total_alertas = $total_alertas ?? 0;
$total_pendientes = $total_pendientes ?? 0;
$total_lotes = $total_lotes ?? 0;
ob_start();
?>

<style>
.pp-bodega-header {
    background: linear-gradient(135deg, #1a5276, #2980b9);
    color: #fff;
    border-radius: 16px;
    padding: 28px 32px;
    margin-bottom: 24px;
}
.pp-bodega-header h1 { color: #fff; font-weight: 700; margin-bottom: 4px; }
.pp-bodega-header p { opacity: 0.85; margin: 0; }
.pp-stat-card {
    background: #fff;
    border-radius: 14px;
    padding: 20px 16px;
    text-align: center;
    border-left: 5px solid #2980b9;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    transition: transform 0.2s;
}
.pp-stat-card:hover { transform: translateY(-3px); }
.pp-stat-card .pp-stat-icon { font-size: 28px; margin-bottom: 6px; }
.pp-stat-card .pp-stat-value { font-size: 2rem; font-weight: 800; color: #1a5276; }
.pp-stat-card .pp-stat-label { font-size: 13px; color: #666; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
.pp-stat-card.pp-alert { border-left-color: #e74c3c; }
.pp-stat-card.pp-alert .pp-stat-value { color: #e74c3c; }
.pp-stat-card.pp-warning { border-left-color: #f39c12; }
.pp-stat-card.pp-warning .pp-stat-value { color: #f39c12; }
.pp-stat-card.pp-success { border-left-color: #27ae60; }
.pp-stat-card.pp-success .pp-stat-value { color: #27ae60; }
.pp-pendientes-banner {
    background: #fff3cd;
    border: 1px solid #ffc107;
    border-radius: 10px;
    padding: 12px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
}
.pp-vencidas-row {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 6px 0;
    border-bottom: 1px dashed #e8dccf;
}
.pp-vencidas-row:last-child { border-bottom: none; }
.table-bodega { font-size: 13px; }
.table-bodega thead { background: #1a5276; color: #fff; }
.table-bodega thead th { font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; }
</style>

<div class="pp-bodega-header">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h1><i class="ti ti-package"></i> Bodega — Gestión de Inventario</h1>
            <p>Control de stock, vencimientos y atención de solicitudes pendientes</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="<?php echo BASE_URL; ?>solicitud/inicio" class="btn btn-lg btn-light fw-bold">
                📋 Solicitudes 
                <?php if ($total_pendientes > 0): ?>
                    <span class="badge bg-danger ms-1"><?php echo $total_pendientes; ?> pendientes</span>
                <?php endif; ?>
            </a>
        </div>
    </div>
</div>

<!-- Tarjetas KPIs -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="pp-stat-card pp-success">
            <div class="pp-stat-icon"><i class="ti ti-package" style="font-size:28px"></i></div>
            <div class="pp-stat-value"><?php echo $total_insumos; ?></div>
            <div class="pp-stat-label">Insumos registrados</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="pp-stat-card pp-warning">
            <div class="pp-stat-icon"><i class="ti ti-clipboard-check" style="font-size:28px"></i></div>
            <div class="pp-stat-value"><?php echo $total_pendientes; ?></div>
            <div class="pp-stat-label">Solicitudes pendientes</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="pp-stat-card pp-alert">
            <div class="pp-stat-icon"><i class="ti ti-bell-ringing" style="font-size:28px"></i></div>
            <div class="pp-stat-value"><?php echo $total_alertas; ?></div>
            <div class="pp-stat-label">Alertas activas</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="pp-stat-card">
            <div class="pp-stat-icon"><i class="ti ti-box" style="font-size:28px"></i></div>
            <div class="pp-stat-value"><?php echo $total_lotes; ?></div>
            <div class="pp-stat-label">Lotes con stock</div>
        </div>
    </div>
</div>

<?php if ($total_pendientes > 0): ?>
<div class="pp-pendientes-banner">
    <span><strong>⚠️ Atención:</strong> Hay <strong><?php echo $total_pendientes; ?></strong> solicitud(es) de materia prima esperando tu revisión.</span>
    <a href="<?php echo BASE_URL; ?>solicitud/inicio" class="btn btn-warning btn-sm fw-bold">Revisar ahora →</a>
</div>
<?php endif; ?>

<!-- Semáforo + Vencimientos -->
<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white fw-bold d-flex align-items-center gap-2">
                🚦 Semáforo de Stock
            </div>
            <div class="card-body">
                <?php if (empty($top_semaforo)): ?>
                    <p class="text-muted text-center py-3">No hay datos de semáforo disponibles.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover table-bodega mb-0">
                            <thead>
                                <tr><th>Insumo</th><th>Stock</th><th>Mínimo</th><th>Estado</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($top_semaforo as $fila): 
                                    $esRojo = strpos($fila['eEstadoSemaforo'], 'Rojo') !== false;
                                    $esAmarillo = strpos($fila['eEstadoSemaforo'], 'Amarillo') !== false;
                                    $color = $esRojo ? 'danger' : ($esAmarillo ? 'warning' : 'success');
                                    $icono = $esRojo ? '🔴' : ($esAmarillo ? '🟡' : '🟢');
                                ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($fila['cInsumo'] ?? '-'); ?></strong></td>
                                    <td><?php echo number_format($fila['nStockActual'] ?? 0, 2); ?></td>
                                    <td><?php echo number_format($fila['nStockMinimo'] ?? 0, 2); ?></td>
                                    <td><span class="badge bg-<?php echo $color; ?>"><?php echo $icono; ?> <?php echo htmlspecialchars($fila['eEstadoSemaforo'] ?? '-'); ?></span></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white fw-bold d-flex align-items-center gap-2">
                Próximos Vencimientos
            </div>
            <div class="card-body">
                <?php if (empty($top_vencimiento)): ?>
                    <p class="text-muted text-center py-3">No hay lotes próximos a vencer. </p>
                <?php else: ?>
                    <?php foreach ($top_vencimiento as $fila): ?>
                    <div class="pp-vencidas-row">
                        <span style="font-size:20px">📦</span>
                        <div style="flex:1">
                            <strong><?php echo htmlspecialchars($fila['cInsumo'] ?? '-'); ?></strong>
                            <small class="text-muted"> — Lote <?php echo htmlspecialchars($fila['cCodigoLote'] ?? '-'); ?></small>
                        </div>
                        <span class="badge bg-secondary"><?php echo number_format($fila['nCantidadActual'] ?? 0, 2); ?></span>
                        <span class="text-danger fw-bold" style="font-size:13px">🗓 <?php echo htmlspecialchars($fila['dVencimiento'] ?? '-'); ?></span>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Alertas activas -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-bold d-flex align-items-center gap-2">
                🔔 Alertas Activas
            </div>
            <div class="card-body">
                <?php if (empty($alertas_activas)): ?>
                    <div class="alert alert-success mb-0"><i class="ti ti-check"></i> No hay alertas activas. Todo en orden.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover table-bodega mb-0">
                            <thead>
                                <tr><th>Mensaje</th><th>Tipo</th><th>Fecha</th><th>Estado</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($alertas_activas as $a): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($a['cMensaje']); ?></td>
                                    <td><?php echo htmlspecialchars($a['eTipo'] ?? '—'); ?></td>
                                    <td><?php echo htmlspecialchars($a['dFecha']); ?></td>
                                    <td><span class="badge bg-danger"><?php echo $a['eEstado']; ?></span></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../Public/plantilla.html';