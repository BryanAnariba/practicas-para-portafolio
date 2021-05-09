<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/control-estudiantes-docentes.php');
    
    switch ($_SERVER['REQUEST_METHOD']) {
        case "POST": 
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                $respaldo = uniqid()."-".$_FILES["documentoSubidoM"]["name"];
                   
                $ruta1 = "../../uploads/documentoRespaldo/departamento/".$respaldo;
                if($_POST['idGestion'] && $_FILES['documentoSubidoM']['type']==="image/jpg" || $_FILES['documentoSubidoM']['type']==="image/jpeg" ||  
                    $_FILES['documentoSubidoM']['type']==="image/png")
                {
                    if (move_uploaded_file($_FILES["documentoSubidoM"]["tmp_name"], $ruta1)){
                        $EstudianteDocente = new EstudianteDocenteController();
    
                        $EstudianteDocente->modificarRespaldo(            
                            $_POST['idGestion'],$respaldo
                        );
                    }
                }else {
                    $EstudianteDocente = new EstudianteDocenteController();
                    $EstudianteDocente->peticionNoValida();
                }
            } else {
                $EstudianteDocente = new EstudianteDocenteController();
                $EstudianteDocente->peticionNoAutorizada();
                require_once('../destruir-sesiones.php');
            }
        break;
        default: 
            $EstudianteDocente = new EstudianteDocenteController();
            $EstudianteDocente->peticionNoValida();
        break;
    }
?>