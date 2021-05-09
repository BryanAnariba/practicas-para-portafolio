<?php
    if (!isset($_SESSION)) {
        session_start();
    }
    require_once('../../config/config.php');
    require_once('../../database/Conexion.php');
    require_once('../../validators/validators.php');
    class AreaEstrategica {
        private $idAreaEstrategica;
        private $idEstadoAreaEstrategica;
        private $idObjetivoInstitucional;
        private $areaEstrategica;

        private $conexionBD;
        private $consulta;
        private $tablaBaseDatos;

        public function getIdAreaEstrategica() {
            return $this->idAreaEstrategica;
        }

        public function setIdAreaEstrategica($idAreaEstrategica) {
            $this->idAreaEstrategica = $idAreaEstrategica;
            return $this;
        }
        
        public function getIdEstadoAreaEstrategica() {
            return $this->idEstadoAreaEstrategica;
        }

        public function setIdEstadoAreaEstrategica($idEstadoAreaEstrategica) {
            $this->idEstadoAreaEstrategica = $idEstadoAreaEstrategica;
            return $this;
        }

        public function getIdObjetivoInstitucional() {
            return $this->idObjetivoInstitucional;
        }

        public function setIdObjetivoInstitucional($idObjetivoInstitucional) {
            $this->idObjetivoInstitucional = $idObjetivoInstitucional;
            return $this;
        }

        public function getAreaEstrategica() {
            return $this->areaEstrategica;
        }

        public function setAreaEstrategica($areaEstrategica) {
            $this->areaEstrategica = $areaEstrategica;
            return $this;
        }

        public function __construct() {
            $this->tablaBaseDatos = TBL_AREA_ESTRATEGICA;
        }

        public function getAreasEstrategicas () {
            if (is_int($this->idObjetivoInstitucional)) {
                try {
                    $this->conexionBD = new Conexion();
                    $this->consulta = $this->conexionBD->connect();
                    $stmt = $this->consulta->prepare('CALL SP_LISTA_AREAS_POR_OBJ(:idObjetivo)');
                    $stmt->bindValue(':idObjetivo', $this->idObjetivoInstitucional);
                    if ($stmt->execute()) {
                        return array(
                            'status' => SUCCESS_REQUEST,
                            'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                        );
                    } else {
                        return array(
                            'status'=> BAD_REQUEST,
                            'data' => array('message' => 'Ha ocurrido un error al listar las areas estrategicas')
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
                    'data' => array('message' => 'Ha ocurrido un error, no se pueden mostrar las areas estrategicas')
                );
            }
        }

        public function getAreasActivasPorObjetivo() {
            if (is_int($this->idObjetivoInstitucional)) {
                try {
                    $this->conexionBD = new Conexion();
                    $this->consulta = $this->conexionBD->connect();
                    $stmt = $this->consulta->prepare('WITH CTE_LISTA_AREAS_ACTIVAS AS (SELECT * FROM AreaEstrategica) SELECT * FROM CTE_LISTA_AREAS_ACTIVAS WHERE CTE_LISTA_AREAS_ACTIVAS.idObjetivoInstitucional = :idObjetivo AND CTE_LISTA_AREAS_ACTIVAS.idEstadoAreaEstrategica = :idEstado;
                ');
                    $stmt->bindValue(':idObjetivo', $this->idObjetivoInstitucional);
                    $stmt->bindValue(':idEstado', $this->idEstadoAreaEstrategica);
                    if ($stmt->execute()) {
                        return array(
                            'status' => SUCCESS_REQUEST,
                            'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                        );
                    } else {
                        return array(
                            'status'=> BAD_REQUEST,
                            'data' => array('message' => 'Ha ocurrido un error al listar las areas estrategicas')
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
                    'data' => array('message' => 'Ha ocurrido un error, no se pueden mostrar las areas estrategicas')
                );
            }
        }

        public function insertaArea () {
            if (campoTexto($this->areaEstrategica, 1, 500) && is_int($this->idObjetivoInstitucional)) {
                $this->idEstadoAreaEstrategica = ESTADO_ACTIVO;
                try {
                    $this->conexionBD = new Conexion();
                    $this->consulta = $this->conexionBD->connect();

                    $this->consulta->prepare("
                        set @persona = {$_SESSION['idUsuario']};
                    ")->execute();

                    $stmt = $this->consulta->prepare('CALL SP_REGISTRA_AREA_ESTRATEGICA(:idObjetivo, :idEstado, :areaEstrategica)');
                    $stmt->bindValue(':idObjetivo', $this->idObjetivoInstitucional);
                    $stmt->bindValue(':idEstado', $this->idEstadoAreaEstrategica);
                    $stmt->bindValue(':areaEstrategica', $this->areaEstrategica);
                    if ($stmt->execute()) {
                        return array(
                            'status' => SUCCESS_REQUEST,
                            'data' => array('message' => 'Area ' . $this->areaEstrategica . ' registrada con exito')
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
                    'data' => array('message' => 'Ha ocurrido un error, el area estrategica no se pudo insertar')
                );
            }
        }

        public function modificarEstadoAreaEstrategica () {
            if (is_int($this->idAreaEstrategica) && is_int($this->idEstadoAreaEstrategica)) {
                if ($this->idEstadoAreaEstrategica == ESTADO_ACTIVO) {
                    $this->idEstadoAreaEstrategica = ESTADO_INACTIVO;
                } else if ($this->idEstadoAreaEstrategica == ESTADO_INACTIVO) {
                    $this->idEstadoAreaEstrategica = ESTADO_ACTIVO;
                }
                try {
                    $this->conexionBD = new Conexion();
                    $this->consulta = $this->conexionBD->connect();

                    $this->consulta->prepare("
                        set @persona = {$_SESSION['idUsuario']};
                    ")->execute();

                    $stmt = $this->consulta->prepare('CALL SP_CAMBIA_ESTADO_AREA(:idArea, :idEstadoArea)');
                    $stmt->bindValue(':idArea', $this->idAreaEstrategica);
                    $stmt->bindValue(':idEstadoArea', $this->idEstadoAreaEstrategica);
                    if ($stmt->execute()) {
                        return array(
                            'status' => SUCCESS_REQUEST,
                            'data' => array('message' => 'Estado del area estrategica actualizada con exito')
                        );
                    } else {
                        return array(
                            'status'=> BAD_REQUEST,
                            'data' => array('error' => 'Ha ocurrido un error al actualizar el area estrategica')
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
                    'data' => array('message' => 'Ha ocurrido un error al actualizar el estado del area estrategica')
                );
            }
        }

        public function modificarAreaEstrategica () {
            if (campoTexto($this->areaEstrategica, 1, 500) && is_int($this->idAreaEstrategica)) {
                try {
                    $this->conexionBD = new Conexion();
                    $this->consulta = $this->conexionBD->connect();

                    $this->consulta->prepare("
                        set @persona = {$_SESSION['idUsuario']};
                    ")->execute();

                    $stmt = $this->consulta->prepare('CALL SP_MODIFICA_AREA(:idArea, :area)');
                    $stmt->bindValue(':idArea', $this->idAreaEstrategica);
                    $stmt->bindValue(':area', $this->areaEstrategica);
                    if ($stmt->execute()) {
                        return array(
                            'status' => SUCCESS_REQUEST,
                            'data' => array('message' => 'El area estrategica fue actualizado existosamente')
                        );
                    } else {
                        return array(
                            'status'=> BAD_REQUEST,
                            'data' => array('message' => 'Ha ocurrido un error al actualizar el area estrategica')
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
                    'data' => array('message' => 'Ha ocurrido un error, el area estrategica no se pudo actualizar')
                );
            }
        }
    }
?>