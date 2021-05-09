<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../helpers/Imagen.php');
    require_once('../../controllers/UsuariosController.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                if (isset($_FILES) && !empty($_FILES)) {
                    $imagen = new Imagen(DIRECTORIO_USUARIOS);
                    $imagen->setImagen($_FILES);
                    $estado = $imagen->removerImagen($_SESSION['avatarUsuario']);
                    $statusSubida = $imagen->subirImagen();
                    if ($statusSubida == false ||  ($estado == false)) {
                        echo json_encode(
                            array('status' => http_response_code(400), 'data' => 
                            array('message' => 'Ha ocurrido un error, la imagen no se subio, por problemas de pesoen MB o tipo de archivo')));
                    } else {
                        $usuario = new UsuariosController();
                        $usuario->cambiaAvatarUsuario($statusSubida);
                    }
                
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