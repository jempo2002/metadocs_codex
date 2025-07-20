document.addEventListener('DOMContentLoaded', () => {
    const modalEliminar = document.getElementById('modal-eliminar');
    const closeEliminar = modalEliminar.querySelector('.close');
    const cancelarBtn = modalEliminar.querySelector('.btn-cancelar');
    const botonesEliminar = document.querySelectorAll('.bi-trash');


      window.addEventListener('click', e => {
    if (e.target === modalEliminar) {
      modalEliminar.style.display = 'none';
    }
  });

    function abrirModalEliminar() {
        modalEliminar.style.display = 'flex';
    }

    function cerrarModalEliminar() {
        modalEliminar.style.display = 'none';
    }

    botonesEliminar.forEach(button => {
        button.addEventListener('click', abrirModalEliminar);
    });

    closeEliminar.addEventListener('click', cerrarModalEliminar);
    cancelarBtn.addEventListener('click', cerrarModalEliminar);


   //==== capturar correo y enviarlo a php, con el fin de borrar el usuario ====//

   let correoAEliminar = '';
    
    // Capturar correo
    document.querySelectorAll('.bi-trash').forEach(icono => {
        icono.addEventListener('click', function(e) {
            const fila = e.target.closest('tr');
            correoAEliminar = fila.getElementsByTagName('td')[1].textContent.trim();
        });
    });
    

  

    //enviar a php 


   document.querySelector('.btn-eliminar').addEventListener('click', function() {
    console.log('Correo a eliminar:', correoAEliminar);

    const formData = new FormData();
    formData.append('accion', 'eliminar_usuario');
    formData.append('correo', correoAEliminar);

    fetch('../../../app/backend/administrador/editar_eliminar.php', {
        method: 'POST',
        body: formData
    })
        .then(response => {
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        return response.text();
    })
    .then(() => {
         alert('Usuario eliminado correctamente.');

    })
    .catch(() => {
        alert('Error al intentar eliminar el usuario.');
    });
});

});

