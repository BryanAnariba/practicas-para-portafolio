<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/TipoActividadController.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                $tipoActividades = new TipoActividadController();
                $tipoActividades->listarTiposCostosActividad();
            } else {
                $tipoActividades = new TipoActividadController();
                $tipoActividades->peticionNoAutorizada();
                require_once('../destruir-sesiones.php');
            }
        break;
        default: 
            $tipoActividades = new TipoActividadController();
            $tipoActividades->peticionNoValida();
        break;
    }
?>