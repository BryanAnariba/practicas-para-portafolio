<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/DimensionesEstrategicasController.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                $dimension = new DimensionesEstrategicasController();
                $dimension->listarDimensionesActivas();
            } else {
                $dimension = new DimensionesEstrategicasController();
                $dimension->peticionNoAutorizada();
                require_once('../destruir-sesiones.php');
            }
        break;
        default: 
            $dimension = new DimensionesEstrategicasController();
            $dimension->peticionNoValida();
        break;
    }
?>