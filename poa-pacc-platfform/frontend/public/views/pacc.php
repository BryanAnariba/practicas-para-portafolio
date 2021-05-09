<?php
session_start();
if (!isset($_SESSION['correoInstitucional'])) {
    header('Location: 401.php');
}
$usuarioEstratega = 'U_E';
$secretariaAdministrativa = 'SE_AD';
if (($_SESSION['abrevTipoUsuario'] != $usuarioEstratega) && ($_SESSION['abrevTipoUsuario'] != $secretariaAdministrativa)) {
    header('Location: 401.php');
}
include('../partials/doctype.php');
//include('verifica-session.php');
?>
<title>Gestion PACC</title>
<!--En esta zona podran poner estilos propios de la vista-->
<link rel="stylesheet" href="../css/sweet-alert-two/sweetalert2.min.css">

<link rel="stylesheet" href="../js/data-tables/datatables.min.css">
<link rel="stylesheet" href="../js/data-tables/DataTables/css/dataTables.bootstrap4.min.css">

<link rel="stylesheet" href="https:use.fontawesome.com/releases/v5.15.1/css/all.css" integrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous">
<link rel="stylesheet" href="../js/chartjs/css/Chart.css" />
<link rel="stylesheet" href="../js/chartjs/css/Chart.min.css" />

<link rel="stylesheet" href="../css/libreria-bootstrap-mdb/select2.min.css">

<script src="../js/chartjs/Chart.bundle.js"></script>
<script src="../js/chartjs/Chart.min.js"></script>
<script src="../js/chartjs/Chart.js"></script>


</head>

<body id="body-pd">
    <?php include('../layouts/Nabvar.php'); ?>
    <?php include('../layouts/Sidebar.php'); ?>

    <div id="profile-card" class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header indigo darken-4 text-white">
                        <h5 class="font-weight-bolder">Control y Reporteria PACC</h5>
                    </div>
                    <div class="card-body blue lighten-5">
                        <div class="container-fluid">
                            <div class="row mt-2">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="display-5 text-center">
                                                Opciones para generar reporte pacc
                                            </h5>
                                        </div>
                                        <div class="card-body row">
                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mx-auto">
                                                <div class="text-center mt-4">
                                                    <button type="button" class="btn indigo darken-4 text-white btn-rounded" data-toggle="modal" onclick="abrirModalReporteGeneral()">
                                                        <img src="../img/partial-sidebar/agregar-icon.svg" alt="">
                                                        Generar Reporte Excel pacc General Facultad
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mx-auto">
                                                <div class="text-center mt-4">
                                                    <button type="button" class="btn indigo darken-4 text-white btn-rounded" data-toggle="modal" onclick="abrirModalReporteDepartamento()">
                                                        <img src="../img/partial-sidebar/agregar-icon.svg" alt="">
                                                        Generar Reporte Excel por departamento
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mx-auto">
                                                <div class="text-center mt-4">
                                                    <button type="button" class="btn indigo darken-4 text-white btn-rounded" data-toggle="modal" onclick="abrirModalReportesEspecificos()">
                                                        <img src="../img/partial-sidebar/agregar-icon.svg" alt="">
                                                        Generar Reporte filtrado por objeto de gasto
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mx-auto">
                                                <div class="text-center mt-4">
                                                    <button type="button" class="btn indigo darken-4 text-white btn-rounded" data-toggle="modal" onclick="abrirModalReportesEspecificosDepto()">
                                                        <img src="../img/partial-sidebar/agregar-icon.svg" alt="">
                                                        Generar Reporte filtrado por correlativo
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <div class="card bg-primary mb-3 border-primary" style="max-width: 100%">
                                        <div class="card-header text-center text-white">Grafica de distribucion de presupuesto por departamentos</div>
                                        <div class="card-body bg-white">
                                            <div class="chart-container">
                                                <canvas id="grafica-presupuestos-departamentos" width="400">

                                                </canvas>
                                            </div>
                                        </div>
                                        <div class="card-footer border-primary">
                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mx-auto">
                                                <div class="text-center mt-4">
                                                    <button type="button" class="btn amber accent-4 text-white btn-rounded" data-toggle="modal" onclick="abrirModalGraficos()">
                                                        <img src="../img/menu/visualizar-icon.svg" alt="">
                                                        Ver mas opciones de reportes de excel
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="container card">
                                <div class="row">
                                    <div class="col-xl-3 col-lg-3 col-md-3 hidden-col-sm">
                                    </div>
                                    <div class="col-6">
                                        <div class="md-form">
                                            <input type="text" id="fechaPresupuesto" class="form-control" disabled />
                                            <label class="form-label" for="fechaPresupuesto">Año Presupuesto Abierto</label>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 hidden-col-sm">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                        <div class="md-form">
                                            <input type="text" id="presupuestoAnual" class="form-control" disabled />
                                            <label class="form-label" for="presupuestoAnual">Presupuesto Abierto Total</label>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                        <div class="md-form">
                                            <input type="text" id="presupuestoUtilizado" class="form-control" disabled />
                                            <label class="form-label" for="presupuestoUtilizado">Presupuesto Abierto Utilizado</label>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                        <div class="md-form">
                                            <input type="text" id="presupuestoDisponible" class="form-control" disabled />
                                            <label class="form-label" for="presupuestoDisponible">Presupuesto Abierto Disponible</label>
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

    <div class="modal fade" id="modalGeneraPaccGeneral" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Opciones para generar reporte</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="" class="text-center" style="color: #757575;">
                        <div class="md-form">
                            <select class="browser-default custom-select" id="FechaPresupuesto" required>
                            </select>
                            <span id="errorsFechaPresupuesto" class="text-danger text-small d-none">
                            </span>
                        </div>

                        <div class="md-form">
                            <select class="browser-default custom-select" id="tipoOrdenamiento" required>
                                <option value="" selected>Seleccione el tipo de ordenamiento para el pacc</option>
                                <option value="1">Ordenar por objeto de gasto</option>
                                <option value="2">Ordenar por correlativo de actividad</option>
                            </select>
                            <span id="errorstipoOrdenamiento" class="text-danger text-small d-none">
                            </span>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="text-center mt-4">
                        <button id="btn-registrar-dimension" type="button" class="btn btn-light-green btn-rounded" onclick="generarReporteGeneralPACC()">Generar Reporte</button>
                    </div>
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-danger btn-rounded" data-dismiss="modal" aria-label="Close">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalGeneraPaccDepartamento" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Opciones para generar reporte por departamento</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-registro-dimension" class="text-center" style="color: #757575;">
                        <div class="md-form">
                            <select class="browser-default custom-select" id="FechaPresupuestoDepartamento" required>
                            </select>
                            <span id="errorsFechaPresupuestoDepartamento" class="text-danger text-small d-none">
                            </span>
                        </div>
                        <div class="md-form">
                            <select class="browser-default custom-select" id="departamento" required>
                            </select>
                            <span id="errorsdepartamento" class="text-danger text-small d-none">
                            </span>
                        </div>
                        <div class="md-form">
                            <select class="browser-default custom-select" id="tipoOrdenamientoDepto" required>
                                <option value="" selected>Seleccione el tipo de ordenamiento para el pacc</option>
                                <option value="1">Ordenar por objeto de gasto</option>
                                <option value="2">Ordenar por correlativo de actividad</option>
                            </select>
                            <span id="errorstipoOrdenamientoDepto" class="text-danger text-small d-none">
                            </span>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="text-center mt-4">
                        <button id="btn-registrar-dimension" type="button" class="btn btn-light-green btn-rounded" onclick="generarReporteDepartamentoPACC()">Generar Reporte</button>
                    </div>
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-danger btn-rounded" data-dismiss="modal" aria-label="Close">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="reporteEspecifico" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Opciones para generar reporte especifico por objeto de gasto</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="text-center" style="color: #757575;">
                        <div class="container">
                            <div class="row">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                    <div class="md-form">
                                        <select class="browser-default custom-select" id="Fecha" required onchange="generarObjetos()">
                                        </select>
                                        <span id="errorsFecha" class="text-danger text-small d-none">
                                        </span>
                                    </div>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12" id="contenedorObjetosGasto">
                                    <div class="md-form">
                                        <select class="browser-default custom-select" id="Objetos" required>
                                            
                                        </select>
                                        <span id="errorsObjetos" class="text-danger text-small d-none">
                                        </span>
                                    </div>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12" id="opcion-generacion">
                                    <div class="md-form">
                                        <select class="browser-default custom-select" id="Opciones" onchange="opcionGenerarDeptos()" required>
                                            <option value="">Opciones para generar costo por objeto de gasto</option>
                                            <option value="1">General</option>
                                            <option value="2">Por Departamento</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-6 col-sm-12" id="opcion-departamento">
                                    <div class="md-form">
                                        <select class="browser-default custom-select" id="Depto" required>
                                        </select>
                                        <span id="errorsDepto" class="text-danger text-small d-none">
                                        </span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="text-center mt-4">
                                        <button id="generarTabla" type="button" class="btn btn indigo darken-4 text-white btn-rounded" onclick="mostrarResultadosFiltroObjetoGasto()">Ver resultados</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <table class="table" id="lista-reporte-por-objeto">
                                    <thead>
                                        <tr>
                                            <th scope="col">Codigo Objeto Gasto</th>
                                            <th scope="col">Descripcion Cuenta</th>
                                            <th scope="col">Costo Objeto Gasto</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-danger btn-rounded" data-dismiss="modal" aria-label="Close"
                            onclick="cancelarOperacion()"
                        >Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="reporteEspecificoCorrelativo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Opciones para generar reporte especifico por correlativo</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="text-center" style="color: #757575;">
                        <div class="container">
                            <div class="row">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12" id="generarListaFechas">
                                    <div class="md-form">
                                        <select class="browser-default custom-select" id="Fechas" required onchange="generaCorrelativosActividad()">
                                        </select>
                                        <span id="errorsFechas" class="text-danger text-small d-none">
                                        </span>
                                    </div>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12" id="contenedorCorrelativos">
                                    <div class="md-form">
                                        <select class="browser-default custom-select" id="Correlativos" required>
                                            
                                        </select>
                                        <span id="errorsCorrelativos" class="text-danger text-small d-none">
                                        </span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="text-center mt-4">
                                        <button id="generarTablas" type="button" class="btn btn indigo darken-4 text-white btn-rounded" onclick="mostrarResultadosFiltroDepto()">Ver resultados</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <table class="table" id="lista-reporte-por-correlativo">
                                    <thead>
                                        <tr>
                                            <th scope="col">Correlativo Actividad</th>
                                            <th scope="col">Departametno</th>
                                            <th scope="col">Costo Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-danger btn-rounded" data-dismiss="modal" aria-label="Close"
                            onclick="cancelarOperacionCorrelativo()"
                        >Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalGraficos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Opciones para generar grafico de gastos por dimension en los departamentos de la facultad</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-registron" class="text-center container" style="color: #757575;">
                        <div class="row my-auto">
                            <div class="col-lx-4 col-lg-4 col-md-6 col-sm-12">
                                <div class="md-form">
                                    <select class="browser-default custom-select" id="FechaPresupuestoDepartamentoGrafica" required>
                                    </select>
                                    <span id="errorsFechaPresupuestoDepartamentoGrafica" class="text-danger text-small d-none">
                                    </span>
                                </div>
                            </div>
                            <div class="col-lx-4 col-lg-4 col-md-6 col-sm-12">
                                <div class="md-form">
                                    <select class="browser-default custom-select" id="departamentoGrafica" required>
                                    </select>
                                    <span id="errorsdepartamento" class="text-danger text-small d-none">
                                    </span>
                                </div>
                            </div>
                            <div class="col-lx-4 col-lg-4 col-md-6 col-sm-12">
                                <div class="text-center mt-4">
                                    <button type="button" class="btn indigo darken-4 text-white btn-rounded" data-toggle="modal" onclick="generarReportes()">
                                        <img src="../img/partial-sidebar/agregar-icon.svg" alt="">
                                        Ver resultados
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <table class="table" id="listado-dimensiones">
                                    <thead>
                                        <tr>
                                            <th scope="col">Numero Dimension</th>
                                            <th scope="col">Dimension Estrategica</th>
                                            <th scope="col">Gasto Total</th>
                                            <th scope="col">Departamento</th>
                                            <th scope="col">Año Presupuesto</th>
                                        </tr>
                                    </thead>
                                    <tbody id="gasto-por-dimension-institucionales">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <div class="text-center mt-4">
                            <button type="button" class="btn btn-danger btn-rounded" data-dismiss="modal" aria-label="Close">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- <div class="modal fade" id="listadoGastos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Opciones para generar grafico de gastos por dimnesion en los departamentos de la facultad</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mt-5">
                        <div class="col-12">
                            <div class="table-responsive">
                                
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="text-center mt-4">
                            <button type="button" class="btn btn-danger btn-rounded" data-dismiss="modal" aria-label="Close">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
        <script src="../js/sweet-alert-two/sweetalert2.min.js"></script>
        <script src="../js/libreria-bootstrap-mdb/jquery.min.js"></script>

        <script src="../js/data-tables/datatables.min.js"></script>

        <script src="../js/data-tables/Buttons/js/dataTables.buttons.min.js"></script>
        <script src="../js/data-tables/JSZip/jszip.min.js"></script>
        <script src="../js/data-tables/pdfmake/pdfmake.min.js"></script>
        <script src="../js/data-tables/pdfmake/vfs_fonts.js"></script>
        <script src="../js/data-tables/Buttons/js/buttons.html5.min.js"></script>

        <script src="../js/libreria-bootstrap-mdb/select2.min.js"></script>
        <script src="../js/config/config.js"></script>
        <script src="../js/validators/form-validator.js"></script>
        <script src="../js/pacc/controlador-pacc.js"></script>


        <?php
        include('../partials/endDoctype.php');
        ?>