<?php
require_once "Config/config.php";
require_once "Core/BaseDatos.php";

echo "<h1>Diagnóstico de Login</h1>";

// 1. Verificar conexión
try {
    $pdo = new PDO("mysql:host=".DB_SERVIDOR.";dbname=".DB_NOMBRE.";charset=utf8", DB_USER, DB_CLAVE);
    echo "<p style='color:green'>✅ Conexión exitosa a ".DB_NOMBRE."</p>";
} catch(Exception $e) {
    die("<p style='color:red'>❌ Error de conexión: ".$e->getMessage()."</p>");
}

// 2. Listar usuarios
$stmt = $pdo->query("SELECT nUsuarioID, cNombre, cNombreUsuario, cContraseñaUsuario, eRol, eEstado FROM TUsuarios");
echo "<h2>Usuarios en la BD:</h2>";
echo "<table border='1' cellpadding='5'><tr><th>ID</th><th>Nombre</th><th>Usuario</th><th>Contraseña</th><th>Rol</th><th>Estado</th></tr>";
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr>
        <td>{$row['nUsuarioID']}</td>
        <td>{$row['cNombre']}</td>
        <td>{$row['cNombreUsuario']}</td>
        <td>{$row['cContraseñaUsuario']}</td>
        <td>{$row['eRol']}</td>
        <td>{$row['eEstado']}</td>
    </tr>";
}
echo "</table>";

// 3. Probar autenticación con "Laura" y "123456"
echo "<h2>Prueba de autenticación:</h2>";
$nombre = "Laura";
$cedula = "123456";

$sql = "SELECT * FROM TUsuarios WHERE (cNombre = ? OR cNombre LIKE ?) AND cContraseñaUsuario = ? AND eEstado = 'activo' LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute([$nombre, "%$nombre%", $cedula]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    echo "<p style='color:green'>✅ Login exitoso para '$nombre' con cédula '$cedula'</p>";
    echo "<p>Rol: {$row['eRol']} | Nombre: {$row['cNombre']}</p>";
} else {
    echo "<p style='color:red'>❌ No se encontró usuario con nombre='$nombre' y contraseña='$cedula'</p>";
    
    // Intentar con cada usuario
    $all = $pdo->query("SELECT cNombre, cContraseñaUsuario FROM TUsuarios")->fetchAll(PDO::FETCH_ASSOC);
    echo "<p>Prueba para cada usuario:</p><ul>";
    foreach ($all as $u) {
        $match = ($u['cNombre'] == $nombre || strpos($u['cNombre'], $nombre) !== false) && $u['cContraseñaUsuario'] == $cedula;
        echo "<li>Nombre: '{$u['cNombre']}' Contraseña: '{$u['cContraseñaUsuario']}' → " . ($match ? "✅ COINCIDE" : "❌ No coincide") . "</li>";
    }
    echo "</ul>";
}

// 4. Verificar si el script datos_prueba.sql se ejecutó
$count = $pdo->query("SELECT COUNT(*) FROM TUsuarios")->fetchColumn();
echo "<h2>Total usuarios: $count</h2>";
if ($count == 0) {
    echo "<p style='color:red'>❌ No hay usuarios en la base de datos. ¡Debes importar BD/datos_prueba.sql!</p>";
}
?>