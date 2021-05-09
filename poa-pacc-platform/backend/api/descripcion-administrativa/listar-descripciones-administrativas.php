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
                if (isset($_POST['idActividad']) && !empty($_POST['idActividad']) && isset($_POST['idDimensionAdmin']) && !empty($_POST['idDimensionAdmin'])) {
                    $descripcion = new DescripcionAdministrativaController();
                    $descripcion->listarDescripcionesAdministrativas($_POST['idActividad'],$_POST['idDimensionAdmin']);
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