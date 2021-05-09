<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/DepartamentoController.php');
    
    switch ($_SERVER['REQUEST_METHOD']) {
        case "POST": 
            //$_POST = json_decode(file_get_contents('php://input'));
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                if ($_POST['idDepartamentoM'] && 
                    $_POST['nombreDepartamentoM'] && 
                    $_POST['estadoDepartamentoM'] && 
                    $_POST['abreviaturaDepartamentoM'] && 
                    $_POST['telefonoDepartamentoM'] && 
                    $_POST['correoDepartamentoM']) {
                    $Departamentos = new DepartamentoController();
                    
                    $Departamentos->modificarDepartamento(
                        $_POST['idDepartamentoM'],
                        $_POST['nombreDepartamentoM'],
                        $_POST['estadoDepartamentoM'],
                        $_POST['abreviaturaDepartamentoM'],
                        $_POST['telefonoDepartamentoM'],
                        $_POST['correoDepartamentoM'],
                    );
                }else {
                    $Departamentos = new DepartamentoController();
                    $Departamentos->peticionNoValida();
                }
            } else {
                $permisos = new DepartamentoController();
                $permisos->peticionNoAutorizada();
                require_once('../destruir-sesiones.php');
            }
        break;
        default: 
            $Departamentos = new DepartamentoController();
            $Departamentos->peticionNoValida();
        break;
    }
?>