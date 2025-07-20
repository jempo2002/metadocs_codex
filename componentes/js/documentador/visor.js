 const modalVisor = document.getElementById("modal_visor");
        const visorDocumento = document.getElementById("visor_documento");
        const cerrarVisor = document.getElementById("cerrar_visor");

        function verDocumento(archivo, extension) {
            extension = extension.toLowerCase();
            visorDocumento.src = "../../helpers/ver_documentos.php?file=" + archivo;
            modalVisor.style.display = "flex";
        }

        // Nueva función para abrir en nueva ventana/pestaña
        function abrirNuevaVentana(archivo) {
            const url = "../../helpers/ver_documentos.php?file=" + archivo;
            window.open(url, '_blank', 'width=1000,height=700,scrollbars=yes,resizable=yes');
        }

        // Event listeners para cerrar modal
        cerrarVisor.onclick = function () {
            modalVisor.style.display = "none";
            visorDocumento.src = ""; // Limpiar visor al cerrar
        };

        window.onclick = function (event) {
            if (event.target === modalVisor) {
                modalVisor.style.display = "none";
                visorDocumento.src = "";
            }
        };