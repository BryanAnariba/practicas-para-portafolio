<?php
include('../partials/doctype.php');
?>
<title>Control de Dimensiones Administrativas</title>
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
        <div class="row mb-4">
            <div class="col-xl-12 mx-auto">
                <div class="text-center mt-4">
                    <button type="button" class="btn btn-light-green btn-rounded" data-toggle="modal" data-target="#modalRegistroDimension">
                        <img src="../img/partial-sidebar/agregar-icon.svg" alt="">
                        Registrar una nueva Dimensión Administrativa 
                    </button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card animate__animated animate__fadeInDown">
                    <div class="card-header indigo darken-4 text-white">
                        <h5 class="font-weight-bolder"> Maneja el control de Dimensiones Administrativas desde este panel</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="listado-dimensiones">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Dimensión Administrativa</th>
                                        <th scope="col">Estado Dimensión</th>
                                        <th scope="col">Modificar Dimensión</th>
                                    </tr>
                                </thead>
                                <tbody id="dimensiones-estrategicas">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer amber accent-4">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--modales-->
    <div class="modal fade" id="modalRegistroDimension" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Formulario para registrar una dimensión administrativa</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-registro-dimension" class="text-center" style="color: #757575;">
                        <div class="form-row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <div class="md-form">
                                    <input 
                                        type="text"
                                        id="R-nombreDimension"
                                        class="form-control" 
                                        maxlength="150" 
                                        minlength="1" 
                                        required>
                                    <span id="errorsR-nombreDimension" class="text-danger text-small d-none">
                                    </span>
                                    <label for="R-nombreDimension" id="labelR-nombreDimension" name="labelR-nombreDimension">Escriba el nombre de la dimension
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="text-center mt-4">
                        <button id="btn-registrar-dimension" type="button" class="btn btn-light-green btn-rounded" onclick="registrarDimension()">Registrar Dimension</button>
                    </div>
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-danger btn-rounded" data-dismiss="modal" aria-label="Close" onclick="cancelarRegistroDimension()">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalModificarDimension" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Formulario para modificar una dimensión</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-modificacion-dimension" class="text-center" style="color: #757575;">
                        <div class="form-row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <div class="md-form">
                                    <label for="M-nombreDimension" class="" id="labelM-nombreDimension" name="labelM-nombreDimension">
                                        Escriba el nombre de la dimensión
                                    </label>
                                    <input 
                                        type="text"
                                        id="M-nombreDimension"
                                        class="form-control" 
                                        maxlength="150" 
                                        minlength="1" 
                                        required>
                                    <span id="errorsM-nombreDimension" class="text-danger text-small d-none">
                                    </span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="text-center mt-4">
                        <button id="btn-modificar-dimension" type="button" class="btn btn-light-green btn-rounded" onclick="modificarDimension()">Modificar Dimension</button>
                    </div>
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-danger btn-rounded" data-dismiss="modal" aria-label="Close" onclick="cancelarModificacionDimension()">Cancelar</button>
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
    <script src="../js/dimensiones-administrativas/dimensiones-administrativas-controlador.js"></script>
    <!--En esta zona podran poner javascripts propios de la vista-->
    <?php
    include('../partials/endDoctype.php');
    ?>