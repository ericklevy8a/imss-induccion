<?php

// Carga librería dompdf
require(__DIR__ . '/dompdf/autoload.inc.php');

use Dompdf\Dompdf;
use Dompdf\Options;

// Instancia objeto de la clase Dompdf
$options = new Options();
$options->set('isRemoteEnabled', TRUE);
$pdf = new Dompdf($options);

// Define tamaño y orientación del medio (papel)
$pdf->setPaper("letter", "portrait");

$opt = $pdf->getOptions();


// Inicia captura de cache de salida
ob_start();

//
// Generación de datos
//

$DATOS = [
    'NOMBRE_UNIDAD'     => 'HOSPITAL GENERAL DE ZONA NO. 1',
    'FECHA_ELABORA'     => '2022-09-22',
    'MATRICULA'         => '311060133',
    'NOMBRE_TRABAJADOR' => 'ERICK DAVID LEVY OCHOA',
    'CATEGORIA'         => 'N39 RESPONSABLE SISTEMAS E0',
    'ADSCRIPCION'       => 'HOSPITAL DE ESPECIALIDADES IMSS BIENESTAR COLIMA',
    'FECHA_INGRESO'     => '2022-08-16',
    'FECHA_INDUCCION'   => '2022-09-01 AL 2022-09-30',
    'DURACION'          => '20 HORAS',
    'NOMBRE_INSTRUCTOR' => 'JAIME LUNA LARIOS',
    'CARGO_INSTRUCTOR'  => 'COORD. ENSEÑANZA Y CALIDAD'
];

//
// Generación de HTML
//

?>

<style>
    @font-face {
        font-family: "Montserrat";
        font-weight: normal;
        src: url('./css/Montserrat-Regular.ttf');
    }

    @font-face {
        font-family: "Montserrat";
        font-weight: bold;
        src: url('./css/Montserrat-Bold.ttf');
    }

    html * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    @page {
        margin: 0.5cm;
        margin-left: 1cm;
    }

    body {
        font-size: 12px !important;
        line-height: 13px;
        font-family: 'Montserrat', sans-serif;
    }

    header table {
        width: 100%;
    }

    h1 {
        font-weight: bold;
        font-size: 18px;
        text-transform: uppercase;
        text-align: center;
    }

    .year {
        font-size: 24px;
        color: #ccc;
    }

    .data table {
        width: 100%;
        border-collapse: collapse;
    }

    .data td {
        padding: 0 4px;
    }

    .data td.check {
        padding: 8px;
        line-height: 1em;
    }

    .data td {
        padding: 0 4px;
    }

    .instructions {
        padding: 4px;
        background-color: lightgray;
    }

    .indications td {
        padding-bottom: 3px;
        vertical-align: top;
    }

    .border {
        border: 1px solid black;
    }

    .border-left-1 {
        border-left: 1px solid black;
    }

    .border-bottom-1 {
        border-bottom: 1px solid black;
    }

    .center {
        text-align: center;
    }

    .bold {
        font-weight: bold;
    }

    .uppercase {
        text-transform: uppercase;
    }

    .mini {
        font-size: smaller;
    }
</style>

<body>

    <header>
        <table>
            <tr>
                <td class="center">
                    <img src="http://localhost/imss-induccion/img/imss_logo_trans.png" height="36" alt="">
                </td>
                <td class="center">
                    <h1>Guía de Inducción al Área y al Puesto</h1>
                </td>
                <td class="center">
                    <div class="year bold">2022</div>
                </td>
            </tr>
        </table>
    </header>

    <div class="data border">
        <table class="border">
            <tr>
                <table>
                    <tr>
                        <td class="center bold uppercase">OOAD Colima</td>
                        <td class="center">Unidad</td>
                        <td class="center border-left-1" width="20%">Fecha de elaboración</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="center bold"><?php echo $DATOS['NOMBRE_UNIDAD'] ?></td>
                        <td class="center bold border-left-1"><?php echo $DATOS['FECHA_ELABORA'] ?></td>
                    </tr>
                </table>
            </tr>
            <tr></tr>
            <tr></tr>
        </table>

        <table class="border">
            <tr>
                <td class="center bold uppercase border-bottom-1" colspan="3">Datos del trabajador</td>
            </tr>
            <tr>
                <td class="border-bottom-1">Matrícula<br>
                    <span class="bold">
                        <?php echo $DATOS['MATRICULA'] ?>
                    </span>
                </td>
                <td class="border-bottom-1 border-left-1" colspan="2">Nombre del trabajador<br>
                    <span class="bold">
                        <?php echo $DATOS['NOMBRE_TRABAJADOR'] ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td width="33%">Categoría<br>
                    <span class="bold"><?php echo $DATOS['CATEGORIA'] ?></span>
                </td>
                <td class="border-left-1">Adscripción<br>
                    <span class="bold"><?php echo $DATOS['ADSCRIPCION'] ?></span>
                </td>
                <td class="center border-left-1" width="20%">Fecha de ingreso<br>
                    <span class="center bold"><?php echo $DATOS['FECHA_INGRESO'] ?></span>
                </td>
            </tr>
        </table>

        <table class="border">
            <tr>
                <td class="center border-bottom-1" colspan="7">
                    <table>
                        <tr>
                            <td class="center" width="33%"><span class="bold uppercase">Tipo de movimiento</span></td>
                            <td class="center"><span class="mini">(MARCAR CON UNA "X" EL RUBRO CORRESPONDIENTE)</span></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="check center" width="15%">[&nbsp;&nbsp;&nbsp;]<br>
                    <span>
                        Nuevo<br>Ingreso
                    </span>
                </td>
                <td class="check center border-left-1" width="14%">[&nbsp;&nbsp;&nbsp;]<br>
                    <span>
                        Cambio de rama
                    </span>
                </td>
                <td class="check center border-left-1" width="14%">[&nbsp;&nbsp;&nbsp;]<br>
                    <span>
                        Promoción escalafonaria
                    </span>
                </td>
                <td class="check center border-left-1" width="14%">[&nbsp;&nbsp;&nbsp;]<br>
                    <span>
                        Promoción confianza "A"
                    </span>
                </td>
                <td class="check center border-left-1" width="14%">[&nbsp;&nbsp;&nbsp;]<br>
                    <span>
                        Promoción confianza "B"
                    </span>
                </td>
                <td class="check center border-left-1" width="14%">[&nbsp;&nbsp;&nbsp;]<br>
                    <span>
                        Cambio de adscripción
                    </span>
                </td>
                <td class="check center border-left-1" width="15%">[&nbsp;&nbsp;&nbsp;]<br>
                    <span>
                        Cambio de residencia
                    </span>
                </td>

            </tr>
        </table>
    </div>

    <br>

    <div class="instructions border">
        <b>Instrucciones para el Jefe Inmediato:</b> Proporcione la Inducción al Área y al Puesto, siguiendo las indicaciones de la presente guía. Marque cada punto cumplido con una palomita.
    </div>

    <br>

    <div class="data indications border">
        <table class="border">
            <tr>
                <td width="5%"></td>
                <td class="bold uppercase">Inducción a la unidad de adscripción</td>
            </tr>
            <tr>
                <td class="center">[&nbsp;&nbsp;&nbsp;]</td>
                <td>De la <b>bienvenida</b> a la trabajadora o trabajador de nuevo ingreso a la Normativa o Delegación de adscripción.</td>
            </tr>
            <tr>
                <td class="center">[&nbsp;&nbsp;&nbsp;]</td>
                <td>Explique la organización de la Normativa o Delegación con el apoyo del organigrama y manual respectivo.</td>
            </tr>
            <tr>
                <td class="center">[&nbsp;&nbsp;&nbsp;]</td>
                <td>Describa brevemente los servicios que presta la Normativa o Delegación.</td>
            </tr>
            <tr>
                <td class="center">[&nbsp;&nbsp;&nbsp;]</td>
                <td>Realice un recorrido físico por las instalaciones de la Normativa o Delegación.</td>
            </tr>
            <tr>
                <td class="center">[&nbsp;&nbsp;&nbsp;]</td>
                <td>Presente a la trabajadora o trabajador con los y las responsables de otras áreas, en caso de tener trato con ellos.</td>
            </tr>
        </table>

        <table class="border">
            <tr>
                <td width="5%"></td>
                <td class="bold uppercase">Inducción al área de trabajo</td>
            </tr>
            <tr>
                <td class="center">[&nbsp;&nbsp;&nbsp;]</td>
                <td>Muestre el lugar de trabajo.</td>
            </tr>
            <tr>
                <td class="center">[&nbsp;&nbsp;&nbsp;]</td>
                <td>Presente a las compañeras y compañeros del grupo de trabajo.</td>
            </tr>
            <tr>
                <td class="center">[&nbsp;&nbsp;&nbsp;]</td>
                <td>Propicie un ambiente de cordialidad e integración de equipo.</td>
            </tr>
            <tr>
                <td class="center">[&nbsp;&nbsp;&nbsp;]</td>
                <td>Describa brevemente el trabajo del área y los procesos en los que están involucrados.</td>
            </tr>
            <tr>
                <td class="center">[&nbsp;&nbsp;&nbsp;]</td>
                <td>Comente el Reglamento Interior de Trabajo en cuanto a su repercusión en el área de trabajo.</td>
            </tr>
        </table>

        <table class="border">
            <tr>
                <td width="5%"></td>
                <td class="bold uppercase">Inducción al puesto</td>
            </tr>
            <tr>
                <td class="center">[&nbsp;&nbsp;&nbsp;]</td>
                <td>Muestre la ubicación orgánica del puesto de trabajo y su correspondencia estructural con la Normativa o Delegación.</td>
            </tr>
            <tr>
                <td class="center">[&nbsp;&nbsp;&nbsp;]</td>
                <td>Explique la misión del puesto.</td>
            </tr>
            <tr>
                <td class="center">[&nbsp;&nbsp;&nbsp;]</td>
                <td>Señale la visión que se desea del puesto.</td>
            </tr>
            <tr>
                <td class="center">[&nbsp;&nbsp;&nbsp;]</td>
                <td>Comente los manuales e instructivos de operación en los que esté involucrado el puesto de trabajo.</td>
            </tr>
            <tr>
                <td class="center">[&nbsp;&nbsp;&nbsp;]</td>
                <td>Explique el uso de formatos.</td>
            </tr>
            <tr>
                <td class="center">[&nbsp;&nbsp;&nbsp;]</td>
                <td>Adiestre en la utilización de equipos y materiales de trabajo.</td>
            </tr>
            <tr>
                <td class="center">[&nbsp;&nbsp;&nbsp;]</td>
                <td>Explique las instrucciones básicas de seguridad e higiene, así como los actos inseguros.</td>
            </tr>
            <tr>
                <td class="center">[&nbsp;&nbsp;&nbsp;]</td>
                <td>Motive a la trabajadora o trabajador para que formule preguntas y aclare dudas sobre sus actividades, la atención al usuario y las necesidades o expectativas a cubrir en relación a su puesto.</td>
            </tr>
        </table>

    </div>

    <div class="data border" style="width: 50%; border-top: none">
        <table class="border" style="border-top: none">
            <tr>
                <td class="center">Fecha de inducción</td>
                <td class="center border-left-1">Duración en horas</td>
            </tr>
            <tr>
                <td class="center bold"><?php echo $DATOS['FECHA_INDUCCION'] ?></td>
                <td class="center bold border-left-1"><?php echo $DATOS['DURACION'] ?></td>
            </tr>
        </table>
    </div>

    <br>

    <div class="data">
        <table>
            <tr>
                <td class="center" width="50%">
                    <div class="bold">Impartió (firma)</div>
                    <div class="mini">Al reverso registrar personal adicional que impartió</div>
                    <br><br><br>
                    <div style="border-top: 1px solid black; width: 75%; margin: auto;"></div>
                    <table class="center mini border-top-1">
                        <tr>
                            <td>Nombre: <span class="bold"><?php echo $DATOS['NOMBRE_INSTRUCTOR'] ?></span></td>
                        </tr>
                        <tr>
                            <td>Cargo: <span class="bold"><?php echo $DATOS['CARGO_INSTRUCTOR'] ?></span></td>
                        </tr>
                    </table>
                </td>
                <td class="center" width="50%">
                    <div class="bold">Recibió (firma)</div>
                    <div class="mini">&nbsp;</div>
                    <br><br><br>
                    <div style="border-top: 1px solid black; width: 75%; margin: auto;"></div>
                    <table class="mini">
                        <tr>
                            <td class="center">Nombre: <span class="bold"><?php echo $DATOS['NOMBRE_TRABAJADOR'] ?></span></td>
                        </tr>
                        <tr>
                            <td class="center">Cargo: <span class="bold"><?php echo $DATOS['CATEGORIA'] ?></span></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <br>

    <div class="mini" style="position:absolute; bottom:0; right:0;">Clave: 1CA0-009-008</div>

</body>

<?php

//
// Fin de generacíon de HTML
//

// Obtiene el codigo cacheado en una variable
$html = ob_get_clean();

// Carga el contenido HTML en objeto Dompdf
$pdf->loadHtml($html);

// Renderiza el documento PDF
$pdf->render();

// Envia el fichero PDF al navegador (Attachment => 0: para abrirse, 1: para descargarse)
$pdf->stream('testfile.pdf', ['Attachment' => 0]);
