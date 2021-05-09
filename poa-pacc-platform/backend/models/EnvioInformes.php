<?php
    if (!isset($_SESSION)) {
        session_start();
    }

    require_once('../../config/config.php');    
    require_once('../../validators/validators.php');
    require_once('../../database/Conexion.php');
    require_once('../../helpers/notificacionesEmail.php');

    class EnvioInformes {
        private $idInforme;
        private $tituloInforme; 
        private $descripcionInforme; 
        private $informe; 
        
        private $conexionBD;
        private $consulta;
        //private $tablaBaseDatos;

        public function getIdInforme(){
            return $this->idInforme;
        }
 
        public function setIdInforme($idInforme){
            $this->idInforme = $idInforme;
            return $this;
        }

        public function getTituloInforme(){
            return $this->tituloInforme;
        }
 
        public function setTituloInforme($tituloInforme){
            $this->tituloInforme = $tituloInforme;
            return $this;
        }

        public function getDescripcionInforme(){
            return $this->descripcionInforme;
        }        

        public function setDescripcionInforme($descripcionInforme){
            $this->descripcionInforme = $descripcionInforme;
            return $this;
        }

        public function getInforme(){
            return $this->informe;
        }
        
        public function setInforme($informe){
            $this->informe = $informe;
            return $this;
        }



        //registrarInforme
        public function registrarInforme() {
            if (
                campoTexto($this->tituloInforme,1,150) &&                  
                campoTexto($this->descripcionInforme,1,255)) {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $this->consulta->prepare("
                    set @persona = {$_SESSION['idUsuario']};
                ")->execute();

                try {
                    $fechaActual = date('Y-m-d');
                    $this->conexionBD = new Conexion();
                    $this->consulta = $this->conexionBD->connect();
                    $this->consulta->prepare("
                        set @persona = {$_SESSION['idUsuario']};
                    ")->execute();
                    $stmt = $this->consulta->prepare("INSERT INTO Informe
                                                        (idPersonaUsuarioEnvia, 
                                                            idPersonaUsuarioAprueba,
                                                            idEstadoInforme, 
                                                            tituloInforme, 
                                                            informe,
                                                            descripcionInforme, 
                                                            fechaRecibido,
                                                            fechaAprobado
                                                        ) 
                                                        VALUES ({$_SESSION['idUsuario']}, 
                                                                NULL, 
                                                                1,
                                                                '$this->tituloInforme', 
                                                                '$this->informe', 
                                                                '$this->descripcionInforme', 
                                                                '$fechaActual',
                                                                NULL
                                                                )"
                                                    );
                    if ($stmt->execute()) {
                        // Hasta este punto los informes ya  han sido registrados si todo es correcto
                        //Luego se procede a notificar via correo de la existencia de nuevas informes 
                        //Para ello se manda a llamar la función que envia correos segun cada tipo de usuario
                        $this->envioInformesModel = new EnvioInformes();    
                        $this->data = $this->envioInformesModel->notificarEnvioInformes(); 

                        return array(
                            'status' => SUCCESS_REQUEST,
                            'data' => array('message' => 'Informe registrado con exito')
                        );
                    } else {
                        return array(
                            'status'=> BAD_REQUEST,
                            'data' => array('error' => 'Ha ocurrido un error al insertar el informe')
                        );
                    }
                } catch (PDOException $ex) {
                    return array(
                        'status'=> INTERNAL_SERVER_ERROR,
                        'data' => array('message' => array($ex->getMessage()))
                    );
                } finally {
                    $this->conexionBD = null;
                }

            } else {
                return array(
                    'status'=> BAD_REQUEST,
                    'data' => array('message' => 'Ha ocurrido un error, los datos insertados son erroneos')
                );
            }
        }




        //listarInformesAprobados
        public function listarInformesAprobados () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("SELECT A.idInforme,
                                                        A.idEstadoInforme,
                                                        A.tituloInforme,
                                                        DATE_FORMAT(A.fechaRecibido,'%d-%m-%Y') as fechaRecibido,
                                                        DATE_FORMAT(A.fechaAprobado,'%d-%m-%Y') as fechaAprobado,
                                                        B.Estado,
                                                        C.nombrePersona,
                                                        C.apellidoPersona
                                                FROM Informe A
                                                INNER JOIN EstadoInforme B
                                                ON (A.idEstadoInforme = 2 AND
                                                    A.idEstadoInforme = B.idEstadoInforme AND
                                                    A.IdPersonaUsuarioEnvia = {$_SESSION['idUsuario']})	
                                                INNER JOIN Persona C
                                                ON (A.idPersonaUsuarioAprueba = C.idPersona)	
                                                ORDER BY A.idInforme DESC"); 
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar los imformes aprobados')
                    );
                }
            } catch (PDOException $ex) {
                return array(
                    'status'=> INTERNAL_SERVER_ERROR,
                    'data' => array('message' => $ex->getMessage())
                );
            } finally {
                $this->conexionBD = null;
            }
        }




        //listarInformesPendientes
        public function listarInformesPendientes () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("SELECT A.idInforme,
                                                        A.idEstadoInforme,
                                                        A.tituloInforme,
                                                        DATE_FORMAT(A.fechaRecibido,'%d-%m-%Y') as fechaRecibido,
                                                        B.Estado
                                                FROM Informe A
                                                INNER JOIN EstadoInforme B
                                                ON (A.idEstadoInforme = 1 AND
                                                    A.idEstadoInforme = B.idEstadoInforme AND
                                                    A.IdPersonaUsuarioEnvia = {$_SESSION['idUsuario']})	
                                                ORDER BY A.idInforme DESC"); 
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar los imformes pendientes')
                    );
                }
            } catch (PDOException $ex) {
                return array(
                    'status'=> INTERNAL_SERVER_ERROR,
                    'data' => array('message' => $ex->getMessage())
                );
            } finally {
                $this->conexionBD = null;
            }
        }




        //ObtenerDescripcionPorId
        public function ObtenerDescripcionPorId() {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("SELECT descripcionInforme
                                                FROM Informe
                                                WHERE idInforme = $this->idInforme"); 
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar la descripción del informe')
                    );
                }
            } catch (PDOException $ex) {
                return array(
                    'status'=> INTERNAL_SERVER_ERROR,
                    'data' => array('message' => $ex->getMessage())
                );
            } finally {
                $this->conexionBD = null;
            }
        }





        //verInformePorId
        public function verInformePorId() {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("SELECT informe 
                                                FROM Informe 
                                                WHERE idInforme = $this->idInforme"); 
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar el informe adjunto')
                    );
                }
            } catch (PDOException $ex) {
                return array(
                    'status'=> INTERNAL_SERVER_ERROR,
                    'data' => array('message' => $ex->getMessage())
                );
            } finally {
                $this->conexionBD = null;
            }
        }



        //Función para notificar via correo sobre nuevos informes en el sistema
        public function notificarEnvioInformes() {
            try {

                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $obtenerCorreoEstratega = $this->consulta->prepare("SELECT correoInstitucional
                                                                        FROM Usuario
                                                                        WHERE idTipoUsuario = 5 AND
                                                                            idEstadoUsuario = 1");
                $obtenerCorreoEstratega->execute(); 
                $correoEstratega = $obtenerCorreoEstratega->fetch();
                if($correoEstratega != false){
                    $correoEstratega = $correoEstratega[0];
                    //echo $correoEstratega;

                    $email = new notificacionesEmail();
                    $email->setEmailDestino($correoEstratega);
                    $email->setHeaderMensaje('Sistema POA PACC: Nuevas Notificaciones del Sistema');
                    $email->setTituloMensaje('Tiene Informes pendientes de revisar');
                    $email->setContenido('Acceda al sistema con sus credenciales para revisar los nuevos informes recibidos');
                    $email->notificarViaCorreo(); 
                }  
            

            } catch (PDOException $ex) {
                return array(
                    'status'=> INTERNAL_SERVER_ERROR,
                    'data' => array('message' => $ex->getMessage())
                );
            } finally {
                $this->conexionBD = null;
            }
        }



    }
?>