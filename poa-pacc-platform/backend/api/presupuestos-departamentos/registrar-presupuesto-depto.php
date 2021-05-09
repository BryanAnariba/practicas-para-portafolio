<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../helpers/Imagen.php');
    require_once('../../controllers/PresupuestosDepartamentosController.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $_POST = json_decode(file_get_contents('php://input'), true);
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                if (isset($_POST['idControlPresupuestoActividad']) && !empty($_POST['idControlPresupuestoActividad']) &&  isset($_POST['idDepartamento']) && !empty($_POST['idDepartamento']) && isset($_POST['montoPresupuesto']) && !empty($_POST['montoPresupuesto'])) {
                    $presupuestoDepartamento = new PresupuestosController();
                    $presupuestoDepartamento->registrarNuevoPresupuestoDepartamento($_POST['montoPresupuesto'], $_POST['idControlPresupuestoActividad'], $_POST['idDepartamento']);
                } else {
                    $presupuestoDepartamento = new PresupuestosController();
                    $presupuestoDepartamento->peticionNoValida();
                }
            } else {
                $presupuestoDepartamento = new PresupuestosController();
                $presupuestoDepartamento->peticionNoAutorizada();
                require_once('../destruir-sesiones.php');
            }
        break;
        default: 
            $presupuestoDepartamento = new PresupuestosController();
            $presupuestoDepartamento->peticionNoValida();
        break;
    }
?>