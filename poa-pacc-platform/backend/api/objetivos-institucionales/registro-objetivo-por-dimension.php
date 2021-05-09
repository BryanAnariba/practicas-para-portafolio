<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/ObjetivosInstitucionalesController.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST': 
            $_POST = json_decode(file_get_contents('php://input'), true);
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                if (isset($_POST['idDimensionEstrategica']) && !empty($_POST['idDimensionEstrategica']) && isset($_POST['objetivoInstitucional']) && !empty($_POST['objetivoInstitucional'])) {
                    $objetivo = new ObjetivosIntitucionalesController();
                    $objetivo->insertarObjetivoPorDimension($_POST['idDimensionEstrategica'], $_POST['objetivoInstitucional']);
                } else {
                    $objetivo = new ObjetivosIntitucionalesController();
                    $objetivo->peticionNoValida();
                }
            } else {
                $objetivo = new ObjetivosIntitucionalesController();
                $objetivo->peticionNoAutorizada();
                require_once('../destruir-sesiones.php');
            }
        break;
        default: 
            $objetivo = new ObjetivosIntitucionalesController();
            $objetivo->peticionNoValida();
        break;
    }
?>