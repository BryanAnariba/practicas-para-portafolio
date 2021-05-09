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
                if (isset($_POST['idObjetivo']) && !empty($_POST['idObjetivo']) && isset($_POST['idEstadoObjetivo']) && !empty($_POST['idEstadoObjetivo'])) {
                    $objetivo = new ObjetivosIntitucionalesController();
                    $objetivo->modificarEstadoObjetivo($_POST['idObjetivo'], $_POST['idEstadoObjetivo']);
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