<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

require_once "includes/conexion.php";

// Obtener clientes y productos
$clientes = $conn->query("SELECT id, codigo_cliente, nombre FROM clientes ORDER BY nombre ASC");
$productos = $conn->query("SELECT id, nombre, precio FROM productos ORDER BY nombre ASC");

// Procesar envío
$msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cliente_id = $_POST["cliente_id"];
    $comentario = $_POST["comentario"];
    $productosSeleccionados = $_POST["productos"];
    $cantidades = $_POST["cantidades"];

    // Insertar factura
    $sqlFactura = "INSERT INTO facturas (cliente_id, comentario) VALUES (?, ?)";
    $stmtFactura = $conn->prepare($sqlFactura);
    $stmtFactura->bind_param("is", $cliente_id, $comentario);
    $stmtFactura->execute();
    $factura_id = $stmtFactura->insert_id;
    $stmtFactura->close();

    // Insertar detalle
    $sqlDetalle = "INSERT INTO detalle_factura (factura_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)";
    $stmtDetalle = $conn->prepare($sqlDetalle);

    foreach ($productosSeleccionados as $i => $producto_id) {
        $cantidad = $cantidades[$i];
        $sqlPrecio = "SELECT precio FROM productos WHERE id = ?";
        $stmtPrecio = $conn->prepare($sqlPrecio);
        $stmtPrecio->bind_param("i", $producto_id);
        $stmtPrecio->execute();
        $stmtPrecio->bind_result($precio_unitario);
        $stmtPrecio->fetch();
        $stmtPrecio->close();

        $stmtDetalle->bind_param("iiid", $factura_id, $producto_id, $cantidad, $precio_unitario);
        $stmtDetalle->execute();
    }

    $stmtDetalle->close();
    $msg = "Factura registrada correctamente.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Generar Factura - La Rubia</title>
</head>
<body>
    <h2>Generar Factura</h2>

    <?php if ($msg): ?>
        <p style="color:green;"><?= $msg ?></p>
    <?php endif; ?>

    <form method="post">
        <label>Cliente:</label><br>
        <select name="cliente_id" required>
            <option value="">Seleccione un cliente</option>
            <?php while ($c = $clientes->fetch_assoc()): ?>
                <option value="<?= $c['id'] ?>"><?= $c['nombre'] ?> (<?= $c['codigo_cliente'] ?>)</option>
            <?php endwhile; ?>
        </select><br><br>

        <label>Comentario (opcional):</label><br>
        <textarea name="comentario" rows="3" cols="30"></textarea><br><br>

        <label>Productos:</label><br>
        <div id="productos">
            <div>
                <select name="productos[]">
                    <option value="">Seleccione</option>
                    <?php
                    $productos->data_seek(0); // Reiniciar puntero
                    while ($p = $productos->fetch_assoc()):
                    ?>
                        <option value="<?= $p['id'] ?>"><?= $p['nombre'] ?> - RD$<?= $p['precio'] ?></option>
                    <?php endwhile; ?>
                </select>
                <input type="number" name="cantidades[]" min="1" value="1" required>
            </div>
        </div>

        <button type="button" onclick="agregarProducto()">➕ Agregar otro producto</button><br><br>

        <button type="submit">Guardar Factura</button>
    </form>

    <br>
    <a href="dashboard.php">← Volver al menú</a>

    <script>
    function agregarProducto() {
        const div = document.createElement('div');

        div.innerHTML = `
            <select name="productos[]">
                <option value="">Seleccione</option>
                <?php
                $productos->data_seek(0);
                while ($p = $productos->fetch_assoc()):
                ?>
                    <option value="<?= $p['id'] ?>"><?= $p['nombre'] ?> - RD$<?= $p['precio'] ?></option>
                <?php endwhile; ?>
            </select>
            <input type="number" name="cantidades[]" min="1" value="1" required>
        `;

        document.getElementById('productos').appendChild(div);
    }
    </script>
</body>
</html>
