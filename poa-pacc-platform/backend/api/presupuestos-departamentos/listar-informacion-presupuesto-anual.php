<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../helpers/Imagen.php');
    require_once('../../controllers/PresupuestosDepartamentosController.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                $presupuestoDepartamento = new PresupuestosController();
                $presupuestoDepartamento->listarInformacionPresupuestosDepartamentos();
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