<?php
if (!isset($_SESSION)) {
    session_start();
}
$secretaria = 'SE_AD';
$decano = 'D_F';
$estratega = 'U_E';
if (($_SESSION['abrevTipoUsuario'] != $secretaria) &&
    ($_SESSION['abrevTipoUsuario'] != $decano) && 
    ($_SESSION['abrevTipoUsuario'] != $estratega)) {
    header('Location: 401.php');
}

if (!isset($_SESSION['correoInstitucional'])) {
    header('Location: 401.php');
}
include('../partials/doctype.php');
include('verifica-session.php');
?>
<title>Control de Presupuesto</title>
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
                        <h5 class="font-weight-bolder"> Maneja el control de presupuestos desde este panel</h5>
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
                                                        Registrar nuevo Presupuesto Anual
                                                    </h6>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4">
                                                    <img src="../img/partial-sidebar/presupuesto-asignacion.svg" alt="Control presupuestos">

                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="view overlay">
                                                <img class="card-img-top" src="../img/presupuestos/registrar-presupuesto.svg" alt="registrar-presupuesto">
                                            </div>
                                            <hr>
                                            <button type="button" class="btn btn-indigo btn-block" onclick="openModalRegistroPresupuesto()">
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
                                                        Visualizar Presupuestos
                                                    </h6>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4">
                                                    <img src="../img/partial-sidebar/presupuesto-asignacion.svg" alt="Control de Departamentos">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="view overlay">
                                                <img class="card-img-top" src="../img/presupuestos/visualizar-presupuestos.svg" alt="visualizar presupuesto">
                                            </div>
                                            <hr>
                                            <button type="button" class="btn btn-indigo btn-block" data-toggle="modal" data-target="#modalVisualizarPresupuesto" onclick="listarPresupuestos()">
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
                                                        Asignar Presupuesto a Departamento
                                                    </h6>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4">
                                                    <img src="../img/partial-sidebar/presupuesto-asignacion.svg" alt="Control de Departamentos">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">

                                            <div class="view overlay">
                                                <img class="card-img-top" src="../img/presupuestos/asignar-presupuesto-deptos.svg" alt="asignar-presupuesto">
                                            </div>
                                            <hr>
                                            <button type="button" class="btn btn-indigo btn-block" onclick="asignarPresupuestoDepto()">
                                                Asignar
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
    <div class="modal fade" id="modalVisualizarPresupuesto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Historial de Presupuestos Anuales</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="listado-presupuestos">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Codigo</th>
                                        <th scope="col">Cantidad Total Presupuesto</th>
                                        <th scope="col">Año</th>
                                        <th scope="col">Ver Detalles</th>
                                        <th scope="col">Editar Presupuesto</th>
                                    </tr>
                                </thead>
                                <tbody id="presupuestos-estrategicas">

                                </tbody>
                            </table>
                            
                            <section id="notificacion-presupuesto-anual" class="text-center">
                            </section> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalRegistrarPresupuesto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Formulario registro de presupuestos por departamento</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body" id="descripcion-presupuesto">
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <div class="md-form">
                                    <input type="number" id="presupuestoAnualTotal" class="form-control" disabled="true">
                                    <span id="presupuestoAnualTotal" class="text-danger text-small d-none">
                                    </span>
                                    <label for="presupuestoAnualTotal" id="labelpresupuestoAnualTotal" name="labelpresupuestoAnualTotal">
                                        Presupuesto Anual Total
                                    </label>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <div class="md-form">
                                    <input type="number" id="presupuestoAnualDisponible" class="form-control"  disabled="true">
                                    <span id="presupuestoAnualDisponible" class="text-danger text-small d-none">
                                    </span>
                                    <label for="presupuestoAnualDisponible" id="labelpresupuestoAnualTotal" name="labelpresupuestoAnualTotal">
                                        Presupuesto Anual Usado
                                    </label>
                                </div>
                            </div>
                            <hr/>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <div class="md-form">
                                    <select name="" id="departamento-facultad" class="browser-default custom-select">

                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <div class="md-form">
                                    <input type="number" id="R-presupuestoDepartamento" class="form-control" maxlength="1" minlength="15" required>
                                    <span id="errorsR-presupuestoDepartamento" class="text-danger text-small d-none">
                                    </span>
                                    <label for="R-presupuestoDepartamento" id="labelR-presupuestoDepartamento" name="labelR-presupuestoDepartamento">
                                        Digite el presupuesto para el departamento
                                    </label>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <div class="text-center mt-4">
                                    <button type="button" id="btn-registra-presupuesto-depto" class="btn btn-light-green btn-rounded" onclick="registrarPresupuestoDepartamento()">
                                    Registrar Presupuesto</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table" id="listado-presupuestos-departamentos">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Departamento</th>
                                        <th scope="col">Abrev</th>
                                        <th scope="col">Monto Presupuesto</th>
                                        <th scope="col">Año</th>
                                        <th scope="col">Editar Presupuesto</th>
                                    </tr>
                                </thead>
                                <tbody id="presupuestos-departamentos">

                                </tbody>
                            </table>
                            <section id="notificacion-presupuesto-departamentos" class="text-center">
                            </section> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">

                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-danger btn-rounded" data-dismiss="modal" aria-label="Close"
                        onclick="cancelarPresupuestoDepto()">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalRegistrarPresupuestoAnual" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Formulario de registro de nuevo presupuesto anual</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <div class="md-form">
                                <input type="number" id="R-presupuestoAnual" class="form-control" maxlength="1" minlength="15" required>
                                <span id="errorsR-presupuestoAnual" class="text-danger text-small d-none">
                                </span>
                                <label for="R-presupuestoAnual" id="labelR-presupuestoAnual" name="labelR-presupuestoAnual">
                                    Digite el la cantidad para el presupuesto anual
                                </label>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="md-form">
                                <select name="" id="estadoPresupuestoAnual" class="form-control">

                                </select>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="md-form">
                                <div class="md-form md-outline input-with-post-icon datepicker" inline="true">
                                    <input placeholder="Select date" type="date" class="form-control" id="R-fechaPresupuestoAnual">
                                    <span id="errorsR-fechaPresupuestoAnual" class="text-danger text-small d-none">
                                    </span>
                                    <label for="R-fechaPresupuestoAnual" id="labelM-fechaNacimiento">Fecha recibimiento del presupuesto
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="text-center mt-4">
                        <button id="btn-registrar-presupuesto" type="button" class="btn btn-light-green btn-rounded" onclick="registrarPresupuesto()">
                            Guardar Presupuesto
                        </button>
                    </div>
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-danger btn-rounded" data-dismiss="modal" aria-label="Close" onclick="cancelarRegistroPresupuesto()">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalModificarPresupuestoAnual" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Formulario para la modificacion de presupuesto anual</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-8 col-lg-8 col-md-6 col-sm-12">
                            <div class="md-form">
                                <input type="number" id="M-presupuestoAnual" class="form-control" maxlength="1" minlength="15" required>
                                <span id="errorsM-presupuestoAnual" class="text-danger text-small d-none">
                                </span>
                                <label for="M-presupuestoAnual" id="labelM-presupuestoAnual" name="labelM-presupuestoAnual">
                                    Digite el la cantidad para el presupuesto anual
                                </label>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                            <div class="md-form">
                                <select name="" id="M-estadoPresupuestoAnual" class="form-control">

                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="text-center mt-4">
                        <button id="btn-modif-presupuesto" type="button" class="btn btn-light-green btn-rounded" onclick="modificaPresupuesto()">
                            Guardar Cambios
                        </button>
                    </div>
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-danger btn-rounded" data-dismiss="modal" aria-label="Close" onclick="cancelarModificacionPresupuesto()">Cancelar Operacion</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalModificarPresupuestoDepto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Informacion del presupuesto</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-8 col-lg-8 col-md-6 col-sm-12">
                            <div class="md-form">
                                <input type="number" id="M-presupuestoDepto" class="form-control" maxlength="1" minlength="15" required>
                                <span id="errorsM-presupuestoDepto" class="text-danger text-small d-none">
                                </span>
                                <label for="M-presupuestoDepto" id="labelM-presupuestoDepto" name="labelM-presupuestoDepto">
                                    Digite el nuevo presupuesto para el departamento
                                </label>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                            <div class="md-form">
                                <select name="" id="M-Depto" class="form-control">

                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="text-center mt-4">
                        <button id="btn-modif-presupuesto-dep" type="button" class="btn btn-light-green btn-rounded" onclick="mPresupuestoDepartamento()">
                            Guardar Cambios
                        </button>
                    </div>
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-danger btn-rounded" data-dismiss="modal" aria-label="Close" onclick="cancelarModificacionPresupuestoDepartamentos()">Cancelar Operacion</button>
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
    <script src="../js/presupuestos/presupuesto.js"></script>
    <!--En esta zona podran poner javascripts propios de la vista-->
    <?php
    include('../partials/endDoctype.php');
    ?>