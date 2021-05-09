<?php
    if (!isset($_SESSION)) {
        session_start();
    }
    require_once('../../config/config.php');
    require_once('../../database/Conexion.php');
    require_once('../../validators/validators.php');
    class DescripcionAdministrativa {
        private $idDescripcionAdministrativa;
        private $idObjetoGasto;
        private $idTipoPresupuesto;
        private $idActividad;
        private $idDimensionAdministrativa;
        private $nombreActividad;
        private $cantidad;
        private $costo;
        private $costoTotal;
        private $mesRequerido;
        private $descripcion;
        private $unidadDeMedida;
        private $valorInicial;
        private $valorFinal;

        private $idDepartamento;
        private $anioPlanificacion;

        private $conexionBD;
        private $consulta;
        private $tablaBaseDatos;


        public function getIdDescripcionAdministrativa() {
            return $this->idDescripcionAdministrativa;
        }

        public function setIdDescripcionAdministrativa($idDescripcionAdministrativa) {
            $this->idDescripcionAdministrativa = $idDescripcionAdministrativa;
            return $this;
        }

        public function getIdObjetoGasto() {
            return $this->idObjetoGasto;
        }

        public function setIdObjetoGasto($idObjetoGasto) {
            $this->idObjetoGasto = $idObjetoGasto;
            return $this;
        }

        public function getIdTipoPresupuesto() {
            return $this->idTipoPresupuesto;
        }

        public function setIdTipoPresupuesto($idTipoPresupuesto) {
            $this->idTipoPresupuesto = $idTipoPresupuesto;
            return $this;
        }

        public function getIdActividad() {
            return $this->idActividad;
        }

        public function setIdActividad($idActividad) {
            $this->idActividad = $idActividad;
            return $this;
        }

        public function getIdDimensionAdministrativa() {
            return $this->idDimensionAdministrativa;
        }

        public function setIdDimensionAdministrativa($idDimensionAdministrativa) {
            $this->idDimensionAdministrativa = $idDimensionAdministrativa;
            return $this;
        }

        public function getCantidad() {
            return $this->cantidad;
        }

        public function setCantidad($cantidad) {
            $this->cantidad = $cantidad;
            return $this;
        }

        public function getCosto() {
            return $this->costo;
        }

        public function setCosto($costo) {
            $this->costo = $costo;
            return $this;
        }

        public function getCostoTotal() {
            return $this->costoTotal;
        }

        public function setCostoTotal($costoTotal) {
            $this->costoTotal = $costoTotal;
            return $this;
        }

        public function getMesRequerido() {
            return $this->mesRequerido;
        }

        public function setMesRequerido($mesRequerido) {
            $this->mesRequerido = $mesRequerido;
            return $this;
        }

        public function getDescripcion() {
            return $this->descripcion;
        }

        public function setDescripcion($descripcion) {
            $this->descripcion = $descripcion;
            return $this;
        }
        public function getNombreActividad(){
            return $this->nombreActividad;
        }

        public function setNombreActividad($nombreActividad){
            $this->nombreActividad = $nombreActividad;
            return $this;
        }

        public function getUnidadDeMedida() {
            return $this->unidadDeMedida;
        }


        public function setUnidadDeMedida($unidadDeMedida) {
            $this->unidadDeMedida = $unidadDeMedida;
            return $this;
        }

        public function getIdDepartamento() {
            return $this->idDepartamento;
        }

        public function setIdDepartamento($idDepartamento) {
            $this->idDepartamento = $idDepartamento;
            return $this;
        }

        public function getAnioPlanificacion(){
            return $this->anioPlanificacion;
        }

        public function setAnioPlanificacion($anioPlanificacion) {
            $this->anioPlanificacion = $anioPlanificacion;
            return $this;
        }

                        /**
         * Get the value of valorInicial
         */ 
        public function getValorInicial()
        {
                return $this->valorInicial;
        }

        /**
         * Set the value of valorInicial
         *
         * @return  self
         */ 
        public function setValorInicial($valorInicial)
        {
                $this->valorInicial = $valorInicial;

                return $this;
        }

        /**
         * Get the value of valorFinal
         */ 
        public function getValorFinal()
        {
                return $this->valorFinal;
        }

        /**
         * Set the value of valorFinal
         *
         * @return  self
         */ 
        public function setValorFinal($valorFinal)
        {
                $this->valorFinal = $valorFinal;

                return $this;
        }

        public function __construct () {
            $this->tablaBaseDatos = TBL_DESCRIPCION_ADMINISTRATIVA;
        }

        public function compruebaCostoActividad () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare('SELECT  SUM(DescripcionAdministrativa.costoTotal) AS costoDescripcionAdmin,
                Actividad.idActividad, (SELECT Actividad.costoTotal FROM Actividad WHERE idActividad = :idActividad) AS costoActividad FROM DescripcionAdministrativa RIGHT JOIN Actividad ON (DescripcionAdministrativa.idActividad = Actividad.idActividad) WHERE Actividad.idActividad = :idActividad1');
                $stmt->bindValue(':idActividad', $this->idActividad);
                $stmt->bindValue(':idActividad1', $this->idActividad);
                if ($stmt->execute()) {
                    $data = $stmt->fetchObject();
                    if ($data->costoDescripcionAdmin == null) {
                        $costoAcumuladoPorActividades = 0;
                    } else {
                        $costoAcumuladoPorActividades = $data->costoDescripcionAdmin;
                    }
                    $costoPorCantidad = $this->cantidad * $this->costo;
                    $costoNuevoDescripcion = $costoPorCantidad + $costoAcumuladoPorActividades;
                    if ($costoNuevoDescripcion > $data->costoActividad) {
                        return false;
                    } else {
                        return true;
                    }
                    
                } else {
                    return false;
                }
            } catch (PDOException $ex) {
                return false;
            } finally {
                $this->conexionBD = null;
            } 
        }

        public function compruebaCostoActividadModificar () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare('SELECT SUM(DescripcionAdministrativa.costoTotal) AS costoDescripcionAdmin,
                Actividad.idActividad, (SELECT Actividad.costoTotal FROM Actividad WHERE idActividad = :idActividad) AS costoActividad , (SELECT CostoTotal FROM DescripcionAdministrativa WHERE idDescripcionAdministrativa = :idDescripcion) AS costoItem FROM DescripcionAdministrativa RIGHT JOIN Actividad ON (DescripcionAdministrativa.idActividad = Actividad.idActividad) WHERE Actividad.idActividad = :idActividad1 GROUP BY Actividad.idActividad, costoActividad, costoItem;');
                $stmt->bindValue(':idActividad', $this->idActividad);
                $stmt->bindValue(':idDescripcion', $this->idDescripcionAdministrativa);
                $stmt->bindValue(':idActividad1', $this->idActividad);
                if ($stmt->execute()) {
                    $data = $stmt->fetchObject();
                    if ($data->costoDescripcionAdmin == null) {
                        $costoAcumuladoPorActividades = 0;
                    } else {
                        $costoAcumuladoPorActividades = $data->costoDescripcionAdmin;
                    }
                    $costoPorCantidad = $this->cantidad * $this->costo;
                    $costoNuevoDescripcion = (($costoAcumuladoPorActividades - $data->costoItem) + $costoPorCantidad);
                    if ($costoNuevoDescripcion > $data->costoActividad) {
                        return false;
                    } else {
                        return true;
                    }
                    
                } else {
                    return false;
                }
            } catch (PDOException $ex) {
                return false;
            } finally {
                $this->conexionBD = null;
            } 
        }

        public function insertaDescripcionAdministrativa () {
            if (
                is_int($this->idObjetoGasto) && 
                is_int($this->idTipoPresupuesto) && 
                is_int($this->idActividad) && 
                is_int($this->idDimensionAdministrativa) &&
                is_numeric($this->cantidad) &&
                is_numeric($this->costo) &&
                is_numeric($this->costoTotal)) {
                    $calculoCorrecto = $this->compruebaCostoActividad();
                    if ($calculoCorrecto == true) {
                        try {
                            $this->conexionBD = new Conexion();
                            $this->consulta = $this->conexionBD->connect();
                            $this->consulta->prepare("
                                set @persona = {$_SESSION['idUsuario']};
                            ")->execute();
                            $stmt = $this->consulta->prepare('INSERT INTO DescripcionAdministrativa (idObjetoGasto, idTipoPresupuesto, idActividad, idDimensionAdministrativa, nombreActividad, Cantidad, Costo, costoTotal, mesRequerido, Descripcion, unidadDeMedida) VALUES (:idObjeto, :idTipoPresupuesto, :idActividad, :idDimension, :nombreAct, :cantidad, :costo, :costoTotal, :mesRequerido, :descripcion, :unidadMedida)');
                            $stmt->bindValue(':idObjeto', $this->idObjetoGasto);
                            $stmt->bindValue(':idTipoPresupuesto', $this->idTipoPresupuesto);
                            $stmt->bindValue(':idActividad', $this->idActividad);
                            $stmt->bindValue(':idDimension', $this->idDimensionAdministrativa);
                            $stmt->bindValue(':cantidad', $this->cantidad);
                            $stmt->bindValue(':nombreAct', $this->nombreActividad);
                            $stmt->bindValue(':costo', $this->costo);
                            $stmt->bindValue(':costoTotal', $this->costo * $this->cantidad);
                            $stmt->bindValue(':mesRequerido', $this->mesRequerido);
                            $stmt->bindValue(':descripcion', json_encode($this->descripcion));
                            $stmt->bindValue(':unidadMedida', $this->unidadDeMedida);

                            if ($stmt->execute()) {
                                return array(
                                    'status'=> SUCCESS_REQUEST,
                                    'data' => array('message' => 'El item fue agregado a la actividad exitosamente')
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
                                'data' => array('message' => $ex->getMessage() . $this->Descripcion)
                            );
                        } finally {
                            $this->conexionBD = null;
                        } 
                    } else {
                        return array(
                            'status'=> BAD_REQUEST,
                            'data' => array('message' => 'ha ocurrido un error al insertar la descripcion de la actividad, el costo * cantidad excede el costo de la actividad, por favor verifique los datos')
                        );
                    }
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'ha ocurrido un error al insertar la descripcion de la actividad, los campos no son correctos')
                    );
                }
        }

        public function getDescripcionAdministrativaPorActividad () {
            if (is_int($this->idActividad) && is_int($this->idDimensionAdministrativa)) {
                try {
                    $this->conexionBD = new Conexion();
                    $this->consulta = $this->conexionBD->connect();
                    $stmt = $this->consulta->prepare('WITH CTE_LISTA_DESGLOSE_ACTIVIDAD AS (SELECT DescripcionAdministrativa.idDescripcionAdministrativa, DescripcionAdministrativa.nombreActividad, DescripcionAdministrativa.idObjetoGasto, ObjetoGasto.descripcionCuenta, ObjetoGasto.abrev ,DescripcionAdministrativa.idTipoPresupuesto, TipoPresupuesto.tipoPresupuesto, Actividad.idActividad, Actividad.Actividad, DescripcionAdministrativa.idDimensionAdministrativa, DimensionAdmin.dimensionAdministrativa, DescripcionAdministrativa.Cantidad, DescripcionAdministrativa.Costo, DescripcionAdministrativa.costoTotal, DescripcionAdministrativa.mesRequerido, DescripcionAdministrativa.Descripcion, DescripcionAdministrativa.unidadDeMedida, Actividad.idDimension, DimensionEstrategica.dimensionEstrategica FROM DescripcionAdministrativa INNER JOIN  ObjetoGasto ON (DescripcionAdministrativa.idObjetoGasto = ObjetoGasto.idObjetoGasto) INNER JOIN Actividad ON (DescripcionAdministrativa.idActividad = Actividad.idActividad) INNER JOIN TipoPresupuesto ON (DescripcionAdministrativa.idTipoPresupuesto = TipoPresupuesto.idTipoPresupuesto) INNER JOIN DimensionAdmin ON (DescripcionAdministrativa.idDimensionAdministrativa = DimensionAdmin.idDimension) INNER JOIN DimensionEstrategica ON (Actividad.idDimension = DimensionEstrategica.idDimension)) SELECT * FROM CTE_LISTA_DESGLOSE_ACTIVIDAD WHERE CTE_LISTA_DESGLOSE_ACTIVIDAD.idActividad = :idActividad  AND CTE_LISTA_DESGLOSE_ACTIVIDAD.idDimensionAdministrativa = :idDimenAdmin ORDER BY CTE_LISTA_DESGLOSE_ACTIVIDAD.idDescripcionAdministrativa DESC');
                    $stmt->bindValue(':idActividad', $this->idActividad);
                    $stmt->bindValue(':idDimenAdmin', $this->idDimensionAdministrativa);
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
            } else {
                return array(
                    'status'=> BAD_REQUEST,
                    'data' => array('message' => 'ha ocurrido un error al listar la descripcion de la actividad, el id no es correcto')
                );
            }
        }

        public function modifDescripcionAdministrativa () {
            if (
                is_int($this->idDescripcionAdministrativa) &&
                is_int($this->idObjetoGasto) && 
                is_int($this->idTipoPresupuesto) && 
                is_int($this->idActividad) && 
                is_int($this->idDimensionAdministrativa) &&
                is_numeric($this->cantidad) &&
                is_numeric($this->costo) &&
                is_numeric($this->costoTotal)) {
                    $calculoCorrecto = $this->compruebaCostoActividadModificar();
                    if ($calculoCorrecto == true) {
                        try {
                            $this->conexionBD = new Conexion();
                            $this->consulta = $this->conexionBD->connect();
                            $this->consulta->prepare("
                                set @persona = {$_SESSION['idUsuario']};
                            ")->execute();
                            $stmt = $this->consulta->prepare('UPDATE DescripcionAdministrativa SET idObjetoGasto = :idObjeto, idTipoPresupuesto = :idTipoPresupuesto, idActividad = :idActividad, idDimensionAdministrativa = :idDimension, nombreActividad = :nombreAct, Cantidad = :cantidad, Costo = :costo, costoTotal = :costoTotal, mesRequerido = :mesRequerido, Descripcion = :descripcion, unidadDeMedida = :unidadMedida WHERE idDescripcionAdministrativa = :idDescripcion ');
                            $stmt->bindValue(':idDescripcion', $this->idDescripcionAdministrativa);
                            $stmt->bindValue(':idObjeto', $this->idObjetoGasto);
                            $stmt->bindValue(':idTipoPresupuesto', $this->idTipoPresupuesto);
                            $stmt->bindValue(':idActividad', $this->idActividad);
                            $stmt->bindValue(':idDimension', $this->idDimensionAdministrativa);
                            $stmt->bindValue(':nombreAct', $this->nombreActividad);
                            $stmt->bindValue(':cantidad', $this->cantidad);
                            $stmt->bindValue(':costo', $this->costo);
                            $stmt->bindValue(':costoTotal', $this->costo * $this->cantidad);
                            $stmt->bindValue(':mesRequerido', $this->mesRequerido);
                            $stmt->bindValue(':descripcion', json_encode($this->descripcion));
                            $stmt->bindValue(':unidadMedida', $this->unidadDeMedida);
                            if ($stmt->execute()) {
                                return array(
                                    'status'=> SUCCESS_REQUEST,
                                    'data' => array('message' => 'El item fue agregado a la actividad exitosamente')
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
                                'data' => array('message' => $ex->getMessage() . $this->Descripcion)
                            );
                        } finally {
                            $this->conexionBD = null;
                        } 
                    } else {
                        return array(
                            'status'=> BAD_REQUEST,
                            'data' => array('message' => 'ha ocurrido un error al insertar la descripcion de la actividad, el costo * cantidad excede el costo de la actividad, por favor verifique los datos')
                        );
                    }
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'ha ocurrido un error al insertar la descripcion de la actividad, los campos no son correctos')
                    );
                }
        }


        public function generaDescripcionAdmin () {
            if (is_int($this->idDescripcionAdministrativa)) {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("WITH CTE_GENERA_DESCRIPCION_ADMIN AS (SELECT DescripcionAdministrativa.Cantidad, DescripcionAdministrativa.Costo, DescripcionAdministrativa.idActividad, DescripcionAdministrativa.descripcion,DescripcionAdministrativa.idDescripcionAdministrativa, DescripcionAdministrativa.idDimensionAdministrativa,DescripcionAdministrativa.mesRequerido, DescripcionAdministrativa.idObjetoGasto, ObjetoGasto.abrev, ObjetoGasto.descripcionCuenta, DescripcionAdministrativa.idTipoPresupuesto, TipoPresupuesto.tipoPresupuesto, DescripcionAdministrativa.nombreActividad, DescripcionAdministrativa.unidadDeMedida FROM DescripcionAdministrativa INNER JOIN ObjetoGasto ON (DescripcionAdministrativa.idObjetoGasto = ObjetoGasto.idObjetoGasto) INNER JOIN TipoPresupuesto ON (DescripcionAdministrativa.idTipoPresupuesto = TipoPresupuesto.idTipoPresupuesto)) SELECT * FROM CTE_GENERA_DESCRIPCION_ADMIN WHERE CTE_GENERA_DESCRIPCION_ADMIN.idDescripcionAdministrativa = :idDescripcion;");
                $stmt->bindValue(':idDescripcion', $this->idDescripcionAdministrativa);
                if ($stmt->execute()) {
                    return array(
                        'status'=> SUCCESS_REQUEST,
                        'data' => $stmt->fetchObject()
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'ha ocurrido un error al listar la descripcion administrativa')
                    );
                }
            } else {
                return array(
                    'status'=> BAD_REQUEST,
                    'data' => array('message' => 'ha ocurrido un error al listar la descripcion administrativa')
                );
            }
        }


        // Desglose de Inventario para el sistema de inventario
        public function getItemsParaCompraInventario () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("WITH CTE_LISTA_ITEMS_INVENTARIO AS (SELECT DescripcionAdministrativa.idDescripcionAdministrativa AS idArticulo, DescripcionAdministrativa.idObjetoGasto,ObjetoGasto.codigoObjetoGasto, ObjetoGasto.descripcionCuenta, DescripcionAdministrativa.unidadDeMedida,DescripcionAdministrativa.Cantidad, DescripcionAdministrativa.costo AS valorUnitario, DescripcionAdministrativa.nombreActividad, Persona.nombrePersona, Persona.apellidoPersona ,Departamento.idDepartamento, Departamento.nombreDepartamento, TipoUsuario.tipoUsuario, YEAR(Actividad.fechaCreacionActividad) AS anioPlanificacion FROM DescripcionAdministrativa INNER JOIN ObjetoGasto ON (DescripcionAdministrativa.idObjetoGasto = ObjetoGasto.idObjetoGasto) INNER JOIN Actividad ON (DescripcionAdministrativa.idActividad = Actividad.idActividad) INNER JOIN Usuario ON (Actividad.idPersonaUsuario = Usuario.idPersonaUsuario) INNER JOIN Persona ON (Usuario.idPersonaUsuario = Persona.idPersona) INNER JOIN Departamento ON (Usuario.idDepartamento = Departamento.idDepartamento) INNER JOIN TipoUsuario ON (Usuario.idTipoUsuario = TipoUsuario.idTipoUsuario)) SELECT * FROM CTE_LISTA_ITEMS_INVENTARIO WHERE CTE_LISTA_ITEMS_INVENTARIO.anioPlanificacion = :fecha LIMIT "  . $this->valorInicial . "," . $this->valorFinal .";");
                $stmt->bindValue(':fecha', $this->anioPlanificacion);
                // $stmt->bindValue(':valorInicial', intval($this->valorInicial));
                // $stmt->bindValue(':valorFinal', intval($this->valorFinal));
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_OBJ);
            
            } catch (PDOException $ex) {
                return array('error' => $ex->getMessage() );
            } finally {
                $this->conexionBD = null;
            }
        }

        public function getCantidadRegistrosTotales () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("WITH CTE_LISTA_ITEMS_INVENTARIO AS (SELECT COUNT(Actividad.idActividad) AS cantidadRegistros, YEAR(Actividad.fechaCreacionActividad) AS anioPlanificacion FROM DescripcionAdministrativa INNER JOIN ObjetoGasto ON (DescripcionAdministrativa.idObjetoGasto = ObjetoGasto.idObjetoGasto) INNER JOIN Actividad ON (DescripcionAdministrativa.idActividad = Actividad.idActividad) INNER JOIN Usuario ON (Actividad.idPersonaUsuario = Usuario.idPersonaUsuario) INNER JOIN Departamento ON (Usuario.idDepartamento = Departamento.idDepartamento) GROUP BY anioPlanificacion) SELECT CTE_LISTA_ITEMS_INVENTARIO.cantidadRegistros FROM CTE_LISTA_ITEMS_INVENTARIO WHERE CTE_LISTA_ITEMS_INVENTARIO.anioPlanificacion = :fecha");
                $stmt->bindValue(':fecha', $this->anioPlanificacion);
                if ($stmt->execute()) {
                    return $stmt->fetchObject();
                } else {
                    return false;
                }
            } catch (PDOException $ex) {
                return false;
            } finally {
                $this->conexionBD = null;
            }
        }

        public function getItemsCompraPorDeparamento () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("WITH CTE_LISTA_ITEMS_INVENTARIO AS (SELECT DescripcionAdministrativa.idDescripcionAdministrativa AS idArticulo, DescripcionAdministrativa.idObjetoGasto,ObjetoGasto.codigoObjetoGasto, ObjetoGasto.descripcionCuenta, DescripcionAdministrativa.unidadDeMedida,DescripcionAdministrativa.Cantidad, DescripcionAdministrativa.costo AS valorUnitario, DescripcionAdministrativa.nombreActividad, Persona.nombrePersona, Persona.apellidoPersona, Departamento.idDepartamento, Departamento.nombreDepartamento, TipoUsuario.tipoUsuario ,YEAR(Actividad.fechaCreacionActividad) AS anioPlanificacion FROM DescripcionAdministrativa INNER JOIN ObjetoGasto ON (DescripcionAdministrativa.idObjetoGasto = ObjetoGasto.idObjetoGasto) INNER JOIN Actividad ON (DescripcionAdministrativa.idActividad = Actividad.idActividad) INNER JOIN Usuario ON (Actividad.idPersonaUsuario = Usuario.idPersonaUsuario) INNER JOIN Persona ON (Usuario.idPersonaUsuario = Persona.idPersona) INNER JOIN TipoUsuario ON (Usuario.idTipoUsuario = TipoUsuario.idTipoUsuario) INNER JOIN Departamento ON (Usuario.idDepartamento = Departamento.idDepartamento)) SELECT * FROM CTE_LISTA_ITEMS_INVENTARIO WHERE CTE_LISTA_ITEMS_INVENTARIO.anioPlanificacion = :fecha AND CTE_LISTA_ITEMS_INVENTARIO.idDepartamento = :idDepartamento LIMIT " . $this->valorInicial . "," . $this->valorFinal . ";");
                $stmt->bindValue(':fecha', $this->anioPlanificacion);
                $stmt->bindValue(':idDepartamento', $this->idDepartamento);
                // $stmt->bindValue(':valorInicial', );
                // $stmt->bindValue(':valorFinal', $this->valorFinal);
                if ($stmt->execute()) {
                    return $stmt->fetchAll(PDO::FETCH_OBJ);
                } else {
                    return false;
                }
            } catch (PDOException $ex) {
                return array('error' => $ex->getMessage() );
            } finally {
                $this->conexionBD = null;
            }
        }

        public function getCantidadRegistrosPorDepartamento () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("SELECT COUNT(Departamento.idDepartamento) AS cantidadRegistros FROM DescripcionAdministrativa INNER JOIN ObjetoGasto ON (DescripcionAdministrativa.idObjetoGasto = ObjetoGasto.idObjetoGasto)INNER JOIN Actividad ON (DescripcionAdministrativa.idActividad = Actividad.idActividad) INNER JOIN Usuario ON (Actividad.idPersonaUsuario = Usuario.idPersonaUsuario) INNER JOIN Departamento ON (Usuario.idDepartamento = Departamento.idDepartamento) WHERE YEAR(Actividad.fechaCreacionActividad) = :fecha AND Departamento.idDepartamento = :idDepartamento;");
                $stmt->bindValue(':fecha', $this->anioPlanificacion);
                $stmt->bindValue(':idDepartamento', $this->idDepartamento);
                if ($stmt->execute()) {
                    return $stmt->fetchObject();
                } else {
                    return $stmt->fetchObject();
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

        public function getItemsCompraPorObjeto () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("WITH CTE_LISTA_ITEMS_INVENTARIO AS (SELECT DescripcionAdministrativa.idDescripcionAdministrativa AS idArticulo, DescripcionAdministrativa.idObjetoGasto,ObjetoGasto.codigoObjetoGasto, ObjetoGasto.descripcionCuenta, DescripcionAdministrativa.unidadDeMedida,DescripcionAdministrativa.Cantidad, DescripcionAdministrativa.costo AS valorUnitario, DescripcionAdministrativa.nombreActividad, Departamento.idDepartamento, Departamento.nombreDepartamento, Persona.nombrePersona, Persona.apellidoPersona, TipoUsuario.tipoUsuario, YEAR(Actividad.fechaCreacionActividad) AS anioPlanificacion FROM DescripcionAdministrativa INNER JOIN ObjetoGasto ON (DescripcionAdministrativa.idObjetoGasto = ObjetoGasto.idObjetoGasto) INNER JOIN Actividad ON (DescripcionAdministrativa.idActividad = Actividad.idActividad) INNER JOIN Usuario ON (Actividad.idPersonaUsuario = Usuario.idPersonaUsuario) INNER JOIN Persona ON (Usuario.idPersonaUsuario = Persona.idPersona) INNER JOIN TipoUsuario ON (Usuario.idTipoUsuario = TipoUsuario.idTipoUsuario) INNER JOIN Departamento ON (Usuario.idDepartamento = Departamento.idDepartamento)) SELECT * FROM CTE_LISTA_ITEMS_INVENTARIO WHERE CTE_LISTA_ITEMS_INVENTARIO.anioPlanificacion = :fecha AND CTE_LISTA_ITEMS_INVENTARIO.idObjetoGasto = :idObjetoGasto LIMIT " . $this->valorInicial . "," . $this->valorFinal . ";");
                $stmt->bindValue(':fecha', $this->anioPlanificacion);
                $stmt->bindValue(':idObjetoGasto', $this->idObjetoGasto);
                // $stmt->bindValue(':valorInicial', $this->valorInicial);
                // $stmt->bindValue(':valorFinal', $this->valorFinal);
                if ($stmt->execute()) {
                    return $stmt->fetchAll(PDO::FETCH_OBJ);
                } else {
                    return false;
                }
            } catch (PDOException $ex) {
                return false;
            } finally {
                $this->conexionBD = null;
            }
        }

        public function getCantidadRegistrosPorObjeto () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("SELECT COUNT(*) AS cantidadRegistros FROM DescripcionAdministrativa INNER JOIN ObjetoGasto ON (DescripcionAdministrativa.idObjetoGasto = ObjetoGasto.idObjetoGasto)INNER JOIN Actividad ON (DescripcionAdministrativa.idActividad = Actividad.idActividad) INNER JOIN Usuario ON (Actividad.idPersonaUsuario = Usuario.idPersonaUsuario) INNER JOIN Departamento ON (Usuario.idDepartamento = Departamento.idDepartamento) WHERE YEAR(Actividad.fechaCreacionActividad) = :fecha AND ObjetoGasto.idObjetoGasto = :idObjetoGasto;");
                $stmt->bindValue(':fecha', $this->anioPlanificacion);
                $stmt->bindValue(':idObjetoGasto', $this->idObjetoGasto);
                if ($stmt->execute()) {
                    return $stmt->fetchObject();
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

        public function getItemsCompraPorObjetoYDepto () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("WITH CTE_LISTA_ITEMS_INVENTARIO AS (SELECT DescripcionAdministrativa.idDescripcionAdministrativa AS idArticulo ,DescripcionAdministrativa.idObjetoGasto,ObjetoGasto.codigoObjetoGasto, ObjetoGasto.descripcionCuenta, DescripcionAdministrativa.unidadDeMedida,DescripcionAdministrativa.Cantidad, DescripcionAdministrativa.costo AS valorUnitario, DescripcionAdministrativa.nombreActividad, Persona.nombrePersona, Persona.ApellidoPersona ,Departamento.idDepartamento, Departamento.nombreDepartamento, TipoUsuario.tipoUsuario, YEAR(Actividad.fechaCreacionActividad) AS anioPlanificacion FROM DescripcionAdministrativa INNER JOIN ObjetoGasto ON (DescripcionAdministrativa.idObjetoGasto = ObjetoGasto.idObjetoGasto) INNER JOIN Actividad ON (DescripcionAdministrativa.idActividad = Actividad.idActividad) INNER JOIN Usuario ON (Actividad.idPersonaUsuario = Usuario.idPersonaUsuario) INNER JOIN Persona ON (Usuario.idPersonaUsuario = Persona.idPersona) INNER JOIN TipoUsuario ON (Usuario.idTipoUsuario = TipoUsuario.idTipoUsuario) INNER JOIN Departamento ON (Usuario.idDepartamento = Departamento.idDepartamento)) SELECT * FROM CTE_LISTA_ITEMS_INVENTARIO WHERE CTE_LISTA_ITEMS_INVENTARIO.anioPlanificacion = :fecha AND CTE_LISTA_ITEMS_INVENTARIO.idObjetoGasto = :idObjetoGasto AND CTE_LISTA_ITEMS_INVENTARIO.idDepartamento = :idDepartamento LIMIT " . $this->valorInicial . "," . $this->valorFinal . ";");
                $stmt->bindValue(':fecha', $this->anioPlanificacion);
                $stmt->bindValue(':idObjetoGasto', $this->idObjetoGasto);
                $stmt->bindValue(':idDepartamento', $this->idDepartamento);
                // $stmt->bindValue(':valorInicial', $this->valorInicial);
                // $stmt->bindValue(':valorFinal', $this->valorFinal);
                if ($stmt->execute()) {
                    return $stmt->fetchAll(PDO::FETCH_OBJ);
                } else {
                    return array(
                        'status'=> INTERNAL_SERVER_ERROR,
                        'data' => array('message' => $stmt->fetchAll(PDO::FETCH_OBJ))
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

        public function getCantidadRegistrosPorObjetoYDepto () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("SELECT COUNT(*) AS cantidadRegistros FROM DescripcionAdministrativa INNER JOIN ObjetoGasto ON (DescripcionAdministrativa.idObjetoGasto = ObjetoGasto.idObjetoGasto)INNER JOIN Actividad ON (DescripcionAdministrativa.idActividad = Actividad.idActividad) INNER JOIN Usuario ON (Actividad.idPersonaUsuario = Usuario.idPersonaUsuario) INNER JOIN Departamento ON (Usuario.idDepartamento = Departamento.idDepartamento) WHERE YEAR(Actividad.fechaCreacionActividad) = :fecha AND ObjetoGasto.idObjetoGasto = :idObjetoGasto AND Departamento.idDepartamento = :idDepartamento;");
                $stmt->bindValue(':fecha', $this->anioPlanificacion);
                $stmt->bindValue(':idObjetoGasto', $this->idObjetoGasto);
                $stmt->bindValue(':idDepartamento', $this->idDepartamento);
                if ($stmt->execute()) {
                    return $stmt->fetchObject();
                } else {
                    return false;
                }
            } catch (PDOException $ex) {
                return false;
            } finally {
                $this->conexionBD = null;
            }
        }

    }
?>