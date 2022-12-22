//
// SCRIPT PARA LA GENERACION DE GUIAS DE INDUCCION PRE-LLENADAS
//

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

// Buscar guias de induccion para una matrícula dada
function fetchData(matricula) {
    const url = './services/get_guias.php?matricula=' + encodeURI(matricula);
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
    html += '<th>Fecha</th>';
    html += '<th>Adscripción</th>';
    html += '<th>Categoría</th>';
    html += '<th>Acciones</th>';
    html += '</tr>';
    for (let r = 0; r < data.length; r++) {
        const url = makeURL(data[r]);
        html += '<tr>';
        html += `<td align="center">${r + 1}</td>`;
        html += `<td>${data[r]['Quincena']}</td>`;
        html += `<td>${data[r]['Adscripción']}</td>`;
        html += `<td>${data[r]['Categoría']}</td>`;
        html += `<td><a class="download ver" href="${url}" target="_blank">Ver</a></td>`;
        html += '</tr>';
    }
    html += '</table>';
    divGuias.innerHTML = html;
}

// Formar enlaces al generador de PDF con los datos de la guía
function makeURL(row) {
    let url = 'getpdf.php';
    url += '?ADSCRIPCION=' + encodeURI(row['Adscripción']);
    url += '&NOMBRE_UNIDAD=' + encodeURI(row['Adscripción']);
    url += '&CATEGORIA=' + encodeURI(row['Categoría']);
    url += '&MATRICULA=' + encodeURI(row['Matrícula']);
    url += '&NOMBRE_TRABAJADOR=' + encodeURI(row['Nombre']);
    url += '&FECHA_INGRESO=' + encodeURI(row['Quincena']);
    url += '&TIPO_COMPUESTO=' + encodeURI(row['Tipo Compuesto']);
    return url;
}
