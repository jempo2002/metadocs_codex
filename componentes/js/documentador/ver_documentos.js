document.addEventListener('DOMContentLoaded', () => {

    //modal crear expediente
    
    const btn_modal_expediente = document.getElementById("btn_crear");
    const modal_expediente = document.getElementById("modal_expediente");
    const btn_cerrar = document.getElementById("close");
    
    
    
    btn_modal_expediente.addEventListener("click", ()=>{
        if(modal_expediente.style.display == 'flex'){
            modal_expediente.style.display='none';
        } else{
            modal_expediente.style.display='flex';
        }
    })

    btn_cerrar.addEventListener("click", ()=>{
        if(modal_expediente.style.display == 'flex'){
            modal_expediente.style.display='none';
        } else{
            modal_expediente.style.display='flex';
        }
    });


    // modal, mensaje de subida exitoso

    const m_subida = document.getElementById("modalOverlay")
    const b_subida = document.getElementById("mrd"

    
    )
    
    b_subida.addEventListener("click", ()=>{
           if(m_subida.style.display == 'none'){
            m_subida.style.display='flex';
        } else{
            m_subida.style.display='none';
        }
    })

    



});