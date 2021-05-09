<?php
    if (!isset($_SESSION)) {
        session_start();
    }
    require_once('../../validators/validators.php');
    
    class Objeto { 
        protected $idObjetoGasto;
        protected $ObjetoDeGasto;
        protected $Abreviatura;
        protected $CodigoObjeto;
        protected $idEstado;

        protected function _construct($idObjetoGasto = null,$ObjetoDeGasto = null,$Abreviatura = null,$CodigoObjeto = null,$idEstado = null){
            $this->idObjetoGasto = $idObjetoGasto;
            $this->ObjetoDeGasto = $ObjetoDeGasto;
            $this->Abreviatura = $Abreviatura;
            $this->CodigoObjeto = $CodigoObjeto;
            $this->idEstado = $idEstado;
        }

        public function getIdObjetoGasto(){
            return $this->idObjetoGasto;
        }

        public function getObjetoDeGasto(){
            return $this->ObjetoDeGasto;
        }

        public function getAbreviatura(){
            return $this->Abreviatura;
        }

        public function getCodigoObjeto(){
            return $this->CodigoObjeto;
        }

        public function getidEstado(){
            return $this->idEstado;
        }

        public function setIdObjetoGasto($idObjetoGasto){
            $this->idObjetoGasto = $idObjetoGasto;
            return $this;
        }
        
        public function setObjetoDeGasto($ObjetoDeGasto){
            $this->ObjetoDeGasto = $ObjetoDeGasto;
            return $this;
        }
    
        public function setAbreviatura($Abreviatura){
            $this->Abreviatura = $Abreviatura;
            return $this;
        }
        
        public function setCodigoObjeto($CodigoObjeto){
            $this->CodigoObjeto = $CodigoObjeto;
            return $this;
        }

        public function setidEstado($idEstado){
            $this->idEstado = $idEstado;
            return $this;
        }

        public function getObjetos () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare('SELECT * from 
                                                  ObjetoGasto as og
                                                  inner join EstadoDCDUOAO as es
                                                  on og.idEstadoObjetoGasto=es.idEstado
                                                  order by og.codigoObjetoGasto asc');
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar los objetos de gasto')
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

        public function getObjetosActivos() {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare('SELECT * from 
                                                  ObjetoGasto as og
                                                  inner join EstadoDCDUOAO as es
                                                  on og.idEstadoObjetoGasto=es.idEstado
                                                  where og.idEstadoObjetoGasto = :idEstado
                                                  order by og.codigoObjetoGasto asc');
                $stmt->bindValue(':idEstado', $this->idEstado);
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar los objetos de gasto')
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
                        'data' => array('message' => 'Ha ocurrido un error al listar los estados')
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

        public function insertarObjeto () {
            if (campoTexto($this->ObjetoDeGasto,1,80) && campoAbrevCodigo($this->Abreviatura,1,15) && campoCodigo($this->CodigoObjeto,1,15) && is_numeric($this->idEstado)) {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                
                $this->consulta->prepare("
                set @persona = {$_SESSION['idUsuario']};
                ")->execute();

                try {
                    $stmt = $this->consulta->prepare("CALL SP_Registrar_Objeto (0, '$this->ObjetoDeGasto', '$this->Abreviatura', '$this->CodigoObjeto', $this->idEstado, 'insert', @resp)");
                    if ($stmt->execute()) {
                        $resp = $this->consulta->query('SELECT @resp')->fetch();
            
                        if(json_encode($resp[0])==0){
                            return array(
                                'status'=> INTERNAL_SERVER_ERROR,
                                'data' => array('message' => 'Es posible que el objeto del gasto, el codigo o la abreviatura ya esten registrados')
                            );
                        }else{
                            return array(
                                'status' => SUCCESS_REQUEST,
                                'data' => array('message' => $this->ObjetoDeGasto . ' registrada con exito')
                            );;
                        };
                    } else {
                        return array(
                            'status'=> BAD_REQUEST,
                            'data' => array('error' => 'Ha ocurrido un error al insertar el objeto de gasto')
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
        public function modificarObjeto () {
            if (campoTexto($this->ObjetoDeGasto,1,80) && campoAbrevCodigo($this->Abreviatura,1,15) && campoCodigo($this->CodigoObjeto,1,15) && is_numeric($this->idObjetoGasto) && is_numeric($this->idEstado)) {
                try {
                    $this->conexionBD = new Conexion();
                    $this->consulta = $this->conexionBD->connect();

                    $this->consulta->prepare("
                        set @persona = {$_SESSION['idUsuario']};
                    ")->execute();

                    $stmt = $this->consulta->prepare("CALL SP_Registrar_Objeto ($this->idObjetoGasto, '$this->ObjetoDeGasto', '$this->Abreviatura', '$this->CodigoObjeto', $this->idEstado, 'actualizarCarrera', @resp)");
                    if ($stmt->execute()) {
                        $resp = $this->consulta->query('SELECT @resp')->fetch();
                        if(json_encode($resp[0])==0){
                            return array(
                                'status'=> INTERNAL_SERVER_ERROR,
                                'data' => array('message' => 'Es posible que el objeto del gasto, el codigo o la abreviatura ya esten registrados')
                            );
                        }else{
                            return array(
                                'status' => SUCCESS_REQUEST,
                                'data' => array('message'=>'Informacion del objeto de gasto actualizado con exito')
                            );
                        }
                    } else {
                        return array(
                            'status'=> BAD_REQUEST,
                            'data' => array('message' => 'Ha ocurrido un error al actualizar la informacion del objeto de gasto')
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