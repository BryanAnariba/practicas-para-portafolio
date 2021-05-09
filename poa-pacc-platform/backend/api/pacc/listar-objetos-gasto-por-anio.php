<?php
    require_once('../request-headers.php');
    require_once('../../controllers/PaccController.php');
    
    switch ($_SERVER['REQUEST_METHOD']) {
        case "POST": 
            $_POST = json_decode(file_get_contents('php://input'), true);
            if (isset($_POST['idPresupuesto']) && !empty($_POST['idPresupuesto'])) {
                $pacc= new PaccController();
            
                $resultado =  $pacc->obtenerObjetosPorAnio($_POST['idPresupuesto']);
            } else {
                $pacc = new PaccController();
                $pacc->peticionNoValida();
            }
        break;
        default: 
            $pacc = new PaccController();
            $pacc->peticionNoValida();
        break;
    }