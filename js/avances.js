//
// SCRIPT AUXILIAR PARA LA INTERACCION CON LOS SERVICIOS
// GENERADORES DE GRAFICAS, TABLAS, LISTADOS Y GUIAS DE INDUCCION PRE-LLENADAS
//

const baseUrl = "";

// Desplegar barra con mensaje
function showMsgBar(container, msg, type = 'info') {
    const iconName = (type === 'warn' ? 'warning' : type);
    let html = `<div class="msg-bar msg-${type}" onClick="this.parentElement.innerHTML=''">`;
    html += `<span class="material-icons md-24">${iconName}</span>`;
    html += msg;
    html += '</div>';
    container.innerHTML = html;
}

// Referencias a objetos del DOM
const selAdscripcion = document.getElementById('adscripcion');
const btnBuscar = document.getElementById('buscar');
const divRespuesta = document.getElementById('respuesta');
const divGuias = document.getElementById('guias');
const spanFecha = document.getElementById('fecha');

const divHospChart = document.getElementById('hosp-chart');
const divUMFsChart = document.getElementById('umfs-chart');
const divSubsChart = document.getElementById('subs-chart');

// Manejador del cambio de matrícula
selAdscripcion.onchange = function () {
    // Habilitar boton de buscar
    btnBuscar.removeAttribute('disabled');
    // Eliminar mensajes y tabla
    divRespuesta.innerHTML = '';
    divGuias.innerHTML = '';
}

// Manejador del click de buscar
btnBuscar.onclick = function () {
    // Deshabilitar boton de buscar
    btnBuscar.setAttribute('disabled', 'disabled');
    // Validar valor de matrícula
    const adscripcion = selAdscripcion.value;
    const valid = adscripcion != '0';
    if (valid) {
        // Buscar guias
        fetchGuias(adscripcion);
    } else {
        // Desplegar advertencia
        showMsgBar(divRespuesta, 'Se debe seleccionar una adscripción válida!', 'warn');
    }
}

// Obtener la fecha
function fetchFecha() {
    const url = baseUrl + 'services/get_avances.php?tipo=f';
    fetch(url)
        .then(res => res.json())
        .then(data => {
            const fecha = new Date(data[0]['Fecha']).toLocaleDateString('es-mx', { "dateStyle": "full" });
            spanFecha.innerHTML = fecha;
        })
        .catch(err => {
            showMsgBar(spanFecha, `Ocurrió un error (${err})`, 'error');
            throw err;
        });
}

// Buscar datos de avances
function fetchAvances(container, dispFunct, tipo) {
    const url = baseUrl + 'services/get_avances.php?tipo=' + tipo;
    fetch(url)
        .then(res => res.json())
        .then(data => {
            if (data.length === 0) {
                showMsgBar(container, 'No se encontraron datos!', 'info');
                return;
            }
            dispFunct(container, data);
        })
        .catch(err => {
            showMsgBar(container, `Ocurrió un error (${err})`, 'error');
            throw err;
        });
}

// Desplegar tabla de avances
function showAvances(container, data) {
    let html = '<table class="avances">';
    html += '<tr>';
    html += '<th>Adscripción</th>';
    html += '<th>Guías Asignadas</th>';
    html += '<th>Inducciones Realizadas</th>';
    html += '</tr>';
    for (let r = 0; r < data.length; r++) {
        html += '<tr>';
        html += `<td>${data[r]['Adscripción']}</td>`;
        html += `<td style="text-align: right">${data[r]['Asignadas']}</td>`;
        html += `<td style="text-align: right">${data[r]['Realizadas']}</td>`;
        html += '</tr>';
    }
    html += '</table>';
    container.innerHTML = html;
}

// LISTA DE ADSCRIPCIONES

// Buscar adscripciones para llenar la lista de selección
function fetchAdscripciones() {
    const url = baseUrl + 'services/get_adscripciones.php';
    fetch(url)
        .then(res => res.json())
        .then(data => {
            if (data.length === 0) {
                showMsgBar(divRespuesta, 'No se encontraron adscripciones con guias pendientes!', 'info');
                return;
            }
            populateOptions(data);
        })
        .catch(err => {
            showMsgBar(divRespuesta, `Ocurrió un error (${err})`, 'error');
            throw err;
        });
}

// Llenar lista de adscripciones
function populateOptions(data) {
    html = '<option value="0">Seleccionar la adscripción a reportar</option>';
    for (let r = 0; r < data.length; r++) {
        html += `<option value="${data[r]['Adscripción']}">`;
        html += `${data[r]['Adscripción']} (${data[r]['Guías']})`;
        html += '</option>';
    }
    selAdscripcion.innerHTML = html;
}

// TABLA DE GUIAS DE INDUCCION PENDIENTES POR ADSCRIPCION

// Buscar guias de induccion para una adscripcion dada
function fetchGuias(adscripcion) {
    const url = baseUrl + 'services/get_adscripciones.php?adscripcion=' + encodeURI(adscripcion);
    fetch(url)
        .then(res => res.json())
        .then(data => {
            if (data.length === 0) {
                showMsgBar(divRespuesta, 'No se encontraron guías de inducciones para esa adscripción!', 'info');
                return;
            }
            showGuias(data);
        })
        .catch(err => {
            showMsgBar(divRespuesta, `Ocurrió un error (${err})`, 'error');
            throw err;
        });
}

// Desplegar tabla de guias de induccion
function showGuias(data) {
    showMsgBar(divRespuesta, 'Se encontraron las siguientes guías de inducciones pendientes:', 'info');
    let html = '<table class="inducciones">';
    html += '<tr>';
    html += '<th>#</th>';
    html += '<th>Fecha</th>';
    html += '<th>Nombre</th>';
    html += '<th>Categoría</th>';
    html += '<th>Acciones</th>';
    html += '</tr>';
    for (let r = 0; r < data.length; r++) {
        const url = makeURL(data[r]);
        html += '<tr>';
        html += `<td align="center">${r + 1}</td>`;
        html += `<td>${data[r]['Quincena']}</td>`;
        html += `<td>${(data[r]['Nombre']).replace(/\//g, ' ')}</td>`;
        html += `<td>${data[r]['Categoría']}</td>`;
        html += `<td><div class="acciones">`
        html += `<a class="download ver" href="${url}" target="_blank"><span class="material-icons md-24">download</span></a>`;
        html += `<a class="download disabled" href="#"><span class="material-icons md-24">upload_file</span></a>`;
        html += `</div></td>`;
        html += '</tr>';
    }
    html += '</table>';
    divGuias.innerHTML = html;
}

// Formar enlaces al generador de PDF con el id de la guia
function makeURL(row) {
    let url = baseUrl + 'services/get_pdf.php';
    url += '?id=' + encodeURIComponent(row['id']);
    return url;
}

// GRAFICOS

function showHospChart(container, data) {
    const pointsS1 = data.map((item, i) => new Object({ x: i + 1, y: parseInt(item["Asignadas"]), label: item["Adscripción"] }));
    const pointsS2 = data.map((item, i) => new Object({ x: i + 1, y: parseInt(item["Realizadas"]), label: item["Adscripción"] }));
    var chart = new CanvasJS.Chart(container.id, {
        animationEnabled: true,
        backgroundColor: "#fff0",
        title: {
            text: "HOSPITALES",
            fontFamily: "Calibri",
            fontWeight: "bolder"
        },
        data: [
            {
                type: "bar",
                showInLegend: true,
                name: "Realizadas",
                color: "#b9cd96",
                dataPoints: pointsS2
            },
            {
                type: "bar",
                showInLegend: true,
                name: "Asignadas",
                color: "#89a54e",
                dataPoints: pointsS1
            },
        ]
    });
    chart.render();
}

function showUMFsChart(container, data) {
    const pointsS1 = data.map((item, i) => new Object({ x: i + 1, y: parseInt(item["Asignadas"]), label: item["Adscripción"] }));
    const pointsS2 = data.map((item, i) => new Object({ x: i + 1, y: parseInt(item["Realizadas"]), label: item["Adscripción"] }));
    var chart = new CanvasJS.Chart(container.id, {
        animationEnabled: true,
        backgroundColor: "#fff0",
        title: {
            text: "UMFs",
            fontFamily: "Calibri",
            fontWeight: "bolder"
        },
        data: [
            {
                type: "column",
                showInLegend: true,
                name: "Realizadas",
                color: "#b9cd96",
                dataPoints: pointsS2
            },
            {
                type: "column",
                showInLegend: true,
                name: "Asignadas",
                color: "#89a54e",
                dataPoints: pointsS1
            },
        ]
    });
    chart.render();
}

function showSubsChart(container, data) {
    const pointsS1 = data.map((item, i) => new Object({ x: i + 1, y: parseInt(item["Asignadas"]), label: item["Adscripción"] }));
    const pointsS2 = data.map((item, i) => new Object({ x: i + 1, y: parseInt(item["Realizadas"]), label: item["Adscripción"] }));
    var chart = new CanvasJS.Chart(container.id, {
        animationEnabled: true,
        backgroundColor: "#fff0",
        title: {
            text: "SUBDELEGACIONES",
            fontFamily: "Calibri",
            fontWeight: "bolder"
        },
        data: [
            {
                type: "bar",
                showInLegend: true,
                name: "Realizadas",
                color: "#b9cd96",
                dataPoints: pointsS2
            },
            {
                type: "bar",
                showInLegend: true,
                name: "Asignadas",
                color: "#89a54e",
                dataPoints: pointsS1
            },
        ]
    });
    chart.render();
}

// INICIALIZACION DE LA PAGINA

// Inicializae la fecha de actualización de la base de datos
fetchFecha();

// Generar graficas
fetchAvances(divHospChart, showHospChart, 'h');
fetchAvances(divUMFsChart, showUMFsChart, 'u');
fetchAvances(divSubsChart, showSubsChart, 's');

// Inicializar el control para la selección de adscripciones tras cargar la pagina
fetchAdscripciones();
