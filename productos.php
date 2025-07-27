<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
require_once "includes/conexion.php";

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $precio = $_POST["precio"];

    $sql = "INSERT INTO productos (nombre, precio) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sd", $nombre, $precio);

    if ($stmt->execute()) {
        $msg = "Producto registrado correctamente.";
    } else {
        $msg = "Error al registrar producto.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Producto - La Rubia</title>
</head>
<body>
    <h2>Registrar Producto</h2>
    <?php if ($msg): ?><p style="color:green;"><?= $msg ?></p><?php endif; ?>
    <form method="post">
        <label>Nombre del Producto:</label><br>
        <input type="text" name="nombre" required><br><br>
        <label>Precio (RD$):</label><br>
        <input type="number" name="precio" step="0.01" required><br><br>
        <button type="submit">Guardar Producto</button>
    </form>
    <br>
    <a href="dashboard.php">Volver al menú</a>
</body>
</html>

