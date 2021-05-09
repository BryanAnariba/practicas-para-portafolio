<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/UsuariosController.php');
    
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST': 
            $_POST = json_decode(file_get_contents('php://input'), true);
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                if (isset($_POST['idUsuario']) && !empty($_POST['idUsuario']) && isset($_POST['correoInstitucional']) && !empty($_POST['correoInstitucional']) && isset($_POST['nombrePersona']) && !empty($_POST['nombrePersona']) && isset($_POST['apellidoPersona']) && !empty($_POST['apellidoPersona']) && isset($_POST['nombreUsuario']) && !empty($_POST['nombreUsuario'])) {
                    $usuario = new UsuariosController();
                    $usuario->reenviarCredencialesAcceso($_POST['idUsuario'], $_POST['correoInstitucional'], $_POST['nombrePersona'], $_POST['apellidoPersona'], $_POST['nombreUsuario']);
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