<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/EstudiantesDocentesEstrategaController.php');
    
    switch ($_SERVER['REQUEST_METHOD']) {
        case "POST": 
            
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                $departamento = new EstudiantesDocentesEstrategaController();
                
                $resultado = $departamento->obtenerDepartamentos();
            } else {
                $departamentos = new EstudiantesDocentesEstrategaController();
                $departamentos->peticionNoAutorizada();
                require_once('../destruir-sesiones.php');
            }
        break;
        default: 
            $departamentos = new EstudiantesDocentesEstrategaController();
            $departamentos->peticionNoValida();
        break;
    }
?>