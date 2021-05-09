<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/ResultadosInstitucionalesController.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $_POST = json_decode(file_get_contents('php://input'),true);
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                if (
                    isset($_POST['idResultadoInstitucional']) && 
                    !empty($_POST['idResultadoInstitucional']) &&
                    isset($_POST['idEstadoResultadoInstitucional']) &&
                    !empty($_POST['idEstadoResultadoInstitucional'])) {
                    $resultadoInstitucional = new ResultadoInstitucionalController();
                    $resultadoInstitucional->cambiarEstadoResultado($_POST['idResultadoInstitucional'], $_POST['idEstadoResultadoInstitucional']);
                } else {
                    $resultadoInstitucional = new DepartamentosController();
                    $resultadoInstitucional->peticionNoValida();
                }
            } else {
                $resultadoInstitucional = new DepartamentosController();
                $resultadoInstitucional->peticionNoAutorizada();
                require_once('../destruir-sesiones.php');
            }
        break;
        default: 
            $resultadoInstitucional = new DepartamentosController();
            $resultadoInstitucional->peticionNoValida();
        break;
    }
?>