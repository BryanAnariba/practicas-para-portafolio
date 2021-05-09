<?php
    if (!isset($_SESSION)) {
        session_start();
    }
    require_once('../../config/config.php');
    require_once('../../database/Conexion.php');
    require_once('../../validators/validators.php');

    class Dimension {
        private $idDimension;
        private $dimensionAdministrativa;
        private $idEstadoDimension;

        private $conexionBD;
        private $consulta;
        private $tablaBaseDatos;

        public function getIdDimension() {
            return $this->idDimension;
        }

        public function setIdDimension($idDimension) {
            $this->idDimension = $idDimension;
            return $this;
        }

        public function getDimensionAdministrativa() {
            return $this->dimensionAdministrativa;
        }

        public function setDimensionAdministrativa($dimensionAdministrativa) {
            $this->dimensionAdministrativa = $dimensionAdministrativa;
            return $this;
        }

        public function getIdEstadoDimension() {
            return $this->idEstadoDimension;
        }

        public function setIdEstadoDimension($idEstadoDimension) {
            $this->idEstadoDimension = $idEstadoDimension;
            return $this;
        }

        public function __construct() {
            $this->tablaBaseDatos = TBL_DIMENSIONES;
        }

        public function getDimensiones () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare('SELECT DimensionAdmin.idDimension, 
                                                    DimensionAdmin.idEstadoDimension, 
                                                    DimensionAdmin.dimensionAdministrativa, 
                                                    EstadoDCDUOAO.estado 
                                                FROM DimensionAdmin 
                                                LEFT JOIN EstadoDCDUOAO 
                                                ON (DimensionAdmin.idEstadoDimension = EstadoDCDUOAO.idEstado) 
                                                ORDER BY DimensionAdmin.idDimension ASC');
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar las dimensiones')
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

        public function getDimensionesActivas () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare('SELECT 	DimensionAdmin.idDimension, DimensionAdmin.idEstadoDimension, DimensionAdmin.dimensionAdministrativa, EstadoDCDUOAO.estado FROM DimensionAdmin LEFT JOIN EstadoDCDUOAO ON (DimensionAdmin.idEstadoDimension = EstadoDCDUOAO.idEstado) WHERE DimensionAdmin.idEstadoDimension = :idEstadoDimension ORDER BY DimensionAdmin.idDimension ASC');
                $stmt->bindValue(':idEstadoDimension', ESTADO_ACTIVO);
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar las dimensiones')
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

        public function insertaDimension () {
            if (campoTexto($this->dimensionAdministrativa,1,150) && is_int($this->idEstadoDimension)) {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();

                $this->consulta->prepare("
                set @persona = {$_SESSION['idUsuario']};
                ")->execute();

                try {
                    $stmt = $this->consulta->prepare('CALL SP_REGISTRA_DIM_ADMINISTRATIVA(:idEstadoDimension, :dimensionAdministrativa)');
                    $stmt->bindValue(':idEstadoDimension', $this->idEstadoDimension);
                    $stmt->bindValue(':dimensionAdministrativa', $this->dimensionAdministrativa);
                    if ($stmt->execute()) {
                        return array(
                            'status' => SUCCESS_REQUEST,
                            'data' => array('message' => 'Dimension ' . $this->dimensionAdministrativa . ' registrada con exito')
                        );
                    } else {
                        return array(
                            'status'=> BAD_REQUEST,
                            'data' => array('error' => 'Ha ocurrido un error al insertar la dimension')
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

        public function getEstadoDimension () {
            if (is_int($this->idDimension)) {
                try {
                    $this->conexionBD = new Conexion();
                    $this->consulta = $this->conexionBD->connect();
                    $stmt = $this->consulta->prepare('CALL SP_GET_DIMENSION_ADMINISTRATIVA(:idDimensionAdministrativa)');
                    $stmt->bindValue(':idDimensionAdministrativa', $this->idDimension);
                    if ($stmt->execute()) {
                        $resultados = $stmt->fetchObject();
                        if ($resultados->idEstadoDimension == ESTADO_ACTIVO) {
                            return ESTADO_INACTIVO;
                        } else if ($resultados->idEstadoDimension == ESTADO_INACTIVO) {
                            return ESTADO_ACTIVO;
                        }
                    } else {
                        return array(
                            'status'=> BAD_REQUEST,
                            'data' => array('message' => 'Ha ocurrido un error')
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

        public function modificaEstadoDimension () {
            try {
                $this->idEstadoDimension = $this->getEstadoDimension();
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();

                $this->consulta->prepare("
                    set @persona = {$_SESSION['idUsuario']};
                ")->execute();

                $stmt = $this->consulta->prepare('CALL SP_CAMBIA_ESTADO_DIMENSION_ADMIN(:idDimensionAdministrativa, :estadoDimension)');
                $stmt->bindValue(':idDimensionAdministrativa', $this->idDimension);
                $stmt->bindValue(':estadoDimension', $this->idEstadoDimension);
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => array('message'=>'Estado dimension administrativa actualizado con exito')
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al actualizar el estado de la dimension')
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

        public function modificarDimension () {
            if ((campoTexto($this->dimensionAdministrativa, 1, 150)) && is_int($this->idDimension)) {
                try {
                    $this->conexionBD = new Conexion();
                    $this->consulta = $this->conexionBD->connect();

                    $this->consulta->prepare("
                        set @persona = {$_SESSION['idUsuario']};
                    ")->execute();
                    $dimensionadmin = $this->consulta->query("SELECT * from DimensionAdmin where idDimension=$this->idDimension")->fetch();
                    $this->consulta->prepare("
                        set @valorI = JSON_OBJECT(
                            'idDimension', $this->idDimension ,
                            'idEstadoDimension',$dimensionadmin[idEstadoDimension],
                            'dimensionAdministrativa','$dimensionadmin[dimensionAdministrativa]'
                        );
                    ")->execute();
                    $this->consulta->prepare("
                        set @valorf = JSON_OBJECT(
                            'idDimension', $this->idDimension ,
                            'idEstadoDimension',$dimensionadmin[idEstadoDimension],
                            'dimensionAdministrativa','$this->dimensionAdministrativa,'
                        );
                    ")->execute();

                    $stmt = $this->consulta->prepare('CALL SP_MODIFICA_DIMENSION_ADMIN(:idDimensionAdministrativa, :dimensionAdministrativa)');
                    $stmt->bindValue(':idDimensionAdministrativa', $this->idDimension);
                    $stmt->bindValue(':dimensionAdministrativa', $this->dimensionAdministrativa);
                    if ($stmt->execute()) {
                        return array(
                            'status' => SUCCESS_REQUEST,
                            'data' => array('message'=>'Dimension administrativa ' . $this->dimensionAdministrativa . ' actualizada con exito')
                        );
                    } else {
                        return array(
                            'status'=> BAD_REQUEST,
                            'data' => array('message' => 'Ha ocurrido un error al actualizar la dimension')
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