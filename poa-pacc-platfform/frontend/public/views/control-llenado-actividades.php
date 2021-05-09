<?php
session_start();
if (!isset($_SESSION['correoInstitucional'])) {
    header('Location: 401.php');
}
$superAdmin = 'SU_AD';
if ($_SESSION['abrevTipoUsuario'] != $superAdmin) {
    header('Location: 401.php');
}
include('../partials/doctype.php');
include('verifica-session.php');
?>
<title>Distribucion LLenado Dimensiones</title>
<!--En esta zona podran poner estilos propios de la vista-->
<link rel="stylesheet" href="../css/sweet-alert-two/sweetalert2.min.css">


<link rel="stylesheet" href="../js/data-tables/datatables.min.css">
<link rel="stylesheet" href="../js/data-tables/DataTables/css/dataTables.bootstrap4.min.css">

<link rel="stylesheet" href="https:use.fontawesome.com/releases/v5.15.1/css/all.css" integrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous">

</head>

<body id="body-pd">

    <?php include('../layouts/Nabvar.php'); ?>
    <?php include('../layouts/Sidebar.php'); ?>
    <!--En esta zona podran poner javascripts propios de la vista-->


    <div id="profile-card" class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header indigo darken-4 text-white">
                        <h5 class="font-weight-bolder"> Maneja el control de la distribucion del llenado de actividades panel</h5>
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
                                                        Visualizar Rangos de llenado
                                                    </h6>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4">
                                                    <img src="../img/rango-actividades/distribuir.svg" alt="Control de Rangos">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="view overlay">
                                                <img class="card-img-top" src="../img/rango-actividades/visualizar-rangos.svg" alt="registrar usuario">
                                            </div>
                                            <hr>
                                            <button type="button" class="btn btn-indigo btn-block" data-toggle="modal" data-target="#modalVisualizarUsuarios" onclick="listarLlenado()">
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
                                                        Registrar un Rango de Llenado
                                                    </h6>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4">
                                                    <img src="../img/rango-actividades/distribuir.svg" alt="Control de Usuarios">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="view overlay">
                                                <img class="card-img-top" src="../img/rango-actividades/registrar-rango.svg" alt="registrar usuario">
                                            </div>
                                            <hr>
                                            <button type="button" id="cargaDataUsuario" class="btn btn-indigo btn-block" data-toggle="modal" onclick="abrirModalRegistroControlLLenado()">
                                                Registrar
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
    <!--Visualizar usuarios-->
    <div class="modal fade" id="modalVisualizarDistribucionLlenado" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Listado de rangos de llenado para jefe depto y coordinador registrados</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table" id="listado-llenado">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Codigo llenado</th>
                                    <th scope="col">Tipo Usuario</th>
                                    <th scope="col">Rango Inicial de llenado</th>
                                    <th scope="col">Rango Final de llenado</th>
                                    <th scope="col">Modificar Rango</th>
                                    <th scope="col">Eliminar Rango</th>
                                </tr>
                            </thead>
                            <tbody id="llenado">

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

    <div class="modal fade" id="modalRegistraLlenadoActividad" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Formulario para registrar nuevos rangos de dimensiones</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <div class="md-form">
                                <select class="browser-default custom-select" id="R-idTipoCargo" required>

                                </select>
                                <span id="errorsR-idTipoCargo" class="text-danger text-small d-none">

                                </span>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                            <div class="md-form">
                                <label for="R-valorMinimo" class="" id="labelR-valorMinimo" name="labelR-valorMinimo">
                                    Digite el valor inicial de la dimension a llenar
                                </label>
                                <input type="number" id="R-valorMinimo" class="md-textarea form-control" required />
                                <span id="errorsR-valorMinimo" class="text-danger text-small d-none">
                                </span>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                            <div class="md-form">
                                <label for="R-valorMaximo" class="" id="labelR-valorMaximo" name="labelM-valorMaximo">
                                    Digite el valor final de la dimension a llenar
                                </label>
                                <input type="number" id="R-valorMaximo" class="md-textarea form-control" required />
                                <span id="errorsR-valorMaximo" class="text-danger text-small d-none">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="text-center mt-4">
                        <button id="btn-registrar-llenado" type="button" class="btn btn-light-green btn-rounded" onclick="registraNuevoRangoLlenadoDimension()">Registrar Rango de valores</button>
                    </div>
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-danger btn-rounded" data-dismiss="modal" aria-label="Close" onclick="limpiarCamposRegistro()">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalModificaLlenadoActividad" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Formulario para modificar nuevos rangos de dimensiones</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <div class="md-form">
                                <select class="browser-default custom-select" id="M-idTipoCargo" required>

                                </select>
                                <span id="errorsM-idTipoCargo" class="text-danger text-small d-none">

                                </span>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                            <div class="md-form">
                                <label for="M-valorMinimo" class="" id="labelM-valorMinimo" name="labelM-valorMinimo">
                                    Digite el valor inicial de la dimension a llenar
                                </label>
                                <input type="number" id="M-valorMinimo" class="md-textarea form-control" required />
                                <span id="errorsM-valorMinimo" class="text-danger text-small d-none">
                                </span>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                            <div class="md-form">
                                <label for="M-valorMaximo" class="" id="labelM-valorMaximo" name="labelM-valorMaximo">
                                    Digite el valor final de la dimension a llenar
                                </label>
                                <input type="number" id="M-valorMaximo" class="md-textarea form-control" required />
                                <span id="errorsM-valorMaximo" class="text-danger text-small d-none">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="text-center mt-4">
                        <button id="btn-modificar-llenado" type="button" class="btn btn-light-green btn-rounded" onclick="modificarValoresLlenado()">Modificar Rango de valores</button>
                    </div>
                    <div class="text-center mt-4">
                        <button 
                            onclick="limpiarCamposModificar()"
                            type="button" class="btn btn-danger btn-rounded" data-dismiss="modal" aria-label="Close">Cancelar</button>
                    </div>
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
    <script src="../js/control-llenado-actividades/controlador-llenado-actividades.js"></script>
    <?php
    include('../partials/endDoctype.php');
    ?>