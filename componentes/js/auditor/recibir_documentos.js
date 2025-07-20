document.addEventListener('DOMContentLoaded', () => {
    
    // Obtener elementos
    const btnDocumentos = document.getElementById('btn-documentos');
    const btnExpedientes = document.getElementById('btn-expedientes');
    const contenedorDocumentos = document.getElementById('contenedor-documentos');
    const contenedorExpedientes = document.getElementById('contenedor-expedientes');

    // Función para mostrar solo documentos
    function mostrarDocumentos() {
        // Cambiar clases activas en los botones
        btnDocumentos.classList.add('active');
        btnExpedientes.classList.remove('active');
        
        // Mostrar contenedor de documentos, ocultar expedientes
        if (contenedorDocumentos) {
            contenedorDocumentos.style.display = 'block';
        }
        if (contenedorExpedientes) {
            contenedorExpedientes.style.display = 'none';
        }
    }

    // Función para mostrar solo expedientes
    function mostrarExpedientes() {
        // Cambiar clases activas en los botones
        btnExpedientes.classList.add('active');
        btnDocumentos.classList.remove('active');
        
        // Mostrar contenedor de expedientes, ocultar documentos
        if (contenedorExpedientes) {
            contenedorExpedientes.style.display = 'block';
        }
        if (contenedorDocumentos) {
            contenedorDocumentos.style.display = 'none';
        }
    }

    // Event listeners para los botones
    if (btnDocumentos) {
        btnDocumentos.addEventListener('click', mostrarDocumentos);
    }
    if (btnExpedientes) {
        btnExpedientes.addEventListener('click', mostrarExpedientes);
    }

    // Estado inicial: mostrar solo documentos
    mostrarDocumentos();

    // =================== MODALES ===================

    // Elementos del modal de expedientes
    const modalExpediente = document.getElementById("modal_confirmar_expediente");
    const btnCancelarExpediente = modalExpediente?.querySelector(".btn_cancelar");
    const btnSalirExpediente = modalExpediente?.querySelector(".close");
    const inputHiddenExpediente = modalExpediente?.querySelector("input[name='datos_expediente']");

    // Elementos del modal de documentos
    const modalDocumento = document.getElementById("modal_confirmar_documento");
    const btnCancelarDocumento = modalDocumento?.querySelector(".btn_cancelar");
    const btnSalirDocumento = modalDocumento?.querySelector(".close");
    const inputHiddenDocumento = modalDocumento?.querySelector("input[name='datos_documento']");

    // Función para mostrar modal
    function mostrarModal(modal) {
        if (modal) {
            modal.style.display = 'block';
        }
    }

    // Función para ocultar modal
    function ocultarModal(modal) {
        if (modal) {
            modal.style.display = 'none';
        }
    }

    // =================== DEBUG MEJORADO ===================
    function debugCapturaDatos(accion, tipo, datos) {
        console.group(`🔍 DEBUG: ${accion} ${tipo}`);
        console.log('📄 Datos capturados:', datos);
        console.log('🎯 ID:', datos.id);
        console.log('👤 Autor:', datos.autor);
        if (tipo === 'DOCUMENTO') {
            console.log('📝 Título:', datos.titulo);
            console.log('🏷️ Categoría:', datos.categoria);
            console.log('📁 Expediente:', datos.expediente);
        } else if (tipo === 'EXPEDIENTE') {
            console.log('📂 Nombre:', datos.nombre);
        }
        console.groupEnd();
    }

    // =================== FUNCIONES DE EXTRACCIÓN DE DATOS ===================
    
    function extraerDatosExpediente(carta) {
        const botonAprobar = carta.querySelector('.btn-aprobar') || carta.querySelector('.aprobado');
        const idExpediente = botonAprobar ? botonAprobar.getAttribute('data-id') : null;
        
        // Extraer nombre del expediente
        const nombreExpediente = carta.querySelector('.info h3')?.textContent?.trim() || '';
        
        // Extraer nombre del usuario/autor - método mejorado
        const autorElement = carta.querySelector('#autor_fecha p:first-child');
        let autor = '';
        if (autorElement) {
            // Remover el ícono y obtener solo el texto
            const iconElement = autorElement.querySelector('i.bi-person-fill');
            if (iconElement) {
                autor = autorElement.textContent.replace(iconElement.textContent || '', '').trim();
            } else {
                autor = autorElement.textContent.trim();
            }
        }
        
        const datos = {
            id: idExpediente,
            nombre: nombreExpediente,
            autor: autor
        };
        
        debugCapturaDatos('EXTRAER', 'EXPEDIENTE', datos);
        return datos;
    }
    
    function extraerDatosDocumento(carta) {
        const botonAprobar = carta.querySelector('.aprobado');
        const idDocumento = botonAprobar ? botonAprobar.getAttribute('data-id') : null;
        
        // Extraer título del documento
        const titulo = carta.querySelector('.info h3')?.textContent?.trim() || '';
        
        // Extraer categoría del documento - método mejorado
        const infoDiv = carta.querySelector('.info #info');
        let categoria = '';
        if (infoDiv) {
            const paragraphs = infoDiv.querySelectorAll('p');
            // Buscar el párrafo que NO tiene la clase expediente-info
            for (const p of paragraphs) {
                if (!p.classList.contains('expediente-info')) {
                    categoria = p.textContent?.trim() || '';
                    break;
                }
            }
        }
        
        // Extraer expediente del documento
        const expedienteInfo = carta.querySelector('.expediente-info strong');
        const expediente = expedienteInfo?.textContent?.trim() || '';
        
        // Extraer nombre del usuario/autor - método mejorado
        const autorElement = carta.querySelector('#autor_fecha p:first-child');
        let autor = '';
        if (autorElement) {
            // Remover el ícono y obtener solo el texto
            const iconElement = autorElement.querySelector('i.bi-person-fill');
            if (iconElement) {
                autor = autorElement.textContent.replace(iconElement.textContent || '', '').trim();
            } else {
                autor = autorElement.textContent.trim();
            }
        }
        
        const datos = {
            id: idDocumento,
            titulo: titulo,
            categoria: categoria,
            expediente: expediente,
            autor: autor
        };
        
        debugCapturaDatos('EXTRAER', 'DOCUMENTO', datos);
        return datos;
    }

    // Event listeners para botones de aprobar EXPEDIENTES
    const botonesAprobarExpedientes = document.querySelectorAll("#contenedor-expedientes .aprobado, #contenedor-expedientes .btn-aprobar");
    botonesAprobarExpedientes.forEach(boton => {
        boton.addEventListener("click", (e) => {
            const carta = e.target.closest('.carta');
            const datos = extraerDatosExpediente(carta);
            
            debugCapturaDatos('APROBAR', 'EXPEDIENTE', datos);
            
            // Asignar los valores a los inputs hidden del modal de expedientes
            if (inputHiddenExpediente && datos.id) {
                inputHiddenExpediente.value = datos.id;
            }
            
            // Llenar los campos adicionales del expediente
            const inputUsuarioDestinatarioExp = modalExpediente?.querySelector("input[name='usuario_destinatario']");
            const inputNombreExpediente = modalExpediente?.querySelector("input[name='nombre_expediente']");
            
            if (inputUsuarioDestinatarioExp) {
                inputUsuarioDestinatarioExp.value = datos.autor;
            }
            
            if (inputNombreExpediente) {
                inputNombreExpediente.value = datos.nombre;
            }
            
            // Mostrar el modal de expedientes
            mostrarModal(modalExpediente);
        });
    });

    // Event listeners para botones de aprobar DOCUMENTOS
    const botonesAprobarDocumentos = document.querySelectorAll("#contenedor-documentos .aprobado");
    botonesAprobarDocumentos.forEach(boton => {
        boton.addEventListener("click", (e) => {
            const carta = e.target.closest('.carta');
            const datos = extraerDatosDocumento(carta);
            
            debugCapturaDatos('APROBAR', 'DOCUMENTO', datos);
            
            // Asignar los valores a los inputs hidden del modal de documentos
            if (inputHiddenDocumento && datos.id) {
                inputHiddenDocumento.value = datos.id;
            }
            
            // Llenar los campos adicionales
            const inputUsuarioDestinatario = modalDocumento?.querySelector("input[name='usuario_destinatario']");
            const inputTitulo = modalDocumento?.querySelector("input[name='titulo']");
            const inputCategoria = modalDocumento?.querySelector("input[name='categoria']");
            const inputExpediente = modalDocumento?.querySelector("input[name='expediente']");
            
            if (inputUsuarioDestinatario) {
                inputUsuarioDestinatario.value = datos.autor;
            }
            
            if (inputTitulo) {
                inputTitulo.value = datos.titulo;
            }
            
            if (inputCategoria) {
                inputCategoria.value = datos.categoria;
            }
            
            if (inputExpediente) {
                inputExpediente.value = datos.expediente;
            }
            
            // Mostrar el modal de documentos
            mostrarModal(modalDocumento);
        });
    });

    // =================== MODALES DE RECHAZO ===================

    // Elementos del modal de rechazo de expedientes
    const modalRechazarExpediente = document.getElementById("modal_rechazar_expediente");
    const btnCancelarRechazarExp = modalRechazarExpediente?.querySelector(".btn_cancelar");
    const btnSalirRechazarExp = modalRechazarExpediente?.querySelector(".close");
    const inputHiddenRechazarExp = modalRechazarExpediente?.querySelector("input[name='datos_expediente']");

    // Elementos del modal de rechazo de documentos
    const modalRechazarDocumento = document.getElementById("modal_rechazar_documento");
    const btnCancelarRechazarDoc = modalRechazarDocumento?.querySelector(".btn_cancelar");
    const btnSalirRechazarDoc = modalRechazarDocumento?.querySelector(".close");
    const inputHiddenRechazarDoc = modalRechazarDocumento?.querySelector("input[name='datos_documento']");

    // Event listeners para botones de rechazar EXPEDIENTES - CORREGIDO
    const botonesRechazarExpedientes = document.querySelectorAll("#contenedor-expedientes .rechazado");
    botonesRechazarExpedientes.forEach(boton => {
        boton.addEventListener("click", (e) => {
            console.log('🚫 Click en botón rechazar expediente');
            
            const carta = e.target.closest('.carta');
            const datos = extraerDatosExpediente(carta);
            
            debugCapturaDatos('RECHAZAR', 'EXPEDIENTE', datos);
            
            // Verificar que tenemos los datos necesarios
            if (!datos.id) {
                console.error('❌ No se pudo obtener el ID del expediente');
                return;
            }
            
            // Asignar valores a los inputs hidden del modal de rechazo
            if (inputHiddenRechazarExp) {
                inputHiddenRechazarExp.value = datos.id;
                console.log('✅ ID asignado al input hidden:', datos.id);
            }
            
            // Llenar campos adicionales para el rechazo de expedientes
            const inputUsuarioDestinatarioRechExp = modalRechazarExpediente?.querySelector("input[name='usuario_destinatario']");
            const inputNombreExpedienteRech = modalRechazarExpediente?.querySelector("input[name='nombre_expediente']");
            
            if (inputUsuarioDestinatarioRechExp) {
                inputUsuarioDestinatarioRechExp.value = datos.autor;
                console.log('✅ Usuario destinatario asignado:', datos.autor);
            }
            
            if (inputNombreExpedienteRech) {
                inputNombreExpedienteRech.value = datos.nombre;
                console.log('✅ Nombre expediente asignado:', datos.nombre);
            }
            
            // Limpiar el textarea
            const textarea = modalRechazarExpediente?.querySelector('textarea[name="motivo_rechazo"]');
            if (textarea) {
                textarea.value = '';
                console.log('✅ Textarea limpiado');
            }
            
            // Mostrar el modal de rechazo de expedientes
            mostrarModal(modalRechazarExpediente);
            console.log('✅ Modal de rechazo de expediente mostrado');
        });
    });

    // Event listeners para botones de rechazar DOCUMENTOS - CORREGIDO
    const botonesRechazarDocumentos = document.querySelectorAll("#contenedor-documentos .rechazado");
    botonesRechazarDocumentos.forEach(boton => {
        boton.addEventListener("click", (e) => {
            console.log('🚫 Click en botón rechazar documento');
            
            const carta = e.target.closest('.carta');
            const datos = extraerDatosDocumento(carta);
            
            debugCapturaDatos('RECHAZAR', 'DOCUMENTO', datos);
            
            // Verificar que tenemos los datos necesarios
            if (!datos.id) {
                console.error('❌ No se pudo obtener el ID del documento');
                return;
            }
            
            // Asignar valores a los inputs hidden del modal de rechazo
            if (inputHiddenRechazarDoc) {
                inputHiddenRechazarDoc.value = datos.id;
                console.log('✅ ID asignado al input hidden:', datos.id);
            }
            
            // Llenar campos adicionales para el rechazo de documentos
            const inputUsuarioDestinatarioRechDoc = modalRechazarDocumento?.querySelector("input[name='usuario_destinatario']");
            const inputTituloRech = modalRechazarDocumento?.querySelector("input[name='titulo']");
            const inputCategoriaRech = modalRechazarDocumento?.querySelector("input[name='categoria']");
            const inputExpedienteRech = modalRechazarDocumento?.querySelector("input[name='expediente']");
            
            if (inputUsuarioDestinatarioRechDoc) {
                inputUsuarioDestinatarioRechDoc.value = datos.autor;
                console.log('✅ Usuario destinatario asignado:', datos.autor);
            }
            
            if (inputTituloRech) {
                inputTituloRech.value = datos.titulo;
                console.log('✅ Título asignado:', datos.titulo);
            }
            
            if (inputCategoriaRech) {
                inputCategoriaRech.value = datos.categoria;
                console.log('✅ Categoría asignada:', datos.categoria);
            }
            
            if (inputExpedienteRech) {
                inputExpedienteRech.value = datos.expediente;
                console.log('✅ Expediente asignado:', datos.expediente);
            }
            
            // Limpiar el textarea
            const textarea = modalRechazarDocumento?.querySelector('textarea[name="motivo_rechazo"]');
            if (textarea) {
                textarea.value = '';
                console.log('✅ Textarea limpiado');
            }
            
            // Mostrar el modal de rechazo de documentos
            mostrarModal(modalRechazarDocumento);
            console.log('✅ Modal de rechazo de documento mostrado');
        });
    });

    // =================== EVENT LISTENERS PARA CERRAR MODALES ===================

    // Cerrar modales con botones de cancelar
    if (btnCancelarExpediente) {
        btnCancelarExpediente.addEventListener("click", () => ocultarModal(modalExpediente));
    }
    if (btnSalirExpediente) {
        btnSalirExpediente.addEventListener("click", () => ocultarModal(modalExpediente));
    }
    if (btnCancelarDocumento) {
        btnCancelarDocumento.addEventListener("click", () => ocultarModal(modalDocumento));
    }
    if (btnSalirDocumento) {
        btnSalirDocumento.addEventListener("click", () => ocultarModal(modalDocumento));
    }
    if (btnCancelarRechazarExp) {
        btnCancelarRechazarExp.addEventListener("click", () => ocultarModal(modalRechazarExpediente));
    }
    if (btnSalirRechazarExp) {
        btnSalirRechazarExp.addEventListener("click", () => ocultarModal(modalRechazarExpediente));
    }
    if (btnCancelarRechazarDoc) {
        btnCancelarRechazarDoc.addEventListener("click", () => ocultarModal(modalRechazarDocumento));
    }
    if (btnSalirRechazarDoc) {
        btnSalirRechazarDoc.addEventListener("click", () => ocultarModal(modalRechazarDocumento));
    }

    // =================== VALIDACIÓN DE FORMULARIOS ===================

    // Validar formulario de rechazo de expedientes antes del envío
    const formRechazarExpediente = modalRechazarExpediente?.querySelector('form');
    if (formRechazarExpediente) {
        formRechazarExpediente.addEventListener('submit', (e) => {
            const textarea = formRechazarExpediente.querySelector('textarea[name="motivo_rechazo"]');
            const motivoRechazo = textarea ? textarea.value.trim() : '';
            
            console.log('📝 Validando formulario rechazo expediente');
            console.log('💬 Motivo ingresado:', motivoRechazo);
            console.log('📏 Longitud del motivo:', motivoRechazo.length);
            
            if (motivoRechazo.length < 10) {
                e.preventDefault();
                alert('El motivo del rechazo debe tener al menos 10 caracteres.');
                textarea.focus();
                console.log('❌ Validación fallida: motivo muy corto');
                return false;
            }
            
            // Debug final antes del envío
            const formData = new FormData(formRechazarExpediente);
            console.group('🚀 ENVÍO FORMULARIO RECHAZO EXPEDIENTE');
            for (let [key, value] of formData.entries()) {
                console.log(`${key}:`, value);
            }
            console.groupEnd();
            
            console.log('✅ Validación exitosa, enviando formulario');
        });
    }

    // Validar formulario de rechazo de documentos antes del envío
    const formRechazarDocumento = modalRechazarDocumento?.querySelector('form');
    if (formRechazarDocumento) {
        formRechazarDocumento.addEventListener('submit', (e) => {
            const textarea = formRechazarDocumento.querySelector('textarea[name="motivo_rechazo"]');
            const motivoRechazo = textarea ? textarea.value.trim() : '';
            
            console.log('📝 Validando formulario rechazo documento');
            console.log('💬 Motivo ingresado:', motivoRechazo);
            console.log('📏 Longitud del motivo:', motivoRechazo.length);
            
            if (motivoRechazo.length < 10) {
                e.preventDefault();
                alert('El motivo del rechazo debe tener al menos 10 caracteres.');
                textarea.focus();
                console.log('❌ Validación fallida: motivo muy corto');
                return false;
            }
            
            // Debug final antes del envío
            const formData = new FormData(formRechazarDocumento);
            console.group('🚀 ENVÍO FORMULARIO RECHAZO DOCUMENTO');
            for (let [key, value] of formData.entries()) {
                console.log(`${key}:`, value);
            }
            console.groupEnd();
            
            console.log('✅ Validación exitosa, enviando formulario');
        });
    }

    // =================== EVENT LISTENERS GLOBALES ===================

    // Cerrar modales al hacer clic fuera de ellos
    window.addEventListener("click", (e) => {
        if (e.target === modalExpediente) {
            ocultarModal(modalExpediente);
        }
        if (e.target === modalDocumento) {
            ocultarModal(modalDocumento);
        }
        if (e.target === modalRechazarExpediente) {
            ocultarModal(modalRechazarExpediente);
        }
        if (e.target === modalRechazarDocumento) {
            ocultarModal(modalRechazarDocumento);
        }
    });

    // Cerrar modales con la tecla ESC
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") {
            ocultarModal(modalExpediente);
            ocultarModal(modalDocumento);
            ocultarModal(modalRechazarExpediente);
            ocultarModal(modalRechazarDocumento);
        }
    });

    // =================== MANEJO DE MENSAJES DE ÉXITO/ERROR ===================

    // Crear estilos CSS para las animaciones
    const styles = document.createElement('style');
    styles.textContent = `
        @keyframes slideDown {
            0% {
                transform: translateY(-100%);
                opacity: 0;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes slideUp {
            0% {
                transform: translateY(0);
                opacity: 1;
            }
            100% {
                transform: translateY(-100%);
                opacity: 0;
            }
        }

        .notificacion {
            animation: slideDown 0.5s ease-out;
        }

        .notificacion.closing {
            animation: slideUp 0.5s ease-in;
        }
    `;
    document.head.appendChild(styles);

    // Mostrar mensajes basados en parámetros URL
    function mostrarMensajes() {
        const urlParams = new URLSearchParams(window.location.search);
        const success = urlParams.get('sucess'); // Nota: hay un typo en 'sucess' en el código PHP
        const error = urlParams.get('error');

        if (success === 'true') {
            mostrarNotificacion('Operación realizada con éxito', 'success');
            // Limpiar la URL después de mostrar el mensaje
            window.history.replaceState({}, document.title, window.location.pathname);
        } else if (error === 'true') {
            mostrarNotificacion('Error al procesar la solicitud', 'error');
            // Limpiar la URL después de mostrar el mensaje
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    }

    // Función para mostrar notificaciones mejorada
    function mostrarNotificacion(mensaje, tipo) {
        // Crear elemento de notificación
        const notificacion = document.createElement('div');
        notificacion.className = `notificacion ${tipo}`;
        
        // Aplicar estilos de posicionamiento centrado sin transform
        notificacion.style.cssText = `
            position: fixed;
            top: 50px;
            left: 50%;
            margin-left: -200px;
            z-index: 9999;
        `;
        
        notificacion.innerHTML = `
            <div class="notificacion-contenido">
                <div class="notificacion-icono">
                    ${tipo === 'success' ? '✓' : '⚠'}
                </div>
                <span class="notificacion-mensaje">${mensaje}</span>
                <button type="button" class="btn-cerrar-notificacion">&times;</button>
            </div>
        `;

        // Estilos para el contenido de la notificación
        const contenido = notificacion.querySelector('.notificacion-contenido');
        contenido.style.cssText = `
            background-color: #3D688A;
            color: white;
            padding: 20px 30px;
            border-radius: 10px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 400px;
            width: 400px;
            max-width: 600px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            position: relative;
            font-size: 16px;
        `;

        // Estilos para el icono
        const icono = notificacion.querySelector('.notificacion-icono');
        icono.style.cssText = `
            font-size: 24px;
            margin-right: 15px;
            background-color: rgba(255, 255, 255, 0.2);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        `;

        // Estilos para el mensaje
        const mensajeEl = notificacion.querySelector('.notificacion-mensaje');
        mensajeEl.style.cssText = `
            flex: 1;
            text-align: center;
            margin: 0 15px;
        `;

        // Estilo para el botón de cerrar
        const btnCerrar = notificacion.querySelector('.btn-cerrar-notificacion');
        btnCerrar.style.cssText = `
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        `;

        // Agregar al body - ya aparecerá centrado directamente
        document.body.appendChild(notificacion);

        // Función para cerrar con animación
        function cerrarNotificacion() {
            notificacion.classList.add('closing');
            setTimeout(() => {
                if (notificacion && notificacion.parentNode) {
                    notificacion.remove();
                }
            }, 500);
        }

        // Event listener para cerrar al hacer clic en el botón
        btnCerrar.addEventListener('click', cerrarNotificacion);

        // Event listener para cerrar con ESC
        const handleEscKey = (e) => {
            if (e.key === 'Escape') {
                cerrarNotificacion();
                document.removeEventListener('keydown', handleEscKey);
            }
        };
        document.addEventListener('keydown', handleEscKey);

        // Auto-cerrar después de 3 segundos
        setTimeout(() => {
            if (notificacion && notificacion.parentNode) {
                cerrarNotificacion();
            }
        }, 3000);
    }

    // Llamar a la función para mostrar mensajes al cargar la página
    mostrarMensajes();

    // =================== DEBUG Y LOGGING ===================

    // Función de debug para desarrollo
    function debug(mensaje, datos = null) {
        if (console && console.log) {
            console.log(`[DEBUG] ${mensaje}`, datos);
        }
    }

    // Log de inicialización
    debug('Script de recibir documentos cargado correctamente');
    debug('Contenedores encontrados:', {
        documentos: !!contenedorDocumentos,
        expedientes: !!contenedorExpedientes
    });

    // Debug de modales encontrados
    debug('Modales encontrados:', {
        modalExpediente: !!modalExpediente,
        modalDocumento: !!modalDocumento,
        modalRechazarExpediente: !!modalRechazarExpediente,
        modalRechazarDocumento: !!modalRechazarDocumento
    });

    // Debug de botones encontrados
    debug('Botones encontrados:', {
        botonesAprobarExpedientes: botonesAprobarExpedientes.length,
        botonesAprobarDocumentos: botonesAprobarDocumentos.length,
        botonesRechazarExpedientes: botonesRechazarExpedientes.length,
        botonesRechazarDocumentos: botonesRechazarDocumentos.length
    });

});