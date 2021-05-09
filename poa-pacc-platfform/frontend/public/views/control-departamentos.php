<?php
include('../partials/doctype.php');
?>
<title>Control de Departamentos</title>
<!--En esta zona podran poner estilos propios de la vista-->

<link rel="stylesheet" href="../css/sweet-alert-two/sweetalert2.min.css">

<link rel="stylesheet" href="../js/data-tables/datatables.min.css">
<link rel="stylesheet" href="../js/data-tables/DataTables/css/dataTables.bootstrap4.min.css">
<!--<script src="../js/libreria-bootstrap-mdb/jquery.min.js" type="text/javascript"></script>-->

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
                        <h5 class="font-weight-bolder"> Maneja el control de departamentos desde este panel</h5>
                    </div>
                    <div class="card-body  blue lighten-5">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">
                                    <div class="card border border-warning b-2 rounded mb-0 animate__animated animate__flipInY">
                                        <div class="card-header amber accent-4">
                                            <div class="row">
                                                <div class="col-xl-9 col-lg-9 col-md-9 col-sm-8">
                                                    <h class="text-white font-weight-bold">
                                                    Visualizar Departamentos
                                                    </h6>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4">
                                                    <img src="../img/partial-sidebar/departamentos-icon.svg" alt="Control de Usuarios">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="view overlay">
                                                <img class="card-img-top" src="../img/control-departamentos/ver-registros-departamentos.svg" alt="ver departamentos">
                                            </div>
                                            <hr>
                                            <button 
                                                type="button" 
                                                class="btn btn-indigo btn-block" 
                                                data-toggle="modal" 
                                                data-target="#modalVisualizarDepartamentos"
                                                onclick="verDepartamentos()">
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
                                                        Registrar Departamento
                                                    </h6>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4">
                                                    <img src="../img/partial-sidebar/departamentos-icon.svg" alt="Control de Usuarios">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <br />
                                            <div class="view overlay">
                                                <img class="card-img-top" src="../img/control-departamentos/registrar-departamento.svg" alt="registo departamento">
                                            </div>
                                            <hr>
                                            <button type="button" 
                                                class="btn btn-indigo btn-block" 
                                                data-toggle="modal" 
                                                data-target="#modalRegistrarDepartamentos"
                                                onclick="cambiarEstado()"
                                            >
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
                                                        Modificar Departamento
                                                    </h6>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4">
                                                    <img src="../img/partial-sidebar/departamentos-icon.svg" alt="Control de Usuarios">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <br>
                                            <div class="view overlay">
                                                <img class="card-img-top" src="../img/control-departamentos/modificar-departamento.svg" alt="registrar usuario">
                                            </div>
                                            <hr>
                                            <button type="button" 
                                                class="btn btn-indigo btn-block" 
                                                data-toggle="modal" 
                                                data-target="#modalModificarDepartamentos"
                                                onclick="cambiarDepartamento()"
                                                >
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
    <!--Visualizar Departamentos-->
    <div class="modal fade" id="modalVisualizarDepartamentos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Listado de departamentos registrados</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                            <table class="table" id="listado-departamentos">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nombre Departamento</th>
                                        <th scope="col">Abreviatura</th>
                                        <th scope="col">Telefono</th>
                                        <th scope="col">Correo Electrónico</th>
                                        <th scope="col">Estado</th>
                                    </tr>
                                </thead>
                                
                            </table>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>


    <!--Registrar departamentos-->
    <div class="modal fade" id="modalRegistrarDepartamentos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Formulario para registrar un departamento</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-registro-departamento" class="text-center" style="color: #757575;">
                        <div class="form-row">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="md-form">
                                        <input 
                                            type="text" 
                                            id="R-nombreDepartamento" 
                                            class="form-control" 
                                            maxlength="80" 
                                            minlength="1" 
                                            required
                                        >
                                        <span id="errorsR-nombreDepartamento" class="text-danger text-small d-none">

                                        </span>
                                        <label for="R-nombreDepartamento" id="labelR-nombreDepartamento" name="labelR-nombreDepartamento">
                                        Escriba el nombre del departamento
                                        </label>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="md-form">
                                        <input 
                                            type="text" 
                                            id="R-abreviaturaDepartamento" 
                                            class="form-control" 
                                            maxlength="2" 
                                            minlength="1" 
                                            required>
                                        <span id="errorsR-abreviaturaDepartamento" class="text-danger text-small d-none">
                                        </span>
                                        <label for="R-abreviaturaDepartamento" id="labelR-abreviaturaDepartamento">
                                            Escriba abreviatura del departamento
                                        </label>
                                    </div>
                                </div>
                        </div>

                        <div class="form-row">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="md-form">
                                        <input type="text" 
                                            id="R-numeroTelefonoDepartamento" 
                                            class="form-control" 
                                            maxlength="9" 
                                            minlength="1" 
                                            required
                                        >
                                        <span id="errorsR-numeroTelefonoDepartamento" class="text-danger text-small d-none">

                                        </span>
                                        <label for="R-numeroTelefonoDepartamento" id="labelR-numeroTelefonoDepartamento" name="labelR-numeroTelefonoDepartamento">
                                            Ingrese número de telefono 
                                        </label>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="md-form">
                                        <input type="text" 
                                            id="R-correoDepartamento" 
                                            class="form-control" 
                                            maxlength="60" 
                                            minlength="1" 
                                            required
                                        >
                                        <span id="errorsR-correoDepartamento" class="text-danger text-small d-none">
                                        </span>
                                        <label for="R-correoDepartamento" id="labelR-correoDepartamento">
                                            Escriba correo del departamento
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
                            
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form row">
                                        &nbsp&nbsp&nbsp&nbsp
                                        <label for="R-estadoDepartamento" id="labelR-estadoDepartamento">
                                            Seleccione estado departamento:
                                        </label>
                                    </div>
                                    <div>
                                        <select name="R-estadoDepartamento" class="browser-default custom-select" id="R-estadoDepartamento" required>
                                         
                                        </select>
                                        <span id="errorsR-estadoDepartamento" class="text-danger text-small d-none">
                                        </span>
                                    </div>
                                
                                </div>
                        </div>
                    </form>
                </div>
                    
                <div class="modal-footer">
                    <div class="text-center mt-4">
                        <button id="btn-registrar-departamento" type="button" class="btn btn-light-green btn-rounded" onclick="registrarDepartamento()">
                            Registrar Departamento
                        </button>
                    </div>
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-danger btn-rounded" data-dismiss="modal" aria-label="Close" onclick="cancelarRegistroDepartamento()">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    

    <!--Modificar departamentos-->
   
    <div class="modal fade" id="modalModificarDepartamentos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Formulario para modificar departamentos</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-modificar-departamento" class="text-center" style="color: #757575;" action="#!">
                            <div class="form-row">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12" id="depaSel" >
                                    <label>Seleccione un Departamento:</label>
                                    <select id="M-Departamento" class="browser-default custom-select mb-4" onchange="cambiarDepartamentoModificado()">  
                                    </select>
                                    
                                </div>          
                            </div>
                            <div id="modificacion">
                            <h4 align="center" style="color:#191970" >Información Departamento Seleccionado:</h4>
                            <div class="form-row">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <div class="md-form">
                                            <input 
                                                type="text" 
                                                id="M-nombreDepartamento" 
                                                class="form-control modificarNombreDepartamento" 
                                                maxlength="80" 
                                                minlength="1" 
                                                required
                                            >
                                            <span id="errorsM-nombreDepartamento" class="text-danger text-small d-none">

                                            </span>
                                            <label for="M-nombreDepartamento" id="labelM-nombreDepartamento" name="labelM-NombreDepartamento">
                                            Nombre del departamento
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <div class="md-form">
                                            <input 
                                                type="text" 
                                                id="M-abreviaturaDepartamento" 
                                                class="form-control" 
                                                maxlength="2" 
                                                minlength="1" 
                                                required>
                                            <span id="errorsM-abreviaturaDepartamento" class="text-danger text-small d-none">
                                            </span>
                                            <label for="M-abreviaturaDepartamento" id="labelM-abreviaturaDepartamento">
                                                 Abreviatura del departamento
                                            </label>
                                        </div>
                                    </div>
                            </div>

                            <div class="form-row">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <div class="md-form">
                                            <input type="text" 
                                                id="M-numeroTelefonoDepartamento" 
                                                class="form-control NumeroTelefonoDepartamento" 
                                                maxlength="20" 
                                                minlength="1" 
                                                required
                                            >
                                            <span id="errorsM-numeroTelefonoDepartamento" class="text-danger text-small d-none">

                                            </span>
                                            <label for="M-numeroTelefonoDepartamento" id="labelM-numeroTelefonoDepartamento" name="labelM-numeroTelefonoDepartamento">
                                                Número de telefono 
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <div class="md-form">
                                            <input 
                                                type="text" 
                                                id="M-correoDepartamento" 
                                                class="form-control" 
                                                maxlength="60" 
                                                minlength="1" 
                                                required
                                            >
                                            <span id="errorsM-correoDepartamento" class="text-danger text-small d-none">
                                            </span>
                                            <label for="M-CorreoDepartamento" id="labelM-correoDepartamento">
                                                Correo del departamento
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
                                
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="form row">
                                        &nbsp&nbsp&nbsp&nbsp
                                        <label for="M-estadoDepartamento" id="labelM-estadoDepartamento">
                                            Estado departamento:
                                        </label>
                                    </div>
                                        <select class="browser-default custom-select" id="M-estadoDepartamento">
                                        </select>
                                        <span id="errorsM-estadoDepartamento" class="text-danger text-small d-none">
                                        </span>
                                </div>
                        </div>
                    </form>
                </div>
                </div>
                <div class="modal-footer">
                    <div class="text-center mt-4">
                        <button type="button" id="botonModificarDepartamento" class="btn btn-light-green btn-rounded" onclick="modificarDepartamento()">Guardar Cambios</button>
                    </div>
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-danger btn-rounded" data-dismiss="modal" aria-label="Close" onclick="cancelarModificacionDepartamento()">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    


    <!--Modificar Carrera-->
    <div 
        class="modal fade" 
        id="modalModificarDepartamento" 
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
                                    <input type="text" id="Carrera2" class="form-control">
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
                                    <input type="text" id="Abreviatura2" class="form-control">
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
    <script src="../js/libreria-bootstrap-mdb/jquery.min.js"></script>
    
    <script src="../js/data-tables/datatables.min.js"></script>


    <script src="../js/data-tables/Buttons/js/dataTables.buttons.min.js"></script>
    <script src="../js/data-tables/JSZip/jszip.min.js"></script>
    <script src="../js/data-tables/pdfmake/pdfmake.min.js"></script>
    <script src="../js/data-tables/pdfmake/vfs_fonts.js"></script>
    <script src="../js/data-tables/Buttons/js/buttons.html5.min.js"></script>

    <script src="../js/config/config.js"></script>
    <script src="../js/validators/form-validator.js"></script>
    <script src="../js/departamentos/departamentos-controlador.js"></script>
    <?php
    include('../partials/endDoctype.php');
    ?>