<?php
    if (!isset($_SESSION)) {
        session_start();
    }
    require_once('../../database/Conexion.php');
    
    class verificarTokenAcceso {
        private $conexionBD;
        private $consulta;
        public function verificarTokenAcceso () {
            if(isset($_SESSION['idUsuario']) && isset($_SESSION['access-token'])) {
                try {
                    $this->conexionBD = new Conexion();
                    $this->consulta = $this->conexionBD->connect();
                        $stmt = $this->consulta->prepare('CALL SP_VERIFICA_TOKEN(:idUsuario, :token)');
                        $stmt->bindValue(':idUsuario', $_SESSION['idUsuario']);
                        $stmt->bindValue(':token', $_SESSION['access-token']);
                        if ($stmt->execute()) {
                            $data = $stmt->fetchObject();
                            /// Fecha del token de la base de datos
                            $token_fecha = new DateTime($data->tokenExpiracion);
                            // Fecha Actual
                            $fecha = new DateTime();
                            // Comprobacion de fechas si la fecha de la bd es mayor que la fecha actual alerta, caso contrario no alerta
                            if($token_fecha->format('Y-m-d h:i:s') > $fecha->format('Y-m-d h:i:s')) {
                                return true;
                            } else {
                                return false;
                            }
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
            } else {
                return false;
            }
        }
    }
?>