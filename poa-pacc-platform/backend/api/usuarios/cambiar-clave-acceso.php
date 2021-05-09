<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/UsuariosController.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                $_POST = json_decode(file_get_contents('php://input'), true);
                if (
                    isset($_POST['nuevaPasswordEmpleado']) && 
                    isset($_POST['repeatNuevaPasswordEmpleado']) &&
                    !empty($_POST['nuevaPasswordEmpleado']) &&
                    !empty($_POST['repeatNuevaPasswordEmpleado'])) {
                    if ($_POST['nuevaPasswordEmpleado'] == $_POST['repeatNuevaPasswordEmpleado']) {
                        $usuario = new UsuariosController();
                        $usuario->cambioClaveUsuario($_POST['nuevaPasswordEmpleado']);
                    } else {
                        $usuario = new UsuariosController();
                        $usuario->peticionNoValida();
                    }
                } else {
                    $usuario = new UsuariosController();
                    $usuario->peticionNoValida();
                }
            } else {
                $usuario = new UsuariosController();
                $usuario->peticionNoAutorizada();
                require_once('../destruir-sesiones.php');
            }
        break;
        default: 
            $usuario = new UsuariosController();
            $usuario->peticionNoValida();
        break;
    }