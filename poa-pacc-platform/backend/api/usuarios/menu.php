<?php
    require_once('../../middlewares/VerificarToken.php');
    require_once('../request-headers.php');
    require_once('../../controllers/UsuariosController.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if (!$tokenEsValido) {
                require_once('../destruir-sesiones.php');
                $usuario->peticionNoAutorizada();
                $usuario = new UsuariosController();
                header('Location: ../../frontend/index.php');
                exit;
            } else {
                $usuario = new UsuariosController();
                $usuario->logueado();
                exit;
            }
        break;
        default: 
            $usuario = new UsuariosController();
            $usuario->peticionNoValida();
        break;
    }