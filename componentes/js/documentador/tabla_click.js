 document.querySelectorAll("tbody tr").forEach((row) => {
        row.addEventListener("click", (event) => {
          // Ignorar clics en el botón del menú
          if (!event.target.closest(".btn_accion")) {
            const url = row.getAttribute("data-url");
            if (url) {
              window.location.href = url;
            }
          }
        });
      });


      const btn_documento = document.getElementById('btn_documento');
      const modal_esc_sub = document.getElementById('modal_escanear_subir');
      const cerrar = document.querySelector('.cerrar_modal_esc_sub ');

      btn_documento.addEventListener("click", ()=>{

          if(modal_esc_sub.style.display == 'flex'){
            modal_esc_sub.style.display = 'none';
          }else{
            modal_esc_sub.style.display = 'flex';
          }
      });
      cerrar.addEventListener("click", ()=>{
        
          if(modal_esc_sub.style.display == 'flex'){
              modal_esc_sub.style.display='none';
          } else{
              modal_esc_sub.style.display='flex';
          }
      });
