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
                    isset($_POST['idTipoUsuario']) && 
                    !empty($_POST['idTipoUsuario']) &&
                    isset($_POST['valorLlenadoDimensionInicial']) && 
                    !empty($_POST['valorLlenadoDimensionInicial']) &&
                    isset($_POST['valorLlenadoDimensionFinal']) && 
                    !empty($_POST['valorLlenadoDimensionFinal'])
                ) {
                    $llenadoActividadDimension = new LlenadoDimensionActividadController();
                    $llenadoActividadDimension->insertaNuevoRangoLlenadoActividad($_POST['idTipoUsuario'], $_POST['valorLlenadoDimensionInicial'], $_POST['valorLlenadoDimensionFinal']);
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