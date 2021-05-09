<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/DescripcionAdministrativaController.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $_POST = json_decode(file_get_contents('php://input'),true);
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                if (isset($_POST['idDescripcionAdministrativa']) && !empty($_POST['idDescripcionAdministrativa'])) {
                    $descripcion = new DescripcionAdministrativaController();
                    $descripcion->listaDescripcion($_POST['idDescripcionAdministrativa']);
                } else {
                    $descripcion = new DescripcionAdministrativaController();
                    $descripcion->peticionNoValida();    
                }
            } else {
                $descripcion = new DescripcionAdministrativaController();
                $descripcion->peticionNoAutorizada();
                require_once('../destruir-sesiones.php');
            }
        break;
        default: 
            $descripcion = new DescripcionAdministrativaController();
            $descripcion->peticionNoValida();
        break;
    }
?>