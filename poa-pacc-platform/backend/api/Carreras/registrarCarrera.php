<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/CarrerasController.php');
    
    switch ($_SERVER['REQUEST_METHOD']) {
        case "POST": 
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                if ($_POST['Carrera'] && $_POST['Abreviatura'] && $_POST['Departamento'] && $_POST['Estado']) {
                    $carrera = new CarrerasController();
    
                    $carrera->registrarCarrera(            
                        $_POST['Carrera'], $_POST['Abreviatura'], $_POST['Departamento'], $_POST['Estado']
                    );
                }else {
                    $carreras = new CarrerasController();
                    $carreras->peticionNoValida();
                }
            } else {
                $carreras = new CarrerasController();
                $carreras->peticionNoAutorizada();
                require_once('../destruir-sesiones.php');
            }
        break;
        default: 
            $carreras = new CarrerasController();
            $carreras->peticionNoValida();
        break;
    }