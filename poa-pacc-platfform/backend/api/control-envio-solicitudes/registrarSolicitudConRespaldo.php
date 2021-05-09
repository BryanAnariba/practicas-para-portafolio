<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/EnvioSolicitudesController.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
           // $_POST = json_decode(file_get_contents('php://input'), true);
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                    $idUsuario = $_POST['R-idUsuario'];
                    $motivoPermiso=$_POST['R-motivoPermiso'];
                    $edificioAsistencia=$_POST['R-edificioAsistencia'];
                    $fechaInicio=$_POST['R-fechaInicio'];
                    $fechaFin=$_POST['R-fechaFin'];
                    $horaInicio=$_POST['R-horaInicio'];
                    $horaFin=$_POST['R-horaFin'];


                    
                    $respaldo = uniqid()."-".$_FILES["respaldo"]["name"];
                   
                    $ruta1 = "../../uploads/images/envioSolicitudesPermisos/".$respaldo;
                    if(($_FILES['respaldo']['type']==="image/jpg" || $_FILES['respaldo']['type']==="image/jpeg" ||  $_FILES['respaldo']['type']==="image/png") 
                        ){
                        if (move_uploaded_file($_FILES["respaldo"]["tmp_name"], $ruta1)){
                            $permisos = new EnvioSolicitudesController();
                            $permisos->registrarSolicitudConRespaldo($idUsuario,
                                                                        $motivoPermiso,
                                                                        $edificioAsistencia,
                                                                        $fechaInicio,
                                                                        $fechaFin,
                                                                        $horaInicio,
                                                                        $horaFin,                                       
                                                                        $respaldo
                                                                    );                                                                    
                        }
                        else {
                            $permisos = new EnvioSolicitudesController();
                            $permisos->peticionNoValida();
                        }

                    }else{
                        echo json_encode(
                            array('status' => http_response_code(400), 'data' => 
                            array('message' => 'Ha ocurrido un error, la extension de imagen adjunta como respaldo  no es valida, verifique que sea alguno de los formatos validos jpg, jpeg o png')));
                    }

                    
            } else {
                $permisos = new EnvioSolicitudesController();
                $permisos->peticionNoAutorizada();
                require_once('../destruir-sesiones.php');
            }
        break;
        default: 
            $permisos = new EnvioSolicitudesController();
            $permisos->peticionNoValida();
        break;
    }
?>