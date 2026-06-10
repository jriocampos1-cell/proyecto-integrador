<?php
require_once __DIR__ . '/../Config/config.php';
$titulo = 'Dashboard - PARVI PAN';
ob_start();
?>
<div class="row mb-4">
    <div class="col-12">
        <h1>Dashboard de vistas</h1>
        <p class="text-muted">Accede a las vistas de datos principales de la base de datos.</p>
    </div>
</div>
<div class="row gy-3">
    <?php
    $vistas = [
        'Usuarios' => 'usuarios.php',
        'Insumos' => 'insumos.php',
        'Proveedores' => 'proveedores.php',
        'Lotes' => 'lotes.php',
        'Movimientos' => 'movimientos.php',
        'Solicitudes' => 'solicitudes.php',
        'Alertas' => 'alertas.php',
        'Semáforo de stock' => 'semaforo_stock.php',
        'Inventario FEFO' => 'inventario_fefo.php',
        'Análisis de mermas' => 'analisis_mermas.php',
    ];
    foreach ($vistas as $tituloVista => $archivo):
    ?>
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?php echo $tituloVista; ?></h5>
                    <p class="card-text text-muted">Ver datos y reportes para <?php echo strtolower($tituloVista); ?>.</p>
                    <a class="btn btn-primary mt-auto" href="<?php echo BASE_URL; ?>/View/<?php echo $archivo; ?>">Abrir</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../Public/plantilla.html';
