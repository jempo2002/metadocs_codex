document.addEventListener('DOMContentLoaded', () => {
    const inputBusqueda = document.getElementById('buscar_usuario');
    const rolFilter = document.getElementById('roles');
    const tabla = document.querySelector('table tbody');

    
    function filtrarUsuarios() {
        const textoBusqueda = inputBusqueda.value.toLowerCase();
        const rolSeleccionado = rolFilter.value.toLowerCase();
        const filas = tabla.getElementsByTagName('tr');

        for (let fila of filas) {
            const celdas = fila.getElementsByTagName('td');
            if (celdas.length === 0) continue;

            const nombre = celdas[0].textContent.toLowerCase();
            const rol = celdas[2].textContent.toLowerCase();

            const coincideTexto = nombre.includes(textoBusqueda);
            const coincideRol = !rolSeleccionado || rol === rolSeleccionado;

            fila.style.display = (coincideTexto && coincideRol) ? '' : 'none';
        }
    }

    
    function inicializarFiltroRoles() {
        const roles = new Set();
        const filas = tabla.getElementsByTagName('tr');

        for (let fila of filas) {
            const celdas = fila.getElementsByTagName('td');
            if (celdas.length > 0) {
                roles.add(celdas[2].textContent.trim());
            }
        }


        while (rolFilter.options.length > 1) {
            rolFilter.remove(1);
        }

        roles.forEach(rol => {
            const option = new Option(rol, rol.toLowerCase());
            rolFilter.add(option);
        });
    }

 
    inicializarFiltroRoles();
    inputBusqueda.addEventListener('input', filtrarUsuarios);
    rolFilter.addEventListener('change', filtrarUsuarios);
});
