<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/ActividadesController.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST': 
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                $_POST = json_decode(file_get_contents('php://input'), true);
                if (
                    isset($_POST['actividad']) &&
                    !empty($_POST['actividad']) &&
                    isset($_POST['correlativoActividad']) &&
                    !empty($_POST['correlativoActividad']) &&
                    isset($_POST['idAreaEstrategica']) &&
                    !empty($_POST['idAreaEstrategica']) &&
                    isset($_POST['idDimension']) &&
                    !empty($_POST['idDimension']) &&
                    isset($_POST['idObjetivoInstitucional']) &&
                    !empty($_POST['idObjetivoInstitucional']) &&
                    isset($_POST['idResultadoInstitucional']) &&
                    !empty($_POST['idResultadoInstitucional']) &&
                    isset($_POST['indicadoresResultado']) &&
                    !empty($_POST['indicadoresResultado']) &&
                    isset($_POST['justificacionActividad']) &&
                    !empty($_POST['justificacionActividad']) &&
                    isset($_POST['medioVerificacionActividad']) &&
                    !empty($_POST['medioVerificacionActividad']) &&
                    isset($_POST['poblacionObjetivoActividad']) &&
                    !empty($_POST['poblacionObjetivoActividad']) &&
                    isset($_POST['resultadosUnidad']) &&
                    !empty($_POST['resultadosUnidad']) &&
                    isset($_POST['responsableActividad']) &&
                    !empty($_POST['responsableActividad']) &&
                    isset($_POST['costoTotal']) &&
                    isset($_POST['porcentajeTrimestre1']) &&
                    isset($_POST['porcentajeTrimestre2']) &&
                    isset($_POST['porcentajeTrimestre3']) &&
                    isset($_POST['porcentajeTrimestre4'])
                ) {
                    $actividades = new ActividadesController();
                    $actividades->insertarNuevaActividad(
                        $_POST['actividad'], 
                        $_POST['correlativoActividad'], 
                        $_POST['idAreaEstrategica'],
                        $_POST['idDimension'],
                        $_POST['idObjetivoInstitucional'],
                        $_POST['idResultadoInstitucional'],
                        $_POST['indicadoresResultado'],
                        $_POST['justificacionActividad'],
                        $_POST['medioVerificacionActividad'],
                        $_POST['poblacionObjetivoActividad'],
                        $_POST['resultadosUnidad'],
                        $_POST['responsableActividad'],
                        $_POST['costoTotal'],
                        $_POST['porcentajeTrimestre1'],
                        $_POST['porcentajeTrimestre2'],
                        $_POST['porcentajeTrimestre3'],
                        $_POST['porcentajeTrimestre4']
                    );
                } else {
                    $actividades = new ActividadesController();
                    $actividades->peticionNoValida();
                }
            } else {
                $actividades = new ActividadesController();
                $actividades->peticionNoAutorizada();
                require_once('../destruir-sesiones.php');
            }
        break;
        default: 
            $actividades = new ActividadesController();
            $actividades->peticionNoValida();
        break;
    }
?>