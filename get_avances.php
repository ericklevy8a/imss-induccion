<?php

// Cargar configuraciones basicas según entorno de ejecución (DEVELOPMENT, PRODUCTION)
require_once(__DIR__ . '/config.php');

// Obtener el valor del tipo de estadística a generar [h: Hospitales, u: UMF's, s: Subdelegaciones]
$tipo = $_GET['tipo'] ?? false;

$data = array(); // Datos a retornar

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_DATA);

// Validar el parámetro
if ($tipo == 'h') {
    // Valida la apertura de la conexión a base de datos
    if ($mysqli->connect_errno == 0) {
        $sql = "SELECT `Adscripción`, SUM(IF(`Considerada` <> 'NO', 1, 0)) AS Asignadas, SUM(IF(`Guía de Inducción` IS NOT NULL AND `Guía de Inducción` <> '', 1, 0)) AS Realizadas FROM `t_2022` WHERE `Adscripción` LIKE 'HG%' GROUP BY `Adscripción` ORDER BY `Adscripción` ASC";
        if ($res = $mysqli->query($sql)) {
            while ($row = $res->fetch_assoc()) {
                $data[] = $row;
            }
        }
        // Cerrar la conexión a base de datos
        $mysqli->close();
        // Retornar los datos en formato JSON y salir
        header("Content-type: application/json");
        header("Access-Control-Allow-Origin: *");
        echo json_encode($data);
        exit();
    }
    exit();
}

// Validar el parámetro
if ($tipo == 'u') {
    // Valida la apertura de la conexión a base de datos
    if ($mysqli->connect_errno == 0) {
        $sql = "SELECT `Adscripción`, SUM(IF(`Considerada` <> 'NO', 1, 0)) AS Asignadas, SUM(IF(`Guía de Inducción` IS NOT NULL AND `Guía de Inducción` <> '', 1, 0)) AS Realizadas FROM `t_2022`  WHERE `Adscripción` LIKE 'UMF%'  GROUP BY `Adscripción` ORDER BY `Adscripción` ASC";
        if ($res = $mysqli->query($sql)) {
            while ($row = $res->fetch_assoc()) {
                $data[] = $row;
            }
        }
        // Cerrar la conexión a base de datos
        $mysqli->close();
        // Retornar los datos en formato JSON y salir
        header("Content-type: application/json");
        header("Access-Control-Allow-Origin: *");
        echo json_encode($data);
        exit();
    }
    exit();
}

// Validar el parámetro
if ($tipo == 's') {
    // Valida la apertura de la conexión a base de datos
    if ($mysqli->connect_errno == 0) {
        $sql = "SELECT `Adscripción`, SUM(IF(`Considerada` <> 'NO', 1, 0)) AS Asignadas, SUM(IF(`Guía de Inducción` IS NOT NULL AND `Guía de Inducción` <> '', 1, 0)) AS Realizadas FROM `t_2022`  WHERE `Adscripción` LIKE 'SUB%' GROUP BY `Adscripción` ORDER BY `Adscripción` ASC";
        if ($res = $mysqli->query($sql)) {
            while ($row = $res->fetch_assoc()) {
                $data[] = $row;
            }
        }
        // Cerrar la conexión a base de datos
        $mysqli->close();
        // Retornar los datos en formato JSON y salir
        header("Content-type: application/json");
        header("Access-Control-Allow-Origin: *");
        echo json_encode($data);
        exit();
    }
    exit();
}
// Validar el parámetro
if ($tipo == 'f') {
    // Valida la apertura de la conexión a base de datos
    if ($mysqli->connect_errno == 0) {
        $sql = "SELECT Fecha FROM `t_2022` ORDER BY Fecha DESC LIMIT 1";
        if ($res = $mysqli->query($sql)) {
            while ($row = $res->fetch_assoc()) {
                $data[] = $row;
            }
        }
        // Cerrar la conexión a base de datos
        $mysqli->close();
        // Retornar los datos en formato JSON y salir
        header("Content-type: application/json");
        header("Access-Control-Allow-Origin: *");
        echo json_encode($data);
        exit();
    }
    exit();
}
http_response_code(404);
