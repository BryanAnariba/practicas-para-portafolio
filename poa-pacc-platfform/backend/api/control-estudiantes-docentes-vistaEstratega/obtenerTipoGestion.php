<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/EstudiantesDocentesEstrategaController.php');
    
    switch ($_SERVER['REQUEST_METHOD']) {
        case "POST": 
            
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                $gestion = new EstudiantesDocentesEstrategaController();
                
                $resultado = $gestion->obtenerTipoGestion();
            } else {
                $gestiones = new EstudiantesDocentesEstrategaController();
                $gestiones->peticionNoAutorizada();
                require_once('../destruir-sesiones.php');
            }
        break;
        default: 
            $gestiones = new EstudiantesDocentesEstrategaController();
            $gestiones->peticionNoValida();
        break;
    }
?>