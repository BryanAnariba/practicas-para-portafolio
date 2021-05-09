<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/EnvioSolicitudesController.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                if (isset($_POST['idSolicitud']) && !empty($_POST['idSolicitud'])) {
                    $permisos = new EnvioSolicitudesController();
                    $permisos->verSolicitudPorId($_POST['idSolicitud']);
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