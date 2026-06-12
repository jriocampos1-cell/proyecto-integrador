<?php
$titulo = 'Nuevo Usuario — ' . APP_NAME;
$accion_actual = 'usuarios';
$rol = $_SESSION['user']['rol'] ?? '';

// Bloqueo doble: solo gerente
if ($rol !== 'gerente') {
    $_SESSION['msg'] = 'No tienes permisos para crear usuarios.';
    $_SESSION['tipo'] = 'danger';
    header('Location: ' . BASE_URL . 'dashboard/inicio');
    exit();
}
ob_start();
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="mb-1"><i class="ti ti-user-plus"></i> Registrar nuevo usuario</h1>
        <p class="text-muted">Completa todos los campos para crear un nuevo empleado en el sistema.</p>
    </div>
    <div class="col-md-4 text-end">
        <a href="<?php echo BASE_URL; ?>usuario/inicio" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left"></i> Volver al listado
        </a>
    </div>
</div>

<?php if (!empty($_SESSION['msg'])): ?>
<div class="alert alert-<?php echo $_SESSION['tipo'] ?? 'info'; ?> alert-dismissible fade show">
    <?php echo $_SESSION['msg']; unset($_SESSION['msg']); unset($_SESSION['tipo']); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="post" action="<?php echo BASE_URL; ?>usuario/createUsuario" novalidate>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nombre completo *</label>
                        <input type="text" name="cNombre" class="form-control" placeholder="Ej: Pedro" required maxlength="100">
                        <small class="text-muted">Nombre real de la persona (sin apellidos obligatorios).</small>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nombre de usuario *</label>
                            <input type="text" name="cNombreUsuario" class="form-control" placeholder="Ej: cocinero" required maxlength="50">
                            <small class="text-muted">Será su identificador único en el sistema.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Cédula (contraseña) *</label>
                            <input type="text" name="cContraseñaUsuario" class="form-control" placeholder="Ej: 345678" required maxlength="255">
                            <small class="text-muted">Esta será su contraseña de acceso.</small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Rol *</label>
                            <select name="eRol" class="form-select" required>
                                <option value="">— Seleccionar rol —</option>
                                <option value="gerente">Gerente</option>
                                <option value="cocinero">Cocinero</option>
                                <option value="pastelero">Pastelero</option>
                                <option value="bodega">Bodega</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Correo electrónico *</label>
                            <input type="email" name="cCorreo" class="form-control" placeholder="ejemplo@parvipan.com" required maxlength="100">
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex mt-4">
                        <button type="submit" class="btn btn-warning btn-lg fw-bold">
                            <i class="ti ti-user-plus"></i> Registrar usuario
                        </button>
                        <a href="<?php echo BASE_URL; ?>usuario/inicio" class="btn btn-outline-secondary btn-lg">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h5 class="card-title"><i class="ti ti-info-circle"></i> Guía de roles</h5>
                <ul class="list-unstyled mb-0 small">
                    <li class="mb-2">🟡 <strong>Gerente:</strong> Acceso total. Ve empleados, inventario, reportes, aprueba/rechaza solicitudes.</li>
                    <li class="mb-2">🔵 <strong>Bodega:</strong> Gestiona inventario, lotes, proveedores, aprueba/rechaza solicitudes.</li>
                    <li class="mb-2">🔴 <strong>Cocinero:</strong> Ve stock (semáforo), crea solicitudes de materia prima.</li>
                    <li>🟣 <strong>Pastelero:</strong> Igual que cocinero: ve stock y crea solicitudes.</li>
                </ul>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="card-title"><i class="ti ti-key"></i> Contraseña</h6>
                <p class="small text-muted mb-0">
                    La <strong>cédula</strong> ingresada será la contraseña del usuario. 
                    Asegúrate de informarle al empleado cuál es su cédula registrada para que pueda iniciar sesión.
                </p>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../Public/plantilla.html';