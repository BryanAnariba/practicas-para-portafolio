<?php
    if (!isset($_SESSION)) {
        session_start();
    }
    require_once('../../config/config.php');
    require_once('../../database/Conexion.php');
    require_once('../../validators/validators.php');

    class Departamentos {
        protected $idDepartamento;
        protected $idEstadoDepartamento;
        protected $nombreDepartamento;
        protected $telefonoDepartamento;
        protected $abreviaturaDepartamento;
        protected $correoDepartamento;
        

        //protected $conexionBD;
        //protected $consulta;

        protected function _construct($idDepartamento = null,
                                        $idEstadoDepartamento = null,
                                        $nombreDepartamento = null,
                                        $telefonoDepartamento = null,
                                        $abreviaturaDepartamento = null,
                                        $correoDepartamento = null
                                    ){
            $this->idDepartamento = $idDepartamento;
            $this->nombreDepartamento = $nombreDepartamento;
            $this->idEstadoDepartamento = $idEstadoDepartamento;
            $this->telefonoDepartamento = $telefonoDepartamento;
            $this->abreviaturaDepartamento = $abreviaturaDepartamento;
            $this->correoDepartamento = $correoDepartamento;
            
        }

        public function getIdDepartamento() {
            return $this->idDepartamento;
        }

        public function setIdDepartamento($idDepartamento) {
            $this->idDepartamento = $idDepartamento;
            return $this;
        }

        public function getNombreDepartamento() {
            return $this->nombreDepartamento;
        }

        public function setNombreDepartamento($nombreDepartamento) {
            $this->nombreDepartamento = $nombreDepartamento;
            return $this;
        }

        public function getAbreviaturaDepartamento() {
            return $this->abreviaturaDepartamento;
        }

        public function setAbreviaturaDepartamento($abreviaturaDepartamento) {
            $this->abreviaturaDepartamento = $abreviaturaDepartamento;
            return $this;
        }

        public function getIdEstadoDepartamento() {
            return $this->idEstadoDepartamento;
        }

        public function setIdEstadoDepartamento($idEstadoDepartamento) {
            $this->idEstadoDepartamento = $idEstadoDepartamento;
            return $this;
        }

        public function getCorreoDepartamento() {
            return $this->correoDepartamento;
        }

        public function setCorreoDepartamento($correoDepartamento) {
            $this->correoDepartamento = $correoDepartamento;
            return $this;
        }

        public function getTelefonoDepartamento() {
            return $this->telefonoDepartamento;
        }

        public function setTelefonoDepartamento($telefonoDepartamento) {
            $this->telefonoDepartamento = $telefonoDepartamento;
            return $this;
        }

        
        //Función para ver los departamentos registrados
        public function getDepartamentos () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare('SELECT Departamento.idDepartamento,
                                                        Departamento.nombreDepartamento,
                                                        Departamento.abrev,
                                                        Departamento.telefonoDepartamento,            
                                                        Departamento.correoDepartamento,
                                                        EstadoDCDUOAO.estado
                                                FROM Departamento
                                                LEFT JOIN EstadoDCDUOAO 
                                                ON (Departamento.idEstadoDepartamento = EstadoDCDUOAO.idEstado) 
                                                ORDER BY Departamento.idDepartamento;');
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

        
        //Función para obtener los estados del departamento
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



        //Función para registrar nuevo departamento
        public function registraDepartamento () {
            if (is_numeric($this->idDepartamento) && 
                is_numeric($this->idEstadoDepartamento) && 
                campoTexto($this->nombreDepartamento,1,80) &&                  
                validaCampotelefono($this->telefonoDepartamento) &&
                campoTexto($this->abreviaturaDepartamento,1,2) &&
                validaCampoEmail($this->correoDepartamento)) {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();

                $this->consulta->prepare("
                    set @persona = {$_SESSION['idUsuario']};
                ")->execute();

                try {
                    $stmt = $this->consulta->prepare('CALL SP_REGISTRAR_DEPARTAMENTO(:idDepartamento,
                                                                                        :idEstadoDepartamento, 
                                                                                        :nombreDepartamento, 
                                                                                        :telefonoDepartamento,
                                                                                        :abreviaturaDepartamento,
                                                                                        :correoDepartamento)');
                    $stmt->bindValue(':idDepartamento', $this->idDepartamento);                                                             
                    $stmt->bindValue(':idEstadoDepartamento', $this->idEstadoDepartamento);
                    $stmt->bindValue(':nombreDepartamento', $this->nombreDepartamento);
                    $stmt->bindValue(':telefonoDepartamento', $this->telefonoDepartamento);
                    $stmt->bindValue(':abreviaturaDepartamento', $this->abreviaturaDepartamento);
                    $stmt->bindValue(':correoDepartamento', $this->correoDepartamento);
                    if ($stmt->execute()) {
                        return array(
                            'status' => SUCCESS_REQUEST,
                            'data' => array('message' => 'Departamento ' . $this->nombreDepartamento . ' registrado con exito')
                        );
                    } else {
                        return array(
                            'status'=> BAD_REQUEST,
                            'data' => array('error' => 'Ha ocurrido un error al insertar el departamento')
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


        //función para listar departamentos a modificar 
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


        //función para departamentos segun id seleccionado en la sección de modificacion de departamentos
        public function getDepartamentosPorId () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("SELECT * from Departamento where idDepartamento=$this->idDepartamento");
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar los Departamentos')
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


        //funcion para modificar y/o actualizar
    
        public function modificarDepartamento () {
            if (is_numeric($this->idDepartamento) && 
                is_numeric($this->idEstadoDepartamento) && 
                campoTexto($this->nombreDepartamento,1,80) &&                  
                validaCampotelefono($this->telefonoDepartamento) &&
                campoTexto($this->abreviaturaDepartamento,1,2) &&
                validaCampoEmail($this->correoDepartamento)) {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();

                $this->consulta->prepare("
                    set @persona = {$_SESSION['idUsuario']};
                ")->execute();

                try {
                    $stmt = $this->consulta->prepare('CALL SP_MODIFICAR_DEPARTAMENTO(:idDepartamento,
                                                                                        :idEstadoDepartamento, 
                                                                                        :nombreDepartamento, 
                                                                                        :telefonoDepartamento,
                                                                                        :abreviaturaDepartamento,
                                                                                        :correoDepartamento)');
                    $stmt->bindValue(':idDepartamento', $this->idDepartamento);                                                             
                    $stmt->bindValue(':idEstadoDepartamento', $this->idEstadoDepartamento);
                    $stmt->bindValue(':nombreDepartamento', $this->nombreDepartamento);
                    $stmt->bindValue(':telefonoDepartamento', $this->telefonoDepartamento);
                    $stmt->bindValue(':abreviaturaDepartamento', $this->abreviaturaDepartamento);
                    $stmt->bindValue(':correoDepartamento', $this->correoDepartamento);
                    if ($stmt->execute()) {
                        return array(
                            'status' => SUCCESS_REQUEST,
                            'data' => array('message' => 'Departamento ' . $this->nombreDepartamento . ' actualizado con exito')
                        );
                    } else {
                        return array(
                            'status'=> BAD_REQUEST,
                            'data' => array('error' => 'Ha ocurrido un error al actualizar el departamento')
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



    }
?>