<?php

// Verificar recepcion de archivo
if (!$_FILES['file_csv'])
    exit('ERROR: No se ha recibido un archivo!');

// Verificar el tipo de archivo
if ($_FILES['file_csv']['type'] != 'text/csv')
    exit('ERROR: El tipo de archivo no es el correcto!');

// Obtener el nombre temporal del archivo
$tmp_name = $_FILES['file_csv']['tmp_name'];

// Abrir el archivo y validar
if (!($handler = fopen($tmp_name, 'r')))
    exit('ERROR: No se pudo abrir el archivo!');

// Obtener nombres de columnas
$cols = fgetcsv($handler, 250);
if (sizeof($cols) == 0) 
    exit('ERROR: No se encontraron columnas!');

// Remover el BOM del UTF-8 de haberlo
$cols = str_replace("\xef\xbb\xbf", '', $cols);

// Cargar los datos en una matriz asociativa
$array = [];
while (($row = fgetcsv($handler, 250)) != FALSE) {
    $data = [];
    for ($i = 0; $i < sizeof($cols); $i++) {
        $data[$cols[$i]] = $row[$i];
    }
    $array[] = $data;
}

// Cerrar el archivo
fclose($handler);

// Preparar la base de datos
include_once('./config.php');
$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_DATA);

// Limpiar la tabla
$sql = "TRUNCATE t_guias_2022";
$mysqli->query($sql);

// Preparar nombres de columnas o campos
foreach ($cols as &$col) {
    $col = '`' . $col . '`';
}
// Preparar primera parte del INSERT con los nombres de los campos
$sql = "INSERT INTO t_guias_2022 (" . implode(", ", $cols) . ") VALUES ";

$values = [];
$num = 0;
$sum = 0;
foreach ($array as $row) {
    // Preparar grupos de valores
    foreach ($row as $key => &$value) {
        if (!is_numeric($value)) {
            $value = '"' . $value . '"';
        }
    }
    // Formar las cadenas de valores
    $values[] = "(" . implode(", ", array_values($row)) . ")";
    $num++;
    // Cada 1000 registros o al final ejecutar el INSERT
    if ($num == 1000 || $sum + $num == sizeof($array)) {
        $sql_values = implode(", ", $values);

        $mysqli->query($sql . $sql_values);

        $values = [];
        $sum += $num;
        $num = 0;
    }
}

$sql = "CALL p_id_update";
$mysqli->query($sql);

$sql = "CALL p_guias_cache";
$mysqli->query($sql);

$mysqli->close();

exit("se han procesado {$sum} registros");
