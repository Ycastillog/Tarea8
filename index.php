<?php
session_start();
if (isset($_SESSION['usuario'])) {
    header("Location: dashboard.php");
    exit();
}

$msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "includes/conexion.php";

    $usuario = $_POST['usuario'];
    $clave = $_POST['clave'];

    $sql = "SELECT * FROM usuarios WHERE usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows == 1) {
        $fila = $resultado->fetch_assoc();
        if (password_verify($clave, $fila['clave'])) {
            $_SESSION['usuario'] = $usuario;
            header("Location: dashboard.php");
            exit();
        } else {
            $msg = "Clave incorrecta.";
        }
    } else {
        $msg = "Usuario no encontrado.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - La Rubia</title>
</head>
<body>
    <h2>Iniciar Sesión</h2>
    <?php if ($msg): ?>
        <p style="color:red;"><?= $msg ?></p>
    <?php endif; ?>
    <form method="post">
        <label>Usuario:</label><br>
        <input type="text" name="usuario" required><br><br>
        <label>Clave:</label><br>
        <input type="password" name="clave" required><br><br>
        <button type="submit">Entrar</button>
    </form>
</body>
</html>
