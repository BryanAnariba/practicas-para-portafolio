<?php
    require_once('../../config/config.php');
    require_once('../../database/Conexion.php');
    require_once('../../validators/validators.php');
    class TipoPresupuesto {
        private $idTipoPresupuesto;
        private $tipoPresupuesto;

        public function getIdTipoPresupuesto()
        {
                return $this->idTipoPresupuesto;
        }

        public function setIdTipoPresupuesto($idTipoPresupuesto)
        {
                $this->idTipoPresupuesto = $idTipoPresupuesto;

                return $this;
        }

        public function getTipoPresupuesto()
        {
                return $this->tipoPresupuesto;
        }

        public function setTipoPresupuesto($tipoPresupuesto)
        {
                $this->tipoPresupuesto = $tipoPresupuesto;

                return $this;
        }
        private $conexionBD;
        private $consulta;
        private $tablaBaseDatos;

        // constructor
        public function __construct() {
            $this->tablaBaseDatos = TBL_TIPO_PRESUPUESTO;
        }
        public function getPresupuestosActivos() {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare('SELECT * FROM TipoPresupuesto');
                if ($stmt->execute()) {
                    return array(
                        'status'=> SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error, los tipos de presupuestos no se pueden ver')
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