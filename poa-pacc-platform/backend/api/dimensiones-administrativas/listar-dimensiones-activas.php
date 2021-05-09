<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/DimensionesAdministrativasController.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST': 
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                $dimension = new DimensionesAdministrativasController();
                $dimension->listarDimensionesActivas();
            } else {
                $dimension = new DimensionesAdministrativasController();
                $dimension->peticionNoAutorizada();
                require_once('../destruir-sesiones.php');
            }
            
        break;
        default: 
            $dimension = new DimensionesAdministrativasController();
            $dimension->peticionNoValida();
        break;
    }
?>