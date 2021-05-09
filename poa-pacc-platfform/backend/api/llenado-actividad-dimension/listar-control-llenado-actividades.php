<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/LlenadoDimensionActividadController.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST': 
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                $llenadoActividadDimension = new LlenadoDimensionActividadController();
                $llenadoActividadDimension->listaControlLlenadoActividades();
            } else {
                $llenadoActividadDimension = new LlenadoDimensionActividadController();
                $llenadoActividadDimension->peticionNoAutorizada();
                require_once('../destruir-sesiones.php');
            }
        break;
        default: 
            $llenadoActividadDimension = new LlenadoDimensionActividadController();
            $llenadoActividadDimension->peticionNoValida();
        break;
    }
?>