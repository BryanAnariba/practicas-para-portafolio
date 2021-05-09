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
                if (isset($_POST['idAreaEstrategica']) && !empty($_POST['idAreaEstrategica'])) {
                    $resultadoInstitucional = new ResultadoInstitucionalController();
                    $resultadoInstitucional->listarResultados($_POST['idAreaEstrategica']);
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