document.addEventListener("DOMContentLoaded", function () {
    const boton_opciones = document.getElementById("menu_opciones");
    const menu_lateral = document.getElementById("menu-lateral");
    const boton_volver = document.getElementById("solo_mobil");

    // Submenús
    const mostrar_menu_usuarios = document.getElementById("gestion-usuarios");
    const submenu_usuarios = document.querySelector(".gestion-submenu");

    const mostrar_menu_usuario = document.getElementById("cerrado-usuarios");
    const submenu_usuario = document.querySelector(".usuario-submenu");

    // Abrir/cerrar menú lateral
    if (boton_opciones && menu_lateral) {
        boton_opciones.addEventListener("click", function () {
            menu_lateral.style.transform = "translateX(0)";
        });
    }

    if (boton_volver && menu_lateral) {
        boton_volver.addEventListener("click", function () {
            menu_lateral.style.transform = "translateX(-100%)";
        });
    }

    if (menu_lateral) {
        let posicion_inicial = 0;

        menu_lateral.addEventListener('touchstart', function (evento) {
            posicion_inicial = evento.touches[0].clientX;
        });

        menu_lateral.addEventListener('touchend', function (evento) {
            const posicion_final = evento.changedTouches[0].clientX;
            const diferencia = posicion_final - posicion_inicial;
            if (diferencia < -50) {
                menu_lateral.style.transform = "translateX(-100%)";
            }
        });
    }

    // Toggle para submenús
    if (mostrar_menu_usuarios && submenu_usuarios) {
        mostrar_menu_usuarios.addEventListener("click", function (e) {
            e.preventDefault();
            submenu_usuarios.classList.toggle("mostrar");
        });
    }

    if (mostrar_menu_usuario && submenu_usuario) {
        mostrar_menu_usuario.addEventListener("click", function (e) {
            e.preventDefault();
            submenu_usuario.classList.toggle("mostrar");
        });
    }

    // ===== EFECTO DE SELECCIÓN EN MENÚS =====
    const enlaces_menu = document.querySelectorAll("#menu-lateral a");

    enlaces_menu.forEach(enlace => {
        enlace.addEventListener("click", function () {
            enlaces_menu.forEach(e => e.classList.remove("selected"));
            this.classList.add("selected");
        });
    });



    const btn_cerrar = document.getElementById("cerrar_sesion");
    const modal = document.getElementById("modal");

    btn_cerrar.addEventListener("click", ()=>{
        if (modal.style.display == 'flex') {
            modal.style.display = 'none';
        }else{
            modal.style.display = 'flex';
        }
    })
});
