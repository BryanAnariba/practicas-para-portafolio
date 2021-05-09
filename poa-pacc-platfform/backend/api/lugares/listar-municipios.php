<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/LugaresController.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST': 
            $_POST = json_decode(file_get_contents('php://input'), true);
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                if (isset($_POST['idCiudad']) && !empty($_POST['idCiudad'])) {
                    $lugares = new LugaresController();
                    $lugares->listarMunicipios($_POST['idCiudad']);
                } else {
                    $lugares = new LugaresController();
                    $lugares->peticionNoValida();
                }
            } else {
                $lugares = new LugaresController();
                $lugares->peticionNoAutorizada();
                require_once('../destruir-sesiones.php');
            }
        break;
        default: 
            $lugares = new LugaresController();
            $lugares->peticionNoValida();
        break;
    }
?>