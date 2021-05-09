<?php
    require_once('../../config/config.php');
    require_once('../../database/Conexion.php');
    require_once('../../validators/validators.php');
    class TipoActividad {
        private $idTipoActividad;
        private $tipoActividad;
 
        private $conexionBD;
        private $consulta;
        private $tablaBaseDatos;

        public function getIdTipoActividad() {
            return $this->idTipoActividad;
        }

        public function setIdTipoActividad($idTipoActividad) {
            $this->idTipoActividad = $idTipoActividad;
            return $this;
        }

        public function getTipoActividad() {
            return $this->tipoActividad;
        }

        public function setTipoActividad($tipoActividad) {
            $this->tipoActividad = $tipoActividad;
            return $this;
        }

        public function __construct() {
            $this->tablaBaseDatos = TBL_TIPO_ACTIVIDAD;
        }

        public function getTipoCostosActividad () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("SELECT * FROM " . $this->tablaBaseDatos . " ORDER BY TipoActividad.idTipoActividad ASC");
                if ($stmt->execute()) {
                    return array(
                        'status'=> SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'ha ocurrido un error al listar los tipos de costos de la actividad')
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