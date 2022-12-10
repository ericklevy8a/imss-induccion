<?php

// Cargar configuraciones basicas según entorno de ejecución (DEVELOPMENT, PRODUCTION)
require_once('./config.php');

// Obtener el valor de la matricula a buscar
$matricula = isset($_GET['matricula']) ? $_GET['matricula'] : false;

// Validar la existencia del parámetro y de un valor a buscar
if (!$matricula) {
    // o terminar con un mensaje de error
    http_response_code(400);
    exit();
}

$data = array(); // Datos a retornar

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_DATA);

// Valida la apertura de la conexión a base de datos
if ($mysqli->connect_errno == 0) {
    $sql = sprintf(
        "SELECT * FROM `t_guias_cache` WHERE `Matrícula` = '%s' ORDER BY `Fecha`",
        $mysqli->real_escape_string($matricula)
    );
    if ($res = $mysqli->query($sql)) {
        while ($row = $res->fetch_assoc()) {
            $data[] = $row;
        }
    }
    // Cerrar la conexión a base de datos
    $mysqli->close();

    // Retornar los datos en formato JSON y salir
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
http_response_code(404);
