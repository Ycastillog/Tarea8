<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "la_rubia";

// Crear conexión
$conn = new mysqli($host, $user, $pass);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Crear base de datos
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Base de datos creada correctamente.<br>";
} else {
    die("Error al crear base de datos: " . $conn->error);
}

$conn->select_db($dbname);

// Crear tablas
$tablas = [

    "CREATE TABLE IF NOT EXISTS usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        usuario VARCHAR(50) NOT NULL,
        clave VARCHAR(255) NOT NULL
    )",

    "INSERT INTO usuarios (usuario, clave) VALUES ('demo', '" . password_hash('tareafacil25', PASSWORD_DEFAULT) . "')",

    "CREATE TABLE IF NOT EXISTS clientes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        codigo_cliente VARCHAR(20) UNIQUE NOT NULL,
        nombre VARCHAR(100) NOT NULL
    )",

    "CREATE TABLE IF NOT EXISTS productos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL,
        precio DECIMAL(10,2) NOT NULL
    )",

    "CREATE TABLE IF NOT EXISTS facturas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        cliente_id INT NOT NULL,
        fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
        comentario TEXT,
        FOREIGN KEY (cliente_id) REFERENCES clientes(id)
    )",

    "CREATE TABLE IF NOT EXISTS detalle_factura (
        id INT AUTO_INCREMENT PRIMARY KEY,
        factura_id INT NOT NULL,
        producto_id INT NOT NULL,
        cantidad INT NOT NULL,
        precio_unitario DECIMAL(10,2) NOT NULL,
        FOREIGN KEY (factura_id) REFERENCES facturas(id),
        FOREIGN KEY (producto_id) REFERENCES productos(id)
    )"
];

// Ejecutar tablas
foreach ($tablas as $query) {
    if ($conn->query($query) === TRUE) {
        echo "Consulta ejecutada correctamente.<br>";
    } else {
        echo "Error ejecutando consulta: " . $conn->error . "<br>";
    }
}

$conn->close();
?>
