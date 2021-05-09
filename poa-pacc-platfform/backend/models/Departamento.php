<?php
    require_once('../../config/config.php');
    require_once('../../database/Conexion.php');

    class Departamento {
        private $idDepartamento;
        private $nombreDepartamento;
        private $abrev;
        private $idEstadoDCDU;

        private $conexionBD;
        private $consulta;
        private $tablaBaseDatos;

        public function __construct() {
            $this->tablaBaseDatos = TBL_DEPARTAMENTO;
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

        public function getAbrev() {
            return $this->abrev;
        }

        public function setAbrev($abrev) {
            $this->abrev = $abrev;
            return $this;
        }

        public function getIdEstadoDCDU() {
            return $this->idEstadoDCDU;
        }

        public function setIdEstadoDCDU($idEstadoDCDU) {
            $this->idEstadoDCDU = $idEstadoDCDU;
            return $this;
        }

        public function getDepartamentosActivos () {
            $this->conexionBD = new Conexion();
            $this->consulta = $this->conexionBD->connect();
            if (is_int($this->idEstadoDCDU)==false) {
                return array(
                    'status'=> BAD_REQUEST,
                    'data' => array(
                        'message' => 'Ha ocurrido un error al retornar la informacion de los departamentos de la facultad'
                    )
                );
            } else {
                try {
                    $stmt = $this->consulta->prepare('CALL SP_LISTAR_DEPARTAMENTOS(:idEstado)');
                    $stmt->bindValue(':idEstado', $this->idEstadoDCDU);
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
    }
?>