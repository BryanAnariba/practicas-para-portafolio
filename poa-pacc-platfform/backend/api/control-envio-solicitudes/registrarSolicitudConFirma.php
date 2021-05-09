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
                    $firma = uniqid()."-".$_FILES["firmaDigital"]["name"];
                    $ruta2 = "../../uploads/images/envioSolicitudesPermisos/".$firma;
                    if(($_FILES['firmaDigital']['type']==="image/jpg" || $_FILES['firmaDigital']['type']==="image/jpeg" ||  $_FILES['firmaDigital']['type']==="image/png") 
                        ){
                        if (move_uploaded_file($_FILES["firmaDigital"]["tmp_name"], $ruta2)){
                            $permisos = new EnvioSolicitudesController();
                            $permisos->registrarSolicitudConFirma($idUsuario,
                                                                        $motivoPermiso,
                                                                        $edificioAsistencia,
                                                                        $fechaInicio,
                                                                        $fechaFin,
                                                                        $horaInicio,
                                                                        $horaFin,                                       
                                                                        $firma
                                                                    );                                                                    
                        }
                        else {
                            $permisos = new EnvioSolicitudesController();
                            $permisos->peticionNoValida();
                        }

                    }else{
                        echo json_encode(
                            array('status' => http_response_code(400), 'data' => 
                            array('message' => 'Ha ocurrido un error, la extension de imagen adjunta como firma digital no es valida, verifique que sea alguno de los formatos validos jpg, jpeg o png')));
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