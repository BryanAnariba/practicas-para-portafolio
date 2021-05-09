<?php

if (!isset($_SESSION)) {
    session_start();
}
$secretaria = 'SE_AD';
$coordinador = 'C_C';
$estratega = 'U_E';
if (($_SESSION['abrevTipoUsuario'] != $secretaria) &&
    ($_SESSION['abrevTipoUsuario'] != $coordinador) && 
    ($_SESSION['abrevTipoUsuario'] != $estratega)) {
    header('Location: 401.php');
}
if (!isset($_SESSION['correoInstitucional'])) {
    header('Location: 401.php');
}
include('../partials/doctype.php');
include('verifica-session.php');
?>


<title>Control de envio solicitudes de permisos</title>
<!--En esta zona podran poner estilos propios de la vista-->
<link rel="stylesheet" href="../css/sweet-alert-two/sweetalert2.min.css">


<link rel="stylesheet" href="../js/data-tables/datatables.min.css">
<link rel="stylesheet" href="../js/data-tables/DataTables/css/dataTables.bootstrap4.min.css">

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css" integrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous">

</head>

<body id="body-pd">
    <?php include('../layouts/Nabvar.php'); ?>
    <?php include('../layouts/Sidebar.php'); ?>

    <div id="profile-card" class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header indigo darken-4 text-white">
                        <h5 class="font-weight-bolder"> Maneja el control de envio de solicitudes de permisos desde este panel</h5>
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
                                                        Solicitar Permisos
                                                    </h6>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4">
                                                    <img src="../img/control-envio-permisos/solicitud-icono.svg" alt="Control de envio Permisos">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="view overlay">
                                                <img class="card-img-top" src="../img/control-envio-permisos/enviar-solicitud.svg" alt="solicitar permiso">
                                            </div>
                                            <hr>
                                            <button type="button" 
                                                class="btn btn-indigo btn-block" 
                                                data-toggle="modal" 
                                                data-target="#modalEnviarSolicitud" 
                                            >
                                                Solicitar
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
                                                        Historial de Permisos
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
                                                <img class="card-img-top" src="../img/control-envio-permisos/historial-solicitudes.svg" alt="ver historial permisos">
                                            </div>
                                            <hr>
                                            <button type="button" 
                                                class="btn btn-indigo btn-block" 
                                                data-toggle="modal" 
                                                data-target="#modalVisualizarHistorialSolicitudes" 
                                                onclick="verSolicitudesPermisos()">
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
    <!--Enviar Solicitud-->
    <div class="modal fade" id="modalEnviarSolicitud" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Formulario para realizar una solicitud de permiso</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-registro-solicitud" class="form" style="color: #757575;" target="_blank" action="exportarSolicitudPdf.php" method="post">
                        <div class="form-row">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="md-form">
                                        <input 
                                            type="text" 
                                            id="R-nombreEmpleado" 
                                            name="R-nombreEmpleado"
                                            class="form-control" 
                                            maxlength="150" 
                                            minlength="1"
                                            value="<?php echo $_SESSION['nombrePersona'].' '.$_SESSION['apellidoPersona']?>"
                                            disabled 
                                            required
                                        >
                                        <span id="errorsR-nombreEmpleado" class="text-danger text-small d-none">

                                        </span>
                                        <label for="R-nombreEmpleado" id="labelR-nombreEmpleado" name="labelR-nombreEmpleado">
                                        Nombre Completo:
                                        </label>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="md-form">
                                        <input 
                                            type="text" 
                                            id="R-codigoEmpleado"
                                            name="R-codigoEmpleado"
                                            class="form-control" 
                                            maxlength="5" 
                                            minlength="1"
                                            value="<?php echo ($_SESSION['codigoEmpleado'])?>"
                                            disabled 
                                            required>
                                        <span id="errorsR-codigoEmpleado" class="text-danger text-small d-none">
                                        </span>
                                        <label for="R-codigoEmpleado" id="labelR-codigoEmpleado">
                                            No. de Empleado:
                                        </label>
                                    </div>
                                </div>
                                
                        </div>

                        <div class="form-row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <div class="md-form">
                                    <input type="text" 
                                        id="R-departamentoLabora" 
                                        name="R-departamentoLabora"
                                        class="form-control" 
                                        maxlength="150" 
                                        minlength="1" 
                                        value="<?php echo $_SESSION['nombreDepartamento']?>"
                                        disabled
                                        required
                                    >
                                    <span id="errorsR-departamentoLabora" class="text-danger text-small d-none">
                                    </span>
                                    <label for="R-departamentoLabora" id="labelR-departamentoLabora">
                                    Departamento donde labora:
                                    </label>
                                </div>
                            </div>
                               
                        </div>

                        <div class="form-row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <div class="md-form">
                                    <label for="R-motivoPermiso" class="" id="labelR-motivoPermiso" name="labelR-motivoPermiso">
                                        Solicito permiso por motivo de:
                                    </label>
                                   
                                    <textarea 
                                        id="R-motivoPermiso" 
                                        name="R-motivoPermiso" 
                                        class="md-textarea form-control"  
                                        rows="5" 
                                        maxlength="1000" 
                                        minlength="1" 
                                        required></textarea>
                                    <span id="errorsR-motivoPermiso" class="text-danger text-small d-none">
                                    </span>
                                </div>
                            </div>
                               
                        </div>

                        <div class="form-row">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <div class="md-form">
                                    <input type="text" 
                                        id="R-edificioAsistencia" 
                                        name="R-edificioAsistencia" 
                                        class="form-control" 
                                        maxlength="255" 
                                        minlength="1" 
                                        required
                                    >
                                    <span id="errorsR-edificioAsistencia" class="text-danger text-small d-none">
                                    </span>
                                    <label for="R-edificioAsistencia" id="labelR-edificioAsistencia">
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
                            <label for="R-duracionPermiso" id="labelR-estadoDepartamento">
                            Tiempo de duración del permiso:
                            </label>
                        </div>

                        <div class="form-row">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="md-form">
                                        <input 
                                            placeholder="Select date" 
                                            type="date"
                                            class="form-control" 
                                            id="R-fechaInicio"
                                            name="R-fechaInicio"
                                        >
                                        <span id="errorsR-fechaInicio" class="text-danger text-small d-none">
                                        </span>
                                        <label for="R-fechaInicio" id="labelR-fechaInicio">
                                        Fecha Inicio:
                                        </label>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="md-form">
                                        <input 
                                            placeholder="Select date" 
                                            type="date"
                                            class="form-control" 
                                            id="R-fechaFin"
                                            name="R-fechaFin"
                                        >
                                        <span id="errorsR-fechaFin" class="text-danger text-small d-none">
                                        </span>
                                        <label for="R-fechaFin" id="labelR-fechaFin">
                                        Fecha Finalización:
                                        </label>
                                    </div>
                                </div>
                        </div>
                        
                       
                        <div class="form-row">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-row"style="width:375px;"  >
                                    <label for="R-horaInicio" id="labelR-horaInicio" name="labelR-horaInicio">
                                    Hora Inicio:
                                    </label>
                                    <input 
                                        type="time" 
                                        id="R-horaInicio" 
                                        name="R-horaInicio" 
                                        class="form-control" 
                                        min="07:00"
                                        max="17:00"
                                        required
                                    >  
                                        <span id="errorsR-horaInicio" class="text-danger text-small d-none">
                                        </span>
                                        
                                    </div>
                                </div>
                                <div></div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-row" style="width:375px;">      
                                    <label for="R-horaFin" id="labelR-horaFin" name="labelR-horaFin">
                                    Hora Fin:
                                    </label>
                                    <input 
                                        type="time" 
                                        id="R-horaFin" 
                                        name="R-horaFin" 
                                        class="form-control" 
                                        min="07:00"
                                        max="17:00"
                                        required
                                    >
                                    <span id="errorsR-horaFin" class="text-danger text-small d-none">
                                    </span>
                                    </div>
        
                                </div>
                        </div>

                        <div class="form-row">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="md-form">
                                        <input 
                                            type="number" 
                                            id="R-idUsuario" 
                                            name="R-idUsuario"
                                            class="form-control" 
                                            maxlength="80" 
                                            minlength="1"
                                            value="<?php echo $_SESSION['idUsuario']?>"
                                            style="visibility:hidden"
                                            required
                                        >
                                        <span id="errorsR-idUsuario" class="text-danger text-small d-none">

                                        </span>
                                        <label for="R-idUsuario" id="labelR-idUsuario" name="labelR-idUsuario">
                                        
                                        </label>
                                    </div>
                                </div>
                                
                        </div>


                        <div class="form-row">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="btn btn-light-blue btn-rounded">
                                        <span>Adjuntar imagen de respaldo /Opc</span>
                                        <input type="file" id="respaldo" name="respaldo">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="btn btn-light-blue btn-rounded">
                                        <span>Adjuntar firma Digital /Opc</span>
                                        <input type="file" id="firmaDigital" name="firmaDigital">
                                    </div>
                                </div>
                        </div>

                    
                </div>
                    
                <div class="modal-footer">
                    <div class="text-center mt-4">
                        <button type="submit" name="exportar" class="btn btn-light-blue btn-rounded">
                            Exportar a PDF
                        </button>
                        
                    </div>
                    <div class="text-center mt-4">
                        <button id="btn-registrar-solicitud" type="button" 
                            class="btn btn-light-green btn-rounded" 
                            onclick="registrarSolicitud()"
                        >
                            Enviar Solicitud
                        </button>
                    </div>
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-danger btn-rounded" data-dismiss="modal" aria-label="Close" onclick="cancelarRegistroSolicitud()">
                            Cancelar
                        </button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>


    <!--Visualizar historial solicitudes-->
    <div class="modal fade" id="modalVisualizarHistorialSolicitudes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Historial de Permisos Solicitados</h4>
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
                                    <th scope="col"><center>Tipo Solicitud<center></th>
                                    <th scope="col"><center>Fecha de Envio</center></th>
                                    <th scope="col"><center>Estado</center></th>
                                    <th scope="col"><center>Ver Solicitud</center></th>
                                    <th scope="col"><center>Adjuntos</center></th>
                                    <th scope="col">Observaciones</th>
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



    <!--Ver Solicitud Enviada-->
    <div class="modal fade" id="modalVerSolicitudEnviada" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Solicitud Enviada</h4>
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
                                            value= "<?php echo $_SESSION['nombrePersona'].' '.$_SESSION['apellidoPersona']?>"
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
                                            value="<?php echo $_SESSION['codigoEmpleado']?>"
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
                                        value="<?php echo $_SESSION['nombreDepartamento']?>"
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
                                        maxlength="500" 
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
                                        <span id="errorsR-horaFin" class="text-danger text-small d-none">
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
                                            id="V-fechaRevision" 
                                            class="form-control" 
                                            maxlength="80" 
                                            minlength="1"
                                            disabled
                                            required
                                        >
                                        <span id="errorsV-fechaRevision" class="text-danger text-small d-none">

                                        </span>
                                        <label for="V-fechaRevision" id="labelV-fechaRevision" name="labelV-fechaRevision">
                                        Fecha Revisión
                                        </label>
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



    <!--Ver Observación sobre Solicitud Enviada-->
    <div class="modal fade" id="modalVerObservacionesSolicitudEnviada" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Observaciones sobre Solicitud Enviada</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-visualizar-observaciones-solicitud" class="text-center" style="color: #757575;">
                        <div class="form-row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <div class="md-form">
                                    <label for="V-observacionesSolicitud" class="" id="labelV-observacionesSolicitud" name="labelV-motivoPermiso">
                                        Observaciones hechas sobre su solicitud: 
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
    <script src="../js/control-envio-permisos/envioPermisos-controlador.js"></script>
    
    <?php
    include('../partials/endDoctype.php');
    ?>