<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/ActividadesController.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST': 
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                $actividades = new ActividadesController();
                $actividades->verEstadoPresupuestoAnual();
            } else {
                $actividades = new ActividadesController();
                $actividades->peticionNoAutorizada();
                require_once('../destruir-sesiones.php');
            }
        break;
        default: 
            $actividades = new ActividadesController();
            $actividades->peticionNoValida();
        break;
    }
?>