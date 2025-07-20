    // PASO 12: JAVASCRIPT PARA MANEJAR LOS MODALES DINÁMICAMENTE
        
        function abrirModal(modalId, datosNotificacion) {
            const modal = document.getElementById(modalId);
            if (!modal) return;
            
            // Llenar datos específicos según el tipo de modal
            switch(modalId) {
                case 'modal-solicitud-documento':
                    llenarModalSolicitud(datosNotificacion);
                    break;
                case 'modal-documento-aprobado':
                    llenarModalDocumentoAprobado(datosNotificacion);
                    break;
                case 'modal-documento-rechazado':
                    llenarModalDocumentoRechazado(datosNotificacion);
                    break;
                case 'modal-expediente-aprobado':
                    llenarModalExpedienteAprobado(datosNotificacion);
                    break;
                case 'modal-expediente-rechazado':
                    llenarModalExpedienteRechazado(datosNotificacion);
                    break;
            }
            
            modal.style.display = 'flex';
            
            // Marcar como visto
            marcarComoVisto(datosNotificacion.id);
        }
        
        function llenarModalSolicitud(datos) {
            document.getElementById('modal-solicitud-usuario').textContent = datos.usuario_nombre;
            document.getElementById('modal-solicitud-solicitante').textContent = datos.usuario_nombre;
            document.getElementById('modal-solicitud-categoria').textContent = datos.datos.categoria || 'No especificada';
            document.getElementById('modal-solicitud-expediente').textContent = datos.datos.expediente_destino || 'No especificado';
            document.getElementById('modal-solicitud-fecha').textContent = formatearFecha(datos.fecha_creacion);
            document.getElementById('modal-solicitud-detalles').textContent = datos.datos.texto || 'Sin detalles adicionales';
        }
        // FUNCIONES ACTUALIZADAS PARA LLENAR LOS MODALES CON INFORMACIÓN DEL RESPONSABLE
// SOLUCIÓN SIMPLE - Reutilizar el nombre del usuario que ya tenemos

function llenarModalDocumentoAprobado(datos) {
    document.getElementById('modal-doc-aprobado-usuario').textContent = datos.usuario_nombre;
    document.getElementById('modal-doc-aprobado-titulo').textContent = datos.datos.titulo_documento || 'Sin título';
    document.getElementById('modal-doc-aprobado-categoria').textContent = datos.datos.categoria || 'No especificada';
    document.getElementById('modal-doc-aprobado-expediente').textContent = datos.datos.expediente_destino || 'No especificado';
    document.getElementById('modal-doc-aprobado-fecha').textContent = formatearFecha(datos.fecha_creacion);
    
    // USAR EL MISMO NOMBRE DEL USUARIO (quien aprobó)
    document.getElementById('modal-doc-aprobado-por').textContent = datos.usuario_nombre;
}

function llenarModalDocumentoRechazado(datos) {
    document.getElementById('modal-doc-rechazado-usuario').textContent = datos.usuario_nombre;
    document.getElementById('modal-doc-rechazado-titulo').textContent = datos.datos.titulo_documento || 'Sin título';
    document.getElementById('modal-doc-rechazado-categoria').textContent = datos.datos.categoria || 'No especificada';
    document.getElementById('modal-doc-rechazado-fecha').textContent = formatearFecha(datos.fecha_creacion);
    document.getElementById('modal-doc-rechazado-motivo').textContent = datos.datos.motivo || 'Sin motivo especificado';
    
    // USAR EL MISMO NOMBRE DEL USUARIO (quien rechazó)
    document.getElementById('modal-doc-rechazado-por').textContent = datos.usuario_nombre;
}

function llenarModalExpedienteAprobado(datos) {
    document.getElementById('modal-exp-aprobado-usuario').textContent = datos.usuario_nombre;
    document.getElementById('modal-exp-aprobado-titulo').textContent = datos.datos.titulo_expediente || 'Sin título';
    document.getElementById('modal-exp-aprobado-fecha').textContent = formatearFecha(datos.fecha_creacion);
    
    // USAR EL MISMO NOMBRE DEL USUARIO (quien aprobó)
    document.getElementById('modal-exp-aprobado-por').textContent = datos.usuario_nombre;
}

function llenarModalExpedienteRechazado(datos) {
    document.getElementById('modal-exp-rechazado-usuario').textContent = datos.usuario_nombre;
    document.getElementById('modal-exp-rechazado-titulo').textContent = datos.datos.titulo_expediente || 'Sin título';
    document.getElementById('modal-exp-rechazado-fecha').textContent = formatearFecha(datos.fecha_creacion);
    document.getElementById('modal-exp-rechazado-motivo').textContent = datos.datos.motivo || 'Sin motivo especificado';
    
    // USAR EL MISMO NOMBRE DEL USUARIO (quien rechazó)
    document.getElementById('modal-exp-rechazado-por').textContent = datos.usuario_nombre;
}
        
        function cerrarModal(modalId) {
            const modal = document.getElementById('modal-' + modalId);
            if (modal) {
                modal.style.display = 'none';
            }
        }
        
        function formatearFecha(fechaString) {
            const fecha = new Date(fechaString);
            return fecha.toLocaleDateString('es-ES', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
        
        function marcarComoVisto(idActividad) {
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `marcar_visto=1&id_actividad=${idActividad}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Actualizar visualmente el elemento
                    const elemento = document.querySelector(`[data-id="${idActividad}"]`);
                    if (elemento) {
                        elemento.classList.remove('no-visto');
                        elemento.classList.add('visto');
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        }
        
        // PASO 13: FILTRADO DINÁMICO
        document.getElementById('tipo-filtro').addEventListener('change', function() {
            const filtro = this.value;
            const mensajes = document.querySelectorAll('.mensaje');
            
            mensajes.forEach(mensaje => {
                if (filtro === 'todos' || mensaje.dataset.tipo === filtro) {
                    mensaje.style.display = 'flex';
                } else {
                    mensaje.style.display = 'none';
                }
            });
        });
        
        // PASO 14: CERRAR MODALES AL HACER CLIC FUERA DE ELLOS
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal-overlay')) {
                e.target.style.display = 'none';
            }
        });