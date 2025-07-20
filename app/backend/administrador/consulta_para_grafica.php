 
 <!-- este archivo es el encargado en subir informacion estadistico del backend, si se quiere subir nueva info aqui se debe poner-->

 <?php

 require_once '../../helpers/q.php';




            // Total documentos
            $query = "SELECT COUNT(*) as total FROM documentos";
            $result = mysqli_query($conn, $query);
            $totalDocs = mysqli_fetch_assoc($result)['total'];

            // Total áreas
            $query = "SELECT COUNT(DISTINCT id_area) as total FROM documentos";
            $result = mysqli_query($conn, $query);
            $totalAreas = mysqli_fetch_assoc($result)['total'];

            // Documentos activos
            $query = "SELECT COUNT(*) as total FROM documentos WHERE estado_retencion = 'activo'";
            $result = mysqli_query($conn, $query);
            $docsActivos = mysqli_fetch_assoc($result)['total'];

            // Documentos rechazados
            $query = "SELECT COUNT(*) as total FROM documentos WHERE estado = 'rechazado'";
            $result = mysqli_query($conn, $query);
            $docsRechazados = mysqli_fetch_assoc($result)['total'];

            // Documentos archivados 
            $query = "SELECT COUNT(*) as total FROM documentos WHERE estado_retencion = 'archivado'";
            $result = mysqli_query($conn, $query);
            $totalArchivado = mysqli_fetch_assoc($result)['total'];


            // Tottal carpetaS

            $query = "SELECT COUNT(*) as total FROM expedientes WHERE estado  = 'aprobado'";
            $result = mysqli_query($conn, $query);
            $totalCarpetas = mysqli_fetch_assoc($result)['total'];


            //Total usuarios 

            $query = "SELECT COUNT(*) as total FROM usuarios";
            $result = mysqli_query($conn, $query);
            $total_usuarios = mysqli_fetch_assoc($result)['total']; 

            //usuarios Activos  

            $query = "SELECT COUNT(*) as total FROM usuarios WHERE estado = 'activo'";
            $result = mysqli_query($conn, $query);
            $usuarios_activos  = mysqli_fetch_assoc($result)['total']; 

            //usuarios Inactivos  

            $query = "SELECT COUNT(*) as total FROM usuarios WHERE estado = 'inactivo'";
            $result = mysqli_query($conn, $query);
            $usuarios_inactivos  = mysqli_fetch_assoc($result)['total']; 


            //Areas unicas 

            $query = "SELECT COUNT(DISTINCT id_area) as total FROM usuarios";
            $result = mysqli_query($conn, $query);
            $areas_unicas= mysqli_fetch_assoc($result)['total'];



            



            ?>