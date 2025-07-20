document.addEventListener('DOMContentLoaded', () => {
  let paginaActual = 1;

  const tablaBody = document.getElementById('tabla-body');
  const cardsContainer = document.getElementById('cards-container');
  const sinResultados = document.getElementById('sin-resultados');
  const infoRegistros = document.getElementById('info-registros');
  const numerosPagina = document.getElementById('numeros-pagina');
  const btnAnterior = document.getElementById('btn-anterior');
  const btnSiguiente = document.getElementById('btn-siguiente');

  const inputBusqueda = document.getElementById('busqueda');
  const selectArchivo = document.getElementById('filtro-archivo');

  const cargarActividades = async (pagina = 1) => {
    try {
      const busqueda = inputBusqueda.value.trim();
      const archivo = selectArchivo.value;

      // Construir URL correctamente
      const baseUrl = window.location.origin + window.location.pathname;
      const url = new URL('../../../app/backend/auditor/obtener_actividades_documentador.php', baseUrl);
      
      url.searchParams.set('pagina', pagina);
      url.searchParams.set('limite', 10);
      if (busqueda) url.searchParams.set('busqueda', busqueda);
      if (archivo) url.searchParams.set('filtro_archivo', archivo);

      console.log('URL de petición:', url.toString()); // Debug

      const response = await fetch(url);
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      const resultado = await response.json();
      console.log('Respuesta del servidor:', resultado); // Debug

      // Limpiar contenido previo
      tablaBody.innerHTML = '';
      cardsContainer.innerHTML = '';
      numerosPagina.innerHTML = '';

      if (resultado.success && resultado.data && resultado.data.length > 0) {
        sinResultados.style.display = 'none';

        resultado.data.forEach(item => {
          // Fila para tabla
          const fila = document.createElement('tr');
          fila.innerHTML = `
            <td>${item.nombre || 'Sin nombre'}</td>
            <td>${item.accion || 'Sin acción'}</td>
        
            <td>${item.archivo || 'Sin archivo'}</td>
            <td>${item.titulo || 'Sin título'}</td>
                <td>${item.fecha || 'Sin fecha'}</td>
          `;
          tablaBody.appendChild(fila);

          // Tarjeta para vista móvil
          const card = document.createElement('div');
          card.classList.add('card-actividad');
          card.innerHTML = `
            <p><strong>Nombre:</strong> ${item.nombre || 'Sin nombre'}</p>
            <p><strong>Acción:</strong> ${item.accion || 'Sin acción'}</p>
            <p><strong>Archivo:</strong> ${item.archivo || 'Sin archivo'}</p>
            <p><strong>Título:</strong> ${item.titulo || 'Sin título'}</p>
              <p><strong>Fecha:</strong> ${item.fecha || 'Sin fecha'}</p>
          `;
          cardsContainer.appendChild(card);
        });

        const pag = resultado.pagination;
        paginaActual = pag.pagina_actual;

        // Actualizar información de registros
        infoRegistros.textContent = `Mostrando ${pag.registro_inicio} - ${pag.registro_fin} de ${pag.total_registros} registros`;

        // Generar números de página
        const maxPaginas = 5; // Mostrar máximo 5 números de página
        const inicio = Math.max(1, pag.pagina_actual - Math.floor(maxPaginas / 2));
        const fin = Math.min(pag.total_paginas, inicio + maxPaginas - 1);

        for (let i = inicio; i <= fin; i++) {
          const num = document.createElement('button');
          num.textContent = i;
          num.classList.add('numero-pagina');
          if (i === pag.pagina_actual) {
            num.classList.add('activo');
          }
          num.addEventListener('click', () => cargarActividades(i));
          numerosPagina.appendChild(num);
        }

        // Botones anterior/siguiente
        btnAnterior.disabled = !pag.hay_anterior;
        btnSiguiente.disabled = !pag.hay_siguiente;

      } else {
        // Mostrar mensaje de sin resultados
        sinResultados.style.display = 'block';
        infoRegistros.textContent = 'Mostrando 0 - 0 de 0 registros';
        btnAnterior.disabled = true;
        btnSiguiente.disabled = true;
        
        // Mostrar mensaje de error si existe
        if (!resultado.success) {
          console.error('Error del servidor:', resultado.message);
        }
      }

    } catch (error) {
      console.error('Error al cargar actividades:', error);
      sinResultados.style.display = 'block';
      infoRegistros.textContent = 'Error al cargar los datos';
      btnAnterior.disabled = true;
      btnSiguiente.disabled = true;
    }
  };

  // Debounce para la búsqueda
  let timeoutBusqueda;
  const buscarConDebounce = () => {
    clearTimeout(timeoutBusqueda);
    timeoutBusqueda = setTimeout(() => {
      cargarActividades(1);
    }, 300);
  };

  // Eventos de filtros
  inputBusqueda.addEventListener('input', buscarConDebounce);
  selectArchivo.addEventListener('change', () => cargarActividades(1));
  btnAnterior.addEventListener('click', () => {
    if (paginaActual > 1) {
      cargarActividades(paginaActual - 1);
    }
  });
  btnSiguiente.addEventListener('click', () => {
    cargarActividades(paginaActual + 1);
  });

  // Carga inicial
  cargarActividades();
});