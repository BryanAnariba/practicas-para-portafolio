<?php
    require_once('../request-headers.php');
    require_once('../../controllers/UsuariosController.php');
    
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST': 
            $_POST = json_decode(file_get_contents('php://input'), true);
            if (
                isset($_POST['correoInstitucional']) && 
                !empty($_POST['correoInstitucional']) && 
                isset($_POST['passwordEmpleado']) && 
                !empty($_POST['passwordEmpleado']))  {
                $usuario = new UsuariosController();
                $usuario->loginUsuario($_POST['correoInstitucional'], $_POST['passwordEmpleado']);
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