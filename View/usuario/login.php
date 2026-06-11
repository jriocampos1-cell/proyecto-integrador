<?php
$titulo = 'Login';
ob_start();
?>
<div class="row">
    <div class="col-md-4 offset-md-4 mt-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h3 class="card-title">Iniciar sesión</h3>
                <?php if (!empty($_SESSION['msg'])): ?>
                    <div class="alert alert-<?php echo $_SESSION['tipo'] ?? 'info'; ?>">
                        <?php echo $_SESSION['msg']; unset($_SESSION['msg']); unset($_SESSION['tipo']); ?>
                    </div>
                <?php endif; ?>
                <form method="post" action="<?php echo BASE_URL; ?>usuario/authenticate">
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="cNombre" class="form-control" placeholder="Tu nombre (ej: Pedro, Laura)" required autocomplete="name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cédula (contraseña)</label>
                        <input type="password" name="cCedula" class="form-control" placeholder="••••••••" required autocomplete="current-password">
                    </div>
                    <div class="d-grid">
                        <button class="btn btn-primary"><i class="ti ti-login"></i> Ingresar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../Public/plantilla.html';
