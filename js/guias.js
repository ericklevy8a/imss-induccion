//
// SCRIPT DE RUTINAS AUXILIARES PARA LA INTERACCION CON LOS SERVICIOS
// PARA LA GENERACION DE TABLAS Y GUIAS DE INDUCCION PRE-LLENADAS
//

const baseUrl = "";

// Referencias a objetos del DOM
const txtMatricula = document.getElementById('matricula');
const btnBuscar = document.getElementById('buscar');
const divRespuesta = document.getElementById('respuesta');
const divGuias = document.getElementById('guias');

// Manejador del cambio de matrícula
txtMatricula.oninput = function () {
    // Habilitar boton de buscar
    btnBuscar.removeAttribute('disabled');
    // Eliminar mensajes y tabla
    divRespuesta.innerHTML = '';
    divGuias.innerHTML = '';
}

// Manejador de la tecla enter dentro del cuadro de texto
txtMatricula.onkeypress = function (evt) {
    if (evt.key === "Enter") {
        evt.preventDefault();
        btnBuscar.click();
    }
}

// Manejador del click de buscar
btnBuscar.onclick = function () {
    // Deshabilitar boton de buscar
    btnBuscar.setAttribute('disabled', 'disabled');
    // Validar valor de matrícula
    const matricula = txtMatricula.value;
    const regex = new RegExp('^[0-9]{7,9}$');
    const valid = regex.test(matricula);
    if (valid) {
        // Buscar guias
        fetchData(matricula);
    } else {
        // Desplegar error
        let html = '<span class="msg-bar msg-warn">';
        html += 'Se debe especificar una matrícula de trabajador válida!';
        html += '</span>';
        divRespuesta.innerHTML = html;
    }
}

// Buscar guias de induccion para una matricula dada
function fetchData(matricula) {
    const url = baseUrl + 'services/get_guias.php?matricula=' + encodeURI(matricula);
    fetch(url)
        .then(res => res.json())
        .then(data => {
            if (data.length === 0) {
                let html = '<span class="msg-bar msg-info">';
                html += 'No se encontraron guías de inducciones para esa matrícula!';
                html += '</span>';
                divRespuesta.innerHTML = html;
                return;
            }
            showTable(data);
        })
        .catch(err => {
            let html = '<span class="msg-bar msg-error">';
            html += 'Ocurrió un error desconocido!';
            html += '</span>';
            divRespuesta.innerHTML = html;
            throw err;
        });
}

// Desplegar tabla de inducciones
function showTable(data) {
    let html = '<span class="msg-bar msg-info">';
    html += 'Se han encontrado guías de inducciones pendientes:';
    html += '</span>';
    divRespuesta.innerHTML = html;
    html = '<table class="inducciones">';
    html += '<tr>';
    html += '<th>#</th>';
    html += '<th>Nombre</th>';
    html += '<th>Fecha</th>';
    html += '<th>Adscripción</th>';
    html += '<th>Categoría</th>';
    html += '<th>Acciones</th>';
    html += '</tr>';
    for (let r = 0; r < data.length; r++) {
        const url = makeURL(data[r]);
        html += '<tr>';
        html += `<td align="center">${r + 1}</td>`;
        html += `<td>${data[r]['Nombre']}</td>`;
        html += `<td>${data[r]['Quincena']}</td>`;
        html += `<td>${data[r]['Adscripción']}</td>`;
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

// Formar enlaces al generador de PDF con los datos de la guía
function makeURL(row) {
    let url = baseUrl + 'services/get_pdf.php';
    url += '?id=' + encodeURIComponent(row['id']);
    /*
    url += '?ADSCRIPCION=' + encodeURIComponent(row['Adscripción']);
    url += '&NOMBRE_UNIDAD=' + encodeURIComponent(row['Adscripción']);
    url += '&CATEGORIA=' + encodeURIComponent(row['Categoría']);
    url += '&MATRICULA=' + encodeURIComponent(row['Matrícula']);
    url += '&NOMBRE_TRABAJADOR=' + encodeURIComponent(row['Nombre']);
    url += '&FECHA_INGRESO=' + encodeURIComponent(row['Quincena']);
    url += '&TIPO_COMPUESTO=' + encodeURIComponent(row['Tipo Compuesto']);
    */
    return url;
}
