<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/PaccController.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                $_POST = json_decode(file_get_contents('php://input'), true);
                if (
                    isset($_POST['fechaPresupuestoActividad']) && 
                    !empty($_POST['fechaPresupuestoActividad']) &&
                    isset($_POST['idDepartamento']) &&
                    !empty($_POST['idDepartamento'])
                    ) {
                        $pacc = new PaccController();
                        $pacc->generaDataGastosPorDimension($_POST['fechaPresupuestoActividad'], $_POST['idDepartamento']);
                    } else {
                        $pacc = new PaccController();
                        $pacc->peticionNoValida();
                    }
                } else {
                    $pacc = new PaccController();
                    $pacc->peticionNoAutorizada();
                }
            break;
            default: 
                $pacc = new PaccController();
                $pacc->peticionNoValida();
            break;
            }
?>