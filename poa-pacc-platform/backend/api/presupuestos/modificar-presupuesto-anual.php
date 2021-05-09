<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../helpers/Imagen.php');
    require_once('../../controllers/PresupuestosController.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                $_POST = json_decode(file_get_contents('php://input'), true);
                if (
                    isset($_POST['idControlPresupuestoActividad']) && 
                    !empty($_POST['idControlPresupuestoActividad']) &&
                    isset($_POST['presupuestoAnual']) && 
                    !empty($_POST['presupuestoAnual']) &&
                    isset($_POST['estadoPresupuestoAnual']) &&
                    !empty($_POST['estadoPresupuestoAnual'])) {
                    $presupuesto = new PresupuestosController();
                    $presupuesto->cambiaPresupuesto($_POST['idControlPresupuestoActividad'], $_POST['presupuestoAnual'], $_POST['estadoPresupuestoAnual']);
                } else {
                    $presupuesto = new PresupuestosController();
                    $presupuesto->peticionNoValida();
                }
            } else {
                $presupuesto = new PresupuestosController();
                $presupuesto->peticionNoAutorizada();
                require_once('../destruir-sesiones.php');
            }
        break;
        default: 
            $presupuesto = new PresupuestosController();
            $presupuesto->peticionNoValida();
        break;
    }
?>