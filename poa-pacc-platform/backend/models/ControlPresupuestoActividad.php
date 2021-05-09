<?php
    require_once('../../config/config.php');    
    require_once('../../validators/validators.php');
    require_once('../../database/Conexion.php');
    class ControlPresupuestoActividad {
        private $idControlPresupuestoActividad;
        private $presupuestoAnual;
        private $fechaPresupuestoAnual;
        private $estadoPresupuestoAnual;
        private $estadoLlenadoActividades;

        private $conexionBD;
        private $consulta;
        private $tablaBaseDatos;

        public function __construct() {
            $this->tablaBaseDatos = TBL_PRESUPUESTO_ANUAL;
        }
        public function getIdControlPresupuestoActividad(){
            return $this->idControlPresupuestoActividad;
        }

        public function setIdControlPresupuestoActividad($idControlPresupuestoActividad){
            $this->idControlPresupuestoActividad = $idControlPresupuestoActividad;
            return $this;
        }

        public function getPresupuestoAnual(){
            return $this->presupuestoAnual;
        }

        public function setPresupuestoAnual($presupuestoAnual){
            $this->presupuestoAnual = $presupuestoAnual;
            return $this;
        }

        public function getFechaPresupuestoAnual(){
            return $this->fechaPresupuestoAnual;
        }

        public function setFechaPresupuestoAnual($fechaPresupuestoAnual){
            $this->fechaPresupuestoAnual = $fechaPresupuestoAnual;
            return $this;
        }

        public function getEstadoPresupuestoAnual(){
            return $this->estadoPresupuestoAnual;
        }

        public function setEstadoPresupuestoAnual($estadoPresupuestoAnual){
            $this->estadoPresupuestoAnual = $estadoPresupuestoAnual;
            return $this;
        }
        public function getEstadoLlenadoActividades()
        {
            return $this->estadoLlenadoActividades;
        }
        public function setEstadoLlenadoActividades($estadoLlenadoActividades)
        {
            $this->estadoLlenadoActividades = $estadoLlenadoActividades;
            return $this;
        }

        public function verificaCantidadPresupuestosAbiertos() {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("SELECT count(*) AS presupuestosAbiertos FROM ControlPresupuestoActividad WHERE estadoLlenadoActividades = 1;");
                if ($stmt->execute()) {
                    $data = $stmt->fetchObject();
                    if ($data->presupuestosAbiertos == 0) {
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
        }

        public function verificaExistenciaPresupuestoAnual() {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("WITH CTE_VERIF_EXIS_PRES_ANUAL AS (SELECT * FROM ControlPresupuestoActividad) 
                SELECT * FROM CTE_VERIF_EXIS_PRES_ANUAL WHERE date_format(CTE_VERIF_EXIS_PRES_ANUAL.fechaPresupuestoAnual, '%Y') = date_format(:fecha, '%Y')");
                $stmt->bindValue(':fecha', $this->fechaPresupuestoAnual);
                if ($stmt->execute()) {
                    if ($stmt->rowCount() == 0) {
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
        }
        public function registrarPresupuesto () {
            if (validaCampoMonetario($this->presupuestoAnual) && is_int($this->estadoPresupuestoAnual)) {
                $noExistePresupuestoParaAnio = $this->verificaExistenciaPresupuestoAnual();
                if ($noExistePresupuestoParaAnio == true) {
                    try {
                        $this->estadoLlenadoActividades = 0;
                        $this->conexionBD = new Conexion();
                        $this->consulta = $this->conexionBD->connect();

                        $this->consulta->prepare("
                            set @persona = {$_SESSION['idUsuario']};
                        ")->execute();

                        $stmt = $this->consulta->prepare('INSERT INTO ' . $this->tablaBaseDatos . '(presupuestoAnual, fechaPresupuestoAnual, idEstadoPresupuestoAnual, estadoLlenadoActividades) VALUES (:presupuesto, :fecha, :estadoPresupuestoAnual, :estadoLlenado)');
                        $stmt->bindValue(':presupuesto', $this->presupuestoAnual);
                        $stmt->bindValue(':fecha', $this->fechaPresupuestoAnual);
                        $stmt->bindValue(':estadoPresupuestoAnual', $this->estadoPresupuestoAnual);
                        $stmt->bindValue(':estadoLlenado', $this->estadoLlenadoActividades);
                        if ($stmt->execute()) {
                            return array(
                                'status'=> SUCCESS_REQUEST,
                                'data' => array('message' => 'El presupuesto fue ingresado exitosamente')
                            );
                        } else {
                            return array(
                                'status'=> BAD_REQUEST,
                                'data' => array('message' => 'ha ocurrido un error al ingresar el presupuesto, vuelva a ingresar la informacion')
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
                        'data' => array('message' => 'El presupuesto para el año ' . $this->fechaPresupuestoAnual . ' ya fue asignado, no puede agregar otro presupuesto, solo puede modificarlo')
                    );
                }
            } else {
                return array(
                    'status'=> BAD_REQUEST,
                    'data' => array('message' => 'El campo presupuesto anual no es valido, debe ser un campo numerico')
                );
            }
        }

        public function getPresupuestosAnuales () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("WITH CTE_VER_PRESUPUESTOS_DEPTO AS (SELECT ControlPresupuestoActividad.idControlPresupuestoActividad, ControlPresupuestoActividad.presupuestoAnual, date_format(ControlPresupuestoActividad.fechaPresupuestoAnual, '%Y') AS fechaPresupuesto, ControlPresupuestoActividad.idEstadoPresupuestoAnual, ControlPresupuestoActividad.estadoLlenadoActividades FROM ControlPresupuestoActividad) SELECT * FROM CTE_VER_PRESUPUESTOS_DEPTO ORDER BY CTE_VER_PRESUPUESTOS_DEPTO.idControlPresupuestoActividad DESC;");
                $stmt->bindValue(':estado', ESTADO_ACTIVO);
                if ($stmt->execute()) {
                    return array(
                        'status'=> SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'ha ocurrido un error al ver los presupuestos, vuelva a ingresar la informacion')
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

        public function verificarPresupuestoModificar () {
            if (is_int($this->idControlPresupuestoActividad)) {
                try {
                    $this->conexionBD = new Conexion();
                    $this->consulta = $this->conexionBD->connect();
                    $stmt = $this->consulta->prepare("WITH CTE_VERIF_PRESUPUESTO AS (SELECT * FROM  ControlPresupuestoActividad) SELECT * FROM CTE_VERIF_PRESUPUESTO  WHERE CTE_VERIF_PRESUPUESTO.idControlPresupuestoActividad = :idPresupuestoAnual");
                    $stmt->bindValue(':idPresupuestoAnual', $this->idControlPresupuestoActividad);
                    if ($stmt->execute()) {
                        if ($stmt->rowCount() == 0) {
                            return array(
                                'status'=> BAD_REQUEST,
                                'data' => array('message' => 'Este presupuesto no pertenece a este año, no es posible modificarlo')
                            );
                        } else {
                            return array(
                                'status'=> SUCCESS_REQUEST,
                                'data' => $stmt->fetchObject()
                            );
                        }
                    } else {
                        return array(
                            'status'=> BAD_REQUEST,
                            'data' => array('message' => 'ha ocurrido un error al ingresar el presupuesto modificado, vuelva a ingresar la informacion')
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
                    'data' => array('message' => 'ha ocurrido un error al ingresar el presupuesto modificado, vuelva a ingresar la informacion')
                );
            }
        }
        
        // SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));

        public function verificaCantidadPresupuestoModificar () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("SELECT ControlPresupuestoActividad.presupuestoAnual, SUM(PresupuestoDepartamento.montoPresupuesto) AS montoTotalPorDepartamentos, ControlPresupuestoActividad.idControlPresupuestoActividad FROM ControlPresupuestoActividad LEFT JOIN PresupuestoDepartamento ON (PresupuestoDepartamento.idControlPresupuestoActividad = ControlPresupuestoActividad.idControlPresupuestoActividad) LEFT JOIN Departamento ON (PresupuestoDepartamento.idDepartamento = Departamento.idDepartamento) WHERE ControlPresupuestoActividad.idControlPresupuestoActividad = :idPresupuestoAnual GROUP BY ControlPresupuestoActividad.presupuestoAnual, ControlPresupuestoActividad.idControlPresupuestoActividad;");
                $stmt->bindValue(':idPresupuestoAnual', $this->idControlPresupuestoActividad);
                if ($stmt->execute()) {
                    $data = $stmt->fetchObject();
                    if ($data->montoTotalPorDepartamentos == null) {
                        $monto = 0;
                    } else {
                        $monto = $data->montoTotalPorDepartamentos;
                    }
                    if ($monto > $this->presupuestoAnual) {
                        return true;
                    } else {
                        return false;
                    }
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

        public function modificaPresupuesto() {
            if (is_int($this->idControlPresupuestoActividad) && validaCampoMonetario($this->presupuestoAnual) && is_int($this->estadoPresupuestoAnual)) {
                if ($this->verificaCantidadPresupuestoModificar() == true) {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'ha ocurrido un error, el presupuesto a modificar es menor al presupuesto utilizado por los departamento, reajuste el presupuesto de los departamentos o aumente el presupuesto anual')
                    );
                } else {
                    try {
                        $this->conexionBD = new Conexion();
                        $this->consulta = $this->conexionBD->connect();

                        $this->consulta->prepare("
                            set @persona = {$_SESSION['idUsuario']};
                        ")->execute();

                        $stmt = $this->consulta->prepare('UPDATE ' . $this->tablaBaseDatos . ' SET presupuestoAnual = :presupuesto, idEstadoPresupuestoAnual = :estado WHERE idControlPresupuestoActividad = :idPresupuestoAnual');
                        $stmt->bindValue(':idPresupuestoAnual', $this->idControlPresupuestoActividad);
                        $stmt->bindValue(':estado', $this->estadoPresupuestoAnual);
                        $stmt->bindValue(':presupuesto', $this->presupuestoAnual);
                        
                        if ($stmt->execute()) {
                            return array(
                                'status'=> SUCCESS_REQUEST,
                                'data' => array('message' => 'El presupuesto fue modificado con exito')
                            );
                        } else {
                            return array(
                                'status'=> BAD_REQUEST,
                                'data' => array('message' => 'ha ocurrido un error al actualizar el presupuesto, vuelva a ingresar la informacion')
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
            } else {
                return array(
                    'status'=> BAD_REQUEST,
                    'data' => array('message' => 'ha ocurrido un error al actualizar el presupuesto, vuelva a ingresar la informacion')
                );
            }
        }

        public function cambiarEstadoPresupuesto () {
            if (is_int($this->idControlPresupuestoActividad)) {
                if ($this->estadoLlenadoActividades == 1) {
                    $noHayPresupuestosAbiertos = $this->verificaCantidadPresupuestosAbiertos();
                    if ($noHayPresupuestosAbiertos == true) {
                        try {
                            $this->conexionBD = new Conexion();
                            $this->consulta = $this->conexionBD->connect();
                            $this->consulta->prepare("
                                set @persona = {$_SESSION['idUsuario']};
                            ")->execute();
                            $stmt = $this->consulta->prepare('UPDATE ControlPresupuestoActividad SET estadoLlenadoActividades = :estadoLlenado WHERE idControlPresupuestoActividad = :idPresupuesto;');
                            $stmt->bindValue(':estadoLlenado', $this->estadoLlenadoActividades);
                            $stmt->bindValue(':idPresupuesto', $this->idControlPresupuestoActividad);
                            if ($stmt->execute()) {
                                return array(
                                    'status'=> SUCCESS_REQUEST,
                                    'data' => array('message' => 'El estado del presupuesto fue modificado exitosamente')
                                );
                            } else {
                                return array(
                                    'status'=> BAD_REQUEST,
                                    'data' => array('message' => 'ha ocurrido un error al modificar el estado del presupuesto, vuelva a ingresar la informacion')
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
                            'data' => array('message' => 'ha ocurrido un error, para modificar el estado de un presupuesto a abierto debe cerrar todos los demas presupuestos existentes.')
                        );
                    }
                } else {
                    try {
                        $this->conexionBD = new Conexion();
                        $this->consulta = $this->conexionBD->connect();
                        $this->consulta->prepare("
                            set @persona = {$_SESSION['idUsuario']};
                        ")->execute();
                        $stmt = $this->consulta->prepare('UPDATE ControlPresupuestoActividad SET estadoLlenadoActividades = :estadoLlenado WHERE idControlPresupuestoActividad = :idPresupuesto;');
                        $stmt->bindValue(':estadoLlenado', $this->estadoLlenadoActividades);
                        $stmt->bindValue(':idPresupuesto', $this->idControlPresupuestoActividad);
                        if ($stmt->execute()) {
                            return array(
                                'status'=> SUCCESS_REQUEST,
                                'data' => array('message' => 'El estado del presupuesto fue modificado exitosamente')
                            );
                        } else {
                            return array(
                                'status'=> BAD_REQUEST,
                                'data' => array('message' => 'ha ocurrido un error al modificar el estado del presupuesto, vuelva a ingresar la informacion')
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

        public function getAniosPresupuestoPOA() {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("WITH CTE_VER_PRESUPUESTOS_DEPTO AS (SELECT ControlPresupuestoActividad.idControlPresupuestoActividad, ControlPresupuestoActividad.presupuestoAnual, date_format(ControlPresupuestoActividad.fechaPresupuestoAnual, '%Y') AS fechaPresupuesto, ControlPresupuestoActividad.idEstadoPresupuestoAnual, ControlPresupuestoActividad.estadoLlenadoActividades FROM ControlPresupuestoActividad) SELECT CTE_VER_PRESUPUESTOS_DEPTO.idControlPresupuestoActividad,  CTE_VER_PRESUPUESTOS_DEPTO.fechaPresupuesto FROM CTE_VER_PRESUPUESTOS_DEPTO ORDER BY CTE_VER_PRESUPUESTOS_DEPTO.idControlPresupuestoActividad DESC;");
                if ($stmt->execute()) {
                    return array(
                        'status'=> SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'ha ocurrido un error al ver los presupuestos, vuelva a ingresar la informacion')
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