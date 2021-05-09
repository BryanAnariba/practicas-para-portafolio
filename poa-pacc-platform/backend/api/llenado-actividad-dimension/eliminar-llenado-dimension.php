<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/LlenadoDimensionActividadController.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST': 
            $_POST = json_decode(file_get_contents('php://input'), true);
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                if (
                    isset($_POST['idLlenadoDimension']) && 
                    !empty($_POST['idLlenadoDimension'])
                ) {
                    $llenadoActividadDimension = new LlenadoDimensionActividadController();
                    $llenadoActividadDimension->eliminarRangoDimension($_POST['idLlenadoDimension']);
                } else {
                    $llenadoActividadDimension = new LlenadoDimensionActividadController();
                    $llenadoActividadDimension->peticionNoValida();
                }
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