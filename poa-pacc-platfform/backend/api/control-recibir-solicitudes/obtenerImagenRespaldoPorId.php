<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/RecibirSolicitudesController.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                if (isset($_POST['idSolicitud']) && !empty($_POST['idSolicitud'])) {
                    $permisos = new RecibirSolicitudesController();
                    $permisos->verImagenRespaldoPorId($_POST['idSolicitud']);
                } else {
                    $permisos = new RecibirSolicitudesController();
                    $permisos->peticionNoValida();
                }
            } else {
                $permisos = new RecibirSolicitudesController();
                $permisos->peticionNoAutorizada();
                require_once('../destruir-sesiones.php');
            }
        break;
        default: 
            $permisos = new RecibirSolicitudesController();
            $permisos->peticionNoValida();
        break;
    }
?>