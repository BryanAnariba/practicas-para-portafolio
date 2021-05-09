<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/ObjetosGastoController.php');
    
    switch ($_SERVER['REQUEST_METHOD']) {
        case "POST": 
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                $Objeto = new ObjetosController();
                
                $resultado = $Objeto->obtenerEstados();
            } else {
                $objeto = new ObjetosController();
                $objeto->peticionNoAutorizada();
                require_once('../destruir-sesiones.php');
            }
        break;
        default: 
            $Objeto = new ObjetosController();
            $Objeto->peticionNoValida();
        break;
    }