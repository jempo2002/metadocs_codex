let archivosSeleccionados = [];

const areaDivision = document.getElementById('area_division');
const inputDocumento = document.getElementById('input_documento');
const vistaPrevia = document.getElementById('vista_previa');
const nombreArchivo = document.getElementById('nombre_archivo');
const tamanoArchivo = document.getElementById('tamano_archivo');

const tiposPermitidos = {
    'application/pdf': 'PDF',
    'application/msword': 'DOC',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document': 'DOCX',
    'application/vnd.ms-excel': 'XLS',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': 'XLSX'
};

const extensionesPermitidas = ['.pdf', '.doc', '.docx', '.xls', '.xlsx'];
const tamanoMaximo = 10 * 1024 * 1024;

document.addEventListener('DOMContentLoaded', function() {
    inicializarEventos();
});

function inicializarEventos() {
    areaDivision.addEventListener('click', () => inputDocumento.click());
    areaDivision.addEventListener('dragover', manejarDragOver);
    areaDivision.addEventListener('dragleave', manejarDragLeave);
    areaDivision.addEventListener('drop', manejarDrop);
    
    inputDocumento.addEventListener('change', manejarSeleccionArchivos);
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        areaDivision.addEventListener(eventName, prevenirDefecto, false);
        document.body.addEventListener(eventName, prevenirDefecto, false);
    });
}

function prevenirDefecto(e) {
    e.preventDefault();
    e.stopPropagation();
}

function manejarDragOver(e) {
    e.preventDefault();
    areaDivision.classList.add('drag-over');
}

function manejarDragLeave(e) {
    e.preventDefault();
    areaDivision.classList.remove('drag-over');
}

function manejarDrop(e) {
    e.preventDefault();
    areaDivision.classList.remove('drag-over');
    
    const archivos = e.dataTransfer.files;
    procesarArchivos(archivos);
}

function manejarSeleccionArchivos(e) {
    const archivos = e.target.files;
    procesarArchivos(archivos);
}

function procesarArchivos(archivos) {
    if (archivos.length === 0) return;
    
    const archivo = archivos[0];
    
    if (validarArchivo(archivo)) {
        archivosSeleccionados = [archivo];
        mostrarVistaPrevia(archivo);
    }
}

function validarArchivo(archivo) {
    if (archivo.size > tamanoMaximo) {
        mostrarError(`El archivo "${archivo.name}" es demasiado grande. Máximo ${formatearTamano(tamanoMaximo)} permitido.`);
        return false;
    }
    
    if (!tiposPermitidos[archivo.type]) {
        const extension = obtenerExtension(archivo.name).toLowerCase();
        if (!extensionesPermitidas.includes(extension)) {
            mostrarError(`Tipo de archivo no soportado. Solo se permiten: ${extensionesPermitidas.join(', ')}`);
            return false;
        }
    }
    
    if (archivo.size === 0) {
        mostrarError('El archivo parece estar vacío o corrupto.');
        return false;
    }
    
    return true;
}

function mostrarVistaPrevia(archivo) {
    nombreArchivo.textContent = archivo.name;
    tamanoArchivo.textContent = formatearTamano(archivo.size);
    
    vistaPrevia.style.display = 'flex';
    areaDivision.style.display = 'none';
}

function removerArchivo() {
    archivosSeleccionados = [];
    vistaPrevia.style.display = 'none';
    areaDivision.style.display = 'block';
    inputDocumento.value = '';
    
    limpiarErrores();
}

function formatearTamano(bytes) {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const tamaños = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + tamaños[i];
}

function obtenerExtension(nombreArchivo) {
    return nombreArchivo.substring(nombreArchivo.lastIndexOf('.'));
}

function mostrarError(mensaje) {
    let elementoError = document.getElementById('error_archivo');
    
    if (!elementoError) {
        elementoError = document.createElement('div');
        elementoError.id = 'error_archivo';
        elementoError.className = 'mensaje-error-archivo';
        elementoError.style.cssText = `
            background: #fed7d7;
            color: #c53030;
            padding: 12px 16px;
            border-radius: 8px;
            margin: 16px 0;
            border: 1px solid #feb2b2;
            font-size: 14px;
            display: none;
        `;
        areaDivision.parentNode.insertBefore(elementoError, areaDivision.nextSibling);
    }
    
    elementoError.textContent = mensaje;
    elementoError.style.display = 'block';
    
    setTimeout(() => {
        if (elementoError) {
            elementoError.style.display = 'none';
        }
    }, 5000);
    
    areaDivision.style.borderColor = '#e53e3e';
    setTimeout(() => {
        areaDivision.style.borderColor = '#cbd5e0';
    }, 3000);
}

function limpiarErrores() {
    const elementoError = document.getElementById('error_archivo');
    if (elementoError) {
        elementoError.style.display = 'none';
    }
    areaDivision.style.borderColor = '#cbd5e0';
}

window.removerArchivo = removerArchivo;