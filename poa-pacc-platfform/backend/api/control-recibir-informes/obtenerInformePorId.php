<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/RecibirInformesController.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                if (isset($_POST['idInforme']) && !empty($_POST['idInforme'])) {
                    $permisos = new RecibirInformesController();
                    $permisos->verInformePorId($_POST['idInforme']);
                } else {
                    $permisos = new RecibirInformesController();
                    $permisos->peticionNoValida();
                }
            } else {
                $permisos = new RecibirInformesController();
                $permisos->peticionNoAutorizada();
                require_once('../destruir-sesiones.php');
            }
        break;
        default: 
            $permisos = new RecibirInformesController();
            $permisos->peticionNoValida();
        break;
    }
?>