<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/UsuariosController.php');
    
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST': 
            $_POST = json_decode(file_get_contents('php://input'), true);
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                if (isset($_POST['nombrePersona']) && isset($_POST['apellidoPersona']) && isset($_POST['codigoEmpleado']) && isset($_POST['fechaNacimiento']) && isset($_POST['idDepartamento']) && isset($_POST['idTipoUsuario']) && isset($_POST['correoInstitucional']) && isset($_POST['nombreUsuario']) && isset($_POST['idPais']) && isset($_POST['idDepartamentoPais']) && isset($_POST['idMunicipioCiudad']) && isset($_POST['nombreLugar'])) {
                    $usuario = new UsuariosController();
                    $usuario->registrarUsuario($_POST['nombrePersona'],$_POST['apellidoPersona'],$_POST['codigoEmpleado'],$_POST['fechaNacimiento'],$_POST['idDepartamento'],$_POST['idTipoUsuario'],$_POST['correoInstitucional'],$_POST['nombreUsuario'],$_POST['idPais'],$_POST['idDepartamentoPais'],$_POST['idMunicipioCiudad'],$_POST['nombreLugar']);
                } else {
                    $usuario = new UsuariosController();
                    $usuario->peticionNoValida();
                }
            } else {
                $usuario = new UsuariosController();
                $usuario->peticionNoAutorizada();
                require_once('../destruir-sesiones.php');
            }
        break;
        default: 
            $usuario = new UsuariosController();
            $usuario->peticionNoValida();
        break;
    }
?>