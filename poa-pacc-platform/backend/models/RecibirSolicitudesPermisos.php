<?php
    if (!isset($_SESSION)) {
        session_start();
    }
    require_once('../../config/config.php');    
    require_once('../../validators/validators.php');
    require_once('../../database/Conexion.php');

    class RecibirSolicitudesPermisos{
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
        private $idDepartamento;

        private $conexionBD;
        private $consulta;
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

        public function setObservaciones($observaciones){
            $this->observaciones = $observaciones;
            return $this;
        }

        public function getIdDepartamento(){
            return $this->idDepartamento;
        }

        public function setIdDepartamento($idDepartamento){
            $this->idDepartamento = $idDepartamento;
            return $this;
        }
        

        //Función para ver solicitudes de permisos recibidas, segun el id de usuario de la sesión activa
        //cuando el usuario activo es de tipo secretaria academica se le listan solo las solicitudes de parte
        //de usuarios tipo administrativos
        public function getSolicitudesRecibidasSecAcademica () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("SELECT A.tipoSolicitudSalida,
                                                        B.idSolicitud,
                                                        DATE_FORMAT(B.fechaRegistroSolicitud,'%d-%m-%Y') as fechaRegistroSolicitud,
                                                        C.nombrePersona,
                                                        C.apellidoPersona,
                                                        D.idTipoEstadoSolicitud,
                                                        E.idTipoUsuario
                                                FROM TipoSolicitudSalida A
                                                INNER JOIN SolicitudSalida B
                                                ON (A.idTipoSolicitudSalida = B.idTipoSolicitud)		
                                                INNER JOIN Persona C
                                                ON(B.idPersonaUsuario = C.idPersona)
                                                INNER JOIN EstadoSolicitudSalida D
                                                ON(D.idTipoEstadoSolicitud = 1 AND
                                                    B.idSolicitud = D.idSolicitudSalida)
                                                INNER JOIN Usuario E
                                                ON(E.idTipoUsuario = 6 AND
                                                    E.idPersonaUsuario = B.idPersonaUsuario)
                                                ORDER BY B.idSolicitud DESC"); 
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar sus solicitudes enviadas')
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


        //getIdDepartamentoJefeConCoordinador
        public function getIdDepartamentoJefeConCoordinador () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("SELECT idDepartamento
                                                FROM Usuario 
                                                WHERE idPersonaUsuario = $this->idUsuario"); 
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar sus solicitudes enviadas')
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



        //getSolicitudesRecibidasJefe
        //Función para ver solicitudes de permisos recibidas, segun el id de usuario de la sesión activa
        //cuando el usuario activo es el usuario de tipo jefe de departamento, se listan las solicitudes 
        //hechas por parte del usuario de tipo coordinador, especificamente solo al que pertenece al mismo
        // del usuario jefe
        public function getSolicitudesRecibidasJefe () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("SELECT A.tipoSolicitudSalida,
                                                        B.idSolicitud,
                                                        DATE_FORMAT(B.fechaRegistroSolicitud,'%d-%m-%Y') as fechaRegistroSolicitud,
                                                        C.nombrePersona,
                                                        C.apellidoPersona,
                                                        D.idTipoEstadoSolicitud,
                                                        E.idTipoUsuario
                                                FROM TipoSolicitudSalida A
                                                INNER JOIN SolicitudSalida B
                                                ON (A.idTipoSolicitudSalida = B.idTipoSolicitud)		
                                                INNER JOIN Persona C
                                                ON(B.idPersonaUsuario = C.idPersona)
                                                INNER JOIN EstadoSolicitudSalida D
                                                ON(D.idTipoEstadoSolicitud = 1 AND
                                                    B.idSolicitud = D.idSolicitudSalida)
                                                INNER JOIN Usuario E
                                                ON(E.idTipoUsuario = 3 AND
                                                    E.idPersonaUsuario = B.idPersonaUsuario AND
                                                    E.idDepartamento = $this->idDepartamento)
                                                ORDER BY B.idSolicitud DESC"); 
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar sus solicitudes enviadas')
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


        //getSolicitudesRecibidasDecano
        //Función para ver solicitudes de permisos recibidas, segun el id de usuario de la sesión activa
        //cuando el usuario activo es el usuario de tipo decano, se listan las solicitudes hechas solo
        //del usuario de tipo estratega
        public function getSolicitudesRecibidasDecano () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("SELECT A.tipoSolicitudSalida,
                                                        B.idSolicitud,
                                                        DATE_FORMAT(B.fechaRegistroSolicitud,'%d-%m-%Y') as fechaRegistroSolicitud,
                                                        C.nombrePersona,
                                                        C.apellidoPersona,
                                                        D.idTipoEstadoSolicitud,
                                                        E.idTipoUsuario
                                                FROM TipoSolicitudSalida A
                                                INNER JOIN SolicitudSalida B
                                                ON (A.idTipoSolicitudSalida = B.idTipoSolicitud)		
                                                INNER JOIN Persona C
                                                ON(B.idPersonaUsuario = C.idPersona)
                                                INNER JOIN EstadoSolicitudSalida D
                                                ON(D.idTipoEstadoSolicitud = 1 AND
                                                    B.idSolicitud = D.idSolicitudSalida)
                                                INNER JOIN Usuario E
                                                ON(E.idTipoUsuario = 5 AND
                                                    E.idPersonaUsuario = B.idPersonaUsuario)
                                                ORDER BY B.idSolicitud DESC"); 
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar sus solicitudes enviadas')
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


        // //Función para ver solicitudes de permisos revisadas, segun el id de usuario de la sesión activa
        public function getSolicitudesRevisadas () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("SELECT B.idSolicitud,
                                                        C.idTipoEstadoSolicitud,
                                                        DATE_FORMAT(C.fechaRevisionSolicitud,'%d-%m-%Y') as fechaRevisionSolicitud,
                                                        E.nombrePersona,
                                                        E.apellidoPersona,
                                                        A.tipoSolicitudSalida,
                                                        D.TipoEstadoSolicitudSalida 
                                                FROM TipoSolicitudSalida A
                                                INNER JOIN SolicitudSalida B
                                                ON (A.idTipoSolicitudSalida = B.idTipoSolicitud)
                                                INNER JOIN EstadoSolicitudSalida C
                                                ON (B.idSolicitud = C.idSolicitudSalida AND
                                                    C.idPersonaUsuarioVeedor = $this->idUsuario)
                                                INNER JOIN TipoEstadoSolicitudSalida D
                                                ON (C.idTipoEstadoSolicitud = D.idTipoEstadoSolicitud AND
                                                    C.idTipoEstadoSolicitud != 1)
                                                INNER JOIN Persona E
                                                ON(B.idPersonaUsuario = E.idPersona)
                                                ORDER BY B.idSolicitud DESC"); 
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar sus solicitudes enviadas')
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


        //ObtenerSolicitudPorId
        public function ObtenerSolicitudPorId () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("SELECT A.nombrePersona,
                                                        A.apellidoPersona,
                                                        B.codigoEmpleado,
                                                        C.nombreDepartamento,
                                                        D.motivoSolicitud,
                                                        D.edificioAsistencia,
                                                        DATE_FORMAT(D.fechaInicioPermiso,'%d-%m-%Y') as fechaInicioPermiso,
                                                        DATE_FORMAT(D.fechaFinPermiso,'%d-%m-%Y') as fechaFinPermiso,
                                                        D.horaInicioSolicitudSalida,
                                                        D.horaFinSolicitudSalida,
                                                        D.diasSolicitados,
                                                        DATE_FORMAT(D.fechaRegistroSolicitud,'%d-%m-%Y') as fechaRegistroSolicitud
                                                FROM Persona A
                                                INNER JOIN Usuario B
                                                ON (A.idPersona = B.idPersonaUsuario)
                                                INNER JOIN Departamento C
                                                ON (B.idDepartamento = C.idDepartamento)
                                                INNER JOIN SolicitudSalida D
                                                ON (D.idPersonaUsuario = B.idPersonaUsuario AND
                                                    D.idSolicitud = $this->idSolicitud)"); 
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar sus solicitudes enviadas')
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


        //ObtenerObservacionesPorId
        public function ObtenerObservacionesPorId() {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("SELECT observacionesSolicitud
                                                FROM EstadoSolicitudSalida
                                                WHERE idSolicitudSalida = $this->idSolicitud"); 
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar sus solicitudes enviadas')
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



        public function hacerObservaciones () {
            if (is_int($this->idSolicitud) && 
                (campoTexto($this->observaciones, 1, 255))
                ) {
                try {
                    $this->conexionBD = new Conexion();
                    $this->consulta = $this->conexionBD->connect();
                    $this->consulta->prepare("
                        set @persona = {$_SESSION['idUsuario']};
                    ")->execute();
                    $stmt = $this->consulta->prepare('CALL SP_HACER_OBSERVACION(:idSolicitud, :observaciones)');
                    $stmt->bindValue(':idSolicitud', $this->idSolicitud);
                    $stmt->bindValue(':observaciones', $this->observaciones);
                    if ($stmt->execute()) {
                        return array(
                            'status' => SUCCESS_REQUEST,
                            'data' => array('message'=>'Observación realizada con exito')
                        );
                    } else {
                        return array(
                            'status'=> BAD_REQUEST,
                            'data' => array('message' => 'Ha ocurrido un error al actualizar la solicitud')
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
            } else {
                return array(
                    'status'=> BAD_REQUEST,
                    'data' => array('message' => 'Ha ocurrido un error, los datos insertados son erroneos')
                );
            }
            
        }
       
        
        //verImagenRespaldoPorId
        public function verImagenRespaldoPorId() {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("SELECT documentoRespaldo, firmaDigital 
                                                FROM SolicitudSalida 
                                                WHERE idSolicitud  = $this->idSolicitud"); 
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar la imagen de respaldo')
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
        

        public function getEstados () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare('SELECT * FROM TipoEstadoSolicitudSalida');
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar los estados de departamentos')
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

        //actualizarSolicitud
        public function actualizarSolicitud () {
            if ((is_numeric($this->idSolicitud)) && 
                (is_numeric($this->idEstadoSolicitud)) && 
                (is_numeric($this->idUsuario))
                ) {
                try {
                    $fechaActual = date('Y-m-d');
                    $this->conexionBD = new Conexion();
                    $this->consulta = $this->conexionBD->connect();
                    $this->consulta->prepare("
                        set @persona = {$_SESSION['idUsuario']};
                    ")->execute();
                    $stmt = $this->consulta->prepare('CALL SP_ACTUALIZAR_SOLICITUD_PERMISO(:idSolicitud, :idEstado, :idUsuario, :fechaRevision)');
                    $stmt->bindValue(':idSolicitud', $this->idSolicitud);
                    $stmt->bindValue(':idEstado', $this->idEstadoSolicitud);
                    $stmt->bindValue(':idUsuario', $this->idUsuario);
                    $stmt->bindValue(':fechaRevision', $fechaActual);
                    if ($stmt->execute()) {
                        return array(
                            'status' => SUCCESS_REQUEST,
                            'data' => array('message'=>'Revisión de solicitud realizada con exito')
                        );
                    } else {
                        return array(
                            'status'=> BAD_REQUEST,
                            'data' => array('message' => 'Ha ocurrido un error al actualizar la solicitud')
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
            } else {
                return array(
                    'status'=> BAD_REQUEST,
                    'data' => array('message' => 'Ha ocurrido un error, los datos insertados son erroneos')
                );
            }
            
        }



    }
?>