<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/RecibirSolicitudesController.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                if (isset($_POST['idDepartamentoUsuario']) && !empty($_POST['idDepartamentoUsuario'])) {
                    $permisos = new RecibirSolicitudesController();
                    $permisos->listarSolicitudesPendientesJefe($_POST['idDepartamentoUsuario']);
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