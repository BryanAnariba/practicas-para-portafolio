<?php
session_start();
if (!isset($_SESSION['correoInstitucional'])) {
    header('Location: 401.php');
}
$coordinadorCarrera = 'C_C';
$jefeDepto = 'J_D';
if (($_SESSION['abrevTipoUsuario'] != $coordinadorCarrera) && ($_SESSION['abrevTipoUsuario'] != $jefeDepto)) {
    header('Location: 401.php');
}
include('../partials/doctype.php');
include('verifica-session.php');
?>
<title>Control de actividades</title>
<!--En esta zona podran poner estilos propios de la vista-->
<link rel="stylesheet" href="../css/Jefe-Coordinador/control-actividades.css">
<link rel="stylesheet" href="../css/Jefe-Coordinador/llenado.css">
<link rel="stylesheet" href="../css/sweet-alert-two/sweetalert2.min.css">
<link rel="stylesheet" href="../css/libreria-bootstrap-mdb/select2.min.css">
<link rel="stylesheet" href="../js/data-tables/datatables.min.css">
<link rel="stylesheet" href="../js/data-tables/DataTables/css/dataTables.bootstrap4.min.css">


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css">

</head>

<body id="body-pd">
    <?php include('../layouts/Nabvar.php'); ?>
    <?php include('../layouts/Sidebar.php'); ?>

    <div id="profile-card" class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header indigo darken-4 text-white">
                        <h5 class="font-weight-bolder"> Maneja el llenado de las dimensiones desde este panel</h5>
                    </div>
                    <div class="card-body  blue lighten-5">
                        <div class="container-fluid">
                            <div class="row text-center mx-auto">
                                <div class="col-xl-2 col-lg-2 hidden-col-md hidden-col-sm"></div>
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
                                    <div class="card border border-warning b-2 rounded mb-0 animate__animated animate__flipInY">
                                        <div class="card-header amber accent-4">
                                            <div class="row">
                                                <div class="col-xl-9 col-lg-9 col-md-9 col-sm-8">
                                                    <h6 class="text-white font-weight-bold">
                                                        Dimensiones Academicas Pendientes
                                                    </h6>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4">
                                                    <img src="../img/partial-sidebar/departamentos-icon.svg" alt="Control de Departamentos">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <br>
                                            <div class="view overlay">
                                                <img class="card-img-top" src="../img/jefe-coordinador/pending_approval.svg" alt="visualizar carrera">
                                            </div>
                                            <hr>
                                            <button type="button" class="btn btn-indigo btn-block" data-toggle="modal" data-target="#modalLlenadoDimension" onclick="llenar('DimensionesTabla')">
                                                Registrar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3" id="mda">
                                    <div class="card border border-warning b-2 rounded mb-0 animate__animated animate__flipInY">
                                        <div class="card-header amber accent-4">
                                            <div class="row">
                                                <div class="col-xl-9 col-lg-9 col-md-9 col-sm-8">
                                                    <h6 class="text-white font-weight-bold">
                                                        Visualizar Actividades
                                                    </h6>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4">
                                                    <img src="../img/partial-sidebar/departamentos-icon.svg" alt="Control de Departamentos">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="view overlay">
                                                <img class="card-img-top" src="../img/jefe-coordinador/Reviewed_docs.svg" alt="registrar carrera">
                                            </div>
                                            <hr>
                                            <button type="button" class="btn btn-indigo btn-block" data-toggle="modal" data-target="#modalModificarDimension" onclick="llenar('DimensionesTablaModificar')">
                                                Visualizar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-2 hidden-col-md hidden-col-sm"></div>
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
    <!--llenado de dimensiones-->
    <div class="modal fade" id="modalLlenadoDimension" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Dimensiones Academicas pendientes</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body table-responsive">
                    <table id="DimensionesTabla" class="table" cellspacing="0" width="100%">
                        <thead>
                            <tr align="center">
                                <th scope="col">Estado</th>
                                <th scope="col">No Dimension</th>
                                <th scope="col">Dimension Academica</th>
                                <th scope="col">Llenar dimension</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="modal-footer card-footer amber accent-4">
                </div>
            </div>
        </div>
    </div>

    <!--Modificar Actividades-->
    <div class="modal fade" id="modalModificarDimension" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Listado de dimensiones a modificar</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="ventana1">
                        <div class="tabla table-responsive">
                            <table id="Actividades-Por-Dimension" class="table" cellspacing="0" width="100%">
                                <thead>
                                    <tr align="center">
                                        <th scope="col">#</th>
                                        <th scope="col">Dimension Estrategica</th>
                                        <th scope="col">Ver actividades planificadas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer card-footer amber accent-4">
                    <div class="text-center" id="foote-modal">
                        <button id="close" type="button" class="btn btn-danger btn-rounded btn-sm" data-dismiss="modal" aria-label="Close">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actividades planificadas
    <div class="modal fade" id="modalActividadesPlanificadas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Dimensiones Academicas pendientes</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="tabla table-responsive">
                        <table id="ActividadTabla" class="table" cellspacing="0" width="100%">
                            <thead>
                                <tr align="center">
                                    <th scope="col">correlativo</th>
                                    <th scope="col">objetivo institucional</th>
                                    <th scope="col">area</th>
                                    <th scope="col">actividad</th>
                                    <th scope="col">monto</th>
                                    <th scope="col">responsable</th>
                                    <th scope="col"># actividades</th>
                                    <th scope="col">accion</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="modal-footer card-footer amber accent-4">
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div> -->

    <!--modales-->

    <div class="modal fade" id="modalCargaActividades" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Listado Actividades por dimension</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table" id="listado-actividades">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center">#</th>
                                    <th scope="col" class="text-center">Objetivo Institucional</th>
                                    <th scope="col" class="text-center">Area Estrategica</th>
                                    <th scope="col" class="text-center">Resultado Institucionale</th>
                                    <th scope="col" class="text-center">Resultado Unidad</th>
                                    <th scope="col" class="text-center">Indicadores Resultado</th>
                                    <th scope="col" class="text-center">Correlativo Actividad</th>
                                    <th scope="col" class="text-center">Actividad</th>
                                    <th scope="col" class="text-center">Porcentaje Trimestre I</th>
                                    <th scope="col" class="text-center">Costo Trimestre I</th>
                                    <th scope="col" class="text-center">Porcentaje Trimestre II</th>
                                    <th scope="col" class="text-center">Costo Trimestre II</th>
                                    <th scope="col" class="text-center">Porcentaje Trimestre III</th>
                                    <th scope="col" class="text-center">Costo Trimestre III</th>
                                    <th scope="col" class="text-center">Porcentaje Trimestre IV</th>
                                    <th scope="col" class="text-center">Costo Trimestre IV</th>
                                    <th scope="col" class="text-center">Sumatoria Porcentajes Trimestres I,II,II,IV</th>
                                    <th scope="col" class="text-center">Costo Total Actividad</th>
                                    <th scope="col" class="text-center">Justificacion</th>
                                    <th scope="col" class="text-center">Medio</th>
                                    <th scope="col" class="text-center">Poblacion</th>
                                    <th scope="col" class="text-center">Responsable</th>
                                    <th scope="col" class="text-center">Desglose Administrativo</th>
                                    <th scope="col" class="text-center">Editar Actividad</th>
                                </tr>
                            </thead>
                            <tbody id="areas-estrategicas">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer card-footer amber accent-4">
                    <div class="text-center" id="foote-modal">
                        <button id="close" type="button" class="btn btn-danger btn-rounded btn-sm" data-dismiss="modal" aria-label="Close">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Agregar Actividad-->
    <div class="modal fade" id="modalesActividad" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Registro dimension administrativa</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container tabla">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="DimensionAdministrativa" id="labelDimensionAdministrativa">Seleccione la dimension administrativa:</label>
                                <select name="DimensionAdministrativa" id="DimensionAdministrativa" class="browser-default custom-select mb-4" onchange="generaTablasAcordeDimension(this)">
                                </select>
                                <span id="errorsDimensionAdministrativa" class="text-danger text-small d-none">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer card-footer amber accent-4">
                    <div class="row col-12">
                        <div class="text-center">
                            <button id="closeAct" type="button" class="btn btn-danger btn-rounded btn-sm" data-dismiss="modal" aria-label="Close">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDimensionesAdmin1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Registro dimension administrativa</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-header">
                    <div class="col-xl-12 mx-auto">
                        <div class="text-center mt-4">
                            <button type="button" class="btn btn-light-green btn-rounded" onclick="registraItemAdministrativo()">
                                <img src="../img/partial-sidebar/agregar-icon.svg" alt="">
                                Registrar datos administrativos de la actividad
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table" id="dimension-administrativa-1">
                            <thead>
                                <tr id="dimension-1">
                                    <th scope="col" class="text-center">#</th>
                                    <th scope="col" class="text-center">Actividad</th>
                                    <th scope="col" class="text-center">Cantidad Personas</th>
                                    <th scope="col" class="text-center">Cantidad</th>
                                    <th scope="col" class="text-center">Costo</th>
                                    <th scope="col" class="text-center">Costo Total</th>
                                    <th scope="col" class="text-center">Tipo Presupuesto</th>
                                    <th scope="col" class="text-center">Objeto de Gasto</th>
                                    <th scope="col" class="text-center">Descripcion Cuenta</th>
                                    <th scope="col" class="text-center">Dimension Estrategica</th>
                                    <th scope="col" class="text-center">Mes Requerido</th>
                                    <th scope="col" class="text-center">Editar Item </th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer card-footer amber accent-4">
                    <div class="row col-12">
                        <div class="text-center">
                            <button id="closeAct" type="button" class="btn btn-danger btn-rounded btn-sm" data-dismiss="modal" aria-label="Close">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalDimensionesAdmin2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Registro dimension administrativa</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-header">
                    <div class="col-xl-12 mx-auto">
                        <div class="text-center mt-4">
                            <button type="button" class="btn btn-light-green btn-rounded" onclick="registraItemAdministrativo()">
                                <img src="../img/partial-sidebar/agregar-icon.svg" alt="">
                                Registrar datos administrativos de la actividad
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table" id="dimension-administrativa-2">
                            <thead>
                                <tr id="dimension-2">
                                    <th scope="col" class="text-center">#</th>
                                    <th scope="col" class="text-center">Actividad</th>
                                    <th scope="col" class="text-center">Cantidad</th>
                                    <th scope="col" class="text-center">Meses</th>
                                    <th scope="col" class="text-center">Costo</th>
                                    <th scope="col" class="text-center">Costo Total</th>
                                    <th scope="col" class="text-center">Tipo Presupuesto</th>
                                    <th scope="col" class="text-center">Objeto de Gasto</th>
                                    <th scope="col" class="text-center">Descripcion Cuenta</th>
                                    <th scope="col" class="text-center">Dimension Estrategica</th>
                                    <th scope="col" class="text-center">Mes Requerido</th>
                                    <th scope="col" class="text-center">Editar Item</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer card-footer amber accent-4">
                    <div class="row col-12">
                        <div class="text-center">
                            <button id="closeAct" type="button" class="btn btn-danger btn-rounded btn-sm" data-dismiss="modal" aria-label="Close">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalDimensionesAdmin3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Registro dimension administrativa</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-header">
                    <div class="col-xl-12 mx-auto">
                        <div class="text-center mt-4">
                            <button type="button" class="btn btn-light-green btn-rounded" onclick="registraItemAdministrativo()">
                                <img src="../img/partial-sidebar/agregar-icon.svg" alt="">
                                Registrar datos administrativos de la actividad
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table" id="dimension-administrativa-3">
                            <thead>
                                <tr id="dimension-3">
                                    <th scope="col" class="text-center">#</th>
                                    <th scope="col" class="text-center">Actividad</th>
                                    <th scope="col" class="text-center">Cantidad</th>
                                    <th scope="col" class="text-center">Costo</th>
                                    <th scope="col" class="text-center">Costo Total</th>
                                    <th scope="col" class="text-center">Tipo Presupuesto</th>
                                    <th scope="col" class="text-center">Objeto de Gasto</th>
                                    <th scope="col" class="text-center">Descripcion Cuenta</th>
                                    <th scope="col" class="text-center">Dimension Estrategica</th>
                                    <th scope="col" class="text-center">Mes Requerido</th>
                                    <th scope="col" class="text-center">Editar Item</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer card-footer amber accent-4">
                    <div class="row col-12">
                        <div class="text-center">
                            <button id="closeAct" type="button" class="btn btn-danger btn-rounded btn-sm" data-dismiss="modal" aria-label="Close">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDimensionesAdmin4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Registro dimension administrativa</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-header">
                    <div class="col-xl-12 mx-auto">
                        <div class="text-center mt-4">
                            <button type="button" class="btn btn-light-green btn-rounded" onclick="registraItemAdministrativo()">
                                <img src="../img/partial-sidebar/agregar-icon.svg" alt="">
                                Registrar datos administrativos de la actividad
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table" id="dimension-administrativa-4">
                            <thead>
                                <tr id="dimension-4">
                                    <th scope="col" class="text-center">#</th>
                                    <th scope="col" class="text-center">Actividad</th>
                                    <th scope="col" class="text-center">Cantidad</th>
                                    <th scope="col" class="text-center">Costo</th>
                                    <th scope="col" class="text-center">Costo Total</th>
                                    <th scope="col" class="text-center">Tipo Presupuesto</th>
                                    <th scope="col" class="text-center">Objeto de Gasto</th>
                                    <th scope="col" class="text-center">Descripcion Cuenta</th>
                                    <th scope="col" class="text-center">Dimension Estrategica</th>
                                    <th scope="col" class="text-center">Mes Requerido</th>
                                    <th scope="col" class="text-center">Tipo de Equipo Tecnologico</th>
                                    <th scope="col" class="text-center">Editar Item</th>
                                </tr>

                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer card-footer amber accent-4">
                    <div class="row col-12">
                        <div class="text-center">
                            <button id="closeAct" type="button" class="btn btn-danger btn-rounded btn-sm" data-dismiss="modal" aria-label="Close">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalDimensionesAdmin5" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Registro dimension administrativa</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-header">
                    <div class="col-xl-12 mx-auto">
                        <div class="text-center mt-4">
                            <button type="button" class="btn btn-light-green btn-rounded" onclick="registraItemAdministrativo()">
                                <img src="../img/partial-sidebar/agregar-icon.svg" alt="">
                                Registrar datos administrativos de la actividad
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table" id="dimension-administrativa-5">
                            <thead>
                                <tr id="dimension-5">
                                    <th scope="col" class="text-center">#</th>
                                    <th scope="col" class="text-center">Actividad</th>
                                    <th scope="col" class="text-center">Cantidad</th>
                                    <th scope="col" class="text-center">Costo</th>
                                    <th scope="col" class="text-center">Costo Total</th>
                                    <th scope="col" class="text-center">Tipo Presupuesto</th>
                                    <th scope="col" class="text-center">Objeto de Gasto</th>
                                    <th scope="col" class="text-center">Descripcion Cuenta</th>
                                    <th scope="col" class="text-center">Dimension Estrategica</th>
                                    <th scope="col" class="text-center">Mes Requerido</th>
                                    <th scope="col" class="text-center">Editar Item</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer card-footer amber accent-4">
                    <div class="row col-12">
                        <div class="text-center">
                            <button id="closeAct" type="button" class="btn btn-danger btn-rounded btn-sm" data-dismiss="modal" aria-label="Close">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalDimensionesAdmin6" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Registro dimension administrativa</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-header">
                    <div class="col-xl-12 mx-auto">
                        <div class="text-center mt-4">
                            <button type="button" class="btn btn-light-green btn-rounded" onclick="registraItemAdministrativo()">
                                <img src="../img/partial-sidebar/agregar-icon.svg" alt="">
                                Registrar datos administrativos de la actividad
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table" id="dimension-administrativa-6">
                            <thead>
                                <tr id="dimension-6">
                                    <th scope="col" class="text-center">#</th>
                                    <th scope="col" class="text-center">Actividad</th>
                                    <th scope="col" class="text-center">Cantidad</th>
                                    <th scope="col" class="text-center">Costo</th>
                                    <th scope="col" class="text-center">Costo Total</th>
                                    <th scope="col" class="text-center">Tipo Presupuesto</th>
                                    <th scope="col" class="text-center">Objeto de Gasto</th>
                                    <th scope="col" class="text-center">Descripcion Cuenta</th>
                                    <th scope="col" class="text-center">Dimension Estrategica</th>
                                    <th scope="col" class="text-center">Mes Requerido</th>
                                    <th scope="col" class="text-center">Area de la Beca</th>
                                    <th scope="col" class="text-center">Editar Item</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer card-footer amber accent-4">
                    <div class="row col-12">
                        <div class="text-center">
                            <button id="closeAct" type="button" class="btn btn-danger btn-rounded btn-sm" data-dismiss="modal" aria-label="Close">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalDimensionesAdmin7" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Registro dimension administrativa</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-header">
                    <div class="col-xl-12 mx-auto">
                        <div class="text-center mt-4">
                            <button type="button" class="btn btn-light-green btn-rounded" onclick="registraItemAdministrativo()">
                                <img src="../img/partial-sidebar/agregar-icon.svg" alt="">
                                Registrar datos administrativos de la actividad
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table" id="dimension-administrativa-7">
                            <thead>
                                <tr id="dimension-7">
                                    <th scope="col" class="text-center">#</th>
                                    <th scope="col" class="text-center">Actividad</th>
                                    <th scope="col" class="text-center">Cantidad</th>
                                    <th scope="col" class="text-center">Costo</th>
                                    <th scope="col" class="text-center">Costo Total</th>
                                    <th scope="col" class="text-center">Tipo Presupuesto</th>
                                    <th scope="col" class="text-center">Objeto de Gasto</th>
                                    <th scope="col" class="text-center">Descripcion Cuenta</th>
                                    <th scope="col" class="text-center">Dimension Estrategica</th>
                                    <th scope="col" class="text-center">Mes Requerido</th>
                                    <th scope="col" class="text-center">Proyecto</th>
                                    <th scope="col" class="text-center">Editar Item</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer card-footer amber accent-4">
                    <div class="row col-12">
                        <div class="text-center">
                            <button id="closeAct" type="button" class="btn btn-danger btn-rounded btn-sm" data-dismiss="modal" aria-label="Close">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalDimensionesAdmin8" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Registro dimension administrativa</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-header">
                    <div class="col-xl-12 mx-auto">
                        <div class="text-center mt-4">
                            <button type="button" class="btn btn-light-green btn-rounded" onclick="registraItemAdministrativo()">
                                <img src="../img/partial-sidebar/agregar-icon.svg" alt="">
                                Registrar datos administrativos de la actividad
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table" id="dimension-administrativa-8">
                            <thead>
                                <tr id="dimension-8">
                                    <th scope="col" class="text-center">#</th>
                                    <th scope="col" class="text-center">Actividad</th>
                                    <th scope="col" class="text-center">Cantidad</th>
                                    <th scope="col" class="text-center">Costo</th>
                                    <th scope="col" class="text-center">Costo Total</th>
                                    <th scope="col" class="text-center">Tipo Presupuesto</th>
                                    <th scope="col" class="text-center">Objeto de Gasto</th>
                                    <th scope="col" class="text-center">Descripcion Cuenta</th>
                                    <th scope="col" class="text-center">Dimension Estrategica</th>
                                    <th scope="col" class="text-center">Mes Requerido</th>
                                    <th scope="col" class="text-center">Descripcion</th>
                                    <th scope="col" class="text-center">Cantidad</th>
                                    <th scope="col" class="text-center">Precio</th>
                                    <th scope="col" class="text-center">Ingreso</th>
                                    <th scope="col" class="text-center">70%</th>
                                    <th scope="col" class="text-center">30%</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer card-footer amber accent-4">
                    <div class="row col-12">
                        <div class="text-center">
                            <button id="closeAct" type="button" class="btn btn-danger btn-rounded btn-sm" data-dismiss="modal" aria-label="Close">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalRegistroDimensionAdmin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Registro/Modificacion dimension administrativa</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="text-center" style="color: #757575;" action="#!">
                        <div class="row">
                            <div class="col-12">
                                <div class="md-form">
                                <input type="text" id="NombreActividad" class="form-control">
                                <span id="errorsNombreActividad" class="text-danger text-small d-none">
                                </span>
                                <label for="NombreActividad" id="labelNombreActividad">
                                    Escriba la Actividad
                                </label>
                            </div>
                            </div>
                            <div class="col-12">
                                <div class="md-form">
                                    <input type="number" id="Cantidad" class="form-control">
                                    <span id="errorsCantidad" class="text-danger text-small d-none">
                                    </span>
                                    <label for="Cantidad" id="labelCantidad">
                                        Cantidad
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="md-form">
                                    <input type="text" id="UnidadMedida" class="form-control">
                                    <span id="errorsUnidadMedida" class="text-danger text-small d-none">
                                    </span>
                                    <label for="UnidadMedida" id="labelUnidadMedida">
                                        Unidad De Medida
                                    </label>
                                </div>
                            </div>
                            <div class="col-12 d-none" id="dimension-1-campo">
                                <div class="md-form">
                                    <input type="number" id="CantidadPersonas" class="form-control">
                                    <span id="errorsCantidadPersonas" class="text-danger text-small d-none">
                                    </span>
                                    <label for="CantidadPersonas" id="labelCantidadPersonas">
                                        Cantidad Personas
                                    </label>
                                </div>
                            </div>
                            <div class="col-12 d-none" id="dimension-2-campo">
                                <div class="md-form">
                                    <input type="number" id="Meses" class="form-control">
                                    <span id="errorsMeses" class="text-danger text-small d-none">
                                    </span>
                                    <label for="Meses" id="labelMeses">
                                        Meses
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="md-form">
                                    <input type="number" id="Costo" class="form-control">
                                    <span id="errorsCosto" class="text-danger text-small d-none">
                                    </span>
                                    <label for="Costo" id="labelCosto">
                                        Costo
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="md-form">
                                    <input type="number" id="CostoT" class="form-control" readonly disabled>

                                    <span id="errorsCostoT" class="text-danger text-small d-none">
                                    </span>
                                    <label for="CostoT" id="labelCostoT">
                                        Costo Total De la Actividad
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col-12">
                                <label for="TipoPresupuesto" id="labelTipoPresupuesto">Tipo de presupuesto:</label>
                                <select name="TipoPresupuesto" id="TipoPresupuesto" class="browser-default custom-select mb-4">

                                </select>
                                <span id="errorsTipoPresupuesto" class="text-danger text-small d-none">
                                </span>
                            </div>
                            <div class="col-12">
                                <label for="ObjGasto" id="labelObjGasto">Objeto de Gasto:</label>
                            </div>
                            <div class="input-field col-12">
                                <select name="ObjGasto" id="ObjGasto" class="browser-default custom-select mb-4" style="width: 100%">

                                </select>
                                <span id="errorsObjGasto" class="text-danger text-small d-none">
                                </span>
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="MesRequerido" id="labelMesRequerido">Mes Requerido:</label>
                            <select name="MesRequerido" id="MesRequerido" class="browser-default custom-select mb-4">
                            </select>
                            <span id="errorsMesRequerido" class="text-danger text-small d-none">
                            </span>
                        </div>
                        <div class="col-12" id="dimension-4-campo">
                            <div class="md-form">
                                <input type="text" id="TipoEquipoTecnologico" class="form-control">
                                <span id="errorsTipoEquipoTecnologico" class="text-danger text-small d-none">
                                </span>
                                <label for="TipoEquipoTecnologico" id="labelTipoEquipoTecnologico">
                                    Escriba el equipo tecnologico
                                </label>
                            </div>
                        </div>
                        <div class="col-12" id="dimension-6-campo">
                            <div class="md-form">
                                <input type="text" id="AreaBeca" class="form-control">
                                <span id="errorsAreaBeca" class="text-danger text-small d-none">
                                </span>
                                <label for="AreaBeca" id="labelAreaBeca">
                                    Escriba en area de la beca
                                </label>
                            </div>
                        </div>
                        <div class="col-12" id="dimension-7-campo">
                            <label for="Proyecto" id="labelProyecto">Proyecto:</label>
                            <select name="Proyecto" id="Proyecto" class="browser-default custom-select mb-4">
                                <option value="" selected>Seleccionar el proyecto</option>
                                <option value="Gestion Academica">Gestion Academica</option>
                                <option value="Proceso integral de la internacionalización de la Educación Superior">Proceso integral de la internacionalización de la Educación Superior</option>
                                <option value="Gobernabilidad y Procesos  de Gestión Descentralizada en Redes">Gobernabilidad y Procesos de Gestión Descentralizada en Redes</option>
                            </select>
                            <span id="errorsProyecto" class="text-danger text-small d-none">
                            </span>
                        </div>
                        <div class="col-12" id="dimension-8-campo">
                            <div class="md-form">
                                <input type="text" id="DescripcionDimOcho" class="form-control">

                                <span id="errorsDescripcionDimOcho" class="text-danger text-small d-none">
                                </span>
                                <label for="DescripcionDimOcho" id="labelDescripcionDimOcho">
                                    Descripcion
                                </label>
                            </div>
                            <div class="md-form">
                                <input type="number" id="CantidadDimOcho" class="form-control">
                                
                                <span id="errorsCantidadDimOcho" class="text-danger text-small d-none">
                                </span>
                                <label for="CantidadDimOcho" id="labelCantidadDimOcho">
                                    Cantidad
                                </label>
                            </div>
                            <div class="md-form">
                                <input type="number" id="PrecioDimOcho" class="form-control">
                                
                                <span id="errorsPrecioDimOcho" class="text-danger text-small d-none">
                                </span>
                                <label for="PrecioDimOcho" id="labelPrecioDimOcho">
                                    Precio
                                </label>
                            </div>
                            <div class="md-form">
                                <input type="number" id="Setenta" class="form-control" minlength="1" maxlength="2">
                                
                                <span id="errorsSetenta" class="text-danger text-small d-none">
                                </span>
                                <label for="Setenta" id="labelSetenta">
                                    70%
                                </label>
                            </div>
                            <div class="md-form">
                                <input type="number" id="Treinta" class="form-control" minlength="1" maxlength="2">
                                
                                <span id="errorsTreinta" class="text-danger text-small d-none">
                                </span>
                                <label for="Treinta" id="labelTreinta">
                                    30%
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer card-footer amber accent-4">
                    <div class="row col-12 ml-auto">
                        <div class="text-center d-none" id="insertarItems">
                            <button onclick="agregarAct()" type="button" class="btn btn-light-green btn-rounded btn-sm">Guardar cambios</button>
                        </div>
                        <div class="text-center d-none" id="modificarItems">
                            <button onclick="modificarAct()" type="button" class="btn btn-light-green btn-rounded btn-sm">Modificar Item</button>
                        </div>
                        <div class="text-center">
                            <button id="closeAct" type="button" class="btn btn-danger btn-rounded btn-sm" data-dismiss="modal" onclick="vaciarAct()" aria-label="Close">
                                Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--llenado de actividades-->
    <!--formulario llenado de dimension-->
    <div class="modal fade" id="modalFormLlenadoDimension" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Dimensiones Academicas pendientes</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body table-responsive">
                    <form id="formulario-registro-dimension" class="text-center" style="color: #757575;">
                        <div class="form-row col-12" style="margin-left: auto;margin-right:auto">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <div class="input-field col-12" style="padding-left:0" align="left">
                                    <label for="DimEstrategica" id="labelDimEstrategica">Dimension Estrategica Seleccionada:</label>
                                    <select name="DimEstrategica" id="DimEstrategica" class="browser-default custom-select mb-4">
                                    </select>
                                    <span id="errorsObjInstitucional" class="text-danger text-small d-none">
                                    </span>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <div class="input-field col-12" style="padding-left:0" align="left">
                                    <label for="ObjInstitucional" id="labelObjInstitucional">Objetivo Institucional:</label>
                                    <select name="ObjInstitucional" id="ObjInstitucional" class="browser-default custom-select mb-4" onchange="cargarAreasEstrategicasActivas()">

                                    </select>
                                    <span id="errorsObjInstitucional" class="text-danger text-small d-none">
                                    </span>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 d-none" id="selecciona-registro-area">
                                <div class="input-field col-12" style="padding-left:0" align="left">
                                    <label for="AreaEstrategica" id="labelAreaEstrategica">Area Estrategica:</label>
                                    <select name="AreaEstrategica" id="AreaEstrategica" class="browser-default custom-select mb-4">
                                        <option value="" selected disabled></option>
                                    </select>
                                    <span id="errorsAreaEstrategica" class="text-danger text-small d-none">
                                    </span>
                                </div>
                            </div>
                            <div class="row col-12" style="margin-left:auto;margin-right:auto">
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12" style="margin-top:2px">
                                    <button type="button" class="btn btn-indigo btn-block" onclick="ag()">
                                        Agregar actividad
                                    </button>
                                </div>
                                <!-- <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12" style="margin-left:auto;margin-top:2px">
                                    <button type="button" class="btn btn-indigo btn-block" data-toggle="modal" data-target="#modalActividadesPlanificadas">
                                        Ver planificación
                                    </button>
                                </div> -->
                            </div>
                        </div>
                    </form>
                    <div class="container">
                        <div class="row">
                            <div class="input col-xl-5 col-lg-5 col-md-5 col-sm-5 row>
                                <label for=" PresupuestoUtilizado">Presupuesto Utilizado (Lps.):</label>
                                <input style="width:50%" type="text" id="PresupuestoUtilizado" class="form-control" readonly disabled>
                            </div>
                            <div class="input col-xl-5 col-lg-5 col-md-5 col-sm-5 row>
                                <label for=" PresupuestoDisponible">Presupuesto Disponible (Lps.):</label>
                                <input style="width:50%" type="text" id="PresupuestoDisponible" class="form-control" readonly disabled>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer card-footer amber accent-4">
                    <div class="row col-12">
                        <!-- <div class="text-center" style="margin-left:auto">
                            <button type="button" class="btn btn-light-green btn-rounded btn-sm" disabled>
                                Enviar
                            </button>
                        </div> -->
                        <div class="text-center" style="margin-left:auto">
                            <button type="button" class="btn btn-danger btn-rounded btn-sm" data-dismiss="modal" aria-label="Close">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalLlenadoActividades" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Dimensiones Academicas pendientes</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid" id="grad1">
                        <div class="row justify-content-center mt-0">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 text-center p-0 mt-3 mb-2">
                                <div class="card px-0 pt-4 pb-0 mt-3 mb-3">
                                    <h2><strong>Formulario para registro actividades</strong></h2>
                                    <div class="row">
                                        <div class="col-md-12 mx-0">
                                            <form id="msform">
                                                <!-- progressbar -->
                                                <ul id="progressbar">
                                                    <li class="active" id="Resultados"><strong>Resultados</strong></li>
                                                    <li id="Actividad"><strong>Actividad</strong></li>
                                                    <li id="MetasTrimestrales"><strong>Metas Trimestrales</strong></li>
                                                    <li id="Justificacion"><strong>Justificacion</strong></li>
                                                    <!-- <li id="ActividadeEspeciales"><strong>Desglosar Actividad</strong></li> -->
                                                </ul> <!-- fieldsets -->
                                                <fieldset id="primero">
                                                    <div class="form-card">
                                                        <h2 class="fs-title">Resultados</h2>
                                                        <div class="col-12">
                                                            <div class="input-field">
                                                                <label for="ResultadosInstitucional" id="labelResultadosInstitucional">Resultados Institucionales:</label>
                                                                <select name="ResultadosInstitucional" id="ResultadosInstitucional" class="browser-default custom-select mb-4">
                                                                    <option value="" selected disabled></option>
                                                                </select>
                                                                <span id="errorsResultadosInstitucional" class="text-danger text-small d-none"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="md-form">
                                                                <label for="ResultadosDeUnidad" id="labelResultadosDeUnidad">Resultados de la unidad:</label>
                                                                <textarea type="text" id="ResultadosDeUnidad" class="md-textarea form-control" minlength="1" maxlength="200"></textarea>
                                                                <span id="errorsResultadosDeUnidad" class="text-danger text-small d-none"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="md-form">
                                                                <label for="Indicador" id="labelIndicador">Indicador:</label>
                                                                <textarea type="text" id="Indicador" class="md-textarea form-control" minlength="1" maxlength="200"></textarea>
                                                                <span id="errorsIndicador" class="text-danger text-small d-none"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input type="button" name="next" class="next action-button" value="Siguiente" />
                                                </fieldset>
                                                <fieldset id="segundo">
                                                    <div class="form-card">
                                                        <h2 class="fs-title">Actividad</h2>
                                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                                            <div class="row" style="margin:auto">
                                                                <label for="Correlativo">Correlativo</label>
                                                                <div class="col-xl-4 col-lg-4 col-md-7 col-sm-8">
                                                                    <input type="text" id="CorrelativoGeneradoParaRegistrar" class="form-control" readonly disabled>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="md-form">
                                                                <input type="text" id="Actividads" class="form-control" minlength="1" maxlength="200">
                                                                <span id="errorsActividads" class="text-danger text-small d-none"></span>
                                                                <label for="Actividads" id="labelActividads">Actividad</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input type="button" name="previous" class="previous action-button-previous" value="Regresar" />
                                                    <input type="button" name="next" class="next action-button" value="Siguiente" />
                                                </fieldset>
                                                <fieldset id="tercero">
                                                    <div class="form-card">
                                                        <h2 class="fs-title">Metas trimestrales</h2>
                                                        <div class="row">
                                                            <div class="md-form col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                                                <label for="PresupuestoActividad" id="labelPresupuestoActividad" class="font-weight-bolder text-dark">
                                                                    Digite el Costo para esta actividad:
                                                                </label>
                                                            </div>
                                                            <div class="md-form col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                                                <input type="number" id="PresupuestoActividad" class="form-control">
                                                                <span id="errorsPresupuestoActividad" class="text-danger text-small d-none">
                                                                </span>
                                                                <label for="PresupuestoActividad" id="labelPresupuestoActividad">
                                                                    Lps.
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="row text-center">
                                                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                                                <label class="font-weight-bolder text-dark">
                                                                    Distribuya los porcentajes por trimestre
                                                                </label>
                                                            </div>
                                                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                                                <div class="form-group d-flex">
                                                                    <label class="font-weight-bolder text-dark  my-auto form-control">
                                                                        Porcentaje total:
                                                                    </label>
                                                                    <input style="width:50%" type="number" id="SumaPorcentajes" class="form-control" readonly disabled>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row text-center">
                                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                                                                <div class="container">
                                                                    <div class="form-group d-flex">
                                                                        <label for="PTrimestre" class="form-control">Primer Trimestre:</label>
                                                                        <input style="width:50%" type="text" id="calculaValorPT1" class="form-control" readonly disabled>
                                                                    </div>
                                                                    <div id="PTrimestre" class="row">
                                                                        <div class="md-form col-xl-6 col-lg-6 col-md-6 col-sm-6 mx-auto">
                                                                            <input type="number" id="PorcentPTrimestre" class="form-control" maxlength="3" minlength="1" onkeyup="procesaValorTrimestreUno(this)">
                                                                            <span id="errorsPorcentPTrimestre" class="text-danger text-small d-none">
                                                                            </span>
                                                                            <label for="PorcentPTrimestre" id="labelPorcentPTrimestre">
                                                                                %
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                                                                <div class="container">
                                                                    <div class="form-group d-flex">
                                                                        <label class="form-control" for="STrimestre">Segundo Trimestre:</label>
                                                                        <input style="width:50%" type="text" id="calculaValorPT2" class="form-control" readonly disabled>
                                                                    </div>
                                                                    <div id="STrimestre" class="row">
                                                                        <div class="md-form col-xl-6 col-lg-6 col-md-6 col-sm-6 mx-auto">
                                                                            <input type="number" id="PorcentSTrimestre" class="form-control" maxlength="3" minlength="1" onkeyup="procesaValorTrimestreDos(this)">
                                                                            <span id="errorsPorcentSTrimestre" class="text-danger text-small d-none">
                                                                            </span>
                                                                            <label for="PorcentSTrimestre" id="labelPorcentSTrimestre">
                                                                                %
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                                                                <div class="container">
                                                                    <div class="form-group d-flex">
                                                                        <label class="form-control" for="TTrimestre">Tercer Trimestre:</label>
                                                                        <input style="width:50%" type="text" id="calculaValorPT3" class="form-control" readonly disabled>
                                                                    </div>
                                                                    <div id="TTrimestre" class="row">
                                                                        <div class="md-form col-xl-6 col-lg-6 col-md-6 col-sm-6 mx-auto">
                                                                            <input type="number" id="PorcentTTrimestre" class="form-control" maxlength="3" minlength="1" onkeyup="procesaValorTrimestreTres(this)">
                                                                            <span id="errorsPorcentTTrimestre" class="text-danger text-small d-none">
                                                                            </span>
                                                                            <label for="PorcentTTrimestre" id="labelPorcentTTrimestre">
                                                                                %
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                                                                <div class="container">
                                                                    <div class="form-group d-flex">
                                                                        <label for="CTrimestre" class="form-control">Cuarto Trimestre:</label>
                                                                        <input style="width:50%" type="text" id="calculaValorPT4" class="form-control" readonly disabled>
                                                                    </div>
                                                                    <div id="CTrimestre" class="row">
                                                                        <div class="md-form col-xl-6 col-lg-6 col-md-6 col-sm-6 mx-auto">
                                                                            <input type="number" id="PorcentCTrimestre" class="form-control" maxlength="3" minlength="1" onkeyup="procesaValorTrimestreCuatro(this)">
                                                                            <span id="errorsPorcentCTrimestre" class="text-danger text-small d-none">
                                                                            </span>
                                                                            <label for="PorcentCTrimestre" id="labelPorcentCTrimestre">
                                                                                %
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input type="button" name="previous" class="previous action-button-previous" value="Regresar" />
                                                    <input type="button" name="next" class="next action-button" value="Siguiente" id="verificaValores"/>
                                                </fieldset>
                                                <fieldset id="cuarto">
                                                    <div class="form-card">
                                                        <h2 class="fs-title">Justificacion</h2>
                                                        <div class="col-12">
                                                            <div class="md-form">
                                                                <label class="form-label" for="Justificacions" id="labelJustificacions">Justificacion:</label>
                                                                <textarea type="text" id="Justificacions" class="md-textarea form-control" minlength="1" maxlength="255"></textarea>
                                                                <span id="errorsJustificacions" class="text-danger text-small d-none"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="md-form">
                                                                <label class="form-label" for="Medio" id="labelMedio">Medio de verificacion:</label>
                                                                <textarea type="text" id="Medio" class="md-textarea form-control" minlength="1" maxlength="255"></textarea>
                                                                <span id="errorsMedio" class="text-danger text-small d-none"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="md-form">
                                                                <label class="form-label" for="Poblacion" id="labelPoblacion">Poblacion Objetivo:</label>
                                                                <textarea type="text" id="Poblacion" class="md-textarea form-control" minlength="1" maxlength="255"></textarea>
                                                                <span id="errorsPoblacion" class="text-danger text-small d-none"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="md-form">
                                                                <label class="form-label" for="Responsable" id="labelResponsable">Responsable:</label>
                                                                <textarea type="text" id="Responsable" class="md-textarea form-control" minlength="1" maxlength="255"></textarea>
                                                                <span id="errorsResponsable" class="text-danger text-small d-none"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input type="button" name="previous" class="previous action-button-previous" value="Regresar" />
                                                    <input type="button" id="save" class="action-button" onclick="insertaActividad()" value="Guardar" />
                                                    <input type="button" id="edit" class="action-button" onclick="modificarDataActividad()" value="Modificar" />
                                                </fieldset>
                                                <!-- </fieldset>
                                                <fieldset id="quinto">
                                                    <div class="form-card">
                                                        <h2 class="fs-title">Desglosar Actividad</h2>
                                                        <div class="container row">
                                                            <div class="col-xl-10 col-lg-10 col-md-10 col-sm-10 text-center mx-auto">
                                                                <button type="button" class="btn btn-indigo btn-block" id="bot">
                                                                    Agregar a dimensiones administrativas
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input type="button" name="previous" class="previous action-button-previous" value="Regresar" />
                                                    <input id="save" type="button" name="save" class="action-button" value="Aceptar" />
                                                </fieldset> -->
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer card-footer amber accent-4">
                    <div class="text-center" id="foote-modal">
                        <button id="close" type="button" class="registrar-actividad-cancelar btn btn-danger btn-rounded btn-sm" data-dismiss="modal" aria-label="Close" onclick="limpiaFormActividad()">Cancelar</button>
                        <button id="close" type="button" class="modificar-actividad-cancelar btn btn-danger btn-rounded btn-sm" data-dismiss="modal" aria-label="Close">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="modal fade" id="modaModificacionActividad" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Formulario para la Modificacion de una Actividad</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer card-footer amber accent-4">
                    <div class="row col-12">
                        <div class="text-center">
                            <button ype="button" class="btn btn-danger btn-rounded btn-sm" data-dismiss="modal" aria-label="Close">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <!--En esta zona podran poner javascripts propios de la vista-->

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
    <script src="../js/Jefe-Coordinador/principal.js"></script>
    <script src="../js/Jefe-Coordinador/llenado.js"></script>

    <?php
    include('../partials/endDoctype.php');
    ?>