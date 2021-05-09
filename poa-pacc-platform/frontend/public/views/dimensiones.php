<?php
if (!isset($_SESSION)) {
    session_start();
}
$superAdmin = 'SU_AD';
if ($_SESSION['abrevTipoUsuario'] != $superAdmin) {
    header('Location: 401.php');
}
if (!isset($_SESSION['correoInstitucional'])) {
    header('Location: 401.php');
}
include('../partials/doctype.php');
include('verifica-session.php');
?>
<title>Control de Dimensiones</title>
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
                        Registrar una nueva dimension
                    </button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card animate__animated animate__fadeInDown">
                    <div class="card-header indigo darken-4 text-white">
                        <h5 class="font-weight-bolder"> Maneja el control de dimensiones desde este panel</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="listado-dimensiones">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Dimension Estrategica</th>
                                        <th scope="col">Estado Dimension</th>
                                        <th scope="col">Ver Objetivos Institucionales</th>
                                        <th scope="col">Modificar Dimension</th>
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
                    <h4 class="modal-title w-100" id="myModalLabel">Formulario para registrar una dimension</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-registro-dimension" class="text-center" style="color: #757575;">
                        <div class="form-row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <div class="md-form">
                                    <input type="text" id="R-nombreDimension" class="form-control" maxlength="150" minlength="1" required>
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
                    <h4 class="modal-title w-100" id="myModalLabel">Formulario para modificar una dimension</h4>
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
                                        Escriba el nombre de la dimension
                                    </label>
                                    <input type="text" id="M-nombreDimension" class="form-control" maxlength="150" minlength="1" required>
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
    <div class="modal fade" id="modalObjetivosInstitucionales" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Listado Objetivos institucionales</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-xl-12 mx-auto">
                            <div class="text-center mt-4">
                                <button type="button" class="btn btn-light-green btn-rounded" data-toggle="modal" data-target="#modalRegistroObjetivo">
                                    <img src="../img/partial-sidebar/agregar-icon.svg" alt="">
                                    Registrar un nuevo objetivo
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table" id="listado-objetivos">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Objetivo Institucional</th>
                                    <th scope="col">Estado Objetivo</th>
                                    <th scope="col">Areas Estrategicas</th>
                                    <th scope="col">Modificar Objetivo</th>
                                </tr>
                            </thead>
                            <tbody id="objetivos-institucionales">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" data-toggle="modal" id="modalRegistroObjetivo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Formulario para registrar un objetivo intitucional</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-registro-objetivo" class="text-center" style="color: #757575;">
                        <div class="form-row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <div class="md-form">
                                    <textarea id="R-objetivoInstitucional" class="md-textarea form-control" rows="5" maxlength="800" minlength="1" required>
                                    </textarea>
                                    <span id="errorsR-objetivoInstitucional" class="text-danger text-small d-none">
                                    </span>
                                    <label for="R-objetivoInstitucional" id="labelR-objetivoInstitucional" name="labelR-objetivoInstitucional">Escriba el nombre del objetivo institucional
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="text-center mt-4">
                        <button id="btn-registrar-objetivo" type="button" class="btn btn-light-green btn-rounded" onclick="registrarObjetivo()">Registrar Objetivo</button>
                    </div>
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-danger btn-rounded" data-dismiss="modal" aria-label="Close" onclick="cancelarRegistroObjetivo()">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalModificarObjetivo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Formulario para modificar un objetivo intitucional</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-modificacion-objetivo" class="text-center" style="color: #757575;">
                        <div class="form-row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <div class="md-form">
                                    <label for="M-objetivoInstitucional" class="" id="labelM-objetivoInstitucional" name="labelM-objetivoInstitucional">
                                        Escriba el nombre del objetivo institucional
                                    </label>
                                    <textarea id="M-objetivoInstitucional" class="md-textarea form-control" rows="5" maxlength="800" minlength="1" required>
                                    </textarea>
                                    <span id="errorsM-objetivoInstitucional" class="text-danger text-small d-none">
                                    </span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="text-center mt-4">
                        <button id="btn-modificar-objetivo" type="button" class="btn btn-light-green btn-rounded" onclick="modificarObjetivoInstitucional()">Modificar Objetivo Institucional</button>
                    </div>
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-danger btn-rounded" data-dismiss="modal" aria-label="Close" onclick="cancelarModificacionObjetivo()">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalAreasEstrategicas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Listado Areas Estrategicas</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-xl-12 mx-auto">
                            <div class="text-center mt-4">
                                <button type="button" class="btn btn-light-green btn-rounded" data-toggle="modal" data-target="#modalRegistroArea">
                                    <img src="../img/partial-sidebar/agregar-icon.svg" alt="">
                                    Registrar un nueva Area Estrategica
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table" id="listado-areas">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Area Estrategica</th>
                                    <th scope="col">Estado Area</th>
                                    <th scope="col">Resultados Institucionales</th>
                                    <th scope="col">Modificar Area Estrategica</th>
                                </tr>
                            </thead>
                            <tbody id="areas-estrategicas">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" data-toggle="modal" id="modalRegistroArea" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Formulario para registrar un area estrategica</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-registro-area" class="text-center" style="color: #757575;">
                        <div class="form-row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <div class="md-form">
                                    <textarea id="R-areaEstrategica" class="md-textarea form-control" rows="5" maxlength="500" minlength="1" required>
                                    </textarea>
                                    <span id="errorsR-areaEstrategica" class="text-danger text-small d-none">
                                    </span>
                                    <label for="R-areaEstrategica" id="labelR-areaEstrategica" name="labelR-areaEstrategica">Escriba el nombre de la area Estrategica
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="text-center mt-4">
                        <button id="btn-registrar-area" type="button" class="btn btn-light-green btn-rounded" onclick="registrarArea()">Registrar Area</button>
                    </div>
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-danger btn-rounded" data-dismiss="modal" aria-label="Close" onclick="cancelarRegistroArea()">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalModificarArea" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Formulario para modificar un area estrategica</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-modificacion-area" class="text-center" style="color: #757575;">
                        <div class="form-row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <div class="md-form">
                                    <label for="M-areaEstrategica" class="" id="labelM-areaEstrategica" name="labelM-areaEstrategica">
                                        Escriba el nombre del area estrategica
                                    </label>
                                    <textarea id="M-areaEstrategica" class="md-textarea form-control" rows="5" maxlength="500" minlength="1" required>
                                    </textarea>
                                    <span id="errorsM-areaEstrategica" class="text-danger text-small d-none">
                                    </span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="text-center mt-4">
                        <button id="btn-modificar-area" type="button" class="btn btn-light-green btn-rounded" onclick="modificarAreaEstrategica()">Modificar Area Estrategica</button>
                    </div>
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-danger btn-rounded" data-dismiss="modal" aria-label="Close" onclick="cancelarModificacionArea()">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalResultadosInstitucionales" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Listado Resultados Institucionales</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-xl-12 mx-auto">
                            <div class="text-center mt-4">
                                <button 
                                    type="button" 
                                    class="btn btn-light-green btn-rounded" 
                                    onclick="modalRegistraResultadoInstitucional()">
                                    <img src="../img/partial-sidebar/agregar-icon.svg" alt="">
                                    Registrar un nuevo resultado institucional
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table" id="listado-resultados">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Resultado Institucional</th>
                                    <th scope="col">Estado Resultado</th>
                                    <th scope="col">Modificar Resultado</th>
                                </tr>
                            </thead>
                            <tbody id="resultados-institucionales">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalRegistraResultadosInstitucionales" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Formulario para registrar resultados institucionales</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <div class="md-form">
                                <label for="R-resultadoInstitucional" class="" id="labelR-resultadoInstitucional" name="labelR-resultadoInstitucional">
                                    Escriba el nombre del resultado institucional
                                </label>
                                <textarea id="R-resultadoInstitucional" class="md-textarea form-control" rows="5" maxlength="700" minlength="1" required>
                                </textarea>
                                <span id="errorsR-resultadoInstitucional" class="text-danger text-small d-none">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="text-center mt-4">
                        <button id="btn-registrar-resultado" type="button" class="btn btn-light-green btn-rounded" onclick="registrarResultado()">Registrar Resultado Institucional</button>
                    </div>
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-danger btn-rounded" data-dismiss="modal" aria-label="Close" onclick="cancelarRegistroResultado()">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalModificaResultadosInstitucionales" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Formulario para modificar resultados institucionales</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <div class="md-form">
                                <label for="M-resultadoInstitucional" class="" id="labelM-resultadoInstitucional" name="labelM-resultadoInstitucional">
                                    Escriba el nombre del resultado institucional
                                </label>
                                <textarea id="M-resultadoInstitucional" class="md-textarea form-control" rows="5" maxlength="700" minlength="1" required>
                                </textarea>
                                <span id="errorsM-resultadoInstitucional" class="text-danger text-small d-none">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="text-center mt-4">
                        <button id="btn-modificar-resultado" type="button" class="btn btn-light-green btn-rounded" onclick="modificarResultado()">Modificar Resultado Institucional</button>
                    </div>
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-danger btn-rounded" data-dismiss="modal" aria-label="Close" onclick="cancelarModificacionResultado()">Cancelar</button>
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
    <script src="../js/dimensiones-estrategicas/dimensiones.controller.js"></script>
    <script stc="../js/objetivos-institucionales/objetivos.controller.js"></script>
    <!--En esta zona podran poner javascripts propios de la vista-->
    <?php
    include('../partials/endDoctype.php');
    ?>