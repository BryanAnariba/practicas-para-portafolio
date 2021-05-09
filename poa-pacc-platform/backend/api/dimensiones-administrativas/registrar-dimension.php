<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../helpers/Respuesta.php');
    require_once('../../controllers/DimensionesAdministrativasController.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST': 
            $_POST = json_decode(file_get_contents('php://input'), true);
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                if (isset($_POST['dimensionAdministrativa']) && !empty($_POST['dimensionAdministrativa'])) {
                    $dimension = new DimensionesAdministrativasController();
                    $dimension->insertaDimension($_POST['dimensionAdministrativa']);
                } else {
                    $dimension = new DimensionesAdministrativasController();
                    $dimension->peticionNoValida();
                }
            } else {
                $dimension = new DimensionesAdministrativasController();
                $dimension->peticionNoAutorizada();
                require_once('../destruir-sesiones.php');
            }
        break;
        default: 
            $dimension = new DimensionesAdministrativasController();
            $dimension->peticionNoValida();
        break;
    }
?>