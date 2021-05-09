<?php
    if (!isset($_SESSION)) {
        session_start();
    }
    require_once('../../config/config.php');
    require_once('../../database/Conexion.php');
    class Persona {
        protected $idPersona;
        protected $idLugar;
        protected $idGenero; // NO ESTA EN USO
        protected $nombrePersona;
        protected $apellidoPersona;
        protected $telefono; // NO ESTA EN USO
        protected $fechaNacimiento;
        protected $direccion;
        private $tablaBaseDatos;

        private $conexionBD;
        private $consulta;
        private $data;

        // Constructor
        public function __construct() {
            $this->tablaBaseDatos = TBL_PERSONA;
        }

        // Metodos getters y setters
    
        public function getIdPersona() {
            return $this->idPersona;
        }

        public function setIdPersona($idPersona) {
            $this->idPersona = $idPersona;
            return $this;
        }

        public function getIdLugar() {
            return $this->idLugar;
        }

        public function setIdLugar($idLugar) {
            $this->idLugar = $idLugar;
            return $this;
        }

        public function getIdGenero() {
            return $this->idGenero;
        }

        public function setIdGenero($idGenero) {
            $this->idGenero = $idGenero;
            return $this;
        }
        
        public function getNombrePersona() {
            return $this->nombrePersona;
        }

        public function setNombrePersona($nombrePersona) {
            $this->nombrePersona = $nombrePersona;
            return $this;
        }

        public function getApellidoPersona() {
            return $this->apellidoPersona;
        }

        public function setApellidoPersona($apellidoPersona) {
            $this->apellidoPersona = $apellidoPersona;
            return $this;
        }

        public function getTelefono() {
                return $this->telefono;
        }

        public function setTelefono($telefono) {
            $this->telefono = $telefono;
            return $this;
        }

        public function getFechaNacimiento() {
                return $this->fechaNacimiento;
        }

        public function setFechaNacimiento($fechaNacimiento) {
            $this->fechaNacimiento = $fechaNacimiento;
            return $this;
        }

        public function getDireccion() {
            return $this->direccion;
        }

        public function setDireccion($direccion) {
            $this->direccion = $direccion;
            return $this;
        }

        //                                          Metodos para la clase Persona

        public function registrarPersona () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();

                $this->consulta->prepare("
                    set @persona = {$_SESSION['idUsuario']};
                ")->execute();

                $stmt = $this->consulta->prepare('INSERT INTO Persona (nombrePersona, apellidoPersona, idLugar, idGenero , direccion,  fechaNacimiento) VALUES (:nombre, :apellido, :lugar,:idGenero, :direccionLugar, :fechaDeNacimiento)');
                $stmt->bindValue(':nombre', $this->nombrePersona);
                $stmt->bindValue(':apellido', $this->apellidoPersona);
                $stmt->bindValue(':lugar', $this->idLugar);
                $stmt->bindValue(':idGenero', NULL);
                $stmt->bindValue(':direccionLugar', $this->direccion);
                $stmt->bindValue(':fechaDeNacimiento', $this->fechaNacimiento);

                // Si la consulta se ejecuto y afecto una fila retorne el ultimo id Insertado
                if ($stmt->execute()) {
                    return $this->consulta->lastInsertId();
                } else {
                    return null;
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

        public function modificaInformacionGeneralPersona () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();

                $this->consulta->prepare("
                    set @persona = {$_SESSION['idUsuario']};
                ")->execute();

                $stmt = $this->consulta->prepare('CALL SP_MODIF_DATOS_GEN_PERSONA(:nombre,:apellido,:fecha,:idUsuario)');
                $stmt->bindValue(':nombre', $this->nombrePersona);
                $stmt->bindValue(':apellido', $this->apellidoPersona);
                $stmt->bindValue(':fecha', $this->fechaNacimiento);
                $stmt->bindValue(':idUsuario', $this->idPersona);
                if ($stmt->execute()) {
                    return true;
                } else {
                    return false;
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

        
        public function modificaDireccion () {
            if (is_int($this->idPersona) && is_int($this->idLugar)) {
                try {
                    $this->conexionBD = new Conexion();
                    $this->consulta = $this->conexionBD->connect();

                    $this->consulta->prepare("
                        set @persona = {$_SESSION['idUsuario']};
                    ")->execute();

                    $stmt = $this->consulta->prepare('CALL SP_MODIFICA_DIRECCION_PERSONA(:idUsuario, :lugar , :direccionLugar)');
                    $stmt->bindValue(':idUsuario', $this->idPersona);
                    $stmt->bindValue(':lugar', $this->idLugar);
                    $stmt->bindValue(':direccionLugar', $this->direccion);
                    if ($stmt->execute()) {
                        return array(
                            'status'=> SUCCESS_REQUEST,
                            'data' => array('message' => 'La direccion del usuario se actualizo exitosamente')
                        );
                    } else {
                        return array(
                            'status'=> BAD_REQUEST,
                            'data' => array('message' => 'Ha ocurrido un error, la direccion del usuario se no se pudo actualizar')
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
                    'data' => array('message' => 'Ha ocurrido un error, los datos ha modificar son erroneos')
                );
            }
        }
    }
?>