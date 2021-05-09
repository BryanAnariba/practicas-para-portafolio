<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/TipoPresupuestoController.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                $tipoPresupuesto = new TipoPresupuestoController();
                $tipoPresupuesto->listarTiposPresupuestos();
            } else {
                $tipoPresupuesto = new TipoPresupuestoController();
                $tipoPresupuesto->peticionNoAutorizada();
                require_once('../destruir-sesiones.php');
            }
        break;
        default: 
            $tipoPresupuesto = new TipoPresupuestoController();
            $tipoPresupuesto->peticionNoValida();
        break;
    }
?>