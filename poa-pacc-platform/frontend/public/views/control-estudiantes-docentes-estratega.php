<?php
    if (!isset($_SESSION)) {
        session_start();
    }
    $estratega = 'U_E';
    if ( $_SESSION['abrevTipoUsuario'] != $estratega) {
        header('Location: 401.php');
    }
    include('../partials/doctype.php');
    include('verifica-session.php');
    //Tipos de roles de usuarios en el sistema
?>
<title>Control Estudiantes-Egresados-Docentes</title>
<!--En esta zona podran poner estilos propios de la vista-->
<link rel="stylesheet" href="../css/sweet-alert-two/sweetalert2.min.css">
<script src="../js/libreria-bootstrap-mdb/jquery.min.js" type="text/javascript"></script>   

<link rel="stylesheet" href="../js/data-tables/datatables.min.css">
<link rel="stylesheet" href="../js/data-tables/DataTables/css/dataTables.bootstrap4.min.css">




</head>

<body id="body-pd">
    <?php include('../layouts/Nabvar.php'); ?>
    <?php include('../layouts/Sidebar.php'); ?>
    
    <div id="profile-card" class="container">
        <div class="text-center mt-4">
        
            <button 
                type="button" 
                class="btn btn-light-green btn-rounded" 
                onclick="verTodos()"                        
            >
                Ver Todos los Registros
            </button>       
            <button 
                type="button" 
                class="btn btn-light-green btn-rounded" 
                data-toggle="modal" 
                data-target="#modalSeleccionDepartamento"
                onclick="listarDepartamentos()"  
            >                     
                Registros por Departamento
            </button>
        
            <button 
                type="button" 
                class="btn btn-light-green btn-rounded" 
                data-toggle="modal" 
                data-target="#modalSeleccionTipoRegistro"
                onclick="listarTipoRegistros()"
            >                     
                Registros por Tipo
            </button>
        </div>


        <div class="row"  >
            
                <div class="card">
                    <div class="card-header indigo darken-4 text-white" >
                        <h5 class="font-weight-bolder">Control Estudiantes Matriculados, Egresados y Docentes con maestría</h5>
                    </div>
                    <div class="card-body  blue lighten-5">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="modal-body table-responsive tabla">
                                    <table id="listadoRegistros" class="table" cellspacing="0" width="100%">
                                        <thead>
                                            <tr align="center">
                                                <th scope="col">#</th>
                                                <th scope="col">Departamento</th>
                                                <th scope="col">Trimestre</th>
                                                <th scope="col">Fecha Realización Registro</th>
                                                <th scope="col">Fecha ultima modificación</th>
                                                <th scope="col">Tipo Registro</th>
                                                <th scope="col">Cantidad</th>
                                                <th scope="col">Nombre Usuario Registro</th>
                                                <th scope="col">Nombre Usuario Modificación</th>
                                                <th scope="col">Ver Respaldo</th>
                                                <th scope="col">Modificar Respaldo</th>
                                                <th scope="col">Modificar Registro</th>
                                            </tr>
                                        </thead>
                                        <tbody id="Registros">

                                        </tbody>
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

    <!--modales-->
    <!--Selección de departamento-->
    <div 
        class="modal fade" 
        id="modalSeleccionDepartamento" 
        tabindex="-1" role="dialog" 
        aria-labelledby="myModalLabel" 
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Registros por departamento</h4>
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
                            onclick="GuardarIdDepartamento()"
                        >
                            Visualizar Registros
                        </button>
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-danger btn-rounded btn-sm" data-dismiss="modal" aria-label="Close">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!--Selección de tipo Registro-->
    <div 
        class="modal fade" 
        id="modalSeleccionTipoRegistro" 
        tabindex="-1" role="dialog" 
        aria-labelledby="myModalLabel" 
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Registros por Tipo</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div>
                    <form class="text-center" style="color: #757575;" action="#!">
                        <div class="container" style="margin:20px">
                            <h4 align="center">Seleccione un tipo de registro a visualizar:</h4>
                            <div class="form-row container">
                                <div class="input-field col-12" align="left">
                                    <label>Seleccione un Tipo de Registro:</label>
                                    <select id="tipoRegistro" name='tipoRegistro' class="browser-default custom-select mb-4" size="1">

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
                            onclick="GuardarIdTipoRegistro()"
                        >
                            Visualizar Registros
                        </button>
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-danger btn-rounded btn-sm" data-dismiss="modal" aria-label="Close">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!--Ver adjuntos-->
    <div class="modal fade" id="modalRespaldoAdjunto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lm" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Body-->
            <div class="modal-body text-center mb-1">
                <div class="container"> 
                    <h5 align="center" style="color:#191970" >Imagen adjuntada como respaldo:</h5>
                    <div class="text-center mt-4" id="V-respaldoAdjunto">
                        

                    </div>  
                    <hr>                
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


    <!--Modificar Registro población Docentes y Estudiantes-->
    <div 
        class="modal fade" 
        id="modalModificarDocentesEstudiantes" 
        tabindex="-1" role="dialog" 
        aria-labelledby="ModificarDocentesEstudiantesLabel" 
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="ModificarDocentesEstudiantesLabel">Formulario para modificar poblacion Docentes y Estudiantes</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modal-body text-center mb-1">
                        <div id="tablaM" class="tabla">
                            <form class="text-center" style="color: #757575;" action="#!">
                                <div class="form-row">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 mx-auto">
                                        <!-- Trimestre -->
                                        <div class="md-form">
                                            <select name="TrimestreModificacion" id="TrimestreModificacion" class="browser-default custom-select mb-4">
                        
                                            </select>
                                            <span id="errorsTrimestreModificacion" class="text-danger text-small d-none">
                                            </span>
                                        </div>
                                    </div>
                                    <div id="campoNumM" class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 mx-auto">
                                        <div class="md-form">
                                            <input type="number" id="Cantidad" class="form-control" maxlength="2">
                                            <span id="errorsCantidad" class="text-danger text-small d-none">
                                            </span>
                                            <label 
                                                for="Cantidad"
                                                id="labelCantidad"
                                            >
                                            Cantidad personas
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer card-footer amber accent-4">
                    <div class="text-center">
                        <button 
                            type="button" 
                            class="btn btn-light-green btn-rounded btn-sm" 
                            id="modificarNumero"
                            onclick="Modificar()"
                        >
                            Modificar
                        </button>
                    </div>
                    <div class="text-center">
                        <button onclick="CerrarModalModificacion()" 
                                type="button" 
                                class="btn btn-danger btn-rounded btn-sm" 
                                data-dismiss="modal" 
                                aria-label="Close"
                                id="cerrarM"
                        >Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Modificar Respaldo-->
    <div 
        class="modal fade" 
        id="modalModificarRespaldo" 
        tabindex="-1" role="dialog" 
        aria-labelledby="ModificarRespaldoLabel" 
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="ModificarRespaldoLabel">Modificar Respaldo</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modal-body text-center mb-1">
                        <div id="tabla" class="tabla">
                            <form id="formulario-modificacion-respaldo" class="text-center form" style="color: #757575;" action="#!">
                                <div class="form-row">
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 mx-auto">
                                        <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-xs-12 m-auto">
                                            <label for="respaldoM" id="labelrespaldoM" style="display:none"></label>
                                            <input accept="image/png,image/jpeg,image/gif" type="file" id="documentoSubidoM" name="documentoSubidoM" style="display:none" onchange="mostrarValorRespaldoASubir()"> 
                                            <button type="button" class="btn btn-light-blue m-auto"
                                                onclick="$('#documentoSubidoM').trigger('click')"
                                            ><img src="../img/control-estudiantes-docentes/upload.svg" alt="Subir"><br>Adjuntar documento de respaldo <br> (Opcional)
                                            </button><br>
                                            <span id="errorsrespaldoM" class="text-danger text-small d-none">
                                                
                                            </span>
                                        </div><br>
                                        <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-xs-12 m-auto">
                                            <input style="width:100%" type="text" id="respaldoM" name="respaldoM" class="form-control" placeholder="Documento subido" readonly>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer card-footer amber accent-4">
                    <div class="text-center">
                        <button 
                            type="button" 
                            class="btn btn-light-green btn-rounded btn-sm" 
                            id="modificarRespaldo"
                            onclick="verModificacionRespaldo()"
                        >modificarRespaldo
                        </button>
                    </div>
                    <div class="text-center">
                        <button onclick="CerrarModalModificacion2()" 
                                type="button" 
                                class="btn btn-danger btn-rounded btn-sm" 
                                data-dismiss="modal" 
                                aria-label="Close"
                                id="cerrarM2"
                        >Cancelar
                        </button>
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

    <script src="../js/config/config.js"></script>

    <script src="../js/validators/form-validator.js"></script>

    <script src="../js/control-estudiantes-docentes-vistaEstratega/control-estudiantes-docentes-vistaEstratega.js"></script>
    

<?php
    include('../partials/endDoctype.php');
?>
