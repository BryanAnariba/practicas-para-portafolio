<?php
    if (!isset($_SESSION)) {
        session_start();
    }
    require_once('../../validators/validators.php');
    
    class EstudiantesDocentesEstratega { 
        private $Trimestre;
        private $numeroPoblacion;
        private $respaldo;
        private $poblacion;
        private $idUsuario;
        private $idGestion;
        private $idDepartamento;
        private $idTipoGestion;

        private function _construct($idGestion = null,$Trimestre = null,$numeroPoblacion = null,$respaldo = null,$poblacion = null, $idUsuario=null, $idDepartamento=null, $idTipoGestion=null){
            $this->Trimestre = $Trimestre;
            $this->numeroPoblacion = $numeroPoblacion;
            $this->respaldo = $respaldo;
            $this->poblacion = $poblacion;
            $this->idUsuario = $idUsuario;
            $this->idGestion = $idGestion;
            $this->idDepartamento = $idDepartamento;
        }

        public function getTrimestre(){
            return $this->Trimestre;
        }

        public function getIdGestion(){
            return $this->idGestion;
        }

        public function getNumeroPoblacion(){
            return $this->numeroPoblacion;
        }

        public function getRespaldo(){
            return $this->respaldo;
        }

        public function getPoblacion(){
            return $this->poblacion;
        }

        public function getIdUsuario(){
            return $this->idUsuario;
        }

        public function getIdDepartamento(){
            return $this->idDepartamento;
        }

        public function getIdTipoGestion(){
            return $this->idTipoGestion;
        }


        public function setTrimestre($Trimestre){
            $this->Trimestre = $Trimestre;
            return $this;
        }

        public function setIdGestion($idGestion){
            $this->idGestion = $idGestion;
            return $this;
        }
        
        public function setNumeroPoblacion($numeroPoblacion){
            $this->numeroPoblacion = $numeroPoblacion;
            return $this;
        }
    
        public function setRespaldo($respaldo){
            $this->respaldo = $respaldo;
            return $this;
        }
        
        public function setPoblacion($poblacion){
            $this->poblacion = $poblacion;
            return $this;
        }

        public function setIdUsuario($idUsuario){
            $this->idUsuario = $idUsuario;
            return $this;
        }
        
        public function setIdDepartamento($idDepartamento){
            $this->idDepartamento = $idDepartamento;
            return $this;
        }

        public function setIdTipoGestion($idTipoGestion){
            $this->idTipoGestion = $idTipoGestion;
            return $this;
        }




        //funcion para mostrar todos los registros 
        public function getGestionRegistrosTotales () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("WITH CTE_ACCION_INSERCION AS (
                                                    SELECT idGestion,
                                                            idAccion,
                                                            idPersonaUsuario as idPersonaRegistro,
                                                            fecha as fechaRegistro 
                                                    FROM TipoAccionGestion
                                                    WHERE idAccion = 1
                                                ), CTE_ACCION_MODIFICACION AS (
                                                    SELECT idGestion,
                                                            idAccion,
                                                            idPersonaUsuario as idPersonaModificacion,
                                                            fecha as fechaModificacion 
                                                    FROM TipoAccionGestion
                                                    WHERE idAccion = 2
                                                )
                                                SELECT DISTINCT Gestion.idTipoGestion,
                                                                CTE_ACCION_INSERCION.idGestion,
                                                                idPersonaRegistro,
                                                                idPersonaModificacion,
                                                                DATE_FORMAT(fechaRegistro,'%d-%m-%Y') as fechaRegistro,
                                                                DATE_FORMAT(fechaModificacion,'%d-%m-%Y') as fechaModificacion,
                                                                TipoGestion.nombre as poblacion,
                                                                Gestion.documentoRespaldo,
                                                                Departamento.idDepartamento,
                                                                Departamento.nombreDepartamento,
                                                                Gestion.cantidad,
                                                                Trimestre.idTrimestre,
                                                                Trimestre.nombreTrimeste,
                                                        (SELECT CONCAT(nombrePersona,' ', apellidoPersona) AS nombreCompleto
                                                            FROM Persona
                                                            WHERE idPersona = CTE_ACCION_INSERCION.idPersonaRegistro) as registro,
                                                        (SELECT CONCAT(nombrePersona,' ', apellidoPersona) AS nombreCompleto
                                                            FROM Persona
                                                            WHERE idPersona = CTE_ACCION_MODIFICACION.idPersonaModificacion) as modifico
                                                FROM CTE_ACCION_INSERCION
                                                LEFT JOIN CTE_ACCION_MODIFICACION
                                                ON (CTE_ACCION_MODIFICACION.idGestion = CTE_ACCION_INSERCION.idGestion)
                                                INNER JOIN Gestion
                                                ON (Gestion.idGestion = CTE_ACCION_INSERCION.idGestion)
                                                INNER JOIN TipoGestion
                                                ON (TipoGestion.idTipoGestion = Gestion.idTipoGestion)
                                                INNER JOIN Trimestre
                                                ON (Trimestre.idTrimestre = Gestion.idTrimestre)
                                                INNER JOIN Usuario
                                                ON (Usuario.idPersonaUsuario = CTE_ACCION_INSERCION.idPersonaRegistro)
                                                INNER JOIN Departamento
                                                ON (Departamento.idDepartamento = Usuario.idDepartamento)
                                                ORDER BY fechaModificacion DESC, fechaRegistro ASC"
                                                );                         
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar los registros de las estudiantes, egresados y docentes con maestria.')
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


        //Funcion para mostrar registros filtrados por departamento
        public function getGestionRegistrosPorDepartamento () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("WITH CTE_ACCION_INSERCION AS (
                                                    SELECT idGestion,
                                                            idAccion,
                                                            idPersonaUsuario as idPersonaRegistro,
                                                            fecha as fechaRegistro 
                                                    FROM TipoAccionGestion
                                                    WHERE idAccion = 1
                                                ), CTE_ACCION_MODIFICACION AS (
                                                    SELECT idGestion,
                                                            idAccion,
                                                            idPersonaUsuario as idPersonaModificacion,
                                                            fecha as fechaModificacion 
                                                    FROM TipoAccionGestion
                                                    WHERE idAccion = 2
                                                )
                                                SELECT DISTINCT Gestion.idTipoGestion,
                                                                CTE_ACCION_INSERCION.idGestion,
                                                                idPersonaRegistro,
                                                                idPersonaModificacion,
                                                                fechaRegistro,
                                                                fechaModificacion,
                                                                TipoGestion.nombre as poblacion,
                                                                Gestion.documentoRespaldo,
                                                                Departamento.idDepartamento,
                                                                Departamento.nombreDepartamento,
                                                                Gestion.cantidad,
                                                                Trimestre.idTrimestre,
                                                                Trimestre.nombreTrimeste,
                                                        (SELECT CONCAT(nombrePersona,' ', apellidoPersona) AS nombreCompleto
                                                            FROM Persona
                                                            WHERE idPersona = CTE_ACCION_INSERCION.idPersonaRegistro) as registro,
                                                        (SELECT CONCAT(nombrePersona,' ', apellidoPersona) AS nombreCompleto
                                                            FROM Persona
                                                            WHERE idPersona = CTE_ACCION_MODIFICACION.idPersonaModificacion) as modifico
                                                FROM CTE_ACCION_INSERCION
                                                LEFT JOIN CTE_ACCION_MODIFICACION
                                                ON (CTE_ACCION_MODIFICACION.idGestion = CTE_ACCION_INSERCION.idGestion)
                                                INNER JOIN Gestion
                                                ON (Gestion.idGestion = CTE_ACCION_INSERCION.idGestion)
                                                INNER JOIN TipoGestion
                                                ON (TipoGestion.idTipoGestion = Gestion.idTipoGestion)
                                                INNER JOIN Trimestre
                                                ON (Trimestre.idTrimestre = Gestion.idTrimestre)
                                                INNER JOIN Usuario
                                                ON (Usuario.idPersonaUsuario = CTE_ACCION_INSERCION.idPersonaRegistro)
                                                INNER JOIN Departamento
                                                ON (Departamento.idDepartamento = Usuario.idDepartamento)
                                                WHERE Departamento.idDepartamento = :idDepartamento
                                                ORDER BY fechaModificacion DESC, fechaRegistro ASC"
                                                );   
                $stmt->bindValue(':idDepartamento', $this->idDepartamento);                      
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar los registros de las estudiantes, egresados y docentes con maestria.')
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


        //Función para mostrar registros filtrados por tipo de gestión
        public function getGestionRegistrosPorTipo () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("WITH CTE_ACCION_INSERCION AS (
                                                    SELECT idGestion,
                                                            idAccion,
                                                            idPersonaUsuario as idPersonaRegistro,
                                                            fecha as fechaRegistro 
                                                    FROM TipoAccionGestion
                                                    WHERE idAccion = 1
                                                ), CTE_ACCION_MODIFICACION AS (
                                                    SELECT idGestion,
                                                            idAccion,
                                                            idPersonaUsuario as idPersonaModificacion,
                                                            fecha as fechaModificacion 
                                                    FROM TipoAccionGestion
                                                    WHERE idAccion = 2
                                                )
                                                SELECT DISTINCT Gestion.idTipoGestion,
                                                                CTE_ACCION_INSERCION.idGestion,
                                                                idPersonaRegistro,
                                                                idPersonaModificacion,
                                                                DATE_FORMAT(fechaRegistro,'%d-%m-%Y') as fechaRegistro,
                                                                DATE_FORMAT(fechaModificacion,'%d-%m-%Y') as fechaModificacion,
                                                                TipoGestion.nombre as poblacion,
                                                                Gestion.documentoRespaldo,
                                                                Departamento.idDepartamento,
                                                                Departamento.nombreDepartamento,
                                                                Gestion.cantidad,
                                                                Trimestre.idTrimestre,
                                                                Trimestre.nombreTrimeste,    
                                                        (SELECT CONCAT(nombrePersona,' ',apellidoPersona) AS nombreCompleto
                                                            FROM Persona
                                                            WHERE idPersona = CTE_ACCION_INSERCION.idPersonaRegistro) as registro,
                                                        (SELECT CONCAT(nombrePersona,' ', apellidoPersona) AS nombreCompleto
                                                            FROM Persona
                                                            WHERE idPersona = CTE_ACCION_MODIFICACION.idPersonaModificacion) as modifico
                                                FROM CTE_ACCION_INSERCION
                                                LEFT JOIN CTE_ACCION_MODIFICACION
                                                ON (CTE_ACCION_MODIFICACION.idGestion = CTE_ACCION_INSERCION.idGestion)
                                                INNER JOIN Gestion
                                                ON (Gestion.idGestion = CTE_ACCION_INSERCION.idGestion)
                                                INNER JOIN TipoGestion
                                                ON (TipoGestion.idTipoGestion = Gestion.idTipoGestion AND
                                                    TipoGestion.idTipoGestion = :idTipoGestion)
                                                INNER JOIN Trimestre
                                                ON (Trimestre.idTrimestre = Gestion.idTrimestre)
                                                INNER JOIN Usuario
                                                ON (Usuario.idPersonaUsuario = CTE_ACCION_INSERCION.idPersonaRegistro)
                                                INNER JOIN Departamento
                                                ON (Departamento.idDepartamento = Usuario.idDepartamento)
                                                ORDER BY fechaModificacion DESC, fechaRegistro ASC"
                                                );   
                $stmt->bindValue(':idTipoGestion', $this->idTipoGestion);                      
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar los registros de las estudiantes, egresados y docentes con maestria.')
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

        public function getTrimestres () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare('SELECT * FROM Trimestre');
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar los trimestres')
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

        public function getImagen () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("SELECT documentoRespaldo from Gestion where idGestion=:idGestion");
                $stmt->bindValue(':idGestion', $this->idGestion);
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al obtener la imagen de respaldo')
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

        
        public function modificarPoblacion () {
            if (is_numeric($this->Trimestre) && is_numeric($this->numeroPoblacion)) {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();

                $this->consulta->prepare("
                    set @persona = {$_SESSION['idUsuario']};
                ")->execute();

                try {
                    $fechaActual = date('Y-m-d');
                    $stmt = $this->consulta->prepare("CALL SP_MODIFICAR_REGISTROS_POBLACION ($this->Trimestre, '$this->numeroPoblacion', '$this->idGestion')");
                    if ($stmt->execute()) {
                        return array(
                            'status' => SUCCESS_REQUEST,
                            'data' => array('message'=>'Informacion de la gestion se ha actualizado con exito')
                        );
                        
                    } else {
                        return array(
                            'status'=> BAD_REQUEST,
                            'data' => array('message' => 'Ha ocurrido un error al actualizar la informacion de la gestion')
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

        public function modificarRespaldo () {
            $this->conexionBD = new Conexion();
            $this->consulta = $this->conexionBD->connect();
            $this->consulta->prepare("
                set @persona = {$_SESSION['idUsuario']};
            ")->execute();
            try {
                $stmt = $this->consulta->prepare("UPDATE Gestion
                                                    SET
                                                    documentoRespaldo = '$this->respaldo'
                                                    WHERE idGestion = $this->idGestion"
                );
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => array('message'=>'Informacion de la gestion se ha actualizado con exito')
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al actualizar la informacion de la gestion')
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

        //función para listar departamentos 
        public function Departamentos () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare('SELECT * FROM Departamento');
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar los departamentos')
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


        //obtenerTipoGestion
        public function obtenerTipoGestion () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare('SELECT * FROM TipoGestion');
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar tipos de registros gestión')
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