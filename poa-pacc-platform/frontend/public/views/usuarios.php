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
<title>Control de Usuarios</title>
<!--En esta zona podran poner estilos propios de la vista-->
<link rel="stylesheet" href="../css/sweet-alert-two/sweetalert2.min.css">


<link rel="stylesheet" href="../js/data-tables/datatables.min.css">
<link rel="stylesheet" href="../js/data-tables/DataTables/css/dataTables.bootstrap4.min.css">

<link rel="stylesheet" href="https:use.fontawesome.com/releases/v5.15.1/css/all.css" integrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous">

</head>

<body id="body-pd">
    <?php include('../layouts/Nabvar.php'); ?>
    <?php include('../layouts/Sidebar.php'); ?>

    <div id="profile-card" class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header indigo darken-4 text-white">
                        <h5 class="font-weight-bolder"> Maneja el control de usuarios desde este panel</h5>
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
                                                        Visualizar Usuarios
                                                    </h6>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4">
                                                    <img src="../img/partial-sidebar/usuarios-icon.svg" alt="Control de Usuarios">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="view overlay">
                                                <img class="card-img-top" src="../img/usuarios/visualizar-usuarios.svg" alt="registrar usuario">
                                            </div>
                                            <hr>
                                            <button type="button" class="btn btn-indigo btn-block" data-toggle="modal" data-target="#modalVisualizarUsuarios" onclick="listarUsuarios()">
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
                                                        Registrar Usuario
                                                    </h6>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4">
                                                    <img src="../img/partial-sidebar/usuarios-icon.svg" alt="Control de Usuarios">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <br />
                                            <div class="view overlay">
                                                <img class="card-img-top" src="../img/usuarios/registrar-usuario.svg" alt="registrar usuario">
                                            </div>
                                            <hr>
                                            <button type="button" id="cargaDataUsuario" class="btn btn-indigo btn-block" data-toggle="modal" data-target="#modalRegistrarUsuarios" onclick="cargarModalRegistro()">
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
    <div class="modal fade" id="modalVisualizarUsuarios" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Listado de usuarios registrados</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table" id="listado-usuarios">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Codigo Empleado</th>
                                    <th scope="col">Nombre Completo</th>
                                    <th scope="col">Estado Empleado</th>
                                    <th scope="col">Visualizar Informacion General Empleado</th>
                                    <th scope="col">Visualizar Direccion</th>
                                    <th scope="col">Visualizar Correo</th>
                                    <th scope="col">Visualizar Fotografia</th>
                                </tr>
                            </thead>
                            <tbody id="usuarios">

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

    <!--Registrar usuarios-->
    <div class="modal fade" id="modalRegistrarUsuarios" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="lds-roller loading-registro">
                <div>
                </div>
                <div>
                </div>
                <div>
                </div>
                <div>
                </div>
                <div>
                </div>
                <div>
                </div>
                <div>
                </div>
                <div>
                </div>
            </div>
            <div class="modal-content" id="modalContentRegistro">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Formulario para registro de usuarios</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-registro-personas" class="text-center" style="color: #757575;">
                        <div class="form-row">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <div class="md-form">
                                    <input type="text" id="R-nombrePersona" class="form-control nombrePersona" maxlength="80" minlength="1" required>
                                    <span id="errorsR-nombrePersona" class="text-danger text-small d-none">

                                    </span>
                                    <label for="R-nombrePersona" id="labelR-nombrePersona" name="labelR-nombrePersona">Escriba los Nombres de la persona
                                    </label>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <div class="md-form">
                                    <input type="text" id="R-apellidoPersona" class="form-control" maxlength="80" minlength="1" required>
                                    <span id="errorsR-apellidoPersona" class="text-danger text-small d-none">
                                    </span>
                                    <label for="R-apellidoPersona" id="labelR-apellidoPersona">
                                        Escriba los apellidos de la persona
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <div class="md-form">
                                    <input type="number" id="R-codigoEmpleado" class="form-control" required>
                                    <span id="errorsR-codigoEmpleado" class="text-danger text-small d-none"></span>
                                    <label for="R-codigoEmpleado" id="labelR-codigoEmpleado">Escriba el codigo empleado: XXXXX</label>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <div class="md-form">
                                    <div class="md-form md-outline input-with-post-icon datepicker" inline="true">
                                        <input placeholder="Select date" type="date" class="form-control" id="R-fechaNacimiento">
                                        <span id="errorsR-fechaNacimiento" class="text-danger text-small d-none">
                                        </span>
                                        <label for="R-fechaNacimiento" id="labelR-fechaNacimiento">Fecha nacimiento persona
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <div class="md-form">
                                    <select class="browser-default custom-select" id="R-idDepartamento" required>

                                    </select>
                                    <span id="errorsR-idDepartamento" class="text-danger text-small d-none">

                                    </span>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <div class="md-form">
                                    <select class="browser-default custom-select" id="R-idTipoUsuario" required>

                                    </select>
                                    <span id="errorsR-idTipoUsuario" class="text-danger text-small d-none">
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                <div class="md-form">
                                    <select class="browser-default custom-select" id="R-idPais" required onchange="cargarCiudadesPais()">
                                    </select>
                                    <span id="errorsR-idPais" class="text-danger text-small d-none">
                                    </span>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                <div class="md-form">
                                    <div class="spinner-grow text-warning" id="spinneridDepartamentoPais" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <select class="browser-default custom-select d-none" id="R-idDepartamentoPais" onchange="cargarMunicipios()" required>
                                    </select>
                                    <span id="errorsR-idDepartamentoPais" class="text-danger text-small d-none">
                                    </span>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                <div class="md-form">
                                    <div class="spinner-grow text-warning" id="spinneridMunicipiosCiudad" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <select class="browser-default custom-select d-none" id="R-idMunicipiosCiudad" required>
                                    </select>
                                    <span id="errorsR-idMunicipiosCiudad" class="text-danger text-small d-none">
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <div class="md-form">
                                    <input type="text" id="R-nombreLugar" class="form-control" maxlength="255" minlength="1" required>
                                    <span id="errorsR-nombreLugar" class="text-danger text-small d-none">
                                    </span>
                                    <label for="R-nombreLugar" id="labelR-nombreLugar">
                                        Escriba la direccion de residencia del usuario/ CAMPO OPCIONAL
                                    </label>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <div class="md-form">
                                    <input type="email" id="R-correoInstitucional" class="form-control" maxlength="60" minlength="1" required>
                                    <span id="errorsR-correoInstitucional" class="text-danger text-small d-none">
                                    </span>
                                    <label for="R-correoInstitucional" id="labelR-correoInstitucional">
                                        Escriba correo institucional persona
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-row d-none">
                            <div class="col">
                                <div class="md-form">
                                    <input type="file" class="custom-file-input" id="avatar" lang="es" onchange="verificarImagen(this)">
                                    <label class="custom-file-label text-center" for="avatar">
                                        Seleccionar Fotografia / Campo Opcional
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="text-center mt-4">
                        <button id="btn-registrar-usuario" type="button" class="btn btn-light-green btn-rounded" onclick="verificarCamposRegistro()">
                            Registrar Usuario
                        </button>
                    </div>
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-danger btn-rounded" data-dismiss="modal" aria-label="Close" onclick="cancelarOperacion()">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Modificar usuarios-->
    <div class="modal fade" id="modalModificarInfoEmpleado" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content" id="modalContentModificacion">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Informacion General Empleado</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <div class="md-form">
                                <input type="text" id="M-nombrePersona" class="form-control nombrePersona" maxlength="80" minlength="1" required>
                                <span id="errorsM-nombrePersona" class="text-danger text-small d-none">

                                </span>
                                <label for="M-nombrePersona" id="labelM-nombrePersona" name="labelM-nombrePersona">Escriba los Nombres de la persona
                                </label>
                            </div>
                            <div class="md-form">
                                <input type="text" id="M-apellidoPersona" class="form-control" maxlength="80" minlength="1" required>
                                <span id="errorsM-apellidoPersona" class="text-danger text-small d-none">
                                </span>
                                <label for="M-apellidoPersona" id="labelM-apellidoPersona">
                                    Escriba los apellidos de la persona
                                </label>
                            </div>
                            <div class="md-form">
                                <input type="number" id="M-codigoEmpleado" class="form-control" required>
                                <span id="errorsM-codigoEmpleado" class="text-danger text-small d-none"></span>
                                <label for="M-codigoEmpleado" id="labelM-codigoEmpleado">Escriba el codigo empleado: XXXXX</label>
                            </div>
                            <div class="md-form">
                                <div class="md-form md-outline input-with-post-icon datepicker" inline="true">
                                    <input placeholder="Select date" type="date" class="form-control" id="M-fechaNacimiento">
                                    <span id="errorsM-fechaNacimiento" class="text-danger text-small d-none">
                                    </span>
                                    <label for="M-fechaNacimiento" id="labelM-fechaNacimiento">Fecha nacimiento persona
                                    </label>
                                </div>
                            </div>
                            <div class="md-form">
                                <select class="browser-default custom-select" id="M-idDepartamento" required>

                                </select>
                                <span id="errorsM-idDepartamento" class="text-danger text-small d-none">

                                </span>
                            </div>
                            <div class="md-form">
                                <select class="browser-default custom-select" id="M-idTipoUsuario" required>

                                </select>
                                <span id="errorsM-idTipoUsuario" class="text-danger text-small d-none">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="text-center mt-4">
                        <button id="btn-actualizar-registro-usuario" type="button" class="btn btn-light-green btn-rounded" onclick="actualizarRegistroUsuario()">Guardar Cambios</button>
                    </div>
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-danger btn-rounded" data-dismiss="modal" aria-label="Close">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalModificarDireccion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Direccion actual del empleado</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <div class="md-form" inline="true" disabled>
                                <input type="text" id="pais" class="form-control" required>
                                <label for="pais" id="labelpais">Pais actual de residencia
                                </label>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <div class="md-form" inline="true" disabled>
                                <input type="text" id="ciudad" class="form-control" required>
                                <label for="ciudad" id="labelciudad">Ciudad actual de residencia
                                </label>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <div class="md-form" inline="true" disabled="true">
                                <input type="text" id="municipio" class="form-control" required>
                                <label for="municipio" id="labelmunicipio">Municipio actual de residencia
                                </label>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <div class="md-form">
                                <textarea id="direccionActual" class="md-textarea form-control" rows="2" disabled></textarea>
                                <label for="direccionActual">Direccion Colonia/Barrio</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-light-green btn-rounded" onclick="modalCambioDireccion()">Cambiar Direccion</button>
                    </div>
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-danger btn-rounded" data-dismiss="modal" aria-label="Close">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalModificarDireccionActualPersona" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Formulario Para Cambio de direaccion</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                            <div class="md-form">
                                <select class="browser-default custom-select" id="M-idPais" required onchange="listarCiudadesPais()">
                                </select>
                                <span id="errorsM-idPais" class="text-danger text-small d-none">
                                </span>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                            <div class="md-form">
                                <select class="browser-default custom-select d-none" id="M-idDepartamentoPais" onchange="listarMunicipios()" required>
                                </select>
                                <span id="errorsM-idDepartamentoPais" class="text-danger text-small d-none">
                                </span>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                            <div class="md-form">
                                <select class="browser-default custom-select d-none" id="M-idMunicipiosCiudad" required>
                                </select>
                                <span id="errorsM-idMunicipiosCiudad" class="text-danger text-small d-none">
                                </span>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <div class="md-form">
                                <textarea id="M-direccionActual" class="md-textarea form-control" rows="2"></textarea>
                                <label for="M-direccionActual">Direccion Colonia/Barrio opcional:</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="text-center mt-4">
                        <button 
                            id="btn-cambiar-direccion-usuario-persona"
                            type="button" 
                            class="btn btn-light-green btn-rounded"
                            onclick="cambiarDireccion()">
                                Guardar Cambios
                        </button>
                    </div>
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-danger btn-rounded" data-dismiss="modal" aria-label="Close"
                        onclick="cancelarModificacionDireccion()">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalModificarCorreoInstitucional" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="lds-roller loading-registro">
                <div>
                </div>
                <div>
                </div>
                <div>
                </div>
                <div>
                </div>
                <div>
                </div>
                <div>
                </div>
                <div>
                </div>
                <div>
                </div>
            </div>
            <div class="modal-content" id="modalContentReenvioCredenciales">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Correo Institucional Empleado</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="md-form">
                        <input type="email" id="M-correoInstitucional" class="form-control" maxlength="60" minlength="1" required>
                        <span id="errorsM-correoInstitucional" class="text-danger text-small d-none">
                        </span>
                        <label for="M-correoInstitucional" id="labelM-correoInstitucional">
                            Escriba correo institucional persona
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="text-center mt-4">
                        <button id="btn-reenvio-credenciales" type="button" class="btn btn-info btn-rounded" onclick="reenviarCredencialesCorreo()">
                            Reenviar Credenciales
                        </button>
                    </div>
                    <div class="text-center mt-4">
                        <button id="btn-modificacion-correo-electronico" type="button" class="btn btn-light-green btn-rounded" onclick="modificarCorreoUsuario()">Modificar Correo
                        </button>
                    </div>
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-danger btn-rounded" data-dismiss="modal" aria-label="Close">Cancelar</button>
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
    <script src="../js/usuarios/ctrl.registro.usuarios.js"></script>
    <script src="../js/usuarios/ctrl.modificar.usuarios.js"></script>
    <?php
    include('../partials/endDoctype.php');
    ?>