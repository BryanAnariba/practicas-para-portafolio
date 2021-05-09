<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/NotificacionesController.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $_POST = json_decode(file_get_contents('php://input'), true);
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                $notificaciones = new NotificacionesController();
                $notificaciones->verNotificacionJefes();
            } else {
                $notificaciones = new NotificacionesController();
                $notificaciones->peticionNoAutorizada();
                require_once('../destruir-sesiones.php');
            }
        break;
        default: 
            $notificaciones = new NotificacionesController();
            $notificaciones->peticionNoValida();
        break;
    }
?>