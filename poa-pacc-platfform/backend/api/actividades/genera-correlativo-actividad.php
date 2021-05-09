<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/ActividadesController.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST': 
            $_POST = json_decode(file_get_contents('php://input'), true);
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                if (isset($_POST['idDimension']) && !empty($_POST['idDimension'])) {
                    $actividades = new ActividadesController();
                    $actividades->generarCorrelativo($_POST['idDimension']);
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