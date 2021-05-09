<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/RecibirInformesController.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST': 
            $_POST = json_decode(file_get_contents('php://input'), true);
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                if (isset($_POST['idInforme']) && !empty($_POST['idInforme'])) {
                    $informe = new RecibirInformesController();
                    $informe->modificaEstadoInforme($_POST['idInforme']);
                } else {
                    $informe = new RecibirInformesController();
                    $informe->peticionNoValida();
                }
            } else {
                $informe = new RecibirInformesController();
                $informe->peticionNoAutorizada();
                require_once('../destruir-sesiones.php');
            }
        break;
        default: 
            $informe = new RecibirInformesController();
            $informe->peticionNoValida();
        break;
    }
?>