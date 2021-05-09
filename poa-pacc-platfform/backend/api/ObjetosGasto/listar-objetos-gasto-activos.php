<?php
    require_once('../request-headers.php');
    require_once('../../controllers/ObjetosGastoController.php');
    
    switch ($_SERVER['REQUEST_METHOD']) {
        case "POST": 
            //$_POST = json_decode(file_get_contents('php://input'));
            $objeto = new ObjetosController();
            
            $resultado = $objeto->obtenerObjetosActivos();
            
        break;
        default: 
            $objetos = new ObjetosController();
            $objetos->peticionNoValida();
        break;
    }