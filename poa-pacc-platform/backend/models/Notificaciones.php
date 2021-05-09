<?php

    if (!isset($_SESSION)) {
        session_start();
    }

    require_once('../../config/config.php');    
    require_once('../../validators/validators.php');
    
    //require_once('../../models/Persona.php');
    //require_once('../../helpers/Email.php');

    require_once('../../database/Conexion.php');


    

    class Notificaciones {
        private $idUsuario; 
        private $idSolicitud; 
        private $idTipoSolicitud; 
        private $idEstadoSolicitud; 
        private $motivoSolicitud;
        private $edificioAsistencia;
        private $firmaDigital;
        private $documentoRespaldo;
        private $horaInicio;
        private $horaFin;
        private $fechaInicio;
        private $fechaFin;
        private $diasSolicitados;
        private $observaciones;
        private $nombreEmpleado;
        private $codigoEmpleado;
        private $departamento;

        private $conexionBD;
        private $consulta;
        private $idUltimaInsercion;
        //private $tablaBaseDatos;

        public function getIdUsuario(){
            return $this->idUsuario;
        }
 
        public function setIdUsuario($idUsuario){
            $this->idUsuario = $idUsuario;
            return $this;
        }

        public function getIdSolicitud(){
            return $this->idSolicitud;
        }        

        public function setIdSolicitud($idSolicitud){
            $this->idSolicitud = $idSolicitud;
            return $this;
        }

        public function getIdTipoSolicitud(){
            return $this->idTipoSolicitud;
        }
        
        public function setIdTipoSolicitud($idTipoSolicitud){
            $this->idTipoSolicitud = $idTipoSolicitud;
            return $this;
        }

        public function getIdEstadoSolicitud(){
            return $this->idEstadoSolicitud;

        }

        public function setIdEstadoSolicitud($idEstadoSolicitud){
            $this->idEstadoSolicitud = $idEstadoSolicitud;
            return $this;
        }

        public function getMotivoSolicitud(){
            return $this->motivoSolicitud;
        }
        
        public function setMotivoSolicitud($motivoSolicitud){
            $this->motivoSolicitud = $motivoSolicitud;
            return $this;
        }

        public function getEdificioAsistencia(){
            return $this->edificioAsistencia;
        }

        public function setEdificioAsistencia($edificioAsistencia){
            $this->edificioAsistencia = $edificioAsistencia;
            return $this;
        }
        
        public function getFirmaDigital(){
            return $this->firmaDigital;
        }

        public function setFirmaDigital($firmaDigital){
            $this->firmaDigital = $firmaDigital;
            return $this;
        }

        public function getDocumentoRespaldo(){
            return $this->documentoRespaldo;
        }

        public function setDocumentoRespaldo($documentoRespaldo){
            $this->documentoRespaldo = $documentoRespaldo;
            return $this;
        }

        public function getHoraInicio(){
            return $this->horaInicio;
        }

        public function setHoraInicio($horaInicio){
            $this->horaInicio = $horaInicio;
            return $this;
        }

        public function getHoraFin(){
            return $this->horaFin;
        }

        public function setHoraFin($horaFin){
            $this->horaFin = $horaFin;
            return $this;
        }

        public function getFechaInicio(){
            return $this->fechaInicio;
        }

        public function setFechaInicio($fechaInicio){
            $this->fechaInicio = $fechaInicio;
            return $this;
        }

        public function getFechaFin(){
            return $this->fechaFin;
        }

        public function setFechaFin($fechaFin){
            $this->fechaFin = $fechaFin;
            return $this;
        }

        public function getDiasSolicitados(){
            return $this->diasSolicitados;
        }

        public function setDiasSolicitados($diasSolicitados){
            $this->diasSolicitados = $diasSolicitados;
            return $this;
        }
        
        public function getObservaciones(){
            return $this->observaciones;
        }

        public function setObsevaciones($observaciones){
            $this->observaciones = $observaciones;
            return $this;
        }

        public function getNombreEmpleado(){
            return $this->nombreEmpleado;
        }

        public function setNombreEmpleado($nombreEmpleado){
            $this->nombreEmpleado = $nombreEmpleado;
            return $this;
        }

        public function getCodigoEmpleado(){
            return $this->codigoEmpleado;
        }

        public function setCodigoEmpleado($codigoEmpleado){
            $this->codigoEmpleado = $codigoEmpleado;
            return $this;
        }

        public function getDepartamento(){
            return $this->departamento;
        }

        public function setDepartamento($departamento){
            $this->departamento = $departamento;
            return $this;
        }
        

        
        //verNotificacionSecAcademica
        public function verNotificacionSecAcademica() {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("SELECT COUNT(A.idSolicitudSalida) as cantidadSolicitudes
                                                FROM EstadoSolicitudSalida A
                                                INNER JOIN SolicitudSalida B
                                                ON (A.idTipoEstadoSolicitud = 1 AND
                                                A.idSolicitudSalida = B.idSolicitud)
                                                INNER JOIN Usuario C
                                                ON (B.idPersonaUsuario = C.idPersonaUsuario AND
                                                    C.idTipoUsuario = 6)"); 
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar la observacion enviadas')
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

       
        
        //verNotificacionJefes
        public function verNotificacionJefes() {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("SELECT COUNT(A.idSolicitudSalida) as cantidadSolicitudes
                                                FROM EstadoSolicitudSalida A
                                                INNER JOIN SolicitudSalida B
                                                ON (A.idTipoEstadoSolicitud = 1 AND
                                                A.idSolicitudSalida = B.idSolicitud)
                                                INNER JOIN Usuario C
                                                ON (B.idPersonaUsuario = C.idPersonaUsuario AND
                                                    C.idTipoUsuario = 3 AND
                                                    C.idDepartamento = {$_SESSION['idDepartamento']})"); 

                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar la observacion enviadas')
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


        
        //verNotificacionDecano
        public function verNotificacionDecano() {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("SELECT COUNT(A.idSolicitudSalida) as cantidadSolicitudes
                                                FROM EstadoSolicitudSalida A
                                                INNER JOIN SolicitudSalida B
                                                ON (A.idTipoEstadoSolicitud = 1 AND
                                                A.idSolicitudSalida = B.idSolicitud)
                                                INNER JOIN Usuario C
                                                ON (B.idPersonaUsuario = C.idPersonaUsuario AND
                                                    C.idTipoUsuario = 5)"); 
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar la observacion enviadas')
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

        

        //verNotificacionEstratega
        public function verNotificacionEstratega() {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("SELECT COUNT(idInforme) as cantidadInformes
                                                FROM Informe
                                                WHERE idEstadoInforme = 1"); 
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar la observacion enviadas')
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
        

        

        //verNotificacionCoordinador
        public function verNotificacionCoordinador() {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("SELECT COUNT(A.idActividad) as cantidadActividadesPendientes
                                                FROM Actividad A
                                                INNER JOIN Usuario B
                                                ON (A.idEstadoActividad = 1 AND
                                                    A.idPersonaUsuario = B.idPersonaUsuario AND
                                                    A.idPersonaUsuario = {$_SESSION['idUsuario']} AND
                                                        B.idDepartamento = {$_SESSION['idDepartamento']})"); 
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar la observacion enviadas')
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
        

        //verNotificacionActividadesJefes
        public function verNotificacionActividadesJefes() {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("SELECT COUNT(A.idActividad) as cantidadActividadesPendientes
                                                FROM Actividad A
                                                INNER JOIN Usuario B
                                                ON (A.idEstadoActividad = 1 AND
                                                	A.idPersonaUsuario = B.idPersonaUsuario AND
                                                    A.idPersonaUsuario = {$_SESSION['idUsuario']} AND
                                               	 	B.idDepartamento = {$_SESSION['idDepartamento']})"); 
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar la observacion enviadas')
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