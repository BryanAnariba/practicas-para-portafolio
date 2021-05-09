<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/EstudiantesDocentesEstrategaController.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                if (isset($_POST['idDepartamentoVer']) && !empty($_POST['idDepartamentoVer'])) {
                    $permisos = new EstudiantesDocentesEstrategaController();
                    $permisos->getGestionRegistrosPorDepartamento($_POST['idDepartamentoVer']);
                } else {
                    $permisos = new EstudiantesDocentesEstrategaController();
                    $permisos->peticionNoValida();
                }
            } else {
                $permisos = new EstudiantesDocentesEstrategaController();
                $permisos->peticionNoAutorizada();
                require_once('../destruir-sesiones.php');
            }
        break;
        default: 
            $permisos = new EstudiantesDocentesEstrategaController();
            $permisos->peticionNoValida();
        break;
    }
?>