<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/DepartamentoController.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                    $departamentos = new DepartamentoController();
                    $departamentos->verDepartamentos();
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