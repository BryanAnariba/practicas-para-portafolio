<?php
    if (!isset($_SESSION)) {
        session_start();
    }

    require_once('../../config/config.php');    
    require_once('../../validators/validators.php');
    require_once('../../database/Conexion.php');

    class RecibirInformes {
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
                                                        C.nombrePersona as nombrePersonaEnvia,
                                                        C.apellidoPersona as apellidoPersonaEnvia,
                                                        D.nombrePersona as nombrePersonaAprueba,
                                                        D.apellidoPersona as apellidoPersonaAprueba
                                                FROM Informe A
                                                INNER JOIN EstadoInforme B
                                                ON (A.idEstadoInforme = 2 AND
                                                    A.idEstadoInforme = B.idEstadoInforme)	
                                                INNER JOIN Persona C
                                                ON (A.idPersonaUsuarioEnvia = C.idPersona)	
                                                INNER JOIN Persona D
                                                ON (A.idPersonaUsuarioAprueba = D.idPersona)	
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
                                                        B.Estado,
                                                        C.nombrePersona,
                                                        C.apellidoPersona
                                                FROM Informe A
                                                INNER JOIN EstadoInforme B
                                                ON (A.idEstadoInforme = 1 AND
                                                    A.idEstadoInforme = B.idEstadoInforme)	
                                                INNER JOIN Persona C
                                                ON (A.idPersonaUsuarioEnvia = C.idPersona)	
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


        
        //modificaEstadoInforme
        public function modificaEstadoInforme() {
            try {
                $fechaActual = date('Y-m-d');
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $this->consulta->prepare("
                    set @persona = {$_SESSION['idUsuario']};
                ")->execute();
                $stmt = $this->consulta->prepare("UPDATE Informe 
                                                SET idEstadoInforme = 2,
                                                    idPersonaUsuarioAprueba = {$_SESSION['idUsuario']},
                                                    fechaAprobado = '$fechaActual'
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







    }
?>