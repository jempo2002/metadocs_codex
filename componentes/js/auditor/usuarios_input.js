document.addEventListener('DOMContentLoaded', function() {
    // Elementos para documentadores
    const inputResponsable = document.getElementById('responsable');
    const inputResponsableId = document.getElementById('responsable_id');
    const dropdownResponsable = document.getElementById('usuario-dropdown');
    
    // Elementos para expedientes
    const inputExpediente = document.getElementById('expediente');
    const inputExpedienteId = document.getElementById('expediente_id');
    const dropdownExpediente = document.getElementById('expediente-dropdown');
    
    let documentadoresData = [];
    let expedientesData = [];
    
    // Cargar datos iniciales
    cargarDatos();
    
    // Función para cargar datos via AJAX
    function cargarDatos() {
        fetch('../../backend/auditor/lista_documentadores.php?ajax=1')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    documentadoresData = data.documentadores || [];
                    expedientesData = data.expedientes || [];
                } else {
                    console.error('Error al cargar datos');
                    documentadoresData = [];
                    expedientesData = [];
                }
            })
            .catch(error => {
                console.error('Error en la petición:', error);
                documentadoresData = [];
                expedientesData = [];
                
                // Usar datos del window como fallback
                if (window.documentadores) {
                    documentadoresData = window.documentadores;
                }
                if (window.expedientes) {
                    expedientesData = window.expedientes;
                }
            });
    }
    
    // Función genérica para mostrar dropdown
    function mostrarDropdown(data, dropdown, inputField, inputId, tipo) {
        dropdown.innerHTML = '';
        
        if (data.length === 0) {
            dropdown.innerHTML = `<div class="dropdown-item">No hay ${tipo} disponibles</div>`;
            dropdown.style.display = 'block';
            return;
        }
        
        data.forEach(item => {
            const element = document.createElement('div');
            element.className = 'dropdown-item';
            element.textContent = item.nombre;
            element.dataset.id = item.id;
            
            element.addEventListener('click', function() {
                inputField.value = item.nombre;
                inputId.value = item.id;
                dropdown.style.display = 'none';
                
                // Limpiar selección previa
                dropdown.querySelectorAll('.dropdown-item').forEach(el => 
                    el.classList.remove('selected')
                );
            });
            
            dropdown.appendChild(element);
        });
        
        dropdown.style.display = 'block';
    }
    
    // Función genérica para filtrar datos
    function filtrarDatos(texto, data, dropdown, inputField, inputId, tipo) {
        dropdown.innerHTML = '';
        
        const filtrados = data.filter(item => 
            item.nombre.toLowerCase().includes(texto.toLowerCase())
        );
        
        if (filtrados.length === 0) {
            dropdown.innerHTML = '<div class="dropdown-item">No se encontraron resultados</div>';
            dropdown.style.display = 'block';
            return;
        }
        
        filtrados.forEach(item => {
            const element = document.createElement('div');
            element.className = 'dropdown-item';
            element.textContent = item.nombre;
            element.dataset.id = item.id;
            
            element.addEventListener('click', function() {
                inputField.value = item.nombre;
                inputId.value = item.id;
                dropdown.style.display = 'none';
                
                // Limpiar selección previa
                dropdown.querySelectorAll('.dropdown-item').forEach(el => 
                    el.classList.remove('selected')
                );
            });
            
            dropdown.appendChild(element);
        });
        
        dropdown.style.display = 'block';
    }
    
    // Función para manejar navegación con teclado
    function manejarNavegacionTeclado(e, dropdown) {
        const items = dropdown.querySelectorAll('.dropdown-item');
        let selectedIndex = -1;
        
        // Encontrar item seleccionado actual
        items.forEach((item, index) => {
            if (item.classList.contains('selected')) {
                selectedIndex = index;
            }
        });
        
        switch(e.key) {
            case 'ArrowDown':
                e.preventDefault();
                if (selectedIndex < items.length - 1) {
                    if (selectedIndex >= 0) items[selectedIndex].classList.remove('selected');
                    items[selectedIndex + 1].classList.add('selected');
                }
                break;
                
            case 'ArrowUp':
                e.preventDefault();
                if (selectedIndex > 0) {
                    items[selectedIndex].classList.remove('selected');
                    items[selectedIndex - 1].classList.add('selected');
                }
                break;
                
            case 'Enter':
                e.preventDefault();
                if (selectedIndex >= 0) {
                    items[selectedIndex].click();
                }
                break;
                
            case 'Escape':
                dropdown.style.display = 'none';
                break;
        }
    }
    
    // Event listeners para DOCUMENTADORES
    if (inputResponsable) {
        inputResponsable.addEventListener('focus', function() {
            if (this.value.trim() === '') {
                mostrarDropdown(documentadoresData, dropdownResponsable, inputResponsable, inputResponsableId, 'documentadores');
            } else {
                filtrarDatos(this.value, documentadoresData, dropdownResponsable, inputResponsable, inputResponsableId, 'documentadores');
            }
        });
        
        inputResponsable.addEventListener('input', function() {
            const valor = this.value.trim();
            if (valor === '') {
                inputResponsableId.value = '';
                mostrarDropdown(documentadoresData, dropdownResponsable, inputResponsable, inputResponsableId, 'documentadores');
            } else {
                filtrarDatos(valor, documentadoresData, dropdownResponsable, inputResponsable, inputResponsableId, 'documentadores');
            }
        });
        
        inputResponsable.addEventListener('keydown', function(e) {
            manejarNavegacionTeclado(e, dropdownResponsable);
        });
    }
    
    // Event listeners para EXPEDIENTES
    if (inputExpediente) {
        inputExpediente.addEventListener('focus', function() {
            if (this.value.trim() === '') {
                mostrarDropdown(expedientesData, dropdownExpediente, inputExpediente, inputExpedienteId, 'expedientes');
            } else {
                filtrarDatos(this.value, expedientesData, dropdownExpediente, inputExpediente, inputExpedienteId, 'expedientes');
            }
        });
        
        inputExpediente.addEventListener('input', function() {
            const valor = this.value.trim();
            if (valor === '') {
                inputExpedienteId.value = '';
                mostrarDropdown(expedientesData, dropdownExpediente, inputExpediente, inputExpedienteId, 'expedientes');
            } else {
                filtrarDatos(valor, expedientesData, dropdownExpediente, inputExpediente, inputExpedienteId, 'expedientes');
            }
        });
        
        inputExpediente.addEventListener('keydown', function(e) {
            manejarNavegacionTeclado(e, dropdownExpediente);
        });
    }
    
    // Cerrar dropdowns al hacer click fuera
    document.addEventListener('click', function(e) {
        if (inputResponsable && dropdownResponsable && 
            !inputResponsable.contains(e.target) && !dropdownResponsable.contains(e.target)) {
            dropdownResponsable.style.display = 'none';
        }
        
        if (inputExpediente && dropdownExpediente && 
            !inputExpediente.contains(e.target) && !dropdownExpediente.contains(e.target)) {
            dropdownExpediente.style.display = 'none';
        }
    });
});