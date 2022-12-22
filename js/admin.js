// DOM objects
const form = document.getElementById('form-file');
const fileSel = document.getElementById('file');
const btnSubmit = document.getElementById('btn-submit');
const btnCancel = document.getElementById('btn-cancel');
const spanStatus = document.getElementById('upload-status');

const MAX_FILESIZE = 5 * 1024 * 1024;

function admin_carga(form) {


    spanStatus.innerHTML = '<span class="msg-bar msg-info">Iniciando carga de archivo...</span>';

    btnSubmit.classList.add('hide');
    btnCancel.classList.remove('hide');

    // peticion
    let peticion = new XMLHttpRequest();

    // progreso
    peticion.upload.addEventListener("progress", (event) => {
        let percent = Math.round(100 * event.loaded / event.total);
        spanStatus.innerHTML = '<span class="msg-bar msg-info">' + percent + '%' + '</span>';
    });

    // terminar
    peticion.addEventListener("load", () => {
        spanStatus.innerHTML = '<span class="msg-bar msg-info">Carga de archivo terminada!</span>';
        btnSubmit.classList.add('hide');
        btnCancel.classList.add('hide');
    });

    // cancelar
    btnCancel.addEventListener("click", () => {
        peticion.abort();
        spanStatus.innerHTML = '<span class="msg-bar msg-error">Carga de archivo cancelada!</span>';
        btnSubmit.classList.remove('hide');
        btnCancel.classList.add('hide');
    });

    // enviar
    peticion.open('POST', '../services/put_file.php');
    peticion.send(new FormData(form));
    //peticion.responseType = 'json';
    peticion.onload = () => {
        if (peticion.readyState == 4 && peticion.status == 200) {
            const data = peticion.response;
            console.log(data);
            spanStatus.innerHTML = '<span class="msg-bar msg-info">Carga de archivo completada (' + data + ')!</span>';
        }
    };
}

// detecta seleccion de archivo
fileSel.addEventListener('change', (event) => {
    spanStatus.innerHTML = '';
    btnSubmit.classList.add('hide');
    btnCancel.classList.add('hide');
    if (event.target.files.length === 1) {
        if (event.target.files[0].size > MAX_FILESIZE) {
            spanStatus.innerHTML = '<span class="msg-bar msg-error">El archivo no debe superar los 5 MB!</span>';
        } else {
            btnSubmit.classList.remove('hide');
            btnCancel.classList.add('hide');
        }
    } else {
        spanStatus.innerHTML = '<span class="msg-bar msg-error">Se debe seleccionar UN archivo!</span>';
    }
});

// intercepta envio de formulario
form.addEventListener('submit', function (event) {
    event.preventDefault();
    admin_carga(this);
});

// Fin del listado