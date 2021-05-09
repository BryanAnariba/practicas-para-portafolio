<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/EnvioSolicitudesController.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $_POST = json_decode(file_get_contents('php://input'), true);
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                if (isset($_POST['idUsuario']) && !empty($_POST['idUsuario']) &&
                    isset($_POST['motivoPermiso']) && !empty($_POST['motivoPermiso']) &&
                    isset($_POST['edificioAsistencia']) && !empty($_POST['edificioAsistencia']) &&
                    isset($_POST['fechaInicio']) && !empty($_POST['fechaInicio']) &&
                    isset($_POST['fechaFin']) && !empty($_POST['fechaFin']) &&
                    isset($_POST['horaInicio']) && !empty($_POST['horaInicio'])&&
                    isset($_POST['horaFin']) && !empty($_POST['horaFin'])
                    ) {
                    $permisos = new EnvioSolicitudesController();
                    $permisos->registrarSolicitudSinImagenes($_POST['idUsuario'],
                                                                $_POST['motivoPermiso'],
                                                                $_POST['edificioAsistencia'],
                                                                $_POST['fechaInicio'],
                                                                $_POST['fechaFin'],
                                                                $_POST['horaInicio'],
                                                                $_POST['horaFin']
                                                            );
                } else {
                    $permisos = new EnvioSolicitudesController();
                    $permisos->peticionNoValida();
                }
            } else {
                $permisos = new EnvioSolicitudesController();
                $permisos->peticionNoAutorizada();
                require_once('../destruir-sesiones.php');
            }
        break;
        default: 
            $permisos = new EnvioSolicitudesController();
            $permisos->peticionNoValida();
        break;
    }
?>
  