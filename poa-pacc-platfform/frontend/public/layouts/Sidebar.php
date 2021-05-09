<?php
    if (!isset($_SESSION)) {
        session_start();
    }
    if (!isset($_SESSION['correoInstitucional'])) {
        header('Location: 401.php');
    }
    include('../partials/doctype.php');
    include('verifica-session.php');
     // Tipos de roles de usuarios en el sistema
    define('ROL_SUPER_ADMIN', 'SU_AD');
    define('ROL_DECANO', 'D_F');
    define('ROL_SECRETARIA_ACADEMICA', 'S_AC');
    define('ROL_SECRETARIA_ADMINISTRATIVA', 'SE_AD');
    define('ROL_COORDINADOR', 'C_C');
    define('ROL_JEFE', 'J_D');
    define('ROL_ESTRATEGA', 'U_E');   
?>
<div class="l-navbar" id="nav-bar">
        <nav class="nav">
            <div>
                <a href="../views/menu.php" class="nav__logo">
                    <img src="../img/partial-sidebar/control-panel-icon.svg" alt="Menu Principal">
                    <span class="nav__logo-name">Menu Principal</span>
                </a>
                    <div class="nav__list">
                    <?php 
                        switch($_SESSION['abrevTipoUsuario']): 
                            case $_SESSION['abrevTipoUsuario'] == ROL_SECRETARIA_ACADEMICA: // ROLE => Secretaria Academica
                    ?>
                        <a href="../views/control-recibir-permisos.php" class="nav__link">
                            <img src="../img/partial-sidebar/permisos-icon.svg" alt="Control de Usuarios">
                            <span class="nav__name">Control Permisos</span>
                        </a>

                        <a href="../views/informes-anuales.php" class="nav__link">
                            <img src="../img/partial-sidebar/informes-icon.svg" alt="Control de Dimensiones">
                            <span class="nav__name">Informes Anuales</span>
                        </a>

                        <a href="../views/tramites-graduacion.php" class="nav__link">
                            <img src="../img/partial-sidebar/graduacion-icon.svg" alt="Control de Dimensiones">
                            <span class="nav__name">Tramites Graduacion</span>
                        </a>
                    <?php 
                        break; 
                    ?>
                    <?php 
                        case $_SESSION['abrevTipoUsuario'] == ROL_SUPER_ADMIN: // ROLE => Super Administrador
                    ?>
                        <a href="../views/usuarios.php" class="nav__link">
                            <img src="../img/partial-sidebar/usuarios-icon.svg" alt="Control de Usuarios">
                            <span class="nav__name">Control Usuarios</span>
                        </a>

                        <a href="../views/control-departamentos.php" class="nav__link">
                            <img src="../img/partial-sidebar/departamentos-icon.svg" alt="Control de Departamentos">
                            <span class="nav__name">Control Departamentos</span>
                        </a>

                        <a href="../views/carreras.php" class="nav__link">
                            <img src="../img/partial-sidebar/carreras-icon.svg" alt="Control de Carreras">
                            <span class="nav__name">Control Carreras</span>
                        </a>

                        <a href="../views/dimensiones.php" class="nav__link">
                            <img src="../img/partial-sidebar/dimensiones-icon.svg" alt="Control de Dimensiones">
                            <span class="nav__name">Control Dimensiones</span>
                        </a>

                        <a href="../views/dimensiones-Administrativas.php" class="nav__link">
                            <img src="../img/partial-sidebar/dimensiones.svg" alt="Control de Dimensiones Admin">
                            <span class="nav__name">Control Dimensiones Admin</span>
                        </a>

                        <a href="../views/ObjetosGasto.php" class="nav__link">
                            <img src="../img/partial-sidebar/ObjetosGasto-icon.png" alt="Control de Objetos de Gasto">
                            <span class="nav__name">Control Objetos de Gasto</span>
                        </a>
                        <a href="../views/control-llenado-actividades.php" class="nav__link">
                            <img src="../img/partial-sidebar/envio-permiso-icon.svg" alt="Control de Carreras">
                            <span class="nav__name">Control llenado actividades</span>
                        </a>
                    <?php 
                        break; 
                    ?>
                    <?php 
                        case $_SESSION['abrevTipoUsuario'] == ROL_DECANO // ROLE => Decano Facultad
                    ?>
                        <a href="../views/control-recibir-informes.php" class="nav__link">
                            <img src="../img/partial-sidebar/departamentos-icon.svg" alt="Control de Usuarios">
                            <span class="nav__name">Decano</span>
                        </a>

                        <a href="../views/presupuestos.php" class="nav__link">
                            <img src="../img/partial-sidebar/presupuesto-icon.svg" alt="Control de Dimensiones">
                            <span class="nav__name">Presupuesto</span>
                        </a>

                        <!--a href="" class="nav__link">
                        <img src="../img/partial-sidebar/informes-icon.svg" alt="Control de Departamentos">
                            <span class="nav__name">Reportes</span>
                        </a-->

                        <a href="../views/Calendario-actividades.php" class="nav__link">
                            <img src="../img/partial-sidebar/calendario-icon.svg" alt="Control de Carreras">
                            <span class="nav__name">Calendario Actividades</span>
                        </a>

                        <a href="../views/control-recibir-permisos.php" class="nav__link">
                            <img src="../img/partial-sidebar/permisos-icon.svg" alt="Control de Usuarios">
                            <span class="nav__name">Control Permisos</span>
                        </a>
                    <?php 
                        break; 
                    ?>
                    <?php 
                        case $_SESSION['abrevTipoUsuario'] == ROL_ESTRATEGA: // ROLE => Secretaria Estratega
                    ?>
                        <a href="../views/control-recibir-informes.php" class="nav__link">
                            <img src="../img/partial-sidebar/departamentos-icon.svg" alt="Control de Usuarios">
                            <span class="nav__name">Estratega</span>
                        </a>

                        <a href="../views/presupuestos.php" class="nav__link">
                            <img src="../img/partial-sidebar/presupuesto-icon.svg" alt="Control de Dimensiones">
                            <span class="nav__name">Presupuesto</span>
                        </a>

                        <!--a href="" class="nav__link">
                        <img src="../img/partial-sidebar/informes-icon.svg" alt="Control de Departamentos">
                            <span class="nav__name">Reportes</span>
                        </-->

                        <a href="../views/Calendario-actividades.php" class="nav__link">
                            <img src="../img/partial-sidebar/calendario-icon.svg" alt="Control de Carreras">
                            <span class="nav__name">Calendario Actividades</span>
                        </a>

                        <a href="../views/control-estudiantes-docentes-estratega.php" class="nav__link">
                            <img src="../img/control-envio-informes/solicitud.svg" alt="Control de Informes">
                            <span class="nav__name">Control Estudiantes-Docentes</span>
                        </a>

                        <a href="../views/control-envio-permisos.php" class="nav__link">
                            <img src="../img/partial-sidebar/envio-permiso-icon.svg" alt="Control de Carreras">
                            <span class="nav__name">Control Permisos</span>
                        </a>
                        <a href="../views/pacc.php" class="nav__link">
                            <img src="../img/pacc/pacc.svg" alt="Control PACC">
                            <span class="nav__name">Control PACC</span>
                        </a>
                    <?php 
                        break; 
                    ?>
                    <?php case $_SESSION['abrevTipoUsuario'] == ROL_SECRETARIA_ADMINISTRATIVA: // ROLE => usuario administrativo
                    ?>
                        <a href="../views/control-envio-informes.php" class="nav__link">
                            <img src="../img/partial-sidebar/departamentos-icon.svg" alt="Control de Usuarios">
                            <span class="nav__name">Depto Administrativo</span>
                        </a>

                        <a href="../views/presupuestos.php" class="nav__link">
                            <img src="../img/partial-sidebar/presupuesto-icon.svg" alt="Control de Dimensiones">
                            <span class="nav__name">Presupuesto</span>
                        </a>

                        <!--a href="" class="nav__link">
                        <img src="../img/partial-sidebar/informes-icon.svg" alt="Control de Departamentos">
                            <span class="nav__name">Reportes</span>
                        </a-->

                        <a href="../views/Calendario-actividades.php" class="nav__link">
                            <img src="../img/partial-sidebar/calendario-icon.svg" alt="Control de Carreras">
                            <span class="nav__name">Calendario Actividades</span>
                        </a>

                        <a href="../views/control-envio-permisos.php" class="nav__link">
                            <img src="../img/partial-sidebar/envio-permiso-icon.svg" alt="Control de Carreras">
                            <span class="nav__name">Control Permisos</span>
                        </a>
                        <a href="../views/pacc.php" class="nav__link">
                            <img src="../img/pacc/pacc.svg" alt="Control PACC">
                            <span class="nav__name">Control PACC</span>
                        </a>
                    <?php 
                        break; 
                    ?>
                    <?php 
                        case $_SESSION['abrevTipoUsuario'] == ROL_COORDINADOR: // ROLE => Coordinador Departamento
                    ?>
                        <a href="../views/control-actividades-JefeCoordinador.php" class="nav__link">
                            <img src="../img/partial-sidebar/departamentos-icon.svg" alt="Control de Usuarios">
                            <span class="nav__name">Control de actividades</span>
                        </a>

                        <a href="../views/Calendario-actividades.php" class="nav__link">
                            <img src="../img/partial-sidebar/calendario-icon.svg" alt="Control de Dimensiones">
                            <span class="nav__name">Calendario Actividades</span>
                        </a>

                        <!-- <a href="" class="nav__link">
                        <img src="../img/partial-sidebar/informes-icon.svg" alt="Control de Departamentos">
                            <span class="nav__name">Graficos y Reportes</span>
                        </a> -->

                        <a href="../views/control-estudiantes-docentes.php" class="nav__link">
                        <img src="../img/control-envio-informes/solicitud.svg" alt="Control de Docentes y Estudiantes">
                            <span class="nav__name">Control de Docentes y Estudiantes</span>
                        </a>

                        <a href="../views/control-envio-permisos.php" class="nav__link">
                            <img src="../img/partial-sidebar/envio-permiso-icon.svg" alt="Control de Carreras">
                            <span class="nav__name">Control Permisos</span>
                        </a>
                        
                        <a href="../views/control-envio-informes.php" class="nav__link">
                            <img src="../img/control-envio-informes/solicitud.svg" alt="Control de Informes">
                            <span class="nav__name">Control Informes</span>
                        </a>
                        
                    <?php 
                        break; 
                    ?>
                    <?php 
                        case $_SESSION['abrevTipoUsuario'] == ROL_JEFE: // ROLE => Jefe Departamento
                    ?>
                        <a href="../views/control-actividades-JefeCoordinador.php" class="nav__link">
                            <img src="../img/partial-sidebar/departamentos-icon.svg" alt="Control de Usuarios">
                            <span class="nav__name">Control de actividades</span>
                        </a>

                        <a href="../views/Calendario-actividades.php" class="nav__link">
                            <img src="../img/partial-sidebar/calendario-icon.svg" alt="Control de Dimensiones">
                            <span class="nav__name">Calendario Actividades</span>
                        </a>

                        <!-- <a href="" class="nav__link">
                        <img src="../img/partial-sidebar/informes-icon.svg" alt="Control de Departamentos">
                            <span class="nav__name">Graficos y Reportes</span>
                        </a> -->

                        <a href="../views/control-estudiantes-docentes.php" class="nav__link">
                        <img src="../img/control-envio-informes/solicitud.svg" alt="Control de Docentes y Estudiantes">
                            <span class="nav__name">Control Estudiantes-Docentes</span>
                        </a>

                        <a href="../views/control-recibir-permisos.php" class="nav__link">
                            <img src="../img/partial-sidebar/permisos-icon.svg" alt="Control de Usuarios">
                            <span class="nav__name">Control Permisos</span>
                        </a>

                        <a href="../views/control-envio-informes.php" class="nav__link">
                            <img src="../img/control-envio-informes/solicitud.svg" alt="Control de Informes">
                            <span class="nav__name">Control Informes</span>
                        </a>
                    <?php 
                        break; 
                    ?>
                    <?php endswitch; ?>
                </div>
            </div>
        <a href="" class="nav__link" data-toggle="modal" data-target="#modalCerrarSesion">
            <img src="../img/partial-sidebar/cerrar-sesion-icon.svg" alt="Cerrar sesion">
            <span class="nav__name">Cerrar sesion</span>
        </a>
    </nav>
</div>

<!-- Modal para Cerrar sesion-->
<div class="modal fade" id="modalCerrarSesion" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header indigo darken-4 text-white">
                <h5 class="modal-title" id="exampleModalLongTitle">Esta seguro de salir de la plataforma ?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <div class="text-center mt-4">
                                <button type="button" class="btn btn-light-green btn-rounded"
                                    onclick="cerrarSesion()">Cerrar Sesion</button>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-center mt-4">
                                <button type="button" class="btn btn-danger btn-rounded" data-dismiss="modal" aria-label="Close">Cancelar</button>
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