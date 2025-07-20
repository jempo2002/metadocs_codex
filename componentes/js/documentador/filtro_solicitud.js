// Filtro de mensajes
const filtro = document.getElementById('tipo-filtro');
const mensajes = document.querySelectorAll('.mensaje');

filtro.addEventListener('change', () => {
  const valor = filtro.value;

  mensajes.forEach(mensaje => {
    const tipo = mensaje.dataset.tipo;
    if (valor === 'todos' || tipo === valor) {
      mensaje.style.display = 'flex';
    } else {
      mensaje.style.display = 'none';
    }
  });
});

// Modal de solicitud de documento
const cuerpo_contenido = document.getElementById('contenido_solicitud');
const cerrar = document.getElementById("cerrar_contenido");

// Función para cerrar modal de solicitud (si existe el elemento)
if (cerrar) {
  cerrar.addEventListener('click', () => {
    cuerpo_contenido.style.display = 'none';
  });
}

// Función para abrir modal de solicitud de documento
function abrirModalSolicitud() {
  const modal = document.getElementById('modal-solicitud-documento');
  if (modal) {
    modal.style.display = 'flex';
    // Prevenir scroll del body cuando el modal está abierto
    document.body.style.overflow = 'hidden';
  }
}

// Función para cerrar modal de solicitud de documento
function cerrarModalSolicitud() {
  const modal = document.getElementById('modal-solicitud-documento');
  if (modal) {
    modal.style.display = 'none';
    // Restaurar scroll del body
    document.body.style.overflow = 'auto';
  }
}

// Función para abrir modales específicos
function abrirModal(modalId) {
  const modal = document.getElementById(`modal-${modalId}`);
  if (modal) {
    modal.style.display = 'flex';
    // Prevenir scroll del body cuando el modal está abierto
    document.body.style.overflow = 'hidden';
  }
}

// Función para cerrar modales específicos
function cerrarModal(modalId) {
  const modal = document.getElementById(`modal-${modalId}`);
  if (modal) {
    modal.style.display = 'none';
    // Restaurar scroll del body
    document.body.style.overflow = 'auto';
  }
}

// Event listeners para las tarjetas de notificación
document.addEventListener('DOMContentLoaded', () => {
  // Agregar evento click a todas las tarjetas clickeables
  const tarjetasClickeables = document.querySelectorAll('.mensaje.clickeable');
  
  tarjetasClickeables.forEach(tarjeta => {
    tarjeta.addEventListener('click', () => {
      const tipo = tarjeta.dataset.tipo;
      const modalId = tarjeta.dataset.modal;
      
      // Marcar como visto al hacer click
      tarjeta.classList.remove('no-visto');
      tarjeta.classList.add('visto');
      
      // Abrir modal correspondiente según el tipo
      if (tipo === 'documento') {
        // Para solicitudes de documento, abrir el modal de solicitud
        abrirModalSolicitud();
      } else if (modalId) {
        // Para otros tipos, abrir su modal específico
        abrirModal(modalId);
      }
    });
    
    // Agregar efecto hover
    tarjeta.style.cursor = 'pointer';
  });
  
  // Cerrar modales al hacer click fuera de ellos
  const modalesOverlay = document.querySelectorAll('.modal-overlay');
  modalesOverlay.forEach(modalOverlay => {
    modalOverlay.addEventListener('click', (e) => {
      // Solo cerrar si se hace click en el overlay, no en el contenido del modal
      if (e.target === modalOverlay) {
        const modalId = modalOverlay.id.replace('modal-', '');
        cerrarModal(modalId);
      }
    });
  });
  
  // Cerrar modal de solicitud al hacer click fuera
  const modalSolicitud = document.getElementById('modal-solicitud-documento');
  if (modalSolicitud) {
    modalSolicitud.addEventListener('click', (e) => {
      if (e.target === modalSolicitud) {
        cerrarModalSolicitud();
      }
    });
  }
  
  // Cerrar modal de solicitud si existe el elemento cuerpo_contenido
  if (cuerpo_contenido) {
    cuerpo_contenido.addEventListener('click', (e) => {
      if (e.target === cuerpo_contenido) {
        cuerpo_contenido.style.display = 'none';
      }
    });
  }
  
  // Cerrar modales con la tecla Escape
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      // Cerrar modal de solicitud si está abierto
      if (modalSolicitud && modalSolicitud.style.display === 'flex') {
        cerrarModalSolicitud();
      }
      
      // Cerrar modal de solicitud antigua si existe y está abierto
      if (cuerpo_contenido && cuerpo_contenido.style.display === 'flex') {
        cuerpo_contenido.style.display = 'none';
      }
      
      // Cerrar cualquier modal abierto
      modalesOverlay.forEach(modalOverlay => {
        if (modalOverlay.style.display === 'flex') {
          const modalId = modalOverlay.id.replace('modal-', '');
          cerrarModal(modalId);
        }
      });
    }
  });
});

// Funciones adicionales para mejorar la experiencia de usuario
function marcarComoVisto(elemento) {
  elemento.classList.remove('no-visto');
  elemento.classList.add('visto');
}

function contarMensajesNoVistos() {
  const noVistos = document.querySelectorAll('.mensaje.no-visto');
  return noVistos.length;
}

// Actualizar contador de mensajes no vistos (opcional)
function actualizarContadorNoVistos() {
  const contador = contarMensajesNoVistos();
  const badge = document.getElementById('contador-no-vistos');
  if (badge) {
    badge.textContent = contador;
    badge.style.display = contador > 0 ? 'inline' : 'none';
  }
}

// Función para filtrar por estado (visto/no visto)
function filtrarPorEstado(estado) {
  mensajes.forEach(mensaje => {
    if (estado === 'todos') {
      mensaje.style.display = 'flex';
    } else if (estado === 'no-visto' && mensaje.classList.contains('no-visto')) {
      mensaje.style.display = 'flex';
    } else if (estado === 'visto' && mensaje.classList.contains('visto')) {
      mensaje.style.display = 'flex';
    } else {
      mensaje.style.display = 'none';
    }
  });
}

// Función para marcar todos como vistos
function marcarTodosComoVistos() {
  const mensajesNoVistos = document.querySelectorAll('.mensaje.no-visto');
  mensajesNoVistos.forEach(mensaje => {
    marcarComoVisto(mensaje);
  });
  actualizarContadorNoVistos();
}

// Inicializar contador al cargar la página
document.addEventListener('DOMContentLoaded', () => {
  actualizarContadorNoVistos();
});