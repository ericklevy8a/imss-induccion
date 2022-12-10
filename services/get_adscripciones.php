<?php

// Cargar configuraciones basicas según entorno de ejecución (DEVELOPMENT, PRODUCTION)
require_once('./config.php');

// Obtener el valor de la adscripción a buscar
$adscripcion = isset($_GET['adscripcion']) ? $_GET['adscripcion'] : false;

$data = array(); // Datos a retornar

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_DATA);

// Validar la existencia del parámetro y de un valor a buscar
if (!$adscripcion) {
    // Valida la apertura de la conexión a base de datos
    if ($mysqli->connect_errno == 0) {
        $sql = "SELECT `Adscripción`, COUNT(`Matrícula`) AS `Guías` FROM t_guias_cache GROUP BY `Adscripción` ORDER BY `Guías` DESC";
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
    exit();
}

// Valida la apertura de la conexión a base de datos
if ($mysqli->connect_errno == 0) {
    $sql = sprintf(
        "SELECT * FROM `t_guias_cache` WHERE `Adscripción` = '%s' ORDER BY `Categoría`",
        $mysqli->real_escape_string($adscripcion)
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
