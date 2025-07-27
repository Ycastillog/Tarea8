<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Principal - La Rubia</title>
</head>
<body>
    <h1>Bienvenido, <?= $_SESSION['usuario'] ?></h1>

    <ul>
        <li><a href="clientes.php">👤 Registrar Cliente</a></li>
        <li><a href="productos.php">📦 Registrar Producto</a></li>
        <li><a href="factura.php">🧾 Generar Factura</a></li>
        <li><a href="reporte.php">📊 Ver Reporte Diario</a></li>
        <li><a href="logout.php">🔒 Cerrar Sesión</a></li>
    </ul>
</body>
</html>
