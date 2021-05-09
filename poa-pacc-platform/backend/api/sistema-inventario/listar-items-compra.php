<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    require_once('../request-headers.php');
    require_once('../../controllers/DescripcionAdministrativaController.php');
    $_POST = json_decode(file_get_contents('php://input'), true);
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            if (
                !isset($_POST['idObjetoGasto']) && 
                !isset($_POST['idDepartamento']) && 
                isset($_POST['anioPlanificacion']) && 
                !empty($_POST['anioPlanificacion'])
                ) {
                $descripcion = new DescripcionAdministrativaController();
                $descripcion->getDataTotalParaCompraInventario($_POST['anioPlanificacion'], $_POST['valorInicial'], $_POST['take']);
            } else {
                switch ($_POST) {
                    case (
                        isset($_POST['idObjetoGasto']) && 
                        !empty($_POST['idObjetoGasto'])  &&
                        !isset($_POST['idDepartamento']) &&
                        isset($_POST['anioPlanificacion']) && 
                        !empty($_POST['anioPlanificacion'])
                    ):
                    $descripcion = new DescripcionAdministrativaController();
                    $descripcion->getDataParaCompraInventarioPorObjeto($_POST['anioPlanificacion'], $_POST['valorInicial'], $_POST['take'], $_POST['idObjetoGasto']);
                    break;
                    case (
                        isset($_POST['idDepartamento']) && 
                        !empty($_POST['idDepartamento']) &&
                        !isset($_POST['idObjetoGasto']) &&
                        isset($_POST['anioPlanificacion']) && 
                        !empty($_POST['anioPlanificacion'])
                    ):
                        $descripcion = new DescripcionAdministrativaController();
                        $descripcion->getDataParaCompraInventarioPorDepto($_POST['anioPlanificacion'], $_POST['valorInicial'], $_POST['take'], $_POST['idDepartamento']);    
                    break;
                    case (
                        isset($_POST['idDepartamento']) && 
                        !empty($_POST['idDepartamento']) &&
                        isset($_POST['idObjetoGasto']) && 
                        !empty($_POST['idObjetoGasto']) &&
                        isset($_POST['anioPlanificacion']) && 
                        !empty($_POST['anioPlanificacion'])
                    ):
                        $descripcion = new DescripcionAdministrativaController();
                        $descripcion->getDataParaCompraInventarioPorObjetoDepto($_POST['anioPlanificacion'], $_POST['valorInicial'], $_POST['take'], $_POST['idObjetoGasto'],$_POST['idDepartamento']);
                    break;
                    default: 
                        $descripcion = new  DescripcionAdministrativaController();
                        $descripcion->peticionNoValida();
                    break;
                }
            }
        break;
        default: 
            $descripcion = new  DescripcionAdministrativaController();
            $descripcion->peticionNoValida();
        break;
    }
?>