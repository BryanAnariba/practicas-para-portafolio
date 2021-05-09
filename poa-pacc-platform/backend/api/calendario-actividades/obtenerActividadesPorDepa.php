<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/CalendarioActividadesController.php');
    
    switch ($_SERVER['REQUEST_METHOD']) {
        case "POST": 
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                $Calendario = new CalendarioController();
                
                $resultado = $Calendario->obtenerActividadesPorDepa($_POST['Depa'],$_POST['idDimension'],$_POST['Anio']);
            } else {
                $Calendario = new CalendarioController();
                $Calendario->peticionNoAutorizada();
                require_once('../destruir-sesiones.php');
            }
        break;
        default: 
            $Calendario = new CalendarioController();
            $Calendario->peticionNoValida();
        break;
    }