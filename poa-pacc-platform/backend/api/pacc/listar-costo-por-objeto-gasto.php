<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/PaccController.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                $_POST = json_decode(file_get_contents('php://input'), true);
                if (
                    isset($_POST['idPresupuesto']) &&
                    !empty($_POST['idPresupuesto']) &&
                    isset($_POST['idObjetoGasto']) &&
                    !empty($_POST['idObjetoGasto'])
                ) {
                    $pacc = new PaccController();
                    $pacc->generaCostoPorObjetoGasto($_POST['idPresupuesto'], $_POST['idObjetoGasto']);
                } else {
                    $pacc = new PaccController();
                    $pacc->peticionNoValida();
                }
                
            } else {
                $pacc = new PaccController();
                $pacc->peticionNoAutorizada();
                require_once('../destruir-sesiones.php');
            }
        break;
        default: 
            $pacc = new PaccController();
            $pacc->peticionNoValida();
        break;
    }
?>