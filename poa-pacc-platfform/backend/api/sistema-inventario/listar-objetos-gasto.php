<?php
    require_once('../request-headers.php');
    require_once('../../controllers/ObjetosGastoController.php');
    
    switch ($_SERVER['REQUEST_METHOD']) {
        case "POST": 
            $objeto = new ObjetosController();
            $resultado = $objeto->obtenerObjetosActivos();
        break;
        default: 
            $objetos = new ObjetosController();
            $objetos->peticionNoValida();
        break;
    }