<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/EnvioInformesController.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                if (isset($_POST['idInforme']) && !empty($_POST['idInforme'])) {
                    $permisos = new EnvioInformesController();
                    $permisos->verDescripcionPorId($_POST['idInforme']);
                } else {
                    $permisos = new EnvioInformesController();
                    $permisos->peticionNoValida();
                }
            } else {
                $permisos = new EnvioInformesController();
                $permisos->peticionNoAutorizada();
                require_once('../destruir-sesiones.php');
            }
        break;
        default: 
            $permisos = new EnvioInformesController();
            $permisos->peticionNoValida();
        break;
    }
?>