<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/RecibirSolicitudesController.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST': 
            $_POST = json_decode(file_get_contents('php://input'), true);
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                if (isset($_POST['idSolicitud']) && !empty($_POST['idSolicitud']) && 
                    isset($_POST['observaciones']) && !empty($_POST['observaciones'])) {
                    $permisos = new RecibirSolicitudesController();
                    $permisos->hacerObservaciones($_POST['idSolicitud'] , $_POST['observaciones']);
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