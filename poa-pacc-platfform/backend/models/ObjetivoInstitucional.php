<?php
    if (!isset($_SESSION)) {
        session_start();
    }
    require_once('../../config/config.php');
    require_once('../../database/Conexion.php');
    require_once('../../validators/validators.php');
    class ObjetivoInstitucional {
        private $idObjetivoInstitucional;
        private $idDimensionEstrategica;
        private $idEstadoObjetivoInstitucional;
        private $objetivoInstitucional;

        private $conexionBD;
        private $consulta;
        private $tablaBaseDatos;

        public function getIdObjetivoInstitucional() {
            return $this->idObjetivoInstitucional;
        }

        public function setIdObjetivoInstitucional($idObjetivoInstitucional) {
            $this->idObjetivoInstitucional = $idObjetivoInstitucional;
            return $this;
        }

        public function getIdDimensionEstrategica() {
            return $this->idDimensionEstrategica;
        }
        
        public function setIdDimensionEstrategica($idDimensionEstrategica) {
            $this->idDimensionEstrategica = $idDimensionEstrategica;
            return $this;
        }

        public function getIdEstadoObjetivoInstitucional() {
            return $this->idEstadoObjetivoInstitucional;
        }

        public function setIdEstadoObjetivoInstitucional($idEstadoObjetivoInstitucional) {
            $this->idEstadoObjetivoInstitucional = $idEstadoObjetivoInstitucional;
            return $this;
        }

        public function getObjetivoInstitucional() {
            return $this->objetivoInstitucional;
        }

        public function setObjetivoInstitucional($objetivoInstitucional) {
            $this->objetivoInstitucional = $objetivoInstitucional;
            return $this;
        }

        public function __construct() {
            $this->tablaBaseDatos = TBL_OBJETIVO_INSTITUCIONAL;
        }
        public function getObjetivosPorDimension () {
            if (is_int($this->idDimensionEstrategica)) {
                try {
                    $this->conexionBD = new Conexion();
                    $this->consulta = $this->conexionBD->connect();
                    $stmt = $this->consulta->prepare('CALL SP_LISTA_OBJ_POR_DIM(:idDimension)');
                    $stmt->bindValue(':idDimension', $this->idDimensionEstrategica);
                    if ($stmt->execute()) {
                        return array(
                            'status' => SUCCESS_REQUEST,
                            'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                        );
                    } else {
                        return array(
                            'status'=> BAD_REQUEST,
                            'data' => array('message' => 'Ha ocurrido un error al listar los objetivos institucionales')
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
                    'data' => array('message' => 'Ha ocurrido un error al listar los objetivos institucionales')
                );
            }
        }

        public function getObjetivosActivosPorDimension () {
            if (is_int($this->idDimensionEstrategica)) {
                try {
                    $this->conexionBD = new Conexion();
                    $this->consulta = $this->conexionBD->connect();
                    $stmt = $this->consulta->prepare('WITH CTE_LISTA_OBJETIVOS_ACTIVOS AS (SELECT * FROM ObjetivoInstitucional) SELECT * FROM CTE_LISTA_OBJETIVOS_ACTIVOS WHERE CTE_LISTA_OBJETIVOS_ACTIVOS.idDimensionEstrategica = :idDimension AND CTE_LISTA_OBJETIVOS_ACTIVOS.idEstadoObjetivoInstitucional = :idEstado;');
                    $stmt->bindValue(':idDimension', $this->idDimensionEstrategica);
                    $stmt->bindValue(':idEstado', $this->idEstadoObjetivoInstitucional);
                    if ($stmt->execute()) {
                        return array(
                            'status' => SUCCESS_REQUEST,
                            'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                        );
                    } else {
                        return array(
                            'status'=> BAD_REQUEST,
                            'data' => array('message' => 'Ha ocurrido un error al listar los objetivos institucionales')
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
                    'data' => array('message' => 'Ha ocurrido un error al listar los objetivos institucionales')
                );
            }
        }

        public function registrarObjetivoPorDimension () {
            if (campoTexto($this->objetivoInstitucional, 1, 800) && is_int($this->idDimensionEstrategica)) {
                $this->idEstadoObjetivoInstitucional = ESTADO_ACTIVO;
                try {
                    $this->conexionBD = new Conexion();
                    $this->consulta = $this->conexionBD->connect();
                    
                    $this->consulta->prepare("
                        set @persona = {$_SESSION['idUsuario']};
                    ")->execute();

                    $stmt = $this->consulta->prepare('CALL SP_REGISTRA_OBJETIVO(:idDimension,:idEstado,:objetivoInstitucional)');
                    $stmt->bindValue(':idDimension', $this->idDimensionEstrategica);
                    $stmt->bindValue(':idEstado', $this->idEstadoObjetivoInstitucional);
                    $stmt->bindValue(':objetivoInstitucional', $this->objetivoInstitucional);
                    if ($stmt->execute()) {
                        return array(
                            'status' => SUCCESS_REQUEST,
                            'data' => array('message' => 'Objetivo institucional ' . $this->objetivoInstitucional . ' insertado exitosamente')
                        );
                    } else {
                        return array(
                            'status'=> BAD_REQUEST,
                            'data' => array('message' => 'Ha ocurrido un error al registrar el objetivo institucional')
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
                    'data' => array('message' => 'Ha ocurrido un error, la insercion del objetivo institucional no se pudo realizar')
                );
            }
        }

        public function modificaEstadoObjetivo() {
            if (is_int($this->idObjetivoInstitucional) && is_int($this->idEstadoObjetivoInstitucional)) {
                if ($this->idEstadoObjetivoInstitucional == ESTADO_ACTIVO) {
                    $this->idEstadoObjetivoInstitucional = ESTADO_INACTIVO;
                } else if ($this->idEstadoObjetivoInstitucional == ESTADO_INACTIVO) {
                    $this->idEstadoObjetivoInstitucional = ESTADO_ACTIVO;
                }
                try {
                    $this->conexionBD = new Conexion();
                    $this->consulta = $this->conexionBD->connect();

                    $this->consulta->prepare("
                        set @persona = {$_SESSION['idUsuario']};
                    ")->execute();

                    $stmt = $this->consulta->prepare('CALL SP_CAMBIA_ESTADO_OBJETIVO(:idObjetivo, :idEstado)');
                    $stmt->bindValue(':idObjetivo', $this->idObjetivoInstitucional);
                    $stmt->bindValue(':idEstado', $this->idEstadoObjetivoInstitucional);
                    if ($stmt->execute()) {
                        return array(
                            'status' => SUCCESS_REQUEST,
                            'data' => array('message' => 'Estado del objetivo institucional fue actualizado existosamente')
                        );
                    } else {
                        return array(
                            'status'=> BAD_REQUEST,
                            'data' => array('message' => 'Ha ocurrido un error al actualizar el estado del objetivo insitucional')
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
                    'data' => array('message' => 'Ha ocurrido un error, la modificacion del estado del objetivo institucional no se pudo realizar')
                );
            }
        }

        public function modificarObjetivo () {
            if (campoTexto($this->objetivoInstitucional, 1, 800) && is_int($this->idObjetivoInstitucional)) {
                try {
                    $this->conexionBD = new Conexion();
                    $this->consulta = $this->conexionBD->connect();

                    $this->consulta->prepare("
                        set @persona = {$_SESSION['idUsuario']};
                    ")->execute();

                    $stmt = $this->consulta->prepare('CALL SP_MODIFICA_OBJETIVO(:idObjetivo, :objetivo)');
                    $stmt->bindValue(':idObjetivo', $this->idObjetivoInstitucional);
                    $stmt->bindValue(':objetivo', $this->objetivoInstitucional);
                    if ($stmt->execute()) {
                        return array(
                            'status' => SUCCESS_REQUEST,
                            'data' => array('message' => 'El objetivo institucional fue actualizado existosamente')
                        );
                    } else {
                        return array(
                            'status'=> BAD_REQUEST,
                            'data' => array('message' => 'Ha ocurrido un error al actualizar el objetivo institucional')
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
                    'data' => array('message' => 'Ha ocurrido un error, la modificacion del objetivo institucional no se pudo realizar')
                );
            }
        }
    }
?>