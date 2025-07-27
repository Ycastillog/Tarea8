<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

require_once "includes/conexion.php";

// Obtener facturas de los últimos 7 días
$sql = "
    SELECT f.id, c.nombre AS cliente, f.fecha,
           SUM(d.cantidad * d.precio_unitario) AS total
    FROM facturas f
    JOIN clientes c ON f.cliente_id = c.id
    JOIN detalle_factura d ON f.id = d.factura_id
    WHERE f.fecha >= CURDATE() - INTERVAL 7 DAY
    GROUP BY f.id, c.nombre, f.fecha
    ORDER BY f.fecha DESC
";

$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Diario - La Rubia</title>
</head>
<body>
    <h2>Reporte de Ventas - Últimos 7 días (<?= date("d/m/Y") ?>)</h2>

    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th># Factura</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Total (RD$)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $totalGeneral = 0;
            while ($fila = $resultado->fetch_assoc()):
                $totalGeneral += $fila['total'];
            ?>
                <tr>
                    <td><?= $fila['id'] ?></td>
                    <td><?= $fila['cliente'] ?></td>
                    <td><?= $fila['fecha'] ?></td>
                    <td><?= number_format($fila['total'], 2) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3"><strong>Total del período</strong></td>
                <td><strong><?= number_format($totalGeneral, 2) ?></strong></td>
            </tr>
        </tfoot>
    </table>

    <br>
    <a href="dashboard.php">← Volver al menú</a>
</body>
</html>

