<?php
$titulo = 'Nueva Solicitud — ' . APP_NAME;
$accion_actual = 'nuevo';
$insumos = $insumos ?? [];
$userId = $_SESSION['user']['id'] ?? 0;
ob_start();
?>

<div class="row mb-4">
    <div class="col-12">
        <h1 class="mb-1"><i class="ti ti-plus"></i> Nueva Solicitud de Materia Prima</h1>
        <p class="text-muted">Selecciona los insumos y cantidades que necesitas para producción.</p>
    </div>
</div>

<?php if (!empty($_SESSION['msg'])): ?>
<div class="alert alert-<?php echo $_SESSION['tipo'] ?? 'info'; ?> alert-dismissible fade show">
    <?php echo $_SESSION['msg']; unset($_SESSION['msg']); unset($_SESSION['tipo']); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="post" id="form-solicitud-completa" action="<?php echo BASE_URL; ?>solicitud/createCompleta">
                    <input type="hidden" name="nUsuarioID" value="<?php echo $userId; ?>">

                    <h5 class="mb-3">Insumos solicitados</h5>
                    <div id="items-container">
                        <div class="row mb-3 item-row">
                            <div class="col-md-5">
                                <label class="form-label">Insumo *</label>
                                <select name="insumo_id[]" class="form-select" required>
                                    <option value="">— Seleccionar —</option>
                                    <?php foreach ($insumos as $in): ?>
                                        <option value="<?php echo $in['nInsumoID']; ?>" 
                                            data-stock="<?php echo $in['nStockActual']; ?>"
                                            data-unidad="<?php echo $in['eUnidadMedida']; ?>">
                                            <?php echo htmlspecialchars($in['cNombre']); ?> 
                                            (Stock: <?php echo $in['nStockActual']; ?> <?php echo $in['eUnidadMedida']; ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Cantidad *</label>
                                <input type="number" name="cantidad[]" class="form-control" min="0.01" step="0.01" required placeholder="Ej: 5">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Unidad</label>
                                <select name="unidad[]" class="form-select">
                                    <option value="kg">kg</option>
                                    <option value="g">g</option>
                                    <option value="litros">litros</option>
                                    <option value="ml">ml</option>
                                    <option value="unidades">unidades</option>
                                </select>
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="button" class="btn btn-outline-danger btn-sm btn-remove-item" title="Eliminar">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <button type="button" id="btn-add-item" class="btn btn-outline-primary btn-sm">
                            <i class="ti ti-plus"></i> Agregar otro insumo
                        </button>
                    </div>

                    <div class="d-grid gap-2 d-md-flex">
                        <button type="submit" class="btn btn-lg btn-warning">
                            <i class="ti ti-send"></i> Enviar solicitud
                        </button>
                        <a href="<?php echo BASE_URL; ?>dashboard/inicio" class="btn btn-lg btn-outline-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title"><i class="ti ti-info-circle"></i> Información</h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><i class="ti ti-check text-success"></i> Puedes solicitar varios insumos a la vez.</li>
                    <li class="mb-2"><i class="ti ti-clock text-warning"></i> La solicitud será revisada por bodega o gerencia.</li>
                    <li><i class="ti ti-alert-triangle text-danger"></i> Verifica el stock disponible antes de pedir.</li>
                </ul>
            </div>
        </div>

        <div class="card shadow-sm mt-3">
            <div class="card-body">
                <h6 class="card-title">Stock rápido</h6>
                <?php if (!empty($insumos)): ?>
                    <ul class="list-group list-group-flush" style="font-size:13px;">
                        <?php foreach (array_slice($insumos, 0, 8) as $in): 
                            $estado = $in['nStockActual'] == 0 ? 'danger' : 
                                      ($in['nStockActual'] <= $in['nStockMinimo'] ? 'warning' : 'success');
                        ?>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span><?php echo htmlspecialchars($in['cNombre']); ?></span>
                            <span class="text-<?php echo $estado; ?> fw-bold">
                                <?php echo $in['nStockActual']; ?> <?php echo $in['eUnidadMedida']; ?>
                            </span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
// Agregar nueva fila de insumo
document.getElementById('btn-add-item').addEventListener('click', function() {
    const container = document.getElementById('items-container');
    const firstRow = container.querySelector('.item-row');
    const newRow = firstRow.cloneNode(true);

    // Limpiar valores
    newRow.querySelectorAll('input').forEach(i => { i.value = ''; });
    newRow.querySelectorAll('select').forEach(s => { s.selectedIndex = 0; });

    // Agregar botón de eliminar
    const btnRemove = newRow.querySelector('.btn-remove-item');
    if (!btnRemove) {
        const div = document.createElement('div');
        div.className = 'col-md-1 d-flex align-items-end';
        div.innerHTML = '<button type="button" class="btn btn-outline-danger btn-sm btn-remove-item" title="Eliminar"><i class="ti ti-trash"></i></button>';
        newRow.appendChild(div);
    }

    container.appendChild(newRow);
    bindRemoveButtons();
});

function bindRemoveButtons() {
    document.querySelectorAll('.btn-remove-item').forEach(btn => {
        btn.onclick = function() {
            const rows = document.querySelectorAll('.item-row');
            if (rows.length > 1) {
                this.closest('.item-row').remove();
            } else {
                alert('Debe haber al menos un insumo en la solicitud.');
            }
        };
    });
}
bindRemoveButtons();
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../Public/plantilla.html';