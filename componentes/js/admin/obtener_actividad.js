let paginaActual = 1;
let totalPaginas = 0;
let totalRegistros = 0;
let datosOriginales = [];
let datosFiltrados = []; // Nuevo: para manejar filtros separados de paginación

// Cargar datos al iniciar
document.addEventListener('DOMContentLoaded', function() {
    cargarActividades();
    configurarFiltros();
});

// Función para cargar actividades
function cargarActividades(pagina = 1) {
    // Mostrar indicador de carga
    const loadingIndicator = document.getElementById('loading-indicator');
    if (loadingIndicator) {
        loadingIndicator.style.display = 'block';
    }
    
    // Obtener filtros actuales
    const filtroAccion = document.getElementById('filtro-accion').value;
    const filtroArchivo = document.getElementById('filtro-archivo').value;
    const busqueda = document.getElementById('busqueda').value;
    
    // Construir URL con parámetros
    let url = `../../../app/backend/administrador/obtener_actividad.php?pagina=${pagina}`;
    
    if (filtroAccion) url += `&accion=${encodeURIComponent(filtroAccion)}`;
    if (filtroArchivo) url += `&archivo=${encodeURIComponent(filtroArchivo)}`;
    if (busqueda) url += `&busqueda=${encodeURIComponent(busqueda)}`;
    
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            // Ocultar indicador de carga
            if (loadingIndicator) {
                loadingIndicator.style.display = 'none';
            }
            
            if (data.success) {
                datosOriginales = data.data;
                paginaActual = data.pagina_actual;
                totalPaginas = data.total_paginas;
                totalRegistros = data.total_registros;
                
                mostrarActividades(datosOriginales);
                actualizarPaginacion(data);
            } else {
                console.error('Error:', data.error);
                mostrarError('Error al cargar los datos: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (loadingIndicator) {
                loadingIndicator.style.display = 'none';
            }
            mostrarError('Error de conexión: ' + error.message);
        });
}

// Mostrar actividades en la tabla
function mostrarActividades(datos) {
    const tablaBody = document.getElementById('tabla-body');
    const cardsContainer = document.getElementById('cards-container');
    const sinResultados = document.getElementById('sin-resultados');
    
    if (!tablaBody || !cardsContainer || !sinResultados) {
        console.error('Elementos de la tabla no encontrados');
        return;
    }
    
    tablaBody.innerHTML = '';
    cardsContainer.innerHTML = '';
    
    if (datos.length === 0) {
        sinResultados.style.display = 'block';
        return;
    }
    
    sinResultados.style.display = 'none';
    
    // Mostrar en tabla
    datos.forEach(actividad => {
        const fila = document.createElement('tr');
        fila.innerHTML = `
            <td>${actividad.nombres || 'N/A'}</td>
            <td>${actividad.rol || 'N/A'}</td>
            <td><span class="badge-accion ${(actividad.accion || '').toLowerCase()}">${actividad.accion || 'N/A'}</span></td>
            <td>${actividad.Archivo || 'Sin archivo'}</td>
            <td>${actividad.titulo || '-'}</td>
            <td>${formatearFecha(actividad.fecha_accion)}</td>
        `;
        tablaBody.appendChild(fila);
    });
    
    // Mostrar en tarjetas (móvil)
    datos.forEach(actividad => {
        const card = document.createElement('div');
        card.className = 'actividad-card';
        card.innerHTML = `
            <div class="card-header">
                <h3>${actividad.nombres || 'N/A'}</h3>
                <span class="badge-accion ${(actividad.accion || '').toLowerCase()}">${actividad.accion || 'N/A'}</span>
            </div>
            <div class="card-body">
                <p><strong>Rol:</strong> ${actividad.rol || 'N/A'}</p>
                <p><strong>Archivo:</strong> ${actividad.Archivo || 'Sin archivo'}</p>
                <p><strong>Título:</strong> ${actividad.titulo || '-'}</p>
                <p><strong>Fecha:</strong> ${formatearFecha(actividad.fecha_accion)}</p>
            </div>
        `;
        cardsContainer.appendChild(card);
    });
}

// Configurar filtros
function configurarFiltros() {
    const busqueda = document.getElementById('busqueda');
    const filtroAccion = document.getElementById('filtro-accion');
    const filtroArchivo = document.getElementById('filtro-archivo');
    
    if (!busqueda || !filtroAccion || !filtroArchivo) {
        console.error('Elementos de filtros no encontrados');
        return;
    }
    
    // Filtro de búsqueda con debounce
    let timeoutId;
    busqueda.addEventListener('input', function() {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => {
            paginaActual = 1; // Resetear a página 1 cuando se aplican filtros
            cargarActividades(1);
        }, 300); // Esperar 300ms después de que el usuario deje de escribir
    });
    
    filtroAccion.addEventListener('change', function() {
        paginaActual = 1;
        cargarActividades(1);
    });
    
    filtroArchivo.addEventListener('change', function() {
        paginaActual = 1;
        cargarActividades(1);
    });
}

// Actualizar paginación - Versión simplificada solo con anterior/siguiente
function actualizarPaginacion(data) {
    // Actualizar información de registros
    const rangoInicio = document.getElementById('rango-inicio');
    const rangoFin = document.getElementById('rango-fin');
    const totalRegistrosEl = document.getElementById('total-registros');
    
    if (rangoInicio) rangoInicio.textContent = data.rango_inicio || 1;
    if (rangoFin) rangoFin.textContent = data.rango_fin || 0;
    if (totalRegistrosEl) totalRegistrosEl.textContent = data.total_registros || 0;
    
    // Actualizar botones de navegación
    const btnAnterior = document.getElementById('btn-anterior');
    const btnSiguiente = document.getElementById('btn-siguiente');
    
    if (btnAnterior) {
        // Mostrar/ocultar botón anterior
        if (paginaActual <= 1) {
            btnAnterior.style.display = 'none';
        } else {
            btnAnterior.style.display = 'flex';
        }
        
        btnAnterior.disabled = paginaActual <= 1;
        btnAnterior.onclick = () => {
            if (paginaActual > 1) {
                cargarActividades(paginaActual - 1);
            }
        };
    }
    
    if (btnSiguiente) {
        // Mostrar/ocultar botón siguiente
        if (paginaActual >= totalPaginas) {
            btnSiguiente.style.display = 'none';
        } else {
            btnSiguiente.style.display = 'flex';
        }
        
        btnSiguiente.disabled = paginaActual >= totalPaginas;
        btnSiguiente.onclick = () => {
            if (paginaActual < totalPaginas) {
                cargarActividades(paginaActual + 1);
            }
        };
    }
    
    // Ocultar el contenedor de números de página
    const numerosContainer = document.getElementById('numeros-pagina');
    if (numerosContainer) {
        numerosContainer.style.display = 'none';
    }
    
    // Mostrar información de página actual
    mostrarInfoPaginaActual();
}

// Función para mostrar información de página actual
function mostrarInfoPaginaActual() {
    const numerosContainer = document.getElementById('numeros-pagina');
    if (numerosContainer && totalPaginas > 0) {
        numerosContainer.style.display = 'flex';
        numerosContainer.innerHTML = `
            <span class="info-pagina-actual">
                Página ${paginaActual} de ${totalPaginas}
            </span>
        `;
    }
}

// Formatear fecha
function formatearFecha(fecha) {
    if (!fecha) return 'N/A';
    
    try {
        return new Date(fecha).toLocaleDateString('es-ES', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
        });
    } catch (error) {
        console.error('Error al formatear fecha:', error);
        return fecha; // Devolver fecha original si hay error
    }
}

// Mostrar errores
function mostrarError(mensaje) {
    const tablaBody = document.getElementById('tabla-body');
    const cardsContainer = document.getElementById('cards-container');
    const sinResultados = document.getElementById('sin-resultados');
    
    if (tablaBody) tablaBody.innerHTML = '';
    if (cardsContainer) cardsContainer.innerHTML = '';
    
    if (sinResultados) {
        sinResultados.innerHTML = `
            <i class="bi bi-exclamation-triangle"></i>
            <p>${mensaje}</p>
        `;
        sinResultados.style.display = 'block';
    }
}