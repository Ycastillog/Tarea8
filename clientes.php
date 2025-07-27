<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
require_once "includes/conexion.php";

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo = $_POST["codigo"];
    $nombre = $_POST["nombre"];

    $sql = "INSERT INTO clientes (codigo_cliente, nombre) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $codigo, $nombre);

    if ($stmt->execute()) {
        $msg = "Cliente registrado correctamente.";
    } else {
        $msg = "Error al registrar cliente.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Cliente - La Rubia</title>
</head>
<body>
    <h2>Registrar Cliente</h2>
    <?php if ($msg): ?><p style="color:green;"><?= $msg ?></p><?php endif; ?>
    <form method="post">
        <label>Código del Cliente:</label><br>
        <input type="text" name="codigo" required><br><br>
        <label>Nombre:</label><br>
        <input type="text" name="nombre" required><br><br>
        <button type="submit">Guardar Cliente</button>
    </form>
    <br>
    <a href="dashboard.php">Volver al menú</a>
</body>
</html>
