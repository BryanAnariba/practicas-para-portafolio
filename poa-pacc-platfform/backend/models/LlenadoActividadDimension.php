<?php
    if (!isset($_SESSION)) {
        session_start();
    }
    require_once('../../config/config.php');
    require_once('../../validators/validators.php');
    require_once('../../database/Conexion.php');

    class LlenadoActividadDimension {
        private $idLlenadoDimension;
        private $valorLlenadoDimensionInicial;
        private $valorLlenadoDimensionFinal;
        private $TipoUsuario_idTipoUsuario;

        private $conexionBD;
        private $consulta;
        private $tablaBaseDatos;

        // constructor
        public function __construct() {
            $this->tablaBaseDatos = TBL_CONTROL_LLENADO_ACTIVIDADES;
        }

        public function getIdLlenadoDimension() {
            return $this->idLlenadoDimension;
        }

        public function setIdLlenadoDimension($idLlenadoDimension) {
            $this->idLlenadoDimension = $idLlenadoDimension;
            return $this;
        }

        public function getValorLlenadoDimensionInicial() {
            return $this->valorLlenadoDimensionInicial;
        }

        public function setValorLlenadoDimensionInicial($valorLlenadoDimensionInicial) {
            $this->valorLlenadoDimensionInicial = $valorLlenadoDimensionInicial;
            return $this;
        }

        public function getValorLlenadoDimensionFinal() {
            return $this->valorLlenadoDimensionFinal;
        }

        public function setValorLlenadoDimensionFinal($valorLlenadoDimensionFinal) {
            $this->valorLlenadoDimensionFinal = $valorLlenadoDimensionFinal;
            return $this;
        }

        public function getTipoUsuario_idTipoUsuario() {
            return $this->TipoUsuario_idTipoUsuario;
        }

        public function setTipoUsuario_idTipoUsuario($TipoUsuario_idTipoUsuario) {
            $this->TipoUsuario_idTipoUsuario = $TipoUsuario_idTipoUsuario;
            return $this;
        }

        public function getLlenadosActividadDimension () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare('WITH CTE_LISTA_CONTROL_LLENADO_ACT AS (SELECT LlenadoActividadDimension.idLlenadoDimension,LlenadoActividadDimension.TipoUsuario_idTipoUsuario, TipoUsuario.tipoUsuario, LlenadoActividadDimension.valorLlenadoDimensionInicial,LlenadoActividadDimension.valorLlenadoDimensionFinal FROM LlenadoActividadDimension INNER JOIN TipoUsuario ON (LlenadoActividadDimension.TipoUsuario_idTipoUsuario = TipoUsuario.idTipoUsuario)) SELECT * FROM CTE_LISTA_CONTROL_LLENADO_ACT;');
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error, al listar la informacion del control de llenado de actividades')
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

        public function insertarNuevoLlenadoDimension () {
            if (
                is_int($this->valorLlenadoDimensionInicial) && 
                is_int($this->valorLlenadoDimensionFinal) && 
                validaNumerZPositivo($this->valorLlenadoDimensionFinal) &&
                validaNumerZPositivo($this->valorLlenadoDimensionInicial) &&
                is_int($this->TipoUsuario_idTipoUsuario)
            ) {
                try {
                    $this->conexionBD = new Conexion();
                    $this->consulta = $this->conexionBD->connect();

                    $this->consulta->prepare("
                        set @persona = {$_SESSION['idUsuario']};
                    ")->execute();
                    
                    $stmt = $this->consulta->prepare('INSERT INTO ' . $this->tablaBaseDatos . ' (TipoUsuario_idTipoUsuario, valorLlenadoDimensionInicial, valorLlenadoDimensionFinal) VALUES (:tipoUsuario, :valorInicial, :valorFinal)');
                    $stmt->bindValue(':tipoUsuario', $this->TipoUsuario_idTipoUsuario);
                    $stmt->bindValue(':valorInicial', $this->valorLlenadoDimensionInicial);
                    $stmt->bindValue(':valorFinal', $this->valorLlenadoDimensionFinal);
                    if ($stmt->execute()) {
                        return array(
                            'status' => SUCCESS_REQUEST,
                            'data' => array('message' => 'El rango de llenado de las actividades se inserto correctamente')
                        );
                    } else {
                        return array(
                            'status'=> BAD_REQUEST,
                            'data' => array('message' => 'Ha ocurrido un error, la insercion de llenado de la actividad no se pudo realizar, recargue la pagina')
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
                    'data' => array('message' => 'La informacion digitada es erronea, vuelva a digitar los campos')
                );
            }
        }   

        public function modificaLlenadoDimension () {
            if (
                is_int($this->valorLlenadoDimensionInicial) && 
                is_int($this->valorLlenadoDimensionFinal) && 
                validaNumerZPositivo($this->valorLlenadoDimensionFinal) &&
                validaNumerZPositivo($this->valorLlenadoDimensionInicial) &&
                is_int($this->idLlenadoDimension)
            ) {
                try {
                    $this->conexionBD = new Conexion();
                    $this->consulta = $this->conexionBD->connect();

                    $this->consulta->prepare("
                        set @persona = {$_SESSION['idUsuario']};
                    ")->execute();
                    
                    $stmt = $this->consulta->prepare('UPDATE ' . $this->tablaBaseDatos . ' SET valorLlenadoDimensionInicial = :valorInicial, valorLlenadoDimensionFinal = :valorFinal WHERE idLlenadoDimension = :idLlenadoDimension');
                    $stmt->bindValue(':valorInicial', $this->valorLlenadoDimensionInicial);
                    $stmt->bindValue(':valorFinal', $this->valorLlenadoDimensionFinal);
                    $stmt->bindValue(':idLlenadoDimension', $this->idLlenadoDimension);
                    if ($stmt->execute()) {
                        return array(
                            'status' => SUCCESS_REQUEST,
                            'data' => array('message' => 'El rango de llenado de las actividades se modifico correctamente')
                        );
                    } else {
                        return array(
                            'status'=> BAD_REQUEST,
                            'data' => array('message' => 'Ha ocurrido un error, la modificacion del llenado de la actividad no se pudo realizar, recargue la pagina')
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
                    'data' => array('message' => 'La informacion digitada es erronea, vuelva a digitar los campos')
                );
            }
        }

        public function eliminaLlenadoDimension () {
            if (
                is_int($this->idLlenadoDimension)
            ) {
                try {
                    $this->conexionBD = new Conexion();
                    $this->consulta = $this->conexionBD->connect();

                    $this->consulta->prepare("
                        set @persona = {$_SESSION['idUsuario']};
                    ")->execute();
                    
                    $stmt = $this->consulta->prepare('DELETE FROM ' . $this->tablaBaseDatos . ' WHERE idLlenadoDimension = :idLlenadoDimension');
                    $stmt->bindValue(':idLlenadoDimension', $this->idLlenadoDimension);
                    if ($stmt->execute()) {
                        return array(
                            'status' => SUCCESS_REQUEST,
                            'data' => array('message' => 'El rango de llenado de las actividades se elimino correctamente')
                        );
                    } else {
                        return array(
                            'status'=> BAD_REQUEST,
                            'data' => array('message' => 'Ha ocurrido un error, el rango de dimensiones no se pudo eliminar')
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
                    'data' => array('message' => 'La informacion digitada es erronea, vuelva a digitar los campos')
                );
            }
        }
    }
?>