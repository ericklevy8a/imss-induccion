<?php

// Cargar configuraciones basicas según entorno de ejecución (DEVELOPMENT, PRODUCTION)
require_once(__DIR__ . '/config.php');

// Obtener el valor de la matricula a buscar
$matricula = isset($_GET['matricula']) ? $_GET['matricula'] : false;

// Validar la existencia del parámetro y de un valor a buscar
if (!$matricula) {
    // o terminar con un mensaje de error
    http_response_code(400);
    exit();
}

$data = array(); // Datos a retornar

$field_name = array();
$row_count = 0;

// Validar la apertura del archivo de datos
if (($handle = fopen("./data/inducciones.csv", "r")) !== FALSE) {
    while (($csv = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($csv);
        // Si es la primera fila
        if ($row_count == 0) {
            // Obtener nombre de campos
            for ($c = 0; $c < $num; $c++) {
                $fieldname[] = trim($csv[$c], "\xEF\xBB\xBF");
            }
        } else {
            // Obtener los datos de cada renglón
            $row = array();
            for ($c = 0; $c < $num; $c++) {
                // en un arreglo asociativo según su nombre de campo
                $row[$fieldname[$c]] = $csv[$c];
            }
            // Si es la matrícula buscada, conservar el renglón
            if ($row['Matricula'] == $matricula) {
                $data[] = $row;
            }
        }
        $row_count++;
    }
    // Cerrar el archivo de datos
    fclose($handle);

    // Retornar los datos en formato JSON y salir
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
http_response_code(404);
