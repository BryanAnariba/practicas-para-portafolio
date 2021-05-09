<?php
    require_once('../../config/config.php');
    require_once('../../database/Conexion.php');
    class TipoUsuario {
        private $idTipoUsuario;
        private $tipoUsuario;
        private $abrev;

        private $conexionBD;
        private $consulta;
        private $tablaBaseDatos;


        public function __construct() {
            $this->tablaBaseDatos = TBL_TIPO_USUARIO;
        }

        public function getIdTipoUsuario() {
            return $this->idTipoUsuario;
        }

        public function setIdTipoUsuario($idTipoUsuario) {
            $this->idTipoUsuario = $idTipoUsuario;
            return $this;
        }

        public function getTipoUsuario() {
            return $this->tipoUsuario;
        }

        public function setTipoUsuario($tipoUsuario) {
            $this->tipoUsuario = $tipoUsuario;
            return $this;
        }

        public function getAbrev() {
                return $this->abrev;
        }

        public function setAbrev($abrev) {
            $this->abrev = $abrev;
            return $this;
        }

        public function getTiposUsuarios () {
            $this->conexionBD = new Conexion();
            $this->consulta = $this->conexionBD->connect();
            try {
                $stmt = $this->consulta->prepare('CALL SP_LISTAR_TIPOS_USUARIOS()');
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

        public function getTipoUsuarioPorId () {

        }

        public function registrarTipoUsuario () {

        }

        public function actualizarTipoUsuario () {

        }
    }
?>