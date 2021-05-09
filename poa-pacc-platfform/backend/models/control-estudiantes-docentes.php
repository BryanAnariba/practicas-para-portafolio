<?php
    if (!isset($_SESSION)) {
        session_start();
    }
    require_once('../../validators/validators.php');
    
    class EstudianteDocente { 
        protected $Trimestre;
        protected $numeroPoblacion;
        protected $respaldo;
        protected $poblacion;
        protected $idUsuario;
        protected $idGestion;

        protected function _construct($idGestion = null,$Trimestre = null,$numeroPoblacion = null,$respaldo = null,$poblacion = null, $idUsuario=null){
            $this->Trimestre = $Trimestre;
            $this->numeroPoblacion = $numeroPoblacion;
            $this->respaldo = $respaldo;
            $this->poblacion = $poblacion;
            $this->idUsuario = $idUsuario;
            $this->idGestion = $idGestion;
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

        public function getGestion () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("WITH CTE_ACCION_INSERCION AS (
                                                    select idGestion,idAccion,idPersonaUsuario as idPersonaRegistro,fecha as fechaRegistro from TipoAccionGestion
                                                    where idAccion=1
                                                ), CTE_ACCION_MODIFICACION AS (
                                                    select idGestion,idAccion,idPersonaUsuario as idPersonaModificacion,fecha as fechaModificacion from TipoAccionGestion
                                                    where idAccion=2
                                                )
                                                SELECT distinct Gestion.idTipoGestion,CTE_ACCION_INSERCION.idGestion,idPersonaRegistro,idPersonaModificacion,fechaRegistro,fechaModificacion,TipoGestion.nombre as poblacion,Gestion.documentoRespaldo,
                                                        Departamento.idDepartamento,Departamento.nombreDepartamento,Gestion.cantidad,Trimestre.idTrimestre,Trimestre.nombreTrimeste,
                                                        (select us.nombreUsuario from Usuario us where us.idPersonaUsuario=CTE_ACCION_INSERCION.idPersonaRegistro) as registro,
                                                        (select usu.nombreUsuario from Usuario usu where usu.idPersonaUsuario=CTE_ACCION_MODIFICACION.idPersonaModificacion) as modifico
                                                FROM CTE_ACCION_INSERCION
                                                left join CTE_ACCION_MODIFICACION
                                                on CTE_ACCION_MODIFICACION.idGestion=CTE_ACCION_INSERCION.idGestion
                                                inner join Gestion
                                                on Gestion.idGestion=CTE_ACCION_INSERCION.idGestion
                                                inner join TipoGestion
                                                on TipoGestion.idTipoGestion=Gestion.idTipoGestion
                                                inner join Trimestre
                                                on Trimestre.idTrimestre=Gestion.idTrimestre
                                                inner join Usuario
                                                on Usuario.idPersonaUsuario=CTE_ACCION_INSERCION.idPersonaRegistro
                                                inner join Departamento
                                                on Departamento.idDepartamento=Usuario.idDepartamento
                                                where Departamento.idDepartamento=(select d.idDepartamento from Usuario u inner join Departamento d on d.idDepartamento=u.idDepartamento where u.idPersonaUsuario=:idUsuario)
                                                ORDER BY fechaModificacion desc, fechaRegistro asc"
                );
                $stmt->bindValue(':idUsuario', $this->idUsuario);
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

        public function ingresarPoblacion () {
            if (is_numeric($this->Trimestre) && is_numeric($this->numeroPoblacion)) {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                try {
                    $stmt = $this->consulta->prepare("CALL SP_Ingresar_Poblacion ($this->Trimestre, '$this->numeroPoblacion', '$this->respaldo', '$this->poblacion',$this->idUsuario,@resp,@resp2)");
                    if ($stmt->execute()) {
                        $resp = $this->consulta->query('SELECT @resp')->fetch();
                        $resp2 = $this->consulta->query('SELECT @resp2')->fetch();
                        if($resp2[0]==0){
                            return array(
                                'status'=> INTERNAL_SERVER_ERROR,
                                'data' => array('message' => 'No puede ingresar otra cantidad para '.$resp[0].' en el trimestre seleccionado')
                            );
                        }else{
                            return array(
                                'status' => SUCCESS_REQUEST,
                                'data' => array('message' => $resp[0] . ' registrada con exito')
                            );
                        }
                    } else {
                        return array(
                            'status'=> BAD_REQUEST,
                            'data' => array('error' => 'Ha ocurrido un error en el registro.')
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
        public function modificarPoblacion ($idTipoGestion,$fechaRegistro) {
            if (is_numeric($this->Trimestre) && is_numeric($this->numeroPoblacion)) {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();

                $this->consulta->prepare("
                    set @persona = {$_SESSION['idUsuario']};
                ")->execute();

                try {
                    $stmt = $this->consulta->prepare("CALL SP_MODIFICAR_POBLACION ($this->Trimestre, '$this->numeroPoblacion', '$this->idGestion','$idTipoGestion','$fechaRegistro',@resp)");
                    if ($stmt->execute()) {
                        $resp = $this->consulta->query('SELECT @resp')->fetch();
                        if($resp[0]==0){
                            return array(
                                'status'=> INTERNAL_SERVER_ERROR,
                                'data' => array('message' => 'Ese trimestre ya existe en ese año')
                            );
                        }else{
                            return array(
                                'status' => SUCCESS_REQUEST,
                                'data' => array('message'=>'Informacion de la gestion se ha actualizado con exito')
                            );
                        }
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
    }