<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/ObjetosGastoController.php');
    
    switch ($_SERVER['REQUEST_METHOD']) {
        case "POST": 
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                if ($_POST['ObjetoDeGasto'] && $_POST['Abreviatura'] && $_POST['CodigoObjeto'] && $_POST['Estado']) {
                    $Objeto = new ObjetosController();
    
                    $Objeto->registrarObjeto(            
                        $_POST['ObjetoDeGasto'],
                        $_POST['Abreviatura'],
                        $_POST['CodigoObjeto'],
                        $_POST['Estado']
                    );
                }else {
                    $Objeto = new ObjetosController();
                    $Objeto->peticionNoValida();
                }
            } else {
                $objeto = new ObjetosController();
                $objeto->peticionNoAutorizada();
                require_once('../destruir-sesiones.php');
            }
        break;
        default: 
            $Objeto = new ObjetosController();
            $Objeto->peticionNoValida();
        break;
    }