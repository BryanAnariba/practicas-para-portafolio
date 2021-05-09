<?php
if (!isset($_SESSION)) {
    session_start();
}
$secretaria = 'SE_AD';
$coordinador = 'C_C';
$JefeDepartamento = 'J_D';
if (($_SESSION['abrevTipoUsuario'] != $secretaria) &&
    ($_SESSION['abrevTipoUsuario'] != $coordinador) &&
    ($_SESSION['abrevTipoUsuario'] != $JefeDepartamento)
    ) {
    header('Location: 401.php');
}
if (!isset($_SESSION['correoInstitucional'])) {
    header('Location: 401.php');
}
include('../partials/doctype.php');
include('verifica-session.php');
?>
<title>Control Envio de Informes</title>
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
                        <h5 class="font-weight-bolder"> Maneja el control de Envio de Informes u Oficios </h5>
                    </div>
                    <div class="card-body  blue lighten-5">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">
                                    <div class="card border border-warning b-2 rounded mb-0 animate__animated animate__flipInY">
                                        <div class="card-header amber accent-4">
                                            <div class="row">
                                                <div class="col-xl-9 col-lg-9 col-md-9 col-sm-8">
                                                    <h6 class="text-white font-weight-bold">
                                                        Registrar Nuevo Informe u Oficio
                                                    </h6>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4">
                                                    <img src="../img/control-envio-informes/informe.svg" alt="Nuevo Informe">

                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="view overlay">
                                                <img class="card-img-top" src="../img/control-envio-informes/registrar-informe.svg" alt="registrar-presupuesto">
                                            </div>
                                            <hr>
                                            <button type="button" 
                                                class="btn btn-indigo btn-block" 
                                                data-toggle="modal" 
                                                data-target="#modalRegistrarInforme">
                                                Registrar
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
                                                        Informes Pendientes de Aprobación
                                                    </h6>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4">
                                                    <img src="../img/control-envio-informes/informe.svg" alt="Control de Departamentos">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="view overlay">
                                                <img class="card-img-top" src="../img/control-envio-informes/informesPendientes.svg" alt="visualizar presupuesto">
                                            </div>
                                            <hr>
                                            <button type="button" 
                                                class="btn btn-indigo btn-block" 
                                                data-toggle="modal" 
                                                data-target="#modalVerInformesPendientes" 
                                                onclick="listarInformesPendientes()">
                                                Visualizar
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
                                                        Informes Aprobados
                                                    </h6>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4">
                                                    <img src="../img/control-envio-informes/informe.svg" alt="Control de Departamentos">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">

                                            <div class="view overlay">
                                                <img class="card-img-top" src="../img/control-envio-informes/informesAprobados.svg" alt="asignar-presupuesto">
                                            </div>
                                            <hr>
                                            <button type="button" 
                                                class="btn btn-indigo btn-block"  
                                                data-toggle="modal" 
                                                data-target="#modalVerInformesAprobados" 
                                                onclick="listarInformesAprobados()">
                                            Visualizar
                                            </button>
                                        </div>
                                    </div>
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
    <!--Registrar informes -->
    <div class="modal fade" id="modalRegistrarInforme" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xs" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Formulario para Registrar Nuevo Informe</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-registro-informe" class="form" style="color: #757575;" method="post">
                        <div class="md-form">
                            <input 
                                type="text" 
                                id="R-tituloInforme" 
                                name="R-tituloInforme"
                                class="form-control" 
                                maxlength="150" 
                                minlength="1"
                                required
                            >
                            <span id="errorsR-tituloInforme" class="text-danger text-small d-none">

                            </span>
                            <label for="R-tituloInforme" id="labelR-tituloInforme" name="labelR-tituloInforme">
                                Ingrese un titulo para el documento:
                            </label>
                        </div>

                        <div class="md-form">
                            <label for="R-descripcionInforme" class="" id="labelR-descripcionInforme" name="labelR-descripcionInforme">
                                Añada una descripción para identificar el informe:
                            </label>
                            <textarea 
                                id="R-descripcionInforme" 
                                name="R-descripcionInforme" 
                                class="md-textarea form-control"  
                                rows="3" 
                                maxlength="255" 
                                minlength="1" 
                                required></textarea>
                            <span id="errorsR-descripcionInforme" class="text-danger text-small d-none">
                            </span>
                        </div>
                        
                        <div class="form-row">
                            <div class="btn btn-light-blue btn-rounded">
                                <span>Adjuntar Documento Informe</span>
                                <input type="file" id="informe" name="informe">
                            </div>
                            
                        </div>

                        
                    
                    </div>
                    <div class="modal-footer">
                        <div class="text-center mt-4">
                            <button id="btn-registrar-informe" type="button" class="btn btn-light-green btn-rounded" onclick="registrarInforme()">
                                Guardar Informe
                            </button>
                        </div>
                        <div class="text-center mt-4">
                            <button type="button" class="btn btn-danger btn-rounded" data-dismiss="modal" aria-label="Close" onclick="cancelarRegistroInforme()">Cancelar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!--Visualizar informes pendientes-->
    <div class="modal fade" id="modalVerInformesPendientes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Informes Pendientes de Aprobación</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table" id="listado-informesPendientes">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col"><center>Titulo Informe</center></th>
                                    <th scope="col"><center>Fecha Envio</center></th>
                                    <th scope="col"><center>Estado</center></th>
                                    <th scope="col"><center>Ver Descripción</center></th>
                                    <th scope="col"><center>Ver Informe</center></th>
                                </tr>
                            </thead>
                            <tbody id="informesPendientes">

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


    <!--Visualizar informes aprobados-->
    <div class="modal fade" id="modalVerInformesAprobados" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Informes Aprobados</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table" id="listado-informesAprobados">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col"><center>Titulo Informe</center></th>
                                    <th scope="col"><center>Fecha Envio</center></th>
                                    <th scope="col"><center>Fecha Aprobación</center></th>
                                    <th scope="col"><center>Nombre Aprobador</center></th>
                                    <th scope="col"><center>Estado</center></th>
                                    <th scope="col"><center>Ver Descripción</center></th>
                                    <th scope="col"><center>Ver Informe</center></th>
                                </tr>
                            </thead>
                            <tbody id="informesAprobados">

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


    <!--Ver Descripción sobre Informe-->
    <div class="modal fade" id="modalVerDescripcionInformeEnviado" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xs" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Descripción del Informe</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-visualizar-descripcion-informe" class="text-center" style="color: #757575;">
                        <div class="form-row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <div class="md-form">
                                    <label for="V-descripcionInforme" class="" id="labelV-descripcionInforme" name="labelV-motivoPermiso">
                                        Descripción: 
                                    </label>
                                   
                                    <textarea id="V-descripcionInforme" class="md-textarea form-control"  
                                        rows="5" 
                                        maxlength="255" 
                                        minlength="1"
                                        disabled
                                        required></textarea>
                                    <span id="errorsV-descripcionInforme" class="text-danger text-small d-none">
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



    <!--Ver Informe adjunto-->
    <div class="modal fade" id="modalInformeAdjunto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Body-->
            <div class="modal-body text-center mb-1">
                <div class="container"> 
            
                    <div class="text-center mt-4" id="V-informeAdjunto">
                        

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
    <script src="../js/control-envio-informes/envioInformes-controlador.js"></script>
    <!--En esta zona podran poner javascripts propios de la vista-->
    <?php
    include('../partials/endDoctype.php');
    ?>