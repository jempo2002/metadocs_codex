<?php 
require_once '../../helpers/conexio_graficas.php';
require_once '../../helpers/verificacion_roles.php';
require_once '../../backend/administrador/consulta_docs.php';
require_once '../../backend/administrador/consulta_para_grafica.php';
AutorizacionRol('administrador');

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Metadocs</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" href="../../../componentes/img/logopng.png" type="image/x-icon">
    <link rel="stylesheet" href="../../../componentes/css/admin/panel.css">
    <link rel="stylesheet" href="../../../componentes/css/admin/control.css">
     <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
     <link rel="stylesheet" href="../../../componentes/css/admin/panel_control_graficas.css">
    <script src="../../../componentes/js/admin/panel.js"></script>
    <script src="../../../componentes/js/admin/grafica_documentos.js"></script>
</head>
<body>
    <header id="cabezote">
        <i class="bi bi-list" id="menu_opciones"></i>
        
    </header>

    <main id="cuerpo">
         <nav id="menu-lateral" class="menu-lateral">
    <figure id="img_menu">
        <img src="../../../componentes/img/image.png" alt="imagen del menu lateral">
    </figure>
    <ul>
        <!-- Opciones principales del menú -->
        <div class="menu-opciones-principales">
            <li>
                <a href="../admin/panel_control.php" class="activo">
                    <i class="bi bi-bar-chart-line"></i>
                    Panel Control
                </a>
            </li>

              <li>
                <a href="../admin/pista_auditoria.php">
                    <i class="bi bi-journal-check"></i>
                    Actividades usuarios
                </a>
            </li>

            <li class="gestion_usuario">
                <a href="#" id="gestion-usuarios"><i class="bi bi-people"></i> Gestión Usuarios</a>
                <ul class="sub_menu gestion-submenu" id="sub_menu">
                    <li><a href="../../vistas/admin/creacion_usuario.php"><i class="bi bi-person-plus"></i> Crear usuario</a></li>
                    <li><a href="../admin/ver_usuarios.php" ><i class="bi bi-eye"></i> Ver usuario</a></li>
                </ul>
            </li>
            
       
            
            <li class="gestion-usuarios">
                <a href="#" id="cerrado-usuarios"><i class="bi bi-person"></i> Admin</a>
                <ul class="sub_menu usuario-submenu" id="sub_menu">
                    <li><a href="../log/informacion_usuario.php"><i class="bi bi-info-circle"></i> Info usuario</a></li>
                    <li><a href="../log/nueva_contraseña.php"><i class="bi bi-key-fill"></i> Cambiar contraseña</a></li>
                </ul>
            </li>
            
            <li class="solo_mobil">
                <a href="#" id="solo_mobil"><i class="bi bi-arrow-left"></i> Volver</a>
            </li>
        </div>

        <!-- Botón cerrar sesión separado -->
        <li class="cerrar-sesion-separado">
            <a href="#" id="cerrar_sesion"><i class="bi bi-box-arrow-left"></i>Cerrar sesión</a>
        </li>
    </ul>
</nav>
       
       <section id="admin-contenido" class="admin">
            
         


    <div class="container">
        <div class="header">
            <h1>Dashboard de Documentos</h1>
            <p>Análisis estadístico de archivos</p>
        </div>

        <!-- Estadísticas generales -->
        <p class="section-header resumen"><i class="bi bi-speedometer"></i> Resumen General</p>
        <div class="stats-grid">

            
           
            <div class="stat-card">
                <div class="stat-number"><?php echo $totalDocs; ?></div>
                <div class="stat-label">Total Documentos</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $totalAreas; ?></div>
                <div class="stat-label">Áreas Diferentes</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $docsActivos; ?></div>
                <div class="stat-label">Documentos Activos</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $docsRechazados; ?></div>
                <div class="stat-label">Documentos Rechazados</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $totalArchivado; ?></div>
                <div class="stat-label">Documentos Archivados</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $totalCarpetas; ?></div>
                <div class="stat-label">Total Carpetas</div>
            </div>


        </div>
    <p class="section-header resumen"><i class="bi bi-info-circle-fill"></i> Informacion del sistema</p>

        <div class="system-info">
            
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-icon">
                        <i class="bi bi-database-fill"></i>
                    </div>
                    <div class="info-details">
                        <h4>Base de Datos</h4>
                        <span>MySQL - Conectado</span>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon">
                        <i class="bi bi-hdd-stack-fill"></i>
                    </div>
                    <div class="info-details">
                        <h4>Servidor</h4>
                        <span><?php echo $host; ?></span>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon">
                       <i class="bi bi-calendar-fill"></i>
                    </div>
                    <div class="info-details">
                        <h4>Última Actualización</h4>
                        <span><?php date_default_timezone_set('America/Bogota'); echo date('d/m/Y H:i'); ?></span>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon">
                       <i class="bi bi-person-fill-gear"></i>
                    </div>
                    <div class="info-details">
                        <h4>Usuario Administrador</h4>
                        <span><?php echo htmlspecialchars($usuario['nombres']);?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos de Documentos - SECCIÓN MEJORADA -->
        <p class="section-header analisis"><i class="bi bi-bar-chart-line"></i> Análisis y Estadísticas</p>
        <div class="main-panel">
            <div class="chart-container">
                <div class="chart-header">
                    <h3 class="chart-title">Análisis de Documentos</h3>
                    <div class="chart-controls">
                        <button class="chart-btn active" onclick="cambiarGraficoDocumentos('mes')">Por Mes</button>
                        <button class="chart-btn" onclick="cambiarGraficoDocumentos('area')">Por Área</button>
                        <button class="chart-btn" onclick="cambiarGraficoDocumentos('estado')">Por Estado</button>
                        <button class="chart-btn" onclick="cambiarGraficoDocumentos('tipo')">Por Tipo</button>
                    </div>
                </div>
                <div class="chart-wrapper">
                    <canvas id="documentosChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Paleta de colores complementarios basada en #3D688A
        const colorPalette = {
            primary: '#3D688A',
            secondary: '#5B8DB3',
            accent1: '#8AAFCC',
            accent2: '#B3D1E6',
            complement1: '#121f29',
            complement2: '#A67C52',
            success: '#243E52',
            warning: '#3d8a86',
            danger: '#B35B5B',
            info: '#5B8AB3'
        };

        // Datos para todos los gráficos de documentos
        <?php
        // Datos para gráfico de documentos por mes
        $query = "
            SELECT 
                DATE_FORMAT(fecha_creacion, '%Y-%m') as mes,
                COUNT(*) as cantidad
            FROM documentos 
            GROUP BY DATE_FORMAT(fecha_creacion, '%Y-%m')
            ORDER BY mes
        ";
        $result = mysqli_query($conn, $query);
        
        $meses = [];
        $cantidades = [];
        while($row = mysqli_fetch_assoc($result)) {
            $meses[] = $row['mes'];
            $cantidades[] = $row['cantidad'];
        }

        // Datos para gráfico de documentos por área
        $query = "
           SELECT documentos.id_area, area_acceso.nombre, COUNT(*) AS cantidad
            FROM documentos
            JOIN area_acceso ON area_acceso.id_area = documentos.id_area
            GROUP BY documentos.id_area, area_acceso.nombre
            ORDER BY cantidad DESC;
        ";
        $result = mysqli_query($conn, $query);
        
        $areasDoc = [];
        $cantidadesArea = [];
        while($row = mysqli_fetch_assoc($result)) {
            $areasDoc[] = 'Área ' . $row['nombre'];
            $cantidadesArea[] = $row['cantidad'];
        }

        // Datos para gráfico de estado de documentos
        $query = "
            SELECT 
                estado_retencion,
                COUNT(*) as cantidad
            FROM documentos 
            GROUP BY estado_retencion
        ";
        $result = mysqli_query($conn, $query);
        
        $estadosDoc = [];
        $cantidadesEstado = [];
        while($row = mysqli_fetch_assoc($result)) {
            $estadosDoc[] = ucfirst($row['estado_retencion']);
            $cantidadesEstado[] = $row['cantidad'];
        }

        // Datos para gráfico de tipo de documentos
        $query = "
            SELECT 
                tipo,
                COUNT(*) as cantidad
            FROM documentos 
            GROUP BY tipo
            ORDER BY cantidad DESC
        ";
        $result = mysqli_query($conn, $query);
        
        $tiposDoc = [];
        $cantidadesTipo = [];
        while($row = mysqli_fetch_assoc($result)) {
            $tiposDoc[] = strtoupper($row['tipo']);
            $cantidadesTipo[] = $row['cantidad'];
        }
        ?>

        // Objeto con todos los datos de documentos
        const datosDocumentos = {
            mes: {
                labels: <?php echo json_encode($meses); ?>,
                data: <?php echo json_encode($cantidades); ?>,
                tipo: 'line'
            },
            area: {
                labels: <?php echo json_encode($areasDoc); ?>,
                data: <?php echo json_encode($cantidadesArea); ?>,
                tipo: 'bar'
            },
            estado: {
                labels: <?php echo json_encode($estadosDoc); ?>,
                data: <?php echo json_encode($cantidadesEstado); ?>,
                tipo: 'doughnut'
            },
            tipo: {
                labels: <?php echo json_encode($tiposDoc); ?>,
                data: <?php echo json_encode($cantidadesTipo); ?>,
                tipo: 'pie'
            }
        };

        let chartDocumentos;
        let tipoActualDocumentos = 'mes';

        const coloresDocumentos = {
            mes: [colorPalette.primary],
            area: [colorPalette.primary, colorPalette.secondary, colorPalette.accent1, colorPalette.accent2, colorPalette.complement1, colorPalette.complement2, colorPalette.success, colorPalette.warning],
            estado: [colorPalette.primary, colorPalette.success, colorPalette.warning, colorPalette.danger],
            tipo: [colorPalette.primary, colorPalette.secondary, colorPalette.complement1, colorPalette.complement2, colorPalette.accent1, colorPalette.accent2]
        };

       // CORRECCIÓN PARA LOS GRÁFICOS DE DOCUMENTOS
function crearGraficoDocumentos(categoria) {
    const ctx = document.getElementById('documentosChart').getContext('2d');
    
    if (chartDocumentos) {
        chartDocumentos.destroy();
    }

    const datos = datosDocumentos[categoria];
    const tipoGrafico = datos.tipo;
    const coloresFondo = coloresDocumentos[categoria];

    let configGrafico = {
        type: tipoGrafico,
        data: {
            labels: datos.labels,
            datasets: [{
                label: categoria === 'mes' ? 'Documentos Subidos' : 'Cantidad de Documentos',
                data: datos.data,
                backgroundColor: tipoGrafico === 'line' ? coloresFondo[0] + '20' : coloresFondo,
                borderColor: tipoGrafico === 'line' ? coloresFondo[0] : '#ffffff',
                borderWidth: tipoGrafico === 'line' ? 3 : (tipoGrafico === 'bar' ? 0 : 3),
                borderRadius: tipoGrafico === 'bar' ? 4 : 0,
                fill: tipoGrafico === 'line' ? true : false,
                tension: tipoGrafico === 'line' ? 0.4 : 0,
                pointBackgroundColor: tipoGrafico === 'line' ? coloresFondo[0] : undefined,
                pointBorderColor: tipoGrafico === 'line' ? '#ffffff' : undefined,
                pointBorderWidth: tipoGrafico === 'line' ? 2 : undefined,
                pointRadius: tipoGrafico === 'line' ? 5 : undefined,
                hoverBorderWidth: (tipoGrafico === 'doughnut' || tipoGrafico === 'pie') ? 4 : undefined
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: tipoGrafico === 'doughnut' || tipoGrafico === 'pie',
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    borderColor: 'rgba(255,255,255,0.2)',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: true,
                    callbacks: {
                        label: function(context) {
                            // CORRECCIÓN AQUÍ - Usar la propiedad correcta según el tipo de gráfico
                            let valor;
                            
                            if (tipoGrafico === 'doughnut' || tipoGrafico === 'pie') {
                                // Para gráficos circulares, usar context.parsed
                                valor = context.parsed;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const porcentaje = ((valor / total) * 100).toFixed(1);
                                return `${context.label}: ${valor} (${porcentaje}%)`;
                            } else if (tipoGrafico === 'line') {
                                // Para gráficos de línea, usar context.parsed.y
                                valor = context.parsed.y;
                                return `${context.dataset.label}: ${valor}`;
                            } else {
                                // Para gráficos de barras, usar context.parsed.y
                                valor = context.parsed.y;
                                return `${context.dataset.label}: ${valor}`;
                            }
                        }
                    }
                }
            },
            scales: (tipoGrafico === 'doughnut' || tipoGrafico === 'pie') ? {} : {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#e0e0e0'
                    },
                    ticks: {
                        color: '#6b7280'
                    }
                },
                x: {
                    grid: {
                        color: tipoGrafico === 'bar' ? 'transparent' : '#e0e0e0',
                        display: tipoGrafico !== 'bar'
                    },
                    ticks: {
                        color: '#6b7280'
                    }
                }
            },
            animation: {
                duration: 1000,
                easing: 'easeInOutQuart'
            }
        }
    };

    // Configuración especial para gráfico de líneas
    if (tipoGrafico === 'line') {
        configGrafico.options.cutout = undefined;
    }

    // Configuración especial para gráfico doughnut
    if (tipoGrafico === 'doughnut') {
        configGrafico.options.cutout = '60%';
    }

    chartDocumentos = new Chart(ctx, configGrafico);
}

// CORRECCIÓN PARA LOS GRÁFICOS DE USUARIOS
function crearGrafico(tipo) {
    const ctx = document.getElementById('mainChart').getContext('2d');
    
    if (chart) {
        chart.destroy();
    }

    let datos, etiquetas, coloresFondo;
    
    switch(tipo) {
        case 'roles':
            etiquetas = datosUsuarios.roles.labels;
            datos = datosUsuarios.roles.data;
            coloresFondo = colores.roles.slice(0, datos.length);
            break;
        case 'areas':
            etiquetas = datosUsuarios.areas.labels;
            datos = datosUsuarios.areas.data;
            coloresFondo = colores.areas.slice(0, datos.length);
            break;
        case 'estado':
            etiquetas = datosUsuarios.estado.labels;
            datos = datosUsuarios.estado.data;
            coloresFondo = colores.estado;
            break;
    }

    const esEstado = tipo === 'estado';

    chart = new Chart(ctx, {
        type: esEstado ? 'doughnut' : 'bar',
        data: {
            labels: etiquetas,
            datasets: [{
                label: 'Cantidad de usuarios',
                data: datos,
                backgroundColor: coloresFondo,
                borderColor: coloresFondo.map(color => color + '80'),
                borderWidth: 2,
                borderRadius: esEstado ? 0 : 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: esEstado,
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    borderColor: 'rgba(255,255,255,0.2)',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: true,
                    callbacks: {
                        label: function(context) {
                            // CORRECCIÓN AQUÍ - Usar la propiedad correcta
                            let valor;
                            
                            if (esEstado) {
                                // Para gráfico doughnut (estado), usar context.parsed
                                valor = context.parsed;
                            } else {
                                // Para gráficos de barras (roles y areas), usar context.parsed.y
                                valor = context.parsed.y;
                            }
                            
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const porcentaje = ((valor / total) * 100).toFixed(1);
                            return `${context.label}: ${valor} (${porcentaje}%)`;
                        }
                    }
                }
            },
            scales: esEstado ? {} : {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        color: '#6b7280'
                    },
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                },
                x: {
                    ticks: {
                        color: '#6b7280'
                    },
                    grid: {
                        display: false
                    }
                }
            },
            animation: {
                duration: 1000,
                easing: 'easeInOutQuart'
            }
        }
    });


            // Configuración especial para gráfico de líneas
            if (tipoGrafico === 'line') {
                configGrafico.options.cutout = undefined;
            }

            // Configuración especial para gráfico doughnut
            if (tipoGrafico === 'doughnut') {
                configGrafico.options.cutout = '60%';
            }

            chartDocumentos = new Chart(ctx, configGrafico);
        }

        function cambiarGraficoDocumentos(categoria) {
            // Actualizar botones
            document.querySelectorAll('.chart-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
            
            // Cambiar gráfico
            tipoActualDocumentos = categoria;
            crearGraficoDocumentos(categoria);
        }

        // Inicializar el gráfico por defecto
        crearGraficoDocumentos('mes');
    </script>
 
       <p class="section-header usuarios"><i class="bi bi-people-fill"></i> Usuarios del sistema</p>

        <div class="container">
       

        <div class="main-panel">
            <div class="stats-container">
                
                
                <div class="stats-list">
                    <div class="stat-row total">
                        <div class="stat-row-icon"><i class="bi bi-people-fill"></i></div>
                        <div class="stat-info">
                            <div class="stat-label">Total de Usuarios</div>
                            <div class="stat-main-value"><?php echo $total_usuarios; ?></div>
                            <div class="stat-detail">Usuarios registrados en el sistema</div>
                        </div>
                    </div>

                    <div class="stat-row active">
                        <div class="stat-row-icon"><i class="bi bi-person-check-fill"></i></div>
                        <div class="stat-info">
                            <div class="stat-label">Usuarios Activos</div>
                            <div class="stat-main-value"><?php echo $usuarios_activos; ?></div>
                            <div class="stat-detail"><?php echo round(($usuarios_activos/$total_usuarios)*100, 1); ?>% del total de usuarios</div>
                        </div>
                    </div>

                    <div class="stat-row inactive">
                        <div class="stat-row-icon"><i class="bi bi-person-fill-x"></i></div>
                        <div class="stat-info">
                            <div class="stat-label">Usuarios Inactivos</div>
                            <div class="stat-main-value"><?php echo $usuarios_inactivos; ?></div>
                            <div class="stat-detail"><?php echo round(($usuarios_inactivos/$total_usuarios)*100, 1); ?>% del total de usuarios</div>
                        </div>
                    </div>

                    <div class="stat-row areas">
                        <div class="stat-row-icon"><i class="bi bi-building-fill"></i></div>
                        <div class="stat-info">
                            <div class="stat-label">Áreas Activas</div>
                            <div class="stat-main-value"><?php echo $areas_unicas; ?></div>
                            <div class="stat-detail">Departamentos con usuarios asignados</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="chart-container">
                <div class="chart-header">
                    <h3 class="chart-title">Distribución de Usuarios</h3>
                    <div class="chart-controls">
                        <button class="chart-btn active" onclick="cambiarGrafico('roles')">Por Roles</button>
                        <button class="chart-btn" onclick="cambiarGrafico('areas')">Por Áreas</button>
                        <button class="chart-btn" onclick="cambiarGrafico('estado')">Por Estado</button>
                    </div>
                </div>
                <div class="chart-wrapper">
                    <canvas id="mainChart"></canvas>
                </div>
            </div>
        </div>
    </div>
<script>
// Datos dinámicos procesados de la base de datos
<?php 
// Cantidad usuarios por rol 
$query = "SELECT rol, COUNT(*) AS cantidad 
FROM usuarios
GROUP BY rol;
";

$result = mysqli_query($conn, $query);

$roles = [];
$cantidad_rol = [];

while($row = mysqli_fetch_assoc($result)) {
    $roles[] = strtoupper($row['rol']);
    $cantidad_rol[] = $row['cantidad'];
}

// Cantidad usuarios por área 
$query = "SELECT usuarios.id_area, area_acceso.nombre, COUNT(*) AS cantidad 
FROM usuarios 
JOIN area_acceso ON area_acceso.id_area = usuarios.id_area 
GROUP BY usuarios.id_area, area_acceso.nombre 
ORDER BY cantidad DESC;";

$result = mysqli_query($conn, $query);

$areas = [];
$cantidad_area = [];

while($row = mysqli_fetch_assoc($result)){
    $areas[] = strtoupper($row['nombre']);
    $cantidad_area[] = $row['cantidad'];
}

// Estado usuarios 
$query = "SELECT usuarios.estado, COUNT(*) AS cantidad 
FROM usuarios 
GROUP BY estado;";

$result = mysqli_query($conn, $query);

$estados = [];
$cantidad_estado = [];

while($row = mysqli_fetch_assoc($result)){
    $estados[] = strtoupper($row['estado']);
    $cantidad_estado[] = $row['cantidad'];
}
?>

const datosUsuarios = {
    roles: {
        labels: <?php echo json_encode($roles); ?>,
        data: <?php echo json_encode($cantidad_rol); ?>
    },
    areas: {
        labels: <?php echo json_encode($areas); ?>,
        data: <?php echo json_encode($cantidad_area); ?>
    },
    estado: {
        labels: <?php echo json_encode($estados); ?>,
        data: <?php echo json_encode($cantidad_estado); ?>
    }
};

let chart;
let tipoActual = 'roles';

const colores = {
    roles: ['#2A4860', '#3D688A', '#5B8DB3'],
    areas: ['#2A4860', '#3D688A', '#5B8DB3', '#B3D1E6'],
    estado: ['#3D688A', '#ef4444']
};

function crearGrafico(tipo) {
    const ctx = document.getElementById('mainChart').getContext('2d');
    
    if (chart) {
        chart.destroy();
    }

    let datos, etiquetas, coloresFondo;
    
    switch(tipo) {
        case 'roles':
            etiquetas = datosUsuarios.roles.labels;
            datos = datosUsuarios.roles.data;
            coloresFondo = colores.roles.slice(0, datos.length);
            break;
        case 'areas':
            etiquetas = datosUsuarios.areas.labels;
            datos = datosUsuarios.areas.data;
            coloresFondo = colores.areas.slice(0, datos.length);
            break;
        case 'estado':
            etiquetas = datosUsuarios.estado.labels;
            datos = datosUsuarios.estado.data;
            coloresFondo = colores.estado;
            break;
    }

    const esEstado = tipo === 'estado';

    chart = new Chart(ctx, {
        type: esEstado ? 'doughnut' : 'bar',
        data: {
            labels: etiquetas,
            datasets: [{
                label: 'Cantidad de usuarios',
                data: datos,
                backgroundColor: coloresFondo,
                borderColor: coloresFondo.map(color => color + '80'),
                borderWidth: 2,
                borderRadius: esEstado ? 0 : 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: esEstado,
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    borderColor: 'rgba(255,255,255,0.2)',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: true,
                    callbacks: {
                        label: function(context) {
                            // CORRECCIÓN AQUÍ - Usar la propiedad correcta según el tipo de gráfico
                            let valor;
                            
                            if (esEstado) {
                                // Para gráfico doughnut (estado), usar context.parsed
                                valor = context.parsed;
                            } else {
                                // Para gráficos de barras (roles y areas), usar context.parsed.y
                                valor = context.parsed.y;
                            }
                            
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const porcentaje = ((valor / total) * 100).toFixed(1);
                            return `${context.label}: ${valor} (${porcentaje}%)`;
                        }
                    }
                }
            },
            scales: esEstado ? {} : {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        color: '#6b7280'
                    },
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                },
                x: {
                    ticks: {
                        color: '#6b7280'
                    },
                    grid: {
                        display: false
                    }
                }
            },
            animation: {
                duration: 1000,
                easing: 'easeInOutQuart'
            }
        }
    });
}

function cambiarGrafico(tipo) {
    // Actualizar botones
    document.querySelectorAll('.chart-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');
    
    // Cambiar gráfico
    tipoActual = tipo;
    crearGrafico(tipo);
}

// Inicializar el gráfico por defecto
crearGrafico('roles');
</script>


<?php
// Cerrar la conexión al final
mysqli_close($conn);
?>
            
        </section>

      <script></script>
    </main>

    
    <?php include '../../vistas/log/modal_cerrar_sesion.php'; ?>
</body>

</html>
