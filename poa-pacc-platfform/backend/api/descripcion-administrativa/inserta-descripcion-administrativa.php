<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/DescripcionAdministrativaController.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $_POST = json_decode(file_get_contents('php://input'),true);
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                if (
                    isset($_POST['idObjetoGasto']) && 
                    !empty($_POST['idObjetoGasto']) &&
                    isset($_POST['idTipoPresupuesto']) && 
                    !empty($_POST['idTipoPresupuesto']) &&
                    isset($_POST['idActividad']) && 
                    !empty($_POST['idActividad']) &&
                    isset($_POST['idDimension']) && 
                    !empty($_POST['idDimension']) &&
                    isset($_POST['nombreActividad']) &&
                    !empty($_POST['nombreActividad']) &&
                    isset($_POST['cantidad']) && 
                    isset($_POST['costo']) && 
                    isset($_POST['costoTotal']) && 
                    isset($_POST['mesRequerido']) && 
                    isset($_POST['descripcion'])
                ) {
                    $descripcion = new DescripcionAdministrativaController();
                    $descripcion->insertaNuevaDescripcionAdministrativa($_POST['idObjetoGasto'], $_POST['idTipoPresupuesto'], $_POST['idActividad'], $_POST['idDimension'], $_POST['nombreActividad'], $_POST['cantidad'], $_POST['costo'], $_POST['costoTotal'], $_POST['mesRequerido'], $_POST['descripcion'], $_POST['unidadDeMedida']);
                } else {
                    $descripcion = new DescripcionAdministrativaController();
                    $descripcion->peticionNoValida();    
                }
            } else {
                $descripcion = new DescripcionAdministrativaController();
                $descripcion->peticionNoAutorizada();
                require_once('../destruir-sesiones.php');
            }
        break;
        default: 
            $descripcion = new DescripcionAdministrativaController();
            $descripcion->peticionNoValida();
        break;
    }
?>