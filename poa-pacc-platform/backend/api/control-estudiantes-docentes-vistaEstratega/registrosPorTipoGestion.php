<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/EstudiantesDocentesEstrategaController.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                if (isset($_POST['idTipoGestionVer']) && !empty($_POST['idTipoGestionVer'])) {
                    $permisos = new EstudiantesDocentesEstrategaController();
                    $permisos->getGestionRegistrosPorTipo($_POST['idTipoGestionVer']);
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