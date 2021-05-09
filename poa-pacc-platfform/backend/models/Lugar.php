<?php
    require_once('../../config/config.php');
    require_once('../../database/Conexion.php');
    class Lugar {
        private $idLugar;
        private $idTipoLugar;
        private $idLugarPadre;
        private $nombreLugar;

        private $conexionBD;
        private $consulta;
        private $tablaBaseDatos;

        public function getIdLugar() {
            return $this->idLugar;
        }

        public function setIdLugar($idLugar) {
            $this->idLugar = $idLugar;
            return $this;
        }

        public function getIdTipoLugar() {
            return $this->idTipoLugar;
        }

        public function setIdTipoLugar($idTipoLugar) {
            $this->idTipoLugar = $idTipoLugar;
            return $this;
        }

        public function getIdLugarPadre() {
            return $this->idLugarPadre;
        }

        public function setIdLugarPadre($idLugarPadre) {
            $this->idLugarPadre = $idLugarPadre;
            return $this;
        }

        public function getNombreLugar() {
            return $this->nombreLugar;
        }

        public function setNombreLugar($nombreLugar) {
            $this->nombreLugar = $nombreLugar;
            return $this;
        }

        public function __construct() {
            $this->tablaBaseDatos = TBL_LUGARES;
        }

        public function getPaises () {
            $this->conexionBD = new Conexion();
            $this->consulta = $this->conexionBD->connect();
            if (is_int($this->idTipoLugar)==false) {
                return array(
                    'status'=> BAD_REQUEST,
                    'data' => array(
                        'message' => 'Ha ocurrido un error al retornar la informacion de los paises'
                    )
                );
            } else {
                try {
                    $stmt = $this->consulta->prepare('CALL SP_LISTAR_PAISES(:id)');
                    $stmt->bindValue(':id', $this->idTipoLugar);
                    if ($stmt->execute()) {
                        return array(
                            'status' => SUCCESS_REQUEST,
                            'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                        );
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
            }
        }

        public function getCiudades () {
            $this->conexionBD = new Conexion();
            $this->consulta = $this->conexionBD->connect();
            if (is_int($this->idLugarPadre)==false) {
                return array(
                    'status'=> BAD_REQUEST,
                    'data' => array(
                        'message' => 'Ha ocurrido un error al retornar la informacion de las ciudades del pais seleccionado'
                    )
                );
            } else {
                try {
                    $stmt = $this->consulta->prepare('CALL SP_LISTAR_CIUDADES_PAIS(:id)');
                    $stmt->bindValue(':id', $this->idLugarPadre);
                    if ($stmt->execute()) {
                        return array(
                            'status' => SUCCESS_REQUEST,
                            'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                        );
                    } else {
                        return array(
                            'status'=> BAD_REQUEST,
                            'data' => array('message' => 'Ha ocurrido un error')
                        );
                    }
                }catch (PDOException $ex) {
                    return array(
                        'status'=> INTERNAL_SERVER_ERROR,
                        'data' => array($ex->getMessage())
                    );
                } finally {
                    $this->conexionBD = null;
                }
            }
        }

        public function getMunicipios () {
            $this->conexionBD = new Conexion();
            $this->consulta = $this->conexionBD->connect();
            if (is_int($this->idLugarPadre)==false) {
                return array(
                    'status'=> BAD_REQUEST,
                    'data' => array(
                        'message' => 'Ha ocurrido un error al retornar la informacion de las ciudades del pais'
                    )
                );
            } else { 
                try {
                    $stmt = $this->consulta->prepare('CALL SP_LISTAR_MUNICIPIOS_CIUDAD(:id)');
                    $stmt->bindValue(':id', $this->idLugarPadre);
                    if ($stmt->execute()) {
                        return array(
                            'status' => SUCCESS_REQUEST,
                            'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                        );
                    } else {
                        return array(
                            'status'=> BAD_REQUEST,
                            'data' => array('message' => 'Ha ocurrido un error')
                        );
                    }
                }catch (PDOException $ex) {
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
?>