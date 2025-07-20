const modal = document.getElementById("modal");
const btn_cancel = document.getElementById("cancelar_btn");
const btn_cerrar = document.getElementById("cerrar");

btn_cancel.addEventListener("click", ()=>{
    if(modal.style.display == 'none'){
        modal.style.display = 'flex';
    }else{
        modal.style.display = 'none';
    }

    

})


btn_cerrar.addEventListener("click", ()=>{

   

    if(modal.style.display == 'none'){
        modal.style.display = 'flex';
    }else{
        modal.style.display = 'none';
    }

    

})