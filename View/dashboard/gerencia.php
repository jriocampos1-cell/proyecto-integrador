<?php
$titulo = 'Gerencia — ' . APP_NAME;
$accion_actual = 'inicio';
$rol = $_SESSION['user']['rol'] ?? 'gerente';
$usuarios = $usuarios ?? [];
$insumos = $insumos ?? [];
$proveedores = $proveedores ?? [];
$lotes = $lotes ?? [];
$movimientos = $movimientos ?? [];
$solicitudes = $solicitudes ?? [];
$alertas_activas = $alertas_activas ?? [];
$pendientes = $pendientes ?? [];
$semaforo = $semaforo ?? [];
$fefo = $fefo ?? [];
$mermas = $mermas ?? [];
$top_semaforo = $top_semaforo ?? [];
$top_vencimiento = $top_vencimiento ?? [];
$top_mermas = $top_mermas ?? [];
$total_usuarios = $total_usuarios ?? 0;
$total_insumos = $total_insumos ?? 0;
$total_proveedores = $total_proveedores ?? 0;
$total_alertas = $total_alertas ?? 0;
$total_pendientes = $total_pendientes ?? 0;
ob_start();
?>

<style>
.pp-gerente-hero {
    background: linear-gradient(135deg, #2c3e50, #8B5E3C, #f39c12);
    color: #fff;
    border-radius: 20px;
    padding: 36px 40px;
    margin-bottom: 28px;
    position: relative;
    overflow: hidden;
}
.pp-gerente-hero::after {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 300px;
    height: 300px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
}
.pp-gerente-hero h1 { font-size: 2.2rem; font-weight: 800; margin-bottom: 4px; position: relative; z-index: 1; }
.pp-gerente-hero p { opacity: 0.9; margin: 0; position: relative; z-index: 1; font-size: 1.05rem; }

.pp-kpi-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 14px;
    margin-bottom: 28px;
}
@media (max-width: 1200px) { .pp-kpi-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 768px) { .pp-kpi-grid { grid-template-columns: repeat(2, 1fr); } }

.pp-kpi-box {
    background: #fff;
    border-radius: 14px;
    padding: 18px 14px;
    text-align: center;
    border-top: 4px solid #8B5E3C;
    box-shadow: 0 3px 14px rgba(0,0,0,0.07);
    transition: all 0.25s;
    cursor: default;
}
.pp-kpi-box:hover { transform: translateY(-4px); box-shadow: 0 8px 25px rgba(0,0,0,0.12); }
.pp-kpi-box .pp-kpi-icon { font-size: 32px; margin-bottom: 6px; }
.pp-kpi-box .pp-kpi-value { font-size: 2rem; font-weight: 800; color: #2c3e50; line-height: 1.1; }
.pp-kpi-box .pp-kpi-label { font-size: 12px; color: #888; font-weight: 600; text-transform: uppercase; letter-spacing: 0.7px; margin-top: 2px; }
.pp-kpi-box.pp-kpi-danger { border-top-color: #e74c3c; }
.pp-kpi-box.pp-kpi-danger .pp-kpi-value { color: #e74c3c; }
.pp-kpi-box.pp-kpi-warning { border-top-color: #f39c12; }
.pp-kpi-box.pp-kpi-warning .pp-kpi-value { color: #f39c12; }
.pp-kpi-box.pp-kpi-success { border-top-color: #27ae60; }
.pp-kpi-box.pp-kpi-success .pp-kpi-value { color: #27ae60; }
.pp-kpi-box.pp-kpi-info { border-top-color: #2980b9; }
.pp-kpi-box.pp-kpi-info .pp-kpi-value { color: #2980b9; }

.pp-section-title {
    font-size: 1.15rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
    padding-bottom: 8px;
    border-bottom: 2px solid #f0e8db;
}

.pp-table-gerente { font-size: 13px; }
.pp-table-gerente thead { background: #2c3e50; color: #fff; }
.pp-table-gerente thead th { font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.6px; }

.pp-empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #aaa;
}
.pp-empty-state .pp-empty-icon { font-size: 48px; margin-bottom: 12px; }
</style>

<!-- Hero gerente -->
<div class="pp-gerente-hero">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h1>Panel de Gerencia General</h1>
            <p>Control total de ParviPan: empleados, inventario, solicitudes, proveedores, movimientos y reportes</p>
        </div>
        <div class="col-md-4 text-end" style="position:relative;z-index:1">
            <a href="<?php echo BASE_URL; ?>solicitud/inicio" class="btn btn-lg btn-light fw-bold me-2">📋 Solicitudes</a>
            <a href="<?php echo BASE_URL; ?>usuario/nuevo" class="btn btn-lg btn-warning fw-bold">➕ Nuevo usuario</a>
        </div>
    </div>
</div>

<!-- KPIs en grid -->
<div class="pp-kpi-grid">
    <div class="pp-kpi-box">
        <div class="pp-kpi-icon"><i class="ti ti-users" style="font-size:32px"></i></div>
        <div class="pp-kpi-value"><?php echo $total_usuarios; ?></div>
        <div class="pp-kpi-label">Empleados</div>
    </div>
    <div class="pp-kpi-box pp-kpi-success">
        <div class="pp-kpi-icon"><i class="ti ti-package" style="font-size:32px"></i></div>
        <div class="pp-kpi-value"><?php echo $total_insumos; ?></div>
        <div class="pp-kpi-label">Insumos</div>
    </div>
    <div class="pp-kpi-box pp-kpi-info">
        <div class="pp-kpi-icon"><i class="ti ti-truck" style="font-size:32px"></i></div>
        <div class="pp-kpi-value"><?php echo $total_proveedores; ?></div>
        <div class="pp-kpi-label">Proveedores</div>
    </div>
    <div class="pp-kpi-box pp-kpi-warning">
        <div class="pp-kpi-icon"><i class="ti ti-clipboard-check" style="font-size:32px"></i></div>
        <div class="pp-kpi-value"><?php echo $total_pendientes; ?></div>
        <div class="pp-kpi-label">Pendientes</div>
    </div>
    <div class="pp-kpi-box pp-kpi-danger">
        <div class="pp-kpi-icon"><i class="ti ti-bell-ringing" style="font-size:32px"></i></div>
        <div class="pp-kpi-value"><?php echo $total_alertas; ?></div>
        <div class="pp-kpi-label">Alertas</div>
    </div>
    <div class="pp-kpi-box">
        <div class="pp-kpi-icon"><i class="ti ti-box" style="font-size:32px"></i></div>
        <div class="pp-kpi-value"><?php echo count($lotes); ?></div>
        <div class="pp-kpi-label">Lotes</div>
    </div>
</div>

<?php if ($total_pendientes > 0 || $total_alertas > 0): ?>
<div class="row mb-4">
    <?php if ($total_pendientes > 0): ?>
    <div class="col-md-<?php echo $total_alertas > 0 ? '6' : '12'; ?>">
        <div class="alert alert-warning d-flex align-items-center justify-content-between mb-0">
            <span>⚠️ <strong><?php echo $total_pendientes; ?></strong> solicitud(es) de materia prima esperando aprobación.</span>
            <a href="<?php echo BASE_URL; ?>solicitud/inicio" class="btn btn-warning btn-sm fw-bold">Gestionar →</a>
        </div>
    </div>
    <?php endif; ?>
    <?php if ($total_alertas > 0): ?>
    <div class="col-md-<?php echo $total_pendientes > 0 ? '6' : '12'; ?>">
        <div class="alert alert-danger d-flex align-items-center justify-content-between mb-0">
            <span>🚨 <strong><?php echo $total_alertas; ?></strong> alerta(s) activa(s) en el sistema.</span>
            <a href="<?php echo BASE_URL; ?>alerta/inicio" class="btn btn-danger btn-sm fw-bold">Atender →</a>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>

<!-- Empleados + Stock crítico -->
<div class="row g-4 mb-4">
    <div class="col-lg-7">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="pp-section-title mb-0" style="border:none;padding:0"> Empleados del Sistema</span>
                <a href="<?php echo BASE_URL; ?>usuario/nuevo" class="btn btn-warning btn-sm fw-bold">➕ Agregar</a>
            </div>
            <div class="card-body">
                <?php if (empty($usuarios)): ?>
                    <div class="pp-empty-state">
                        <div class="pp-empty-icon"></div>
                        <p>No hay empleados registrados.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover pp-table-gerente mb-0">
                            <thead>
                                <tr><th>#</th><th>Nombre</th><th>Usuario</th><th>Rol</th><th>Correo</th><th>Estado</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($usuarios as $u): ?>
                                <tr>
                                    <td><?php echo $u['nUsuarioID']; ?></td>
                                    <td><strong><?php echo htmlspecialchars($u['cNombre']); ?></strong></td>
                                    <td><code><?php echo htmlspecialchars($u['cNombreUsuario']); ?></code></td>
                                    <td><span class="badge role-badge role-<?php echo $u['eRol']; ?>"><?php echo htmlspecialchars($u['eRol']); ?></span></td>
                                    <td><small><?php echo htmlspecialchars($u['cCorreo']); ?></small></td>
                                    <td><span class="badge bg-<?php echo $u['eEstado'] == 'activo' ? 'success' : 'secondary'; ?>"><?php echo $u['eEstado']; ?></span></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white">
                <span class="pp-section-title mb-0" style="border:none;padding:0">🚦 Semáforo de Stock</span>
            </div>
            <div class="card-body">
                <?php if (empty($top_semaforo)): ?>
                    <div class="pp-empty-state"><div class="pp-empty-icon">📊</div><p>Sin datos de semáforo.</p></div>
                <?php else: ?>
                    <?php foreach ($top_semaforo as $fila): 
                        $esRojo = strpos($fila['eEstadoSemaforo'], 'Rojo') !== false;
                        $esAmarillo = strpos($fila['eEstadoSemaforo'], 'Amarillo') !== false;
                        $barColor = $esRojo ? '#e74c3c' : ($esAmarillo ? '#f39c12' : '#27ae60');
                        $porcentaje = ($fila['nStockMinimo'] > 0) ? min(100, ($fila['nStockActual'] / $fila['nStockMinimo']) * 100) : 100;
                    ?>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <strong style="font-size:13px"><?php echo htmlspecialchars($fila['cInsumo']); ?></strong>
                            <small class="text-muted"><?php echo number_format($fila['nStockActual'], 1); ?> / <?php echo number_format($fila['nStockMinimo'], 1); ?></small>
                        </div>
                        <div class="progress" style="height:10px;border-radius:6px">
                            <div class="progress-bar" style="width:<?php echo $porcentaje; ?>%;background:<?php echo $barColor; ?>;border-radius:6px"></div>
                        </div>
                        <small class="text-muted"><?php echo htmlspecialchars($fila['eEstadoSemaforo']); ?></small>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- FEFO + Mermas -->
<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white">
                <span class="pp-section-title mb-0" style="border:none;padding:0"> Próximos Vencimientos (FEFO)</span>
            </div>
            <div class="card-body">
                <?php if (empty($top_vencimiento)): ?>
                    <div class="pp-empty-state"><div class="pp-empty-icon"></div><p>No hay vencimientos próximos.</p></div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm pp-table-gerente mb-0">
                            <thead><tr><th>Insumo</th><th>Lote</th><th>Cant.</th><th>Vence</th></tr></thead>
                            <tbody>
                                <?php foreach ($top_vencimiento as $fila): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($fila['cInsumo'] ?? '-'); ?></td>
                                    <td><code><?php echo htmlspecialchars($fila['cCodigoLote'] ?? '-'); ?></code></td>
                                    <td><?php echo number_format($fila['nCantidadActual'] ?? 0, 2); ?></td>
                                    <td class="text-danger fw-bold"><?php echo htmlspecialchars($fila['dVencimiento'] ?? '-'); ?></td>
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
            <div class="card-header bg-white">
                <span class="pp-section-title mb-0" style="border:none;padding:0">🗑 Análisis de Mermas</span>
            </div>
            <div class="card-body">
                <?php if (empty($top_mermas)): ?>
                    <div class="pp-empty-state"><div class="pp-empty-icon">📈</div><p>No hay datos de mermas.</p></div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm pp-table-gerente mb-0">
                            <thead><tr><th>Insumo</th><th>Comprado</th><th>Usado</th><th>Merma</th><th>Vencido</th></tr></thead>
                            <tbody>
                                <?php foreach ($top_mermas as $fila): 
                                    $totalPerdida = ($fila['nTotalMermas'] ?? 0) + ($fila['nTotalVencido'] ?? 0);
                                ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($fila['cInsumo'] ?? '-'); ?></strong></td>
                                    <td><?php echo number_format($fila['nTotalComprado'] ?? 0, 1); ?></td>
                                    <td><?php echo number_format($fila['nTotalUtilizado'] ?? 0, 1); ?></td>
                                    <td class="text-warning fw-bold"><?php echo number_format($fila['nTotalMermas'] ?? 0, 1); ?></td>
                                    <td class="text-danger fw-bold"><?php echo number_format($fila['nTotalVencido'] ?? 0, 1); ?></td>
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

<!-- Solicitudes pendientes rápidas -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="pp-section-title mb-0" style="border:none;padding:0"> Solicitudes Pendientes (<?php echo count($pendientes); ?>)</span>
                <a href="<?php echo BASE_URL; ?>solicitud/inicio" class="btn btn-outline-primary btn-sm">Ver todas →</a>
            </div>
            <div class="card-body">
                <?php if (empty($pendientes)): ?>
                    <div class="alert alert-success mb-0"> No hay solicitudes pendientes en este momento.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover pp-table-gerente mb-0">
                            <thead><tr><th>#</th><th>Solicitante</th><th>Fecha</th><th>Acción</th></tr></thead>
                            <tbody>
                                <?php foreach ($pendientes as $p): ?>
                                <tr>
                                    <td><strong>#<?php echo $p['nSolicitudID']; ?></strong></td>
                                    <td><?php echo htmlspecialchars($p['cUsuario']); ?></td>
                                    <td><?php echo htmlspecialchars($p['dFecha']); ?></td>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>solicitud/detalle/<?php echo $p['nSolicitudID']; ?>" class="btn btn-sm btn-primary">Revisar</a>
                                    </td>
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