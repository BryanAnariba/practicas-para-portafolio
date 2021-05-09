<?php
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/EnvioInformesController.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
           // $_POST = json_decode(file_get_contents('php://input'), true);
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                    $tituloInforme = $_POST['R-tituloInforme'];
                    $descripcionInforme = $_POST['R-descripcionInforme'];
                    
                    $informe = uniqid()."-".$_FILES["informe"]["name"];
                   
                    $ruta1 = "../../uploads/documentosSubidos/envioInformes/".$informe;
                    if(($_FILES['informe']['type']==="application/pdf") || ($_FILES['informe']['type']==="application/vnd.openxmlformats-officedocument.wordprocessingml.document")  
                       ){
                        if (move_uploaded_file($_FILES["informe"]["tmp_name"], $ruta1)){
                            $permisos = new EnvioInformesController();
                            $permisos->registrarInforme($tituloInforme,
                                                        $descripcionInforme,                                     
                                                        $informe
                                                    );                                                                    
                        }
                        else {
                            $permisos = new EnvioInformesController();
                            $permisos->peticionNoValida();
                        }

                    }else{
                        echo json_encode(
                           array('status' => http_response_code(400), 'data' => 
                           array('message' => 'Ha ocurrido un error, la extensión del documento adjunto como informe  no es valido, verifique que sea un formato valido como pdf o word.docx')));
                    }

                    
            } else {
                $permisos = new EnvioInformesController();
                $permisos->peticionNoAutorizada();
                require_once('../destruir-sesiones.php');
            }
        break;
        default: 
            $permisos = new EnvioInformesController();
            $permisos->peticionNoValida();
        break;
    }
?>