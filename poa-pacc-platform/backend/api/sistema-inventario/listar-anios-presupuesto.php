<?php
    require_once('../request-headers.php');
    require_once('../../controllers/PresupuestosController.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':        
            $presupuesto = new PresupuestosController();
            $presupuesto->listarAniosPresupuestos();
            
        break;
        default: 
            $presupuesto = new PresupuestosController();
            $presupuesto->peticionNoValida();
        break;
    }
?>