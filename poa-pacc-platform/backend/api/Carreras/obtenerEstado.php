<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/CarrerasController.php');
    
    switch ($_SERVER['REQUEST_METHOD']) {
        case "POST": 
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                $carrera = new CarrerasController();
                
                $resultado = $carrera->obtenerEstados();
            } else {
                $carreras = new CarrerasController();
                $carreras->peticionNoAutorizada();
                require_once('../destruir-sesiones.php');
            }
        break;
        default: 
            $carreras = new CarrerasController();
            $carreras->peticionNoValida();
        break;
    }