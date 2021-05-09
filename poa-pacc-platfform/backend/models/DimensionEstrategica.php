<?php
    if (!isset($_SESSION)) {
        session_start();
    }
    require_once('../../config/config.php');
    require_once('../../database/Conexion.php');
    require_once('../../validators/validators.php');

    class Dimension {
        private $idDimension;
        private $dimensionEstrategica;
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

        public function getDimensionEstrategica() {
            return $this->dimensionEstrategica;
        }

        public function setDimensionEstrategica($dimensionEstrategica) {
            $this->dimensionEstrategica = $dimensionEstrategica;
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
                $stmt = $this->consulta->prepare('WITH RECURSIVE CTE_LISTAR_DIMENSIONES_ESTRATEGICAS AS (SELECT  DimensionEstrategica.idDimension, DimensionEstrategica.idEstadoDimension, DimensionEstrategica.dimensionEstrategica, EstadoDCDUOAO.estado FROM DimensionEstrategica LEFT JOIN EstadoDCDUOAO ON (DimensionEstrategica.idEstadoDimension = EstadoDCDUOAO.idEstado) ORDER BY DimensionEstrategica.idDimension ASC) SELECT * FROM CTE_LISTAR_DIMENSIONES_ESTRATEGICAS;');
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
                $stmt = $this->consulta->prepare('WITH CTE_LISTAR_DIMENSIONES_ESTRATEGICAS AS (SELECT  DimensionEstrategica.idDimension, DimensionEstrategica.idEstadoDimension, DimensionEstrategica.dimensionEstrategica, EstadoDCDUOAO.estado FROM DimensionEstrategica LEFT JOIN EstadoDCDUOAO ON (DimensionEstrategica.idEstadoDimension = EstadoDCDUOAO.idEstado) ORDER BY DimensionEstrategica.idDimension ASC) SELECT * FROM CTE_LISTAR_DIMENSIONES_ESTRATEGICAS WHERE CTE_LISTAR_DIMENSIONES_ESTRATEGICAS.idEstadoDimension = :idEstado;');
                $stmt->bindValue(':idEstado', $this->idEstadoDimension);
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
            if (campoTexto($this->dimensionEstrategica,1,150) && is_int($this->idEstadoDimension)) {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();

                $this->consulta->prepare("
                set @persona = {$_SESSION['idUsuario']};
                ")->execute();

                try {
                    $stmt = $this->consulta->prepare('CALL SP_REGISTRA_DIM_ESTRATEGICA(:idEstadoDimension, :dimensionEstrategica)');
                    $stmt->bindValue(':idEstadoDimension', $this->idEstadoDimension);
                    $stmt->bindValue(':dimensionEstrategica', $this->dimensionEstrategica);
                    if ($stmt->execute()) {
                        return array(
                            'status' => SUCCESS_REQUEST,
                            'data' => array('message' => 'Dimension ' . $this->dimensionEstrategica . ' registrada con exito')
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
                    $stmt = $this->consulta->prepare('CALL SP_GET_DIMENSION_ESTRATEGICA(:idDimensionEstrategica)');
                    $stmt->bindValue(':idDimensionEstrategica', $this->idDimension);
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

                $stmt = $this->consulta->prepare('CALL SP_CAMBIA_ESTADO_DIMENSION(:idDimensionEstrategica, :estadoDimension)');
                $stmt->bindValue(':idDimensionEstrategica', $this->idDimension);
                $stmt->bindValue(':estadoDimension', $this->idEstadoDimension);
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => array('message'=>'Estado dimension estrategica actualizado con exito')
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
            if ((campoTexto($this->dimensionEstrategica, 1, 150)) && is_int($this->idDimension)) {
                try {
                    $this->conexionBD = new Conexion();
                    $this->consulta = $this->conexionBD->connect();

                    $this->consulta->prepare("
                        set @persona = {$_SESSION['idUsuario']};
                    ")->execute();

                    $stmt = $this->consulta->prepare('CALL SP_MODIFICA_DIMENSION(:idDimensionEstrategica, :dimensionEstrategica)');
                    $stmt->bindValue(':idDimensionEstrategica', $this->idDimension);
                    $stmt->bindValue(':dimensionEstrategica', $this->dimensionEstrategica);
                    if ($stmt->execute()) {
                        return array(
                            'status' => SUCCESS_REQUEST,
                            'data' => array('message'=>'Dimension estrategica ' . $this->dimensionEstrategica . ' actualizada con exito')
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