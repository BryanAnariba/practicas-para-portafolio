<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/control-estudiantes-docentes.php');
    
    switch ($_SERVER['REQUEST_METHOD']) {
        case "POST": 
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                $respaldo = uniqid()."-".$_FILES["documentoSubido"]["name"];
                   
                $ruta1 = "../../uploads/documentoRespaldo/departamento/".$respaldo;
                if ($_POST['Trimestre'] && $_POST['numeroPoblacion'] && $_POST['respaldo'] && $_POST['poblacion'] && $_POST['idUsuario'] &&
                ($_FILES['documentoSubido']['type']==="image/jpg" || $_FILES['documentoSubido']['type']==="image/jpeg" ||  $_FILES['documentoSubido']['type']==="image/png")
                ) {
                    if (move_uploaded_file($_FILES["documentoSubido"]["tmp_name"], $ruta1)){
                        $EstudianteDocente = new EstudianteDocenteController();
    
                        $EstudianteDocente->ingresarPoblacion(            
                            $_POST['Trimestre'], $_POST['numeroPoblacion'], $respaldo, $_POST['poblacion'],$_POST['idUsuario']
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