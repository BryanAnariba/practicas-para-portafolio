<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/EstadoDCDUOAOController.php');

    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                $estado = new EstadoDCDUOAOController();
                $estado->listarEstados();
            } else {
                $estado = new EstadoDCDUOAOController();
                $estado->peticionNoAutorizada();
                require_once('../destruir-sesiones.php');
            }
        break;
        default: 
            $estado = new EstadoDCDUOAOController();
            $estado->peticionNoValida();
        break;
    }
?>