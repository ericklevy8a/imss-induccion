<?php

// Cargar configuraciones basicas según entorno de ejecución (DEVELOPMENT, PRODUCTION)
require_once('./config.php');

// Cargar librería dompdf
require('../vendor/autoload.php');

use Dompdf\Dompdf;

// Instanciar objeto de la clase Dompdf
$pdf = new Dompdf();

// Ajustar la raíz del documento
$pdf->getOptions()->setChroot($CHROOT);
// Habilitar elementos remotos (Google fonts)
$pdf->getOptions()->setIsRemoteEnabled(true);

// Definir tamaño y orientación del medio (papel)
$pdf->setPaper("letter", "portrait");

// Inicia captura de cache de salida
ob_start();

//
// Obtener datos
//

$DATOS = [
    'NOMBRE_UNIDAD'     => $_REQUEST['NOMBRE_UNIDAD'] ?? '',
    'FECHA_ELABORA'     => $_REQUEST['FECHA_ELABORA'] ?? '',
    'MATRICULA'         => $_REQUEST['MATRICULA'] ?? '',
    'NOMBRE_TRABAJADOR' => $_REQUEST['NOMBRE_TRABAJADOR'] ?? '',
    'CATEGORIA'         => $_REQUEST['CATEGORIA'] ?? '',
    'TIPO_COMPUESTO'    => $_REQUEST['TIPO_COMPUESTO'] ?? '',
    'ADSCRIPCION'       => $_REQUEST['ADSCRIPCION'] ?? '',
    'FECHA_INGRESO'     => $_REQUEST['FECHA_INGRESO'] ?? '',
    'FECHA_INDUCCION'   => $_REQUEST['FECHA_INDUCCION'] ?? '',
    'DURACION'          => $_REQUEST['DURACION'] ?? '',
    'NOMBRE_INSTRUCTOR' => $_REQUEST['NOMBRE_INSTRUCTOR'] ?? '',
    'CARGO_INSTRUCTOR'  => $_REQUEST['CARGO_INSTRUCTOR'] ?? ''
];

//
// Generar HTML
//

?>

<style>
    @font-face {
        font-family: 'Montserrat';
        font-weight: normal;
        font-style: normal;
        font-variant: normal;
        src: url('../fonts/Montserrat-Regular.ttf') format('truetype');
    }

    @font-face {
        font-family: 'Montserrat';
        font-weight: bold;
        font-style: normal;
        font-variant: normal;
        src: url('../fonts/Montserrat-Bold.ttf') format('truetype');
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

    .left {
        text-align: left;
    }

    .center {
        text-align: center;
    }

    .right {
        text-align: right;
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
                    <img src="../img/logo-imss.png" height="36" alt="">
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
                        <td class="bold uppercase" width="10%" rowspan="2">
                            <nobr>
                                <h2>OOAD Colima</h2>
                            </nobr>
                        </td>
                        <td class="center">Unidad</td>
                        <td class="border-left-1" width="20%">Fecha de elaboración</td>
                    </tr>
                    <tr>
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
                <td class="">Matrícula</td>
                <td class="border-left-1" colspan="2">Nombre del trabajador</td>
            </tr>
            <tr>
                <td class="center bold border-bottom-1"><?php echo $DATOS['MATRICULA'] ?></td>
                <td class="center bold border-bottom-1 border-left-1" colspan="2"><?php echo $DATOS['NOMBRE_TRABAJADOR'] ?></td>
            </tr>
            <tr>
                <td width="33%">Categoría</td>
                <td class="border-left-1">Adscripción</td>
                <td class="border-left-1" width="20%">Fecha de ingreso</td>
            </tr>
            <tr>
                <td class="center bold"><?php echo $DATOS['CATEGORIA'] ?></td>
                <td class="center bold border-left-1"><?php echo $DATOS['ADSCRIPCION'] ?></td>
                <td class="center bold border-left-1"><?php echo $DATOS['FECHA_INGRESO'] ?></td>
            </tr>
        </table>

        <table class="border">
            <tr>
                <td class="center border-bottom-1" colspan="7">
                    <table>
                        <tr>
                            <td class="left">Tipo de movimiento: <span class="bold uppercase"><?php echo $DATOS['TIPO_COMPUESTO'] ?></span></td>
                            <td class="right"><span class="mini">(MARCAR CON UNA "X" EL RUBRO CORRESPONDIENTE)</span></td>
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
        <table>
            <tr style="vertical-align: top;">
                <td class="right">
                    <nobr>Instrucciones para el Jefe Inmediato:&nbsp;</nobr>
                </td>
                <td class="left bold">Proporcione la Inducción al Área y al Puesto, siguiendo las indicaciones de la presente guía. Marque cada punto cumplido con una palomita.</td>
            </tr>
        </table>
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
                <td>Dé la bienvenida a la trabajadora o trabajador de nuevo ingreso a la Normativa o Delegación de Adscripción.</td>
            </tr>
            <tr>
                <td class="center">[&nbsp;&nbsp;&nbsp;]</td>
                <td>Explique la organización de la Normativa o Delegación con el apoyo del organigrama o manual respectivo.</td>
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

    <div class="data border" style="border-top: none">
        <table class="border" style="border-top: none">
            <tr>
                <td>Fecha de inducción:</td>
                <td class="border-left-1">Duración en horas:</td>
            </tr>
        </table>
    </div>

    <br>

    <div class="data">
        <table>
            <tr>
                <td class="center" width="50%">
                    <table>
                        <tr>
                            <td colspan="2" class="bold center">Impartió (firma)</td>
                        </tr>
                        <tr>
                            <td colspan="2" class="mini center">Al reverso registrar personal adicional que impartió</td>
                        </tr>
                        <tr>
                            <td colspan="2"><br><br><br><br></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div style="border-top: 1px solid black; width: 100%; margin: auto;"></div>
                            </td>
                        </tr>
                        <tr>
                            <td class="right" width="50">Nombre:</td>
                            <td><span class="bold"><?php echo $DATOS['NOMBRE_INSTRUCTOR'] ?></span></td>
                        </tr>
                        <tr>
                            <td class="right" width="50">Cargo:</td>
                            <td><span class="bold"><?php echo $DATOS['CARGO_INSTRUCTOR'] ?></span></td>
                        </tr>
                    </table>
                </td>
                <td class="center" width="50%">
                    <table>
                        <tr>
                            <td colspan="2" class="bold center">Recibió (firma)</td>
                        </tr>
                        <tr>
                            <td colspan="2" class="mini center">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="2"><br><br><br><br></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div style="border-top: 1px solid black; width: 100%; margin: auto;"></div>
                            </td>
                        </tr>
                        <tr>
                            <td class="right" width="50">Nombre:</td>
                            <td><span class="bold"><?php echo $DATOS['NOMBRE_TRABAJADOR'] ?></span></td>
                        </tr>
                        <tr>
                            <td class="right" width="50">Cargo:</td>
                            <td><span class="bold"><?php echo $DATOS['CATEGORIA'] ?></span></td>
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
// Fin de generar HTML
//

// Obtener el codigo cacheado en una variable
$html = ob_get_clean();

// Cargar el contenido HTML en objeto Dompdf
$pdf->loadHtml($html);

// Renderizar el documento PDF
$pdf->render();

// Enviar el fichero PDF al navegador (Attachment => 0: para abrirse[1: para descargarse)
$pdf->stream('guia-induccion.pdf', ['Attachment' => 0]);
