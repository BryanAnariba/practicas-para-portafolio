<?php
    if (!isset($_SESSION)) {
        session_start();
    }
    $coordinador = 'C_C';
    $jefe = 'J_D';
    $decano = 'D_F';
    $admin = 'SE_AD';
    $estratega = 'U_E';
    if ($_SESSION['abrevTipoUsuario'] != $decano 
    and $_SESSION['abrevTipoUsuario'] != $admin 
    and $_SESSION['abrevTipoUsuario'] != $estratega 
    and $_SESSION['abrevTipoUsuario'] != $coordinador 
    and $_SESSION['abrevTipoUsuario'] != $jefe) {
        header('Location: 401.php');
    }
    include('../partials/doctype.php');
    include('verifica-session.php');
    //Tipos de roles de usuarios en el sistema
?>
<title>Control de actividades</title>
<!--En esta zona podran poner estilos propios de la vista-->
<link rel="stylesheet" href="../css/sweet-alert-two/sweetalert2.min.css">
<script src="../js/libreria-bootstrap-mdb/jquery.min.js" type="text/javascript"></script>   

<link rel="stylesheet" href="../js/data-tables/datatables.min.css">
<link rel="stylesheet" href="../js/data-tables/DataTables/css/dataTables.bootstrap4.min.css">

<link href='../css/calendario-actividades/calendario.css' rel='stylesheet'>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css">

</head>

<body id="body-pd">
    <?php include('../layouts/Nabvar.php'); ?>
    <?php include('../layouts/Sidebar.php'); ?>
    
    <div id="profile-card" class="container">
        <div class="text-center mt-4">
        <?php 
            if(
                $_SESSION['abrevTipoUsuario'] == ROL_ESTRATEGA || 
                $_SESSION['abrevTipoUsuario'] == ROL_DECANO ||
                $_SESSION['abrevTipoUsuario'] == ROL_SECRETARIA_ADMINISTRATIVA
            ){
        ?>
            <button 
                type="button" 
                class="btn btn-light-green btn-rounded" 
                onclick="EliminarIdDepaDepa()"                        
            >

                Listado de actividades consolidado
            </button>       
            <button 
                type="button" 
                class="btn btn-light-green btn-rounded" 
                data-toggle="modal" 
                data-target="#modalSeleccionDepart"
            >                     
                Listado de actividades por departamento
            </button>
        <?php 
            }; 
        ?>
            <button 
                type="button" 
                class="btn btn-light-green btn-rounded" 
                onclick= " location.href='Calendario-actividades.php' "
            >                     
                Ver calendario de actividades
            </button>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header indigo darken-4 text-white">
                        <h5 class="font-weight-bolder">Listado de actividades</h5>
                    </div>
                    <div class="card-body  blue lighten-5">
                        <form class="text-center" style="color: #757575;" action="#!">
                            <div class="container">
                                <div class="form-row container">
                                    <div class="input-field col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 m-auto" align="center">
                                        <h4 class="modal-title w-100">Filtrar por Año:</h4>
                                        <select id="PorAnio" class="browser-default custom-select mb-4" size="1" onchange="GuardarAnio()">
                                            
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="container-fluid">
                            <div class="row">
                                <div class="modal-body table-responsive tabla">
                                    <table id="DimensionesListado" class="table" cellspacing="0" width="100%">
                                        <thead>
                                            <tr align="center">
                                                <th scope="col">#</th>
                                                <th scope="col">Dimension Estrategica</th>
                                                <th scope="col">Ver actividades planificadas</th>
                                            </tr>
                                        </thead>
                                    </table>
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
    <!--Seleccion de departamento-->
    <div 
        class="modal fade" 
        id="modalSeleccionDepart" 
        tabindex="-1" role="dialog" 
        aria-labelledby="myModalLabel" 
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Actividades por departamento</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div>
                    <form class="text-center" style="color: #757575;" action="#!">
                        <div class="container" style="margin:20px">
                            <h4 align="center">Seleccione departamento a visualizar:</h4>
                            <div class="form-row container">
                                <div class="input-field col-12" align="left">
                                    <label>Seleccione un Departamento:</label>
                                    <select id="Departamento" name='Departamento' class="browser-default custom-select mb-4" size="1">

                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer card-footer amber accent-4">
                    <div class="text-center">
                        <button 
                            type="button" 
                            class="btn btn-light-green btn-rounded btn-sm" 
                            onclick="GuardarIdDepaDepa()"
                        >
                            Visualizar actividades
                        </button>
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-danger btn-rounded btn-sm" data-dismiss="modal" aria-label="Close">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--ActividadesDimensionEstrategica-->
    <div 
        class="modal fade" 
        id="modalActividadesDimensionEstrategica" 
        tabindex="-1" role="dialog" 
        aria-labelledby="RegistosLabel" 
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="ActividadesDimensionEstrategicaLabel"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modal-body table-responsive tabla">
                        <table id="ActividadesListado" class="table" cellspacing="0" width="100%">
                            <thead>
                                <tr align="center">
                                    <th scope="col">#</th>
                                    <th scope="col">Correlativo</th>
                                    <th scope="col">Departamento</th>
                                    <th scope="col">Tema Actividad</th>
                                    <th scope="col">Tipo Actividad</th>
                                    <th scope="col"># Actividades definidas</th>
                                    <th scope="col">ver mas</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="modal-footer card-footer amber accent-4">
                    
                </div>
            </div>
        </div>
    </div>

    <!--VerMas-->
    <div 
        class="modal fade" 
        id="modalVerMas" 
        tabindex="-1" role="dialog" 
        aria-labelledby="VerMasLabel" 
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="VerMasLabel">Visualizacion de Actividad Seleccionada</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="form-group d-flex row">
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <h5 class="form-control">correlativo:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <input 
                                    type="text" 
                                    id="correlativo" 
                                    class="form-control" 
                                    value="" 
                                    align="justify"
                                    readonly
                                >
                            </div>
                        </div>
                        <div class="form-group d-flex row">
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <h5 class="form-control">Departamento:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <input 
                                    type="text" 
                                    id="Dep" 
                                    class="form-control"  
                                    value=""
                                    align="justify"
                                    readonly
                                >
                            </div>
                        </div>
                        <div class="form-group d-flex row">
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <h5 class="form-control">Dimension Estrategica:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <input 
                                    type="text" 
                                    id="DimEst" 
                                    class="form-control" 
                                    value="" 
                                    align="justify"
                                    readonly
                                >
                            </div>
                        </div>
                        <div class="form-group d-flex row">
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <h5 class="form-control">Objetivo Institucional:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <textarea 
                                    type="text" 
                                    id="ObjInst" 
                                    class="form-control"  
                                    align="justify"
                                    readonly
                                >
                                </textarea>
                            </div>
                        </div>
                        <div class="form-group d-flex row">
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <h5 class="form-control">Area Estrategica:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <textarea 
                                    type="text" 
                                    id="AreaEst" 
                                    class="form-control"  
                                    align="justify"
                                    readonly
                                >
                                </textarea>
                            </div>
                        </div>
                        <div class="form-group d-flex row">
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <h5 class="form-control">Tema de la actividad:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <textarea 
                                    type="text" 
                                    id="TemaAct" 
                                    class="form-control"  
                                    align="justify"
                                    readonly
                                >
                                </textarea>
                            </div>
                        </div>
                        <div class="form-group d-flex row">
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <h5 class="form-control">Tipo de actividad:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <textarea 
                                    type="text" 
                                    id="TipoAct" 
                                    class="form-control"  
                                    align="justify"
                                    readonly
                                >
                                </textarea>
                            </div>
                        </div>
                        <div class="form-group d-flex row">
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <h5 class="form-control">Numero de actividades definidas:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <input 
                                    type="text" 
                                    id="NumAct" 
                                    class="form-control" 
                                    value="" 
                                    align="justify"
                                    readonly
                                >
                            </div>
                        </div>
                    </div>
                    <div class="container row mx-auto">
                        <div class="tabla col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12 mx-auto" style="color: #757575;" align="center">
                            <h5>Resultados de la unidad</h5>
                            <div class="mx-auto">
                                <button 
                                    type="button"
                                    class="btn btn-indigo btn-block" 
                                    id="ResUn" 
                                    style="width:50%"
                                    onclick="llenar('resultado')">
                                    Ver
                                </button>
                            </div>
                        </div>
                        <div class="tabla col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12 mx-auto" style="color: #757575;" align="center">
                            <h5>Justificacion de la actividad</h5>
                            <div class="mx-auto">
                                <button 
                                    type="button"
                                    id="justificacion"
                                    class="btn btn-indigo btn-block"  
                                    style="width:50%"
                                    onclick="llenar('justificacion')">
                                    Ver
                                </button>
                            </div>
                        </div>
                        <div class="tabla col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12 mx-auto" style="color: #757575;" align="center">
                            <h5>Pesupuesto de la actividad</h5>
                            <div class="mx-auto">
                                <button 
                                    type="button"
                                    id="presupuesto"
                                    class="btn btn-indigo btn-block"  
                                    style="width:50%"
                                    onclick="llenar('presupuesto')">
                                    Ver
                                </button>
                            </div>
                        </div>
                        <div class="tabla col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12 mx-auto" style="color: #757575;" align="center">
                            <h5>Actividades definidas</h5>
                            <div class="mx-auto">
                                <button 
                                    type="button"
                                    id="actividades"
                                    class="btn btn-indigo btn-block"  
                                    style="width:50%"
                                    onclick="llenar('actividades')">
                                    Ver
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer card-footer amber accent-4">
                    
                </div>
            </div>
        </div>
    </div>

    <!--Agregar Actividad-->
    <div class="modal fade" id="modalesActividad" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Filtro de dimensiones administrativa</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container tabla">
                        <div class="row">
                            <div class="input-field col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12" align="center">
                                <label for="DimensionAdministrativa" id="labelDimensionAdministrativa">Seleccione la dimension administrativa:</label>
                                <select name="DimensionAdministrativa" id="DimensionAdministrativa" class="browser-default custom-select mb-4">
                                </select>
                                <span id="errorsDimensionAdministrativa" class="text-danger text-small d-none">
                                </span>
                            </div>
                            <div class="input-field col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12" align="center">
                                <h4 class="modal-title w-100">Filtrar por mes:</h4>
                                <select id="PorMes" class="browser-default custom-select mb-4" size="1">
                                    
                                </select>
                            </div>
                            <div class="input-field col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12" align="center">
                                <button 
                                    type="button"
                                    id="actividadesAdminDescrip"
                                    class="btn btn-indigo btn-block"  
                                    style="width:50%"
                                >
                                    Ver
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer card-footer amber accent-4">

                </div>
            </div>
        </div>
    </div>

    <!--Registos-->
    <div 
        class="modal fade" 
        id="modalRegistros" 
        tabindex="-1" role="dialog" 
        aria-labelledby="RegistosLabel" 
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="RegistosLabel"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modal-body table-responsive">
                        <table id="RegistrosTodos" class="table" cellspacing="0" width="100%">
                            <thead>
                                <tr align="center">
                                    <th></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="modal-footer card-footer amber accent-4">
                    
                </div>
            </div>
        </div>
    </div>

    <!--RegistosNoT-->
    <div 
        class="modal fade" 
        id="modalRegistosNoT" 
        tabindex="-1" role="dialog" 
        aria-labelledby="RegistosNoTLabel" 
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="RegistosNoTLabel"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container espacioLL tabla">
                        
                    </div>
                </div>
                <div class="modal-footer card-footer amber accent-4">
                    
                </div>
            </div>
        </div>
    </div>

<!--VerMasAct-->
<div 
        class="modal fade" 
        id="modalVerMasAct" 
        tabindex="-1" role="dialog" 
        aria-labelledby="VerMasActLabel" 
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="VerMasActLabel"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container" id="verMasDimensionAdministrativa">
                        
                    </div>
                </div>
                <div class="modal-footer card-footer amber accent-4">
                    
                </div>
            </div>
        </div>
    </div>

    <!--En esta zona podran poner javascripts propios de la vista-->
    
    <script src="../js/sweet-alert-two/sweetalert2.min.js"></script>
    <script src="../js/libreria-bootstrap-mdb/jquery.min.js"></script>
    
    <script src="../js/data-tables/datatables.min.js"></script>

    <script src="../js/data-tables/Buttons/js/dataTables.buttons.min.js"></script>

    <script src="../js/config/config.js"></script>

    <script type="text/javascript">
        var Usuario = <?= json_encode($_SESSION) ?>;
    </script>

    <script src="../js/Calendario-actividades/Listado-actividades.js"></script>

<?php
    include('../partials/endDoctype.php');
?>
