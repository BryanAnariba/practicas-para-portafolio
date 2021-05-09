<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/DimensionesAdministrativasController.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST': 
            $_POST = json_decode(file_get_contents('php://input'), true);
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                if (isset($_POST['idDimensionAdministrativa']) && !empty($_POST['idDimensionAdministrativa'])) {
                    $dimension = new DimensionesAdministrativasController();
                    $dimension->modificaEstadoDimension($_POST['idDimensionAdministrativa']);
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