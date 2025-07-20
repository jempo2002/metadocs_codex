   // Función para filtrar la tabla
        function filtrarTabla() {
            const busqueda = document.getElementById('entradaBusqueda').value.toLowerCase().trim();
            const categoriaFiltro = document.getElementById('filtroCategoria').value.toLowerCase();
            const tbody = document.getElementById('cuerpoTabla');
            const filas = tbody.getElementsByTagName('tr');
            let filasVisibles = 0;
            let hayDocumentos = false;

            // Verificar si hay documentos reales (no solo la fila vacía)
            for (let i = 0; i < filas.length; i++) {
                const fila = filas[i];
                if (fila.id !== 'filaVacia' && fila.id !== 'filaSinResultados') {
                    hayDocumentos = true;
                    break;
                }
            }

            // Si no hay documentos, no hacer filtrado
            if (!hayDocumentos) {
                return;
            }

            // Remover fila de "sin resultados" si existe
            const filaSinResultados = document.getElementById('filaSinResultados');
            if (filaSinResultados) {
                filaSinResultados.remove();
            }

            // Ocultar fila vacía original si existe
            const filaVacia = document.getElementById('filaVacia');
            if (filaVacia) {
                filaVacia.style.display = 'none';
            }

            // Filtrar filas
            for (let i = 0; i < filas.length; i++) {
                const fila = filas[i];
                
                // Saltar filas especiales
                if (fila.id === 'filaSinResultados' || fila.id === 'filaVacia') {
                    continue;
                }
                
                const celdas = fila.getElementsByTagName('td');
                if (celdas.length >= 2) {
                    const nombre = celdas[0].textContent.toLowerCase().trim();
                    const categoria = celdas[1].textContent.toLowerCase().trim();
                    
                    const coincideNombre = busqueda === '' || nombre.includes(busqueda);
                    const coincideCategoria = categoriaFiltro === '' || categoria === categoriaFiltro;
                    
                    if (coincideNombre && coincideCategoria) {
                        fila.style.display = '';
                        filasVisibles++;
                    } else {
                        fila.style.display = 'none';
                    }
                }
            }

            // Si no hay filas visibles después del filtrado, mostrar mensaje
            if (filasVisibles === 0 && hayDocumentos) {
                const nuevaFila = document.createElement('tr');
                nuevaFila.id = 'filaSinResultados';
                nuevaFila.innerHTML = `
                    <td colspan="5" style="text-align: center; padding: 20px; color: #6c757d; font-style: italic;">
                        No se encontraron documentos que coincidan con los filtros seleccionados.
                    </td>
                `;
                tbody.appendChild(nuevaFila);
            }
        }

        

        // Función para limpiar filtros
        function limpiarFiltros() {
            document.getElementById('entradaBusqueda').value = '';
            document.getElementById('filtroCategoria').value = '';
            filtrarTabla();
        }

        // Inicialización cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', function() {
            // Cualquier inicialización adicional aquí
            console.log('Página de archivo histórico cargada correctamente');
        });