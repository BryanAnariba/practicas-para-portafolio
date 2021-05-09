<?php
    if (!isset($_SESSION)) {
        session_start();
    }
    require_once('../../validators/validators.php');
    
    class Carrera { 
        protected $idCarrera;
        protected $Carrera;
        protected $Abreviatura;
        protected $idDepartamento;
        protected $idEstado;

        protected function _construct($idCarrera = null,$Carrera = null,$Abreviatura = null,$idDepartamento = null,$idEstado = null){
            $this->idCarrera = $idCarrera;
            $this->Carrera = $Carrera;
            $this->Abreviatura = $Abreviatura;
            $this->idDepartamento = $idDepartamento;
            $this->idEstado = $idEstado;
        }

        public function getIdCarrera(){
            return $this->idCarrera;
        }

        public function getCarrera(){
            return $this->Carrera;
        }

        public function getAbreviatura(){
            return $this->Abreviatura;
        }

        public function getidDepartamento(){
            return $this->idDepartamento;
        }

        public function getidEstado(){
            return $this->idEstado;
        }

        public function setIdCarrera($idCarrera){
            $this->idCarrera = $idCarrera;
            return $this;
        }
        
        public function setCarrera($Carrera){
            $this->Carrera = $Carrera;
            return $this;
        }
    
        public function setAbreviatura($Abreviatura){
            $this->Abreviatura = $Abreviatura;
            return $this;
        }
        
        public function setidDepartamento($idDepartamento){
            $this->idDepartamento = $idDepartamento;
            return $this;
        }

        public function setidEstado($idEstado){
            $this->idEstado = $idEstado;
            return $this;
        }

        public function getCarreras () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare('SELECT ca.idCarrera, ca.carrera, ca.abrev, dep.nombreDepartamento, es.estado
                                                  from Carrera as ca
                                                  inner join Departamento as dep
                                                      on dep.idDepartamento=ca.idDepartamento
                                                  inner join EstadoDCDUOAO as es
                                                      on es.idEstado=ca.idEstadoCarrera');
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar las carreras')
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

        public function getDepartamentos () {
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

        public function getEstados () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare('SELECT * FROM EstadoDCDUOAO');
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar los estados de la carrera')
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

        public function getCarrerasPorDepa ($idDepartamento) {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("SELECT * from Carrera where idDepartamento=$idDepartamento");
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar las carreras')
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

        public function getCarrerasPorId () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("SELECT * from Carrera where idCarrera=$this->idCarrera");
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar las carreras')
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

        public function insertaCarrera () {
            if (campoTexto($this->Carrera,1,80) && campoTexto($this->Abreviatura,1,2) && is_numeric($this->idDepartamento) && is_numeric($this->idEstado)) {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();

                $this->consulta->prepare("
                    set @persona = {$_SESSION['idUsuario']};
                ")->execute();

                try {
                    $stmt = $this->consulta->prepare("CALL SP_Registrar_Carrera (0, '$this->Carrera', '$this->Abreviatura', $this->idDepartamento, $this->idEstado, 'insert', @resp)");
                    if ($stmt->execute()) {
                        $resp = $this->consulta->query('SELECT @resp')->fetch();
            
                        if(json_encode($resp[0])==0){
                            return array(
                                'status'=> INTERNAL_SERVER_ERROR,
                                'data' => array('message' => 'Es posible que la carrera o la abreviatura ya esten registrados')
                            );
                        }else{
                            return array(
                                'status' => SUCCESS_REQUEST,
                                'data' => array('message' => $this->Carrera . ' registrada con exito')
                            );;
                        };
                    } else {
                        return array(
                            'status'=> BAD_REQUEST,
                            'data' => array('error' => 'Ha ocurrido un error al insertar la carrera')
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
        public function modificarCarrera () {
            if (campoTexto($this->Carrera,1,80) && campoTexto($this->Abreviatura,1,2) && is_numeric($this->idCarrera) && is_numeric($this->idDepartamento) && is_numeric($this->idEstado)) {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();

                $this->consulta->prepare("
                    set @persona = {$_SESSION['idUsuario']};
                ")->execute();

                try {
                    $stmt = $this->consulta->prepare("CALL SP_Registrar_Carrera ($this->idCarrera, '$this->Carrera', '$this->Abreviatura', $this->idDepartamento, $this->idEstado, 'actualizarCarrera', @resp)");
                    if ($stmt->execute()) {
                        $resp = $this->consulta->query('SELECT @resp')->fetch();
                        if(json_encode($resp[0])==0){
                            return array(
                                'status'=> INTERNAL_SERVER_ERROR,
                                'data' => array('message' => 'Es posible que la carrera o la abreviatura ya esten registrados')
                            );
                        }else{
                            return array(
                                'status' => SUCCESS_REQUEST,
                                'data' => array('message'=>'Informacion de la carrera actualizado con exito')
                            );
                        }
                    } else {
                        return array(
                            'status'=> BAD_REQUEST,
                            'data' => array('message' => 'Ha ocurrido un error al actualizar la informacion de la carrera')
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
    }