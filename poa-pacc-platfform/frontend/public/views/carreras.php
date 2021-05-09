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
    include('../partials/doctype.php');;
    include('verifica-session.php');
?>
<title>Control de Carreras</title>
<!--En esta zona podran poner estilos propios de la vista-->
<link rel="stylesheet" href="../css/animaciones/animate.min.css" />
<link rel="stylesheet" href="../css/Carreras/Carrera.css" />
<link rel="stylesheet" href="../css/sweet-alert-two/sweetalert2.min.css">
<script src="../js/libreria-bootstrap-mdb/jquery.min.js" type="text/javascript"></script>   

<link rel="stylesheet" href="../js/data-tables/datatables.min.css">
<link rel="stylesheet" href="../js/data-tables/DataTables/css/dataTables.bootstrap4.min.css">


</head>

<body id="body-pd">
    <?php include('../layouts/Nabvar.php'); ?>
    <?php include('../layouts/Sidebar.php'); ?>
    
    <div id="profile-card" class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header indigo darken-4 text-white">
                        <h5 class="font-weight-bolder"> Maneja el control de carreras desde este panel</h5>
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
                                                        Visualizar Carreras
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
                                                <img 
                                                    class="card-img-top" 
                                                    src="../img/carreras/visualizar-carreras.svg" 
                                                    alt="visualizar carrera">
                                            </div>
                                            <hr>
                                            <button 
                                                type="button"
                                                class="btn btn-indigo btn-block"  
                                                data-toggle="modal" 
                                                data-target="#modalVisualizarCarreras"
                                                onclick="obtenerCarreras()">
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
                                                        Registrar Carrera
                                                    </h6>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4">
                                                    <img src="../img/partial-sidebar/departamentos-icon.svg" alt="Control de Departamentos">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="view overlay">
                                                <img 
                                                    class="card-img-top" 
                                                    src="../img/departamentos/registro-deptos.svg" 
                                                    alt="registrar carrera">
                                            </div>
                                            <hr>
                                            <button 
                                                type="button"
                                                class="btn btn-indigo btn-block"  
                                                data-toggle="modal" 
                                                data-target="#modalRegistrarCarrera"
                                                onclick="cambiarDepaEstado()">
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
                                                        Modificar Carrera
                                                    </h6>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4">
                                                    <img src="../img/partial-sidebar/departamentos-icon.svg" alt="Control de Departamentos">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="view overlay">
                                                <img 
                                                    class="card-img-top" 
                                                    src="../img/departamentos/registro-deptos.svg"  
                                                    alt="Modificar carrera">
                                            </div>
                                            <hr>
                                            <button 
                                                type="button"
                                                class="btn btn-indigo btn-block"  
                                                data-toggle="modal" 
                                                data-target="#modalModificarCarrera"
                                                onclick="cambiarModif()">
                                                Modificar
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
    <!--Visualizar Carreras-->
    <div 
        class="modal fade" 
        id="modalVisualizarCarreras" 
        tabindex="-1" role="dialog" 
        aria-labelledby="myModalLabel" 
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Listado de carreras registradas</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body table-responsive">
                    <table id="CarrerasTodas" class="table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Carrera</th>
                                <th scope="col">Abreviatura</th>
                                <th scope="col">Departamento</th>
                                <th scope="col">Estado</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="modal-footer card-footer amber accent-4">
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!--Registrar Carreras-->
    <div 
        class="modal fade" 
        id="modalRegistrarCarrera" 
        tabindex="-1" role="dialog" 
        aria-labelledby="myModalLabel" 
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Formulario para registro de carreras</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <div class="modal-body text-center mb-1">
                <form class="text-center" style="color: #757575;" action="#!">
                        <div class="form-row">
                            <div class="col-10">
                                <!-- Carrera -->
                                <div class="md-form">
                                    <input type="text" id="Carrera" class="form-control" maxlength="80">
                                    <span id="errorsCarrera" class="text-danger text-small d-none">
                                    </span>
                                    <label 
                                        for="Carrera"
                                        id="labelCarrera"
                                    >
                                    Carrera
                                    </label>
                                </div>
                            </div>
                            <div class="col-2">
                                <!-- Abrev -->
                                <div class="md-form" style="width:50%; margin-left:auto;margin-right:auto;">
                                    <input type="text" id="Abreviatura" class="form-control" maxlength="2">
                                    <span id="errorsAbreviatura" class="text-danger text-small d-none">
                                    </span>
                                    <label 
                                        for="Abreviatura"
                                        id="labelAbreviatura"
                                    >
                                    Abrev
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="input-field col-8" style="padding-left:0" align="left">
                                <label for="Departamento" id="labelDepartamento">Seleccione un Departamento:</label>
                                <select name="Departamento" id="Departamento" class="browser-default custom-select mb-4">
               
                                </select>
                                <span id="errorsDepartamento" class="text-danger text-small d-none">
                                </span>
                            </div>
                            <div class="input-field col-4" style="padding-left:0" align="left">
                                <label for="Estado" id="labelEstado">Estado:</label>
                                <select name="Estado" id="Estado" class="browser-default custom-select mb-4">
            
                                </select>
                                <span id="errorsEstado" class="text-danger text-small d-none">
                                </span>
                            </div>
                        </div>

                    </form>
                </div>
                </div>
                <div class="modal-footer card-footer amber accent-4">
                    <div class="text-center">
                        <button 
                            type="button" 
                            class="btn btn-light-green btn-rounded btn-sm" 
                            onclick="insertadoCarrera()">
                            Registrar Carrera
                        </button>
                    </div>
                    <div class="text-center">
                        <button onclick="limpiarR()" type="button" class="btn btn-danger btn-rounded btn-sm" data-dismiss="modal" aria-label="Close">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Modificar Carrera-->
    <div 
        class="modal fade" 
        id="modalModificarCarrera" 
        tabindex="-1" role="dialog" 
        aria-labelledby="myModalLabel" 
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Formulario para modificar Carreras</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <form class="text-center" style="color: #757575;" action="#!">
                    <h4 align="center">Seleccione carrera a editar:</h4>
                    <div class="form-row">
                        <div class="input-field col-6" align="left">
                            <label>Seleccione un Departamento:</label>
                            <select id="Departamento2" class="browser-default custom-select mb-4" onchange="cambiarDepaModif()">
                                
                            </select>
                        </div>
                        <div id="carreraSel"  class="input-field col-6" align="left">
                            <label>Seleccione una Carrera:</label>
                            <select id="carreraDepa" class="browser-default custom-select mb-4" onchange="cambiarCarreraModif()">
                                
                            </select>
                        </div>
                    </div>

                    <div id="modifAbajo">
                        <h4 align="center">Carrera Seleccionada:</h4>
                        <div class="row">
                            <div class="col-8">
                                <!-- Carrera -->
                                <div class="md-form">
                                    <input type="text" id="Carrera2" class="form-control" maxlength="80">
                                    <span id="errorsCarrera2" class="text-danger text-small d-none">
                                    </span>
                                    <label 
                                        for="Carrera2"
                                        id="labelCarrera2"
                                    >
                                    Carrera
                                    </label>
                                </div>
                            </div>
                            <div class="col-4">
                                <!-- Abrev -->
                                <div class="md-form">
                                    <input type="text" id="Abreviatura2" class="form-control" maxlength="2">
                                    <span id="errorsAbreviatura2" class="text-danger text-small d-none">
                                    </span>
                                    <label 
                                        for="Abreviatura2"
                                        id="labelAbreviatura2"
                                    >
                                    Abrev
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col-8" align="left">
                                <label for="DepartamentoModif" id="labelDepartamentoModif">Seleccione un Departamento:</label>
                                <select name="DepartamentoModif" id="DepartamentoModif" class="browser-default custom-select mb-4">
               
                                </select>
                                <span id="errorsDepartamentoModif" class="text-danger text-small d-none">
                                </span>
                            </div>
                            <div class="input-field col-4" align="left">
                                <label for="EstadoModif" id="labelEstadoModif">Estado:</label>
                                <select name="EstadoModif" id="EstadoModif" class="browser-default custom-select mb-4">
            
                                </select>
                                <span id="errorsEstadoModif" class="text-danger text-small d-none">
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
                </div>
                <div class="modal-footer card-footer amber accent-4">
                    <div class="text-center">
                        <button id="botonModif" type="button" class="btn btn-light-green btn-rounded btn-sm" onclick="actualizarCarrera()">Guardar Cambios</button>
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-danger btn-rounded btn-sm" data-dismiss="modal" aria-label="Close">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--En esta zona podran poner javascripts propios de la vista-->
    
    <script src="../js/sweet-alert-two/sweetalert2.min.js"></script>

    <script src="../js/data-tables/datatables.min.js"></script>

    <script src="../js/data-tables/Buttons/js/dataTables.buttons.min.js"></script>
    
    <script src="../js/config/config.js"></script>
    <script src="../js/validators/form-validator.js"></script>
    <script src="../js/Carreras/CarrerasController.js"></script>

<?php
    include('../partials/endDoctype.php');
?>
