<?php
    if (!isset($_SESSION)) {
        session_start();
    }

    require_once('../../config/config.php');    
    require_once('../../validators/validators.php');
    require_once('../../database/Conexion.php');
    require_once('../../helpers/notificacionesEmail.php');

    class EnvioSolicitudesPermisos {
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
        

        //Función para ver solicitudes de permisos enviadas, segun el id de usuario de la sesión activa
        public function getSolicitudesEnviadas () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("SELECT B.idSolicitud,
                                                        C.idTipoEstadoSolicitud,
                                                        A.tipoSolicitudSalida,
                                                        DATE_FORMAT(B.fechaRegistroSolicitud,'%d-%m-%Y') as fechaRegistroSolicitud,
                                                        D.TipoEstadoSolicitudSalida
                                                FROM TipoSolicitudSalida A
                                                INNER JOIN SolicitudSalida B
                                                ON (A.idTipoSolicitudSalida = B.idTipoSolicitud AND
                                                    B.idPersonaUsuario = $this->idUsuario)
                                                INNER JOIN EstadoSolicitudSalida C
                                                ON (B.idSolicitud = C.idSolicitudSalida)
                                                INNER JOIN TipoEstadoSolicitudSalida D
                                                ON (C.idTipoEstadoSolicitud = D.idTipoEstadoSolicitud)
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
                $stmt = $this->consulta->prepare("SELECT A.motivoSolicitud,
                                                        A.edificioAsistencia,
                                                        DATE_FORMAT(A.fechaInicioPermiso,'%d-%m-%Y') as fechaInicioPermiso,
                                                        DATE_FORMAT(A.fechaFinPermiso,'%d-%m-%Y') as fechaFinPermiso,
                                                        A.horaInicioSolicitudSalida,
                                                        A.horaFinSolicitudSalida,
                                                        A.diasSolicitados,
                                                        DATE_FORMAT(B.fechaRevisionSolicitud,'%d-%m-%Y') as fechaRevisionSolicitud
                                                FROM SolicitudSalida A
                                                INNER JOIN EstadoSolicitudSalida B
                                                ON (A.idSolicitud = B.idSolicitudSalida AND
                                                    A.idSolicitud = $this->idSolicitud)"); 
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

       
        //registrarSolicitudSinImagenes
        public function registrarSolicitudSinImagenes() {
            if (is_numeric($this->idTipoSolicitud) && 
                is_numeric($this->idUsuario) && 
                campoTexto($this->motivoSolicitud,1,1000) &&                  
                campoTexto($this->edificioAsistencia,1,80)) {
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
                    $stmt = $this->consulta->prepare("INSERT INTO SolicitudSalida
                                                        (idTipoSolicitud, 
                                                            idPersonaUsuario, 
                                                            motivoSolicitud, 
                                                            edificioAsistencia, 
                                                            firmaDigital, 
                                                            documentoRespaldo, 
                                                            horaInicioSolicitudSalida, 
                                                            horaFinSolicitudSalida, 
                                                            diasSolicitados, 
                                                            fechaInicioPermiso, 
                                                            fechaFinPermiso,
                                                            fechaRegistroSolicitud
                                                        ) 
                                                        VALUES ($this->idTipoSolicitud, 
                                                                $this->idUsuario, 
                                                                '$this->motivoSolicitud', 
                                                                '$this->edificioAsistencia', 
                                                                NULL, 
                                                                NULL, 
                                                                '$this->horaInicio',
                                                                '$this->horaFin',
                                                                NULL, 
                                                                '$this->fechaInicio',
                                                                '$this->fechaFin',
                                                                '$fechaActual'
                                                                )"
                                                    );
                    if ($stmt->execute()) {
                    //$id variable para guardar el id de la ultima iserción que se hizo en la tabla
                    //solicitudSalida, usando la funcion lastInsertId(); de php, para obtener dicho id
                    // esta función de php solo se puede usar cuando el id de una tabla es auto incremental                              
                        $id = $this->consulta->lastInsertId();
                        //insertando en la tabla estadosolicitudsalida
                        $this->conexionBD = new Conexion();
                        $this->consulta = $this->conexionBD->connect();
                        $this->consulta->prepare("
                            set @persona = {$_SESSION['idUsuario']};
                        ")->execute();
                        $stmt1 = $this->consulta->prepare("INSERT INTO EstadoSolicitudSalida(
                                                                idPersonaUsuarioVeedor,
                                                                idSolicitudSalida,
                                                                idTipoEstadoSolicitud,
                                                                observacionesSolicitud,
                                                                fechaRevisionSolicitud
                                                            )
                                                            VALUES(NULL,
                                                                $id,
                                                                1,
                                                                NULL,
                                                                NULL)"
                                                        );
                        $stmt1->execute();

                        $this->conexionBD = new Conexion();
                        $this->consulta = $this->conexionBD->connect();
                        $this->consulta->prepare("
                            set @persona = {$_SESSION['idUsuario']};
                        ")->execute();
                        $stmt2 = $this->consulta->prepare("UPDATE SolicitudSalida 
                                                            SET diasSolicitados = DATEDIFF(fechaFinPermiso,fechaInicioPermiso)
                                                            WHERE idSolicitud = $id");
                        $stmt2->execute();  
                        
                        // Hasta este punto las solicitudes ya  han sido registradas si todo es correcto
                        //Luego se procede a notificar via correo de la existencia de nuevas solicitudes
                        //Para ello se manda a llamar la función que envia correos segun cada tipo de usuario
                        $this->envioSolicitudesPermisosModel = new EnvioSolicitudesPermisos(); 
                        $this->data = $this->envioSolicitudesPermisosModel->notificarSolicitudes();


                        return array(
                            'status' => SUCCESS_REQUEST,
                            'data' => array('message' => 'Solicitud registrada con exito')
                        ); 

                        
                    } else {
                        return array(
                            'status'=> BAD_REQUEST,
                            'data' => array('error' => 'Ha ocurrido un error al insertar la solicitud')
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



        //registrarSolicitudConImagenes
        public function registrarSolicitudConImagenes() {
            if (is_numeric($this->idTipoSolicitud) && 
                is_numeric($this->idUsuario) && 
                campoTexto($this->motivoSolicitud,1,1000) &&                  
                campoTexto($this->edificioAsistencia,1,80)) {
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
                    $stmt = $this->consulta->prepare("INSERT INTO SolicitudSalida
                                                        (idTipoSolicitud, 
                                                            idPersonaUsuario, 
                                                            motivoSolicitud, 
                                                            edificioAsistencia, 
                                                            firmaDigital, 
                                                            documentoRespaldo, 
                                                            horaInicioSolicitudSalida, 
                                                            horaFinSolicitudSalida, 
                                                            diasSolicitados, 
                                                            fechaInicioPermiso, 
                                                            fechaFinPermiso,
                                                            fechaRegistroSolicitud 
                                                        ) 
                                                        VALUES ($this->idTipoSolicitud, 
                                                                $this->idUsuario, 
                                                                '$this->motivoSolicitud', 
                                                                '$this->edificioAsistencia', 
                                                                '$this->firmaDigital', 
                                                                '$this->documentoRespaldo', 
                                                                '$this->horaInicio',
                                                                '$this->horaFin',
                                                                NULL, 
                                                                '$this->fechaInicio',
                                                                '$this->fechaFin',
                                                                '$fechaActual'
                                                                )"
                                                    );
                    if ($stmt->execute()) {
                    //$id variable para guardar el id de la ultima iserción que se hizo en la tabla
                    //solicitudSalida, usando la funcion lastInsertId(); de php, para obtener dicho id
                    // esta función de php solo se puede usar cuando el id de una tabla es auto incremental                              
                        $id = $this->consulta->lastInsertId();
                        //insertando en la tabla estadosolicitudsalida
                        $this->conexionBD = new Conexion();
                        $this->consulta = $this->conexionBD->connect();
                        $this->consulta->prepare("
                            set @persona = {$_SESSION['idUsuario']};
                        ")->execute();
                        $stmt1 = $this->consulta->prepare("INSERT INTO EstadoSolicitudSalida(
                                                                idPersonaUsuarioVeedor,
                                                                idSolicitudSalida,
                                                                idTipoEstadoSolicitud,
                                                                observacionesSolicitud,
                                                                fechaRevisionSolicitud
                                                            )
                                                            VALUES(NULL,
                                                                $id,
                                                                1,
                                                                NULL,
                                                                NULL)"
                                                        );
                        $stmt1->execute();

                        $this->conexionBD = new Conexion();
                        $this->consulta = $this->conexionBD->connect();
                        $this->consulta->prepare("
                            set @persona = {$_SESSION['idUsuario']};
                        ")->execute();
                        $stmt2 = $this->consulta->prepare("UPDATE SolicitudSalida 
                                                            SET diasSolicitados = DATEDIFF(fechaFinPermiso,fechaInicioPermiso)
                                                            WHERE idSolicitud = $id");
                        $stmt2->execute();

                        //Hasta este punto las solicitudes ya  han sido registradas si todo es correcto
                        //Luego se procede a notificar via correo de la existencia de nuevas solicitudes
                        //Para ello se manda a llamar la función que envia correos segun cada tipo de usuario
                        $this->envioSolicitudesPermisosModel = new EnvioSolicitudesPermisos(); 
                        $this->data = $this->envioSolicitudesPermisosModel->notificarSolicitudes();

                        return array(
                            'status' => SUCCESS_REQUEST,
                            'data' => array('message' => 'Solicitud registrada con exito')
                        );
                    } else {
                        return array(
                            'status'=> BAD_REQUEST,
                            'data' => array('error' => 'Ha ocurrido un error al insertar la solicitud')
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


        //registrarSolicitudConFirma
        public function registrarSolicitudConFirma() {
            if (is_numeric($this->idTipoSolicitud) && 
                is_numeric($this->idUsuario) && 
                campoTexto($this->motivoSolicitud,1,1000) &&                  
                campoTexto($this->edificioAsistencia,1,80)) {
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
                    $stmt = $this->consulta->prepare("INSERT INTO SolicitudSalida
                                                        (idTipoSolicitud, 
                                                            idPersonaUsuario, 
                                                            motivoSolicitud, 
                                                            edificioAsistencia, 
                                                            firmaDigital, 
                                                            documentoRespaldo, 
                                                            horaInicioSolicitudSalida, 
                                                            horaFinSolicitudSalida, 
                                                            diasSolicitados, 
                                                            fechaInicioPermiso, 
                                                            fechaFinPermiso,
                                                            fechaRegistroSolicitud
                                                        ) 
                                                        VALUES ($this->idTipoSolicitud, 
                                                                $this->idUsuario, 
                                                                '$this->motivoSolicitud', 
                                                                '$this->edificioAsistencia', 
                                                                '$this->firmaDigital', 
                                                                NULL, 
                                                                '$this->horaInicio',
                                                                '$this->horaFin',
                                                                NULL, 
                                                                '$this->fechaInicio',
                                                                '$this->fechaFin',
                                                                '$fechaActual'
                                                                )"
                                                    );
                    if ($stmt->execute()) {
                    //$id variable para guardar el id de la ultima iserción que se hizo en la tabla
                    //solicitudSalida, usando la funcion lastInsertId(); de php, para obtener dicho id
                    // esta función de php solo se puede usar cuando el id de una tabla es auto incremental                              
                        $id = $this->consulta->lastInsertId();
                        //insertando en la tabla estadosolicitudsalida
                        $this->conexionBD = new Conexion();
                        $this->consulta = $this->conexionBD->connect();
                        $this->consulta->prepare("
                            set @persona = {$_SESSION['idUsuario']};
                        ")->execute();
                        $stmt1 = $this->consulta->prepare("INSERT INTO EstadoSolicitudSalida(
                                                                idPersonaUsuarioVeedor,
                                                                idSolicitudSalida,
                                                                idTipoEstadoSolicitud,
                                                                observacionesSolicitud,
                                                                fechaRevisionSolicitud
                                                            )
                                                            VALUES(NULL,
                                                                $id,
                                                                1,
                                                                NULL,
                                                                NULL)"
                                                        );
                        $stmt1->execute();

                        $this->conexionBD = new Conexion();
                        $this->consulta = $this->conexionBD->connect();
                        $this->consulta->prepare("
                            set @persona = {$_SESSION['idUsuario']};
                        ")->execute();
                        $stmt2 = $this->consulta->prepare("UPDATE SolicitudSalida 
                                                            SET diasSolicitados = DATEDIFF(fechaFinPermiso,fechaInicioPermiso)
                                                            WHERE idSolicitud = $id");
                        $stmt2->execute();

                        // Hasta este punto las solicitudes ya  han sido registradas si todo es correcto
                        //Luego se procede a notificar via correo de la existencia de nuevas solicitudes
                        //Para ello se manda a llamar la función que envia correos segun cada tipo de usuario
                        $this->envioSolicitudesPermisosModel = new EnvioSolicitudesPermisos(); 
                        $this->data = $this->envioSolicitudesPermisosModel->notificarSolicitudes();

                        return array(
                            'status' => SUCCESS_REQUEST,
                            'data' => array('message' => 'Solicitud registrada con exito')
                        );
                    } else {
                        return array(
                            'status'=> BAD_REQUEST,
                            'data' => array('error' => 'Ha ocurrido un error al insertar la solicitud')
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


        //registrarSolicitudConRespaldo
        public function registrarSolicitudConRespaldo() {
            if (is_numeric($this->idTipoSolicitud) && 
                is_numeric($this->idUsuario) && 
                campoTexto($this->motivoSolicitud,1,1000) &&                  
                campoTexto($this->edificioAsistencia,1,80)) {
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
                    $stmt = $this->consulta->prepare("INSERT INTO SolicitudSalida
                                                        (idTipoSolicitud, 
                                                            idPersonaUsuario, 
                                                            motivoSolicitud, 
                                                            edificioAsistencia, 
                                                            firmaDigital, 
                                                            documentoRespaldo, 
                                                            horaInicioSolicitudSalida, 
                                                            horaFinSolicitudSalida, 
                                                            diasSolicitados, 
                                                            fechaInicioPermiso, 
                                                            fechaFinPermiso,
                                                            fechaRegistroSolicitud
                                                        ) 
                                                        VALUES ($this->idTipoSolicitud, 
                                                                $this->idUsuario, 
                                                                '$this->motivoSolicitud', 
                                                                '$this->edificioAsistencia', 
                                                                NULL, 
                                                                '$this->documentoRespaldo', 
                                                                '$this->horaInicio',
                                                                '$this->horaFin',
                                                                NULL, 
                                                                '$this->fechaInicio',
                                                                '$this->fechaFin',
                                                                '$fechaActual'
                                                                )"
                                                    );
                    if ($stmt->execute()) {
                    //$id variable para guardar el id de la ultima iserción que se hizo en la tabla
                    //solicitudSalida, usando la funcion lastInsertId(); de php, para obtener dicho id
                    // esta función de php solo se puede usar cuando el id de una tabla es auto incremental                              
                        $id = $this->consulta->lastInsertId();
                        //insertando en la tabla estadosolicitudsalida
                        $this->conexionBD = new Conexion();
                        $this->consulta = $this->conexionBD->connect();
                        $this->consulta->prepare("
                            set @persona = {$_SESSION['idUsuario']};
                        ")->execute();
                        $stmt1 = $this->consulta->prepare("INSERT INTO EstadoSolicitudSalida(
                                                                idPersonaUsuarioVeedor,
                                                                idSolicitudSalida,
                                                                idTipoEstadoSolicitud,
                                                                observacionesSolicitud,
                                                                fechaRevisionSolicitud
                                                            )
                                                            VALUES(NULL,
                                                                $id,
                                                                1,
                                                                NULL,
                                                                NULL)"
                                                        );
                        $stmt1->execute();

                        $this->conexionBD = new Conexion();
                        $this->consulta = $this->conexionBD->connect();
                        $this->consulta->prepare("
                            set @persona = {$_SESSION['idUsuario']};
                        ")->execute();
                        $stmt2 = $this->consulta->prepare("UPDATE SolicitudSalida 
                                                            SET diasSolicitados = DATEDIFF(fechaFinPermiso,fechaInicioPermiso)
                                                            WHERE idSolicitud = $id");
                        $stmt2->execute();

                        // Hasta este punto las solicitudes ya  han sido registradas si todo es correcto
                        //Luego se procede a notificar via correo de la existencia de nuevas solicitudes
                        //Para ello se manda a llamar la función que envia correos segun cada tipo de usuario
                        $this->envioSolicitudesPermisosModel = new EnvioSolicitudesPermisos(); 
                        $this->data = $this->envioSolicitudesPermisosModel->notificarSolicitudes();

                        return array(
                            'status' => SUCCESS_REQUEST,
                            'data' => array('message' => 'Solicitud registrada con exito')
                        );
                    } else {
                        return array(
                            'status'=> BAD_REQUEST,
                            'data' => array('error' => 'Ha ocurrido un error al insertar la solicitud')
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


        //Función para notificar via correo sobre nuevas solicitudes de permisos
        public function notificarSolicitudes() {
            try {

                //primero verifica que tipo de usuario envio la solicitud para 
                //enviar notificacion al correo de la persona que le corresponda
                //revisar la solicitud.
                if($_SESSION['abrevTipoUsuario'] === 'SE_AD'){
                    //el usuario que revisa las solicitudes de permisos de los usuarios administrativos
                    //es el usuario de tipo secretaria academica, por lo tanto se le notifica en el sistema y 
                    //mediante correo electronico que tiene solicitudes pendientes de revisar,
                    //cada vez que se registra una nueva solicitud por parte de usuarios administrativos
                    //para ello se comprueba el tipo de usuario que remite la solicitud y luego se obtiene
                    //el correo del receptor 
                    $this->conexionBD = new Conexion();
                    $this->consulta = $this->conexionBD->connect();
                    $obtenerCorreoSecAcademica = $this->consulta->prepare("SELECT correoInstitucional
                                                                            FROM Usuario
                                                                            WHERE idTipoUsuario = 7 AND
                                                                                idEstadoUsuario = 1");
                    $obtenerCorreoSecAcademica->execute(); 
                    $correoSecAcademica = $obtenerCorreoSecAcademica->fetch();
                    if($correoSecAcademica != false){
                        $correoSecAcademica = $correoSecAcademica[0];
                        //echo $correoSecAcademica;

                        $email = new notificacionesEmail();
                        $email->setEmailDestino($correoSecAcademica);
                        $email->setHeaderMensaje('Sistema POA PACC: Nuevas Notificaciones del Sistema');
                        $email->setTituloMensaje('Tiene una nueva Solicitud de Permiso pendiente de revisar');
                        $email->setContenido('Acceda al sistema con sus credenciales para revisar nuevas solicitudes recibidas');
                        $email->notificarViaCorreo(); 
                    }             
                    
                }else if ($_SESSION['abrevTipoUsuario'] === 'C_C'){
                    //el usuario que revisa las solicitudes de permisos de los usuarios de tipo coordinadores
                    //es el usuario de tipo jefe departamento, cada jefe atiende las solicitudes del 
                    //coordinador de su respectivo departamento, por lo tanto se le notifica en el sistema y 
                    //mediante correo electronico que tiene solicitudes pendientes de revisar,
                    //cada vez que se registra una nueva solicitud por parte de usuarios coordinadores
                    //para ello se comprueba el tipo de usuario que remite la solicitud se verifica el
                    //departamento al que este corresponde y poder obtener el correo del receptor que corresponda
                    //con el mismo departamento del usuario que 
                    
                    $this->conexionBD = new Conexion();
                    $this->consulta = $this->conexionBD->connect();
                    $obtenerCorreoJefe = $this->consulta->prepare("SELECT correoInstitucional
                                                                    FROM Usuario
                                                                    WHERE idTipoUsuario = 2 AND
                                                                        idEstadoUsuario = 1 AND
                                                                        idDepartamento = {$_SESSION['idDepartamento']}");
                    $obtenerCorreoJefe->execute(); 
                    $correoJefe = $obtenerCorreoJefe->fetch();
                    if($correoJefe != false){
                        $correoJefe = $correoJefe[0];
                        $email = new notificacionesEmail();
                        $email->setEmailDestino($correoJefe);
                        $email->setHeaderMensaje('Sistema POA PACC: Nuevas Notificaciones del Sistema');
                        $email->setTituloMensaje('Tiene una nueva Solicitud de Permiso pendiente de revisar');
                        $email->setContenido('Acceda al sistema con sus credenciales para revisar nuevas solicitudes recibidas');
                        $email->notificarViaCorreo(); 
                    }  
                  
                    
                }else if ($_SESSION['abrevTipoUsuario'] === 'U_E') {
                    //el usuario que revisa las solicitudes de permisos del usuario de tipo estratega
                    //es el usuario de tipo decano se le notifica en el sistema y 
                    //mediante correo electronico que tiene solicitudes pendientes de revisar,
                    //cada vez que se registra una nueva solicitud por parte de usuario tipo estratega
                    //para ello se comprueba el tipo de usuario que remite la solicitud se verifica el
                    //poder obtener el correo del receptor que corresponda

                    $this->conexionBD = new Conexion();
                    $this->consulta = $this->conexionBD->connect();
                    $obtenerCorreoDecano = $this->consulta->prepare("SELECT correoInstitucional
                                                                    FROM Usuario
                                                                    WHERE idTipoUsuario = 4 AND
                                                                        idEstadoUsuario = 1");
                    $obtenerCorreoDecano->execute(); 
                    $correoDecano = $obtenerCorreoDecano->fetch();    
                    if($correoDecano != false){
                        $correoDecano = $correoDecano[0];
                        $email = new notificacionesEmail();
                        $email->setEmailDestino($correoDecano);
                        $email->setHeaderMensaje('Sistema POA PACC Nuevas Notificaciones del Sistema');
                        $email->setTituloMensaje('Tiene una nueva Solicitud de Permiso pendiente de revisar');
                        $email->setContenido('Acceda al sistema con sus credenciales para revisar nuevas solicitudes recibidas');
                        $enviado = $email->notificarViaCorreo();
                    }  
                    
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