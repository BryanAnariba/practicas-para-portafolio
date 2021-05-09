<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/DimensionesEstrategicasController.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST': 
            $_POST = json_decode(file_get_contents('php://input'), true);
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                if (isset($_POST['idDimensionEstrategica']) && !empty($_POST['idDimensionEstrategica'])) {
                    $dimension = new DimensionesEstrategicasController();
                    $dimension->modificaEstadoDimension($_POST['idDimensionEstrategica']);
                } else {
                    $dimension = new DimensionesEstrategicasController();
                    $dimension->peticionNoValida();
                }
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