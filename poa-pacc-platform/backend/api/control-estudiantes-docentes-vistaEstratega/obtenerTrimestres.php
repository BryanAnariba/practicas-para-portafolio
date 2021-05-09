<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/EstudiantesDocentesEstrategaController.php');
    
    switch ($_SERVER['REQUEST_METHOD']) {
        case "POST": 
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                $EstudianteDocente = new EstudiantesDocentesEstrategaController();
                
                $resultado = $EstudianteDocente->obtenerTrimestres();
            } else {
                $EstudianteDocente = new EstudiantesDocentesEstrategaController();
                $EstudianteDocente->peticionNoAutorizada();
                require_once('../destruir-sesiones.php');
            }
        break;
        default: 
            $EstudianteDocente = new EstudiantesDocentesEstrategaController();
            $EstudianteDocente->peticionNoValida();
        break;
    }
?>