<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/control-estudiantes-docentes.php');
    
    switch ($_SERVER['REQUEST_METHOD']) {
        case "POST": 
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                $EstudianteDocente = new EstudianteDocenteController();
                
                $resultado = $EstudianteDocente->obtenerTrimestres();
            } else {
                $EstudianteDocente = new EstudianteDocenteController();
                $EstudianteDocente->peticionNoAutorizada();
                require_once('../destruir-sesiones.php');
            }
        break;
        default: 
            $EstudianteDocente = new EstudianteDocenteController();
            $EstudianteDocente->peticionNoValida();
        break;
    }
?>