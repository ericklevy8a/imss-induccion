//
// SCRIPT AUXILIAR PARA LA INTERACCION CON LOS SERVICIOS
// DE RECEPCION DE ARCHIVOS CSV
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

// DOM objects
const form = document.getElementById('form-file');
const fileSel = document.getElementById('file');
const btnSubmit = document.getElementById('btn-submit');
const btnCancel = document.getElementById('btn-cancel');
const uploadStatus = document.getElementById('upload-status');

const MAX_FILESIZE = 5 * 1024 * 1024;

// Administrar el envio o carga del archivo
function admin_carga(form) {

    uploadStatus.innerHTML = 'Iniciando carga de archivo...';

    btnSubmit.classList.add('hide');
    btnCancel.classList.remove('hide');

    // peticion
    let peticion = new XMLHttpRequest();

    // progreso
    peticion.upload.addEventListener("progress", (event) => {
        const percent = Math.round(100 * event.loaded / event.total);
        const progressBar = document.querySelector('#file-progress .progress-bar');
        if (!progressBar) {
            const content = `<div id="file-progress" class="progress-border"><span class="progress-bar"></span>`;
            uploadStatus.innerHTML = content;
        }
        progressBar.style.width = percent + '%';
        progressBar.style.innerHTML = percent + '%';
    });

    // terminar
    peticion.addEventListener("load", () => {
        uploadStatus.innerHTML = 'Carga de archivo terminada! Procesando registros...';
        btnSubmit.classList.add('hide');
        btnCancel.classList.add('hide');
    });

    // cancelar
    btnCancel.addEventListener("click", () => {
        peticion.abort();
        const msg = 'Carga de archivo cancelada!';
        showMsgBar(uploadStatus, msg, 'warn');
        btnSubmit.classList.remove('hide');
        btnCancel.classList.add('hide');
    });

    // enviar
    peticion.open('POST', baseUrl + 'services/put_file.php');
    peticion.send(new FormData(form));
    peticion.onload = () => {
        if (peticion.readyState == 4 && peticion.status == 200) {
            const data = peticion.response;
            const msg = `Procesamiento del archivo completado (${data})`;
            showMsgBar(uploadStatus, msg, 'info');
        }
    };
}

// Detectar seleccion de archivo
fileSel.addEventListener('change', (event) => {
    uploadStatus.innerHTML = '';
    btnSubmit.classList.add('hide');
    btnCancel.classList.add('hide');
    if (event.target.files.length === 1) {
        if (event.target.files[0].size > MAX_FILESIZE) {
            const msg = 'El archivo no debe superar los 5 MB!';
            showMsgBar(uploadStatus, msg, 'error');
        } else {
            btnSubmit.classList.remove('hide');
            btnCancel.classList.add('hide');
        }
    } else {
        const msg = 'Se debe seleccionar un archivo!';
        showMsgBar(uploadStatus, msg, 'error');
    }
});

// Interceptar envio del formulario (archivo)
form.addEventListener('submit', function (event) {
    event.preventDefault();
    admin_carga(this);
});

// Fin del listado