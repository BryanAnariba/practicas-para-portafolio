<?php
    require_once('../../config/config.php');
    require_once('../../database/Conexion.php');

    class EstadoDCDUOAO {
        private $idEstado;
        private $estado;
        
        public function getIdEstado() {
            return $this->idEstado;
        }

        public function setIdEstado($idEstado) {
            $this->idEstado = $idEstado;
            return $this;
        }

        public function getEstado() {
            return $this->estado;
        }

        public function setEstado($estado){
            $this->estado = $estado;
            return $this;
        }

        public function __construct() {
            $this->tablaBaseDatos = TBL_ESTADOS;
        }

        public function getEstados () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare('WITH CTE_LISTAR_ESTADOS AS (SELECT * FROM ' . $this->tablaBaseDatos . ') SELECT * FROM CTE_LISTAR_ESTADOS');
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error, no se pueden mostrar los estados')
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
?>