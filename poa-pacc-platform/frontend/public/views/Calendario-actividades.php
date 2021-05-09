<?php
    if (!isset($_SESSION)) {
        session_start();
    }
    $coordinador = 'C_C';
    $jefe = 'J_D';
    $decano = 'D_F';
    $admin = 'SE_AD';
    $estratega = 'U_E';
    if ($_SESSION['abrevTipoUsuario'] != $decano and $_SESSION['abrevTipoUsuario'] != $admin and $_SESSION['abrevTipoUsuario'] != $estratega and $_SESSION['abrevTipoUsuario'] != $coordinador and
     $_SESSION['abrevTipoUsuario'] != $jefe) {
        header('Location: 401.php');
    }
    include('../partials/doctype.php');
    include('verifica-session.php');
    //Tipos de roles de usuarios en el sistema
?>
<title>Control de actividades</title>
<!--En esta zona podran poner estilos propios de la vista-->
<link rel="stylesheet" href="../css/sweet-alert-two/sweetalert2.min.css">

<link href='../css/calendar/fullcalendar4.css' rel='stylesheet'>
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

                Calendario de actividades consolidado
            </button>       
            <button 
                type="button" 
                class="btn btn-light-green btn-rounded" 
                data-toggle="modal" 
                data-target="#modalSeleccionDepart"
            >                     
                Calendario de actividades por departamento
            </button>
        <?php 
            }; 
        ?>
            <button 
                type="button" 
                class="btn btn-light-green btn-rounded" 
                onclick= " location.href='Listado-actividades.php' "
            >                     
                Ver listado de actividades
            </button>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header indigo darken-4 text-white">
                        <h5 class="font-weight-bolder">Calendario de actividades</h5>
                    </div>
                    <div class="card-body  blue lighten-5">
                        <div id="calendar" class="col-12 tabla"></div>
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
                                    <select id="Departamento" name="Departamento" class="browser-default custom-select mb-4">

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

    <!--modal al presionar body de la fecha-->
    <!-- <div class="modal fade" id="ModalAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		  <div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
			<form class="form-horizontal">
			
			  <div class="modal-header indigo darken-4 text-white">
                <h4 class="modal-title" id="myModalLabel">Actividades correspondientes a la fecha</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  </div>
			  <div class="modal-body">
                    <form class="text-center" style="color: #757575;" action="#!">
                        <div class="container">
                            <div class="form-row container">
                                <div class="input-field mx-auto tabla align-self-center text-center">
                                    <select id="actividadFecha" class="browser-default custom-select" onchange="cambioAct()">
                                        <option value="" selected disabled>Seleccione la actividad:</option>
                                    </select>
                                </div>
                            </div>
                            <div class="container tabla" id="calend" style="display:none">
                                <div style="border-bottom:2px double rgba(11, 3, 126, 0.13);">
                                    <h4 align="center">Información de la actividad:</h4>
                                </div><br>
                                <div class=" container mx-auto">
                                    <div class="form-group d-flex row col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 tabla m-auto">
                                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                            <h5 class="form-control" align="center">Departamento:</h5>
                                        </div>
                                        <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                            <input 
                                                type="text" 
                                                id="Dep" 
                                                class="form-control"  
                                                value="Ingenieria en sistemas"
                                                align="justify"
                                                readonly
                                            >
                                        </div>
                                    </div><br>
                                    <div class="row col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 tabla m-auto">
                                        <div class="form-group d-flex row m-auto">
                                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                                <h5 class="form-control" align="center">Cantidad:</h5>
                                            </div>
                                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                                <input 
                                                    type="text" 
                                                    id="Cant" 
                                                    class="form-control"  
                                                    value="3"
                                                    align="justify"
                                                    readonly
                                                >
                                            </div>
                                        </div>
                                        <div class="form-group d-flex row m-auto">
                                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                                <h5 class="form-control" align="center">Presupuesto asignado:</h5>
                                            </div>
                                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                                <input 
                                                    type="text" 
                                                    id="Pres" 
                                                    class="form-control"  
                                                    value="10,000"
                                                    align="justify"
                                                    readonly
                                                >
                                            </div>
                                        </div>
                                    </div>
                                </div><br>
                                <div class="form-group row col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 tabla m-auto">
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <h5 class="form-control" align="center">Actividad:</h5>
                                    </div>
                                    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                        <textarea 
                                            type="text" 
                                            id="Act" 
                                            class="form-control"  
                                            align="justify"
                                            readonly
                                        >Viáticos Nacionales Categoría III Zona 1 Periodo Corto</textarea>
                                    </div>
                                </div>
                                <br>
                                <div class="form-group row col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 tabla m-auto">
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <h5 class="form-control" align="center">Justificacion:</h5>
                                    </div>
                                    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                        <textarea 
                                            type="text" 
                                            id="Just" 
                                            class="form-control"  
                                            align="justify"
                                            readonly
                                        >Necesidad de actulizar los planes de estudio en la FI</textarea>
                                    </div>
                                </div> <br>
                                <div class="form-group row col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 tabla m-auto">
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <h5 class="form-control" align="center">Responsable:</h5>
                                    </div>
                                    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                        <textarea 
                                            type="text" 
                                            id="Resp" 
                                            class="form-control"  
                                            align="justify"
                                            readonly
                                        >Subcomisión de Rediseño Curricular</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
			  </div>
			  <div class="modal-footer card-footer amber accent-4">
			  </div>
			</form>
			</div>
		  </div>
	</div> -->

    <div class="modal fade" id="ModalVerActividadCalendario" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		  <div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
			<form class="form-horizontal" method="POST" action="editEventTitle.php">
			  <div class="modal-header indigo darken-4 text-white">
                <h4 class="modal-title" id="ActividadCalendarioLabel">Información de la actividad</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  </div>
			  <div class="modal-body">
                    <div id="ActividadCalendario" class="tabla"></div>
			  </div>
			  <div class="modal-footer card-footer amber accent-4">
			  </div>
			</form>
			</div>
		  </div>
		</div>
    <!--En esta zona podran poner javascripts propios de la vista-->
    
    <script src="../js/sweet-alert-two/sweetalert2.min.js"></script>
    <script src="../js/libreria-bootstrap-mdb/jquery.min.js"></script>
    <script src='../js/jquery.js'></script>

    <!-- FullCalendar -->
	<script src='../js/moment.min.js'></script>
	<script src='../js/fullcalendar/fullcalendar.min.js'></script>
	<script src='../js/fullcalendar/fullcalendar.js'></script>
	<script src='../js/fullcalendar/locale/es.js'></script>


    <script src="../js/config/config.js"></script>
    <script src="../js/validators/form-validator.js"></script>

    <script type="text/javascript">
        var Usuario = <?= json_encode($_SESSION) ?>;
        var idDepartamento=null;
        var departamento=null;
    </script>

    <script src="../js/Calendario-actividades/Calendario.js"></script>
    <script src="../js/Calendario-actividades/Calendario-actividades.js"></script>
    
<?php
    include('../partials/endDoctype.php');
?>
