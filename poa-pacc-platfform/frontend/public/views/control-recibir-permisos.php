<?php
if (!isset($_SESSION)) {
    session_start();
}
$secretariaAca = 'S_AC';
$jefeDepto = 'J_D';
$decano = 'D_F';
if (($_SESSION['abrevTipoUsuario'] != $secretariaAca) &&
    ($_SESSION['abrevTipoUsuario'] != $jefeDepto) && 
    ($_SESSION['abrevTipoUsuario'] != $decano)) {
    header('Location: 401.php');
}
if (!isset($_SESSION['correoInstitucional'])) {
    header('Location: 401.php');
}
include('../partials/doctype.php');
include('verifica-session.php');
?>


<title>Control de recibimiento de solicitudes de permisos</title>
<!--En esta zona podran poner estilos propios de la vista-->
<link rel="stylesheet" href="../css/sweet-alert-two/sweetalert2.min.css">


<link rel="stylesheet" href="../js/data-tables/datatables.min.css">
<link rel="stylesheet" href="../js/data-tables/DataTables/css/dataTables.bootstrap4.min.css">

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css" integrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous">

</head>

<body id="body-pd"  >
    <?php include('../layouts/Nabvar.php'); ?>
    <?php include('../layouts/Sidebar.php'); ?>

    <div id="profile-card" class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header indigo darken-4 text-white">
                        <h5 class="font-weight-bolder"> Maneja el control de las solicitudes de permisos que recibes desde este panel</h5>
                    </div>
                    <div class="card-body  blue lighten-5">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-xl-2 col-lg-2 col-md-2 hidden-sm-down hidden-down">
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">
                                    <div class="card border border-warning b-2 rounded mb-0 animate__animated animate__flipInY">
                                        <div class="card-header amber accent-4">
                                            <div class="row">
                                                <div class="col-xl-9 col-lg-9 col-md-9 col-sm-8">
                                                    <h6 class="text-white font-weight-bold">
                                                        Revisar Solicitudes Pendientes
                                                    </h6>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4">
                                                    <img src="../img/control-envio-permisos/solicitud-icono.svg" alt="Control de envio Permisos">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="view overlay">
                                                <img class="card-img-top" src="../img/control-recibir-permisos/ver-pendientes.svg" alt="ver permisos pendientes">
                                            </div>
                                            <hr>
                                            <button type="button" 
                                                class="btn btn-indigo btn-block" 
                                                data-toggle="modal" 
                                                data-target="#modalVerSolicitudesPendientes"
                                                <?php 
                                                    if ($_SESSION['abrevTipoUsuario'] === 'S_AC') {
                                                        echo 'onclick="verSolicitudesPendientesSecAcademica()"';
                                                    }else if ($_SESSION['abrevTipoUsuario'] === 'J_D') {
                                                        echo 'onclick="obtenerIdDepartamentoJefeConCoordinador()"';
                                                    }else if ($_SESSION['abrevTipoUsuario'] === 'D_F') {
                                                        echo 'onclick="verSolicitudesPendientesDecano()"';
                                                    }
                                                
                                                ?>
                                                
                                            >
                                                Revisar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">
                                    <div class="card border border-warning b-2 rounded mb-0 animate__animated animate__flipInY">
                                        <div class="card-header amber accent-4">
                                            <div class="row">
                                                <div class="col-xl-9 col-lg-9 col-md-9 col-sm-8">
                                                    <h6 class="text-white font-weight-bold">
                                                        Historial de Solicitudes Revisadas
                                                    </h6>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4">
                                                    <img src="../img/control-envio-permisos/solicitud-icono.svg" alt="Control de Usuarios">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <br />
                                            <div class="view overlay">
                                                <img class="card-img-top" src="../img/control-recibir-permisos/ver-historial.svg" alt="ver historial permisos">
                                            </div>
                                            <hr>
                                            <button type="button" 
                                                id="idUsuario" 
                                                class="btn btn-indigo btn-block" 
                                                data-toggle="modal" 
                                                data-target="#modalVisualizarHistorialSolicitudes" 
                                                value="<?php echo $_SESSION['idUsuario']?>"
                                                onclick="verHistorialSolicitudes()"
                                            >
                                                Ver
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-2 hidden-sm-down hidden-down">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer amber accent-4">

                    </div>
                </div>
            </div>
        </div>
    </div>


    <!--modales-->
    
    <!--Visualizar solicitudes Pendientes-->
    <div class="modal fade" id="modalVerSolicitudesPendientes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Solicitudes de Permisos Pendientes de Revisar</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table" id="listado-solicitudes">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Tipo Solicitud</th>
                                    <th scope="col">Fecha Recibida</th>
                                    <th scope="col">Recibida de</th>
                                    <th scope="col"><center>Ver Solicitud</center></th>
                                    <th scope="col"><center>Adjuntos</center></th>
                                    <th scope="col">Observaciones</th>
                                    <th scope="col"><center>Estado</center></th>
                                    <th scope="col"><center>Actualizar</center></th>   
                                </tr>
                            </thead>
                            <tbody id="solicitudes">

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>


    <!--Visualizar historial solicitudes-->
    <div class="modal fade" id="modalVisualizarHistorialSolicitudes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Historial de Solicitudes de Permisos Revisados</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table" id="listado-solicitudes-revisadas">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Tipo Solicitud</th>
                                    <th scope="col">Fecha Revisión</th>
                                    <th scope="col">Recibida de</th>
                                    <th scope="col"><center>Estado<center></th>
                                    <th scope="col"><center>Ver Solicitud</center></th>
                                    <th scope="col"><center>Adjuntos</center></th>
                                    <th scope="col">Observaciones</th>
                                </tr>
                            </thead>
                            <tbody id="solicitudes revisadas">

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>


    <!--Ver Solicitud Recibida-->
    <div class="modal fade" id="modalVerSolicitudRecibida" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Solicitud Recibida</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-visualizar-solicitud" class="text-center" style="color: #757575;">
                        <div class="form-row">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="md-form">
                                        <input 
                                            type="text" 
                                            id="V-nombreEmpleado" 
                                            class="form-control" 
                                            maxlength="100" 
                                            minlength="1"
                                            disabled 
                                            required
                                        >
                                        <span id="errorsV-nombreEmpleado" class="text-danger text-small d-none">

                                        </span>
                                        <label for="V-nombreEmpleado" id="labelV-nombreEmpleado" name="labelV-nombreEmpleado">
                                        Nombre Completo:
                                        </label>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="md-form">
                                        <input 
                                            type="text" 
                                            id="V-codigoEmpleado" 
                                            class="form-control" 
                                            maxlength="5" 
                                            minlength="1"
                                            disabled 
                                            required>
                                        <span id="errorsV-codigoEmpleado" class="text-danger text-small d-none">
                                        </span>
                                        <label for="V-codigoEmpleado" id="labelV-codigoEmpleado">
                                            No. de Empleado:
                                        </label>
                                    </div>
                                </div>
                                
                        </div>

                        <div class="form-row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <div class="md-form">
                                    <input type="text" 
                                        id="V-departamentoLabora" 
                                        class="form-control" 
                                        maxlength="150" 
                                        minlength="1" 
                                        disabled
                                        required
                                    >
                                    <span id="errorsV-departamentoLabora" class="text-danger text-small d-none">
                                    </span>
                                    <label for="V-departamentoLabora" id="labelV-departamentoLabora">
                                    Departamento donde labora:
                                    </label>
                                </div>
                            </div>
                               
                        </div>

                        <div class="form-row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <div class="md-form">
                                    <label for="V-motivoPermiso" class="" id="labelV-motivoPermiso" name="labelV-motivoPermiso">
                                        Solicito permiso por motivo de:
                                    </label>
                                   
                                    <textarea id="V-motivoPermiso" class="md-textarea form-control"  
                                        rows="5" 
                                        maxlength="1000" 
                                        minlength="1"
                                        disabled
                                        required></textarea>
                                    <span id="errorsV-motivoPermiso" class="text-danger text-small d-none">
                                    </span>
                                </div>
                            </div>
                               
                        </div>

                        <div class="form-row">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <div class="md-form">
                                    <input type="text" 
                                        id="V-edificioAsistencia" 
                                        class="form-control" 
                                        maxlength="255" 
                                        minlength="1" 
                                        disabled
                                        required
                                    >
                                    <span id="errorsV-edificioAsistencia" class="text-danger text-small d-none">
                                    </span>
                                    <label for="V-edificioAsistencia" id="labelV-edificioAsistencia">
                                    Edificio donde tiene registrada su asistencia:
                                    </label>
                                </div>
                            </div>
                               
                        </div>

                        <div class="form-row">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">       
                                <div class="md-form">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <div class="md-form">  
                                </div>
                            </div>
                        </div>

                        <div class="form-row">      
                            <label for="V-duracionPermiso" id="labelR-estadoDepartamento">
                            Tiempo de duración del permiso:
                            </label>
                        </div>

                        <div class="form-row">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="md-form">
                                        <input 
                                            type="text"
                                            class="form-control" 
                                            id="V-fechaInicio"
                                            disabled
                                        >
                                        <span id="errorsV-fechaInicio" class="text-danger text-small d-none">
                                        </span>
                                        <label for="V-fechaInicio" id="labelV-fechaInicio">
                                        Fecha Inicio:
                                        </label>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="md-form">
                                        <input 
                                            type="text"
                                            class="form-control" 
                                            id="V-fechaFin"
                                            disabled
                                        >
                                        <span id="errorsV-fechaFin" class="text-danger text-small d-none">
                                        </span>
                                        <label for="V-fechaFin" id="labelV-fechaFin">
                                        Fecha Finalización:
                                        </label>
                                    </div>
                                </div>
                        </div>
                        

                        <div class="form-row">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="md-form">
                                        <input 
                                            type="text" 
                                            id="V-horaInicio" 
                                            class="form-control" 
                                            maxlength="80" 
                                            minlength="1"
                                            disabled
                                        >
                                        <span id="errorsV-horaInicio" class="text-danger text-small d-none">
                                        </span>
                                        <label for="V-horaInicio" id="labelV-horaInicio" name="labelV-horaInicio">
                                        Hora Inicio:
                                        </label>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="md-form">
                                        <input 
                                            type="text" 
                                            id="V-horaFin" 
                                            class="form-control" 
                                            maxlength="80" 
                                            minlength="1"
                                            disabled
                                            required
                                        >
                                        <span id="errorsV-horaFin" class="text-danger text-small d-none">
                                        </span>
                                        <label for="V-fechaFin" id="labelV-fechaFin">
                                        Hora Finalización:
                                        </label>
                                    </div>
                                </div>
                        </div>

                        <div class="form-row">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="md-form">
                                        <input 
                                            type="text" 
                                            id="V-diasSolicitados" 
                                            class="form-control" 
                                            maxlength="80" 
                                            minlength="1"
                                            disabled
                                            required
                                        >
                                        <span id="errorsV-diasSolicitados" class="text-danger text-small d-none">

                                        </span>
                                        <label for="V-diasSolicitados" id="labelR-diasSolicitados" name="labelV-diasSolicitados">
                                        Dias Solicitados:
                                        </label>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="md-form">
                                        <input 
                                            type="text" 
                                            id="V-fechaRecibida" 
                                            class="form-control" 
                                            maxlength="80" 
                                            minlength="1"
                                            disabled
                                            required
                                        >
                                        <span id="errorsV-fechaRecibida" class="text-danger text-small d-none">

                                        </span>
                                        <label for="V-fechaRecibida" id="labelV-fechaRecibida" name="labelV-fechaRecibida">
                                        Fecha en que se recibio la solicitud:
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    
                                        <input 
                                            type="text" 
                                            id="V-idUsuario" 
                                            class="form-control" 
                                            maxlength="80" 
                                            minlength="1"
                                            value="<?php echo $_SESSION['idUsuario']?>"
                                            style="visibility:hidden"
                                            required
                                        >   
                                </div>
                                
                        </div>


                        

                    </form>
                </div>
                    
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>


    <!--Hacer Observación sobre Solicitud Recibida-->
    <div class="modal fade" id="modalHacerObservacionesSolicitudRecibida" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Hacer Observaciones sobre Solicitud Recibida</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-hacer-observaciones-solicitud" class="text-center" style="color: #757575;">
                        <div class="form-row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <div class="md-form">
                                    <label for="H-observacionesSolicitud" class="" id="labelH-observacionesSolicitud" name="labelH-observacionesSolicitud">
                                        Observaciones sobre la solicitud recibida: 
                                    </label>
                                   
                                    <textarea id="H-observacionesSolicitud" class="md-textarea form-control"  
                                        rows="5" 
                                        maxlength="500" 
                                        minlength="1"
                                        required></textarea>
                                    <span id="errorsH-observacionesSolicitud" class="text-danger text-small d-none">
                                    </span>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                    
                <div class="modal-footer">
                    <div class="text-center mt-4">
                        <button id="btn-hacer-observacion" type="button" class="btn btn-light-green btn-rounded" onclick="hacerObservacion()">
                            Guardar Observación
                        </button>
                    </div>
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-danger btn-rounded" data-dismiss="modal" aria-label="Close" onclick="cancelarGuardarOservacion()">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
    <!--Ver Observación hecha sobre Solicitud Recibida-->
    <div class="modal fade" id="modalVerObservacionesSolicitudRecibida" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Observaciones sobre Solicitud Recibida</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-visualizar-observaciones-solicitud" class="text-center" style="color: #757575;">
                        <div class="form-row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <div class="md-form">
                                    <label for="V-observacionesSolicitud" class="" id="labelV-observacionesSolicitud" name="labelV-observacionesSolicitud">
                                        Observaciones sobre la solicitud recibida: 
                                    </label>
                                   
                                    <textarea id="V-observacionesSolicitud" class="md-textarea form-control"  
                                        rows="5" 
                                        maxlength="500" 
                                        minlength="1"
                                        disabled
                                        required></textarea>
                                    <span id="errorsV-observacionesSolicitud" class="text-danger text-small d-none">
                                    </span>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                    
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>


    <!--Ver adjuntos-->
    <div class="modal fade" id="modalRespaldoAdjunto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lm" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Body-->
            <div class="modal-body text-center mb-1">
                <div class="container"> 
                    <h5 align="center" style="color:#191970" >Imagen adjuntada como respaldo:</h5>
                    <div class="text-center mt-4" id="V-respaldoAdjunto">
                        

                    </div>  
                    <hr>  
                    <h5 align="center" style="color:#191970" >Imagen adjuntada como firma digital:</h5>
                    <div class="text-center mt-4" id="V-firmaDigitalAdjunta">
                        

                    </div>  
                                        
                    <div class="row">
                        <div class="col">
                            <div class="text-center mt-4">
                                <button type="button" class="btn btn-danger btn-rounded" data-dismiss="modal" aria-label="Close">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer amber accent-4">
                
            </div>

        </div>
    </div>
    </div>

    
    
    <!--En esta zona podran poner javascripts propios de la vista-->

    <script src="../js/sweet-alert-two/sweetalert2.min.js"></script>
    <script src="../js/libreria-bootstrap-mdb/jquery.min.js"></script>

    <script src="../js/data-tables/datatables.min.js"></script>

    <script src="../js/data-tables/Buttons/js/dataTables.buttons.min.js"></script>
    <script src="../js/data-tables/JSZip/jszip.min.js"></script>
    <script src="../js/data-tables/pdfmake/pdfmake.min.js"></script>
    <script src="../js/data-tables/pdfmake/vfs_fonts.js"></script>
    <script src="../js/data-tables/Buttons/js/buttons.html5.min.js"></script>

    <script src="../js/config/config.js"></script>
    <script src="../js/validators/form-validator.js"></script>
    <script src="../js/control-recibir-permisos/recibirPermisos-controlador.js"></script>
    <script setInterval(notificaciones,1000)></script>
    
    <?php
    include('../partials/endDoctype.php');
    ?>