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
                    isset($_POST['idActividad']))  {
                    $actividades = new ActividadesController();
                    $actividades->verificaRangoPresupuestoParaModificar($_POST['costoTotalActividad'], $_POST['idActividad']);
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