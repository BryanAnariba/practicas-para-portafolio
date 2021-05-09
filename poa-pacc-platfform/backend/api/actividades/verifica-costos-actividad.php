<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/ActividadesController.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST': 
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                $_POST = json_decode(file_get_contents('php://input'), true);
                if (
                    isset($_POST['costoTotalActividad']) && 
                    isset($_POST['porcentajeTrimestre1']) && 
                    isset($_POST['porcentajeTrimestre2']) && 
                    isset($_POST['porcentajeTrimestre3']) && 
                    isset($_POST['porcentajeTrimestre4'])) {
                    $actividades = new ActividadesController();
                    $actividades->verificaCostoRangoActividad($_POST['costoTotalActividad'], $_POST['porcentajeTrimestre1'], $_POST['porcentajeTrimestre2'], $_POST['porcentajeTrimestre3'], $_POST['porcentajeTrimestre4']);
                } else {
                    $actividades = new ActividadesController();
                    $actividades->peticionNoValida();
                }
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