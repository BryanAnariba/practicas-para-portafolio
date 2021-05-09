<?php
    require_once('../request-headers.php');
    require_once('../../controllers/UsuariosController.php');
    
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST': 
            $_POST = json_decode(file_get_contents('php://input'), true);
            if (
                isset($_POST['correoInstitucional']) && 
                !empty($_POST['correoInstitucional'])) {
                $usuario = new UsuariosController();
                    $usuario->recuperarCrendeciales($_POST['correoInstitucional']);
            } else {
                $usuario = new UsuariosController();
                $usuario->peticionNoValida();
            }
        break;
        default: 
            $usuario = new UsuariosController();
            $usuario->peticionNoValida();
        break;
    }