// JavaScript para el modal de edición de expedientes

document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const modalEdicion = document.getElementById('modal_edicion_expediente');
    const cerrarModal = document.querySelector('.cerrar_modal_expediente');
    const formularioEdicion = modalEdicion.querySelector('form');
    
    // Campos del formulario
    const campoId = document.getElementById('campo_id_expediente');
    const campoTitulo = document.getElementById('campo_titulo_expediente');
    const campoDescripcion = document.getElementById('campo_descripcion_expediente');

    // Event listener para los botones de edición
    document.addEventListener('click', function(e) {
        // Verificar si el click fue en el botón de edición (icono lápiz)
        if (e.target.classList.contains('bi-pencil-square') || 
            (e.target.classList.contains('btn_accion') && e.target.querySelector('.bi-pencil-square'))) {
            
            e.preventDefault();
            e.stopPropagation();
            
            // Obtener el botón clickeado
            let botonEdicion;
            if (e.target.classList.contains('btn_accion')) {
                botonEdicion = e.target;
            } else {
                botonEdicion = e.target.closest('.btn_accion');
            }
            
            // Obtener el ID del expediente
            const idExpediente = botonEdicion.getAttribute('data-id');
            
            // Obtener la fila de la tabla
            const filaExpediente = botonEdicion.closest('tr');
            
            if (filaExpediente && idExpediente) {
                // Extraer datos de la fila
                const nombreCelda = filaExpediente.querySelector('.documento-nombre a');
                const nombreExpediente = nombreCelda ? nombreCelda.textContent.trim().replace(/^\s*📁\s*/, '').replace(/^\s*\s*/, '') : '';
                
                // Cargar datos en el modal
                cargarDatosEnModal(idExpediente, nombreExpediente);
                
                // Mostrar el modal
                mostrarModal();
            }
        }
    });

    // Función para cargar datos en el modal
    function cargarDatosEnModal(id, titulo) {
        campoId.value = id;
        campoTitulo.value = titulo;
        
        // Obtener descripción del servidor (opcional)
        // Si tienes un endpoint para obtener la descripción, puedes hacer una petición AJAX aquí
        obtenerDescripcionExpediente(id);
    }

    // Función para obtener descripción del expediente (AJAX)
    function obtenerDescripcionExpediente(idExpediente) {
        // Crear formulario de datos para enviar
        const formData = new FormData();
        formData.append('accion', 'obtener_expediente');
        formData.append('id_expediente', idExpediente);

        // Realizar petición AJAX
        fetch('../../backend/auditor/gestor_archivos_auditor.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.expediente) {
                campoDescripcion.value = data.expediente.descripcion || '';
            } else {
                // Si no se puede obtener la descripción, dejar el campo vacío
                campoDescripcion.value = '';
                console.warn('No se pudo obtener la descripción del expediente');
            }
        })
        .catch(error => {
            console.error('Error al obtener datos del expediente:', error);
            campoDescripcion.value = '';
        });
    }

    // Función para mostrar el modal
    function mostrarModal() {
        modalEdicion.style.display = 'flex';
        document.body.style.overflow = 'hidden'; // Prevenir scroll del body
        
        // Enfocar el primer campo
        setTimeout(() => {
            campoTitulo.focus();
            campoTitulo.select();
        }, 100);
    }

    // Función para cerrar el modal
    function cerrarModalEdicion() {
        modalEdicion.style.display = 'none';
        document.body.style.overflow = 'auto'; // Restaurar scroll del body
        
        // Limpiar formulario
        formularioEdicion.reset();
    }

    // Event listener para cerrar el modal
    cerrarModal.addEventListener('click', cerrarModalEdicion);

    // Cerrar modal al hacer click fuera de él
    modalEdicion.addEventListener('click', function(e) {
        if (e.target === modalEdicion) {
            cerrarModalEdicion();
        }
    });

    // Cerrar modal con la tecla Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modalEdicion.style.display === 'flex') {
            cerrarModalEdicion();
        }
    });

    // Validación del formulario antes del envío
    formularioEdicion.addEventListener('submit', function(e) {
        const titulo = campoTitulo.value.trim();
        const descripcion = campoDescripcion.value.trim();
        
        if (!titulo) {
            e.preventDefault();
            alert('El título es obligatorio');
            campoTitulo.focus();
            return false;
        }
        
        if (!descripcion) {
            e.preventDefault();
            alert('La descripción es obligatoria');
            campoDescripcion.focus();
            return false;
        }
        
        // Si todo está correcto, el formulario se enviará normalmente
        return true;
    });
});

// Función auxiliar para limpiar texto (remover iconos y espacios extra)
function limpiarTexto(texto) {
    return texto.replace(/[\u{1F4C1}\u{1F4C2}\u{1F4C4}]/gu, '') // Remover emojis de carpeta/documento
                .replace(/^\s+|\s+$/g, '') // Remover espacios al inicio y final
                .replace(/\s+/g, ' '); // Normalizar espacios múltiples
}