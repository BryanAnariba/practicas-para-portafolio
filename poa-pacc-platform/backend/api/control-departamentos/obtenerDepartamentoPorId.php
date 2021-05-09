<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/DepartamentoController.php');
    
    switch ($_SERVER['REQUEST_METHOD']) {
        case "POST": 
            //$_POST = json_decode(file_get_contents('php://input'));
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                $departamento = new DepartamentoController();
                
                $resultado = $departamento->obtenerDepartamentoPorId($_POST['idDepartamento']);
            } else {
                $departamentos = new DepartamentoController();
                $departamentos->peticionNoAutorizada();
                require_once('../destruir-sesiones.php');
            }
        break;
        default: 
            $departamentos = new DepartamentoController();
            $departamentos->peticionNoValida();
        break;
    }
?>