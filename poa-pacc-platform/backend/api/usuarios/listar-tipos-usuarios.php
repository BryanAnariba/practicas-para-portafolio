<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/TipoUsuarioController.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                $tipoUsuario = new TipoUsuarioController();
                $tipoUsuario->listarTiposUsuarios();
            } else {
                $tipoUsuario = new TipoUsuarioController();
                $tipoUsuario->peticionNoAutorizada();
                require_once('../destruir-sesiones.php');
            }
        break;
        default: 
            $tipoUsuario = new TipoUsuarioController();
            $tipoUsuario->peticionNoValida();
        break;
    }