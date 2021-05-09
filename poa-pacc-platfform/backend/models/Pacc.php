<?php
    require_once('../../config/config.php');
    require_once('../../database/Conexion.php');
    require_once('../../validators/validators.php');
    require_once('../../models/DescripcionAdministrativa.php');

    
    require('../../vendor/autoload.php');
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\IOFactory;


    

    class Pacc {
        private $fechaPresupuestoAnual;
        private $idDepartamento;
        private $idPresupuesto;
        private $idObjetoGasto;
        private $idActividad;

        
        public function getFechaPresupuestoAnual() {
            return $this->fechaPresupuestoAnual;
        }

        public function setFechaPresupuestoAnual($fechaPresupuestoAnual) {
            $this->fechaPresupuestoAnual = $fechaPresupuestoAnual;
            return $this;
        }

        public function getIdDepartamento() {
            return $this->idDepartamento;
        }

        public function setIdDepartamento($idDepartamento) {
            $this->idDepartamento = $idDepartamento;
            return $this;
        }

        public function getIdPresupuesto() {
            return $this->idPresupuesto;
        }

        public function setIdPresupuesto($idPresupuesto) {
            $this->idPresupuesto = $idPresupuesto;
            return $this;
        }

        
        public function getIdObjetoGasto() {
            return $this->idObjetoGasto;
        }

        public function setIdObjetoGasto($idObjetoGasto) {
            $this->idObjetoGasto = $idObjetoGasto;
            return $this;
        }

        
        public function getIdActividad() {
            return $this->idActividad;
        }

        public function setIdActividad($idActividad) {
            $this->idActividad = $idActividad;
            return $this;
        }

        private $conexionBD;
        private $consulta;

            // Funciones/Metodos que generan el pacc facultad
        public function generaAnioPaccSeleccionado () {
            $this->conexionBD = new Conexion();
            $this->consulta = $this->conexionBD->connect();
            $stmt = $this->consulta->prepare("WITH CTE_GENERA_ANIO_PACC AS (SELECT YEAR(ControlPresupuestoActividad.fechaPresupuestoAnual) AS anioPacc FROM ControlPresupuestoActividad  WHERE ControlPresupuestoActividad.idControlPresupuestoActividad = :idPresupuesto) SELECT * FROM CTE_GENERA_ANIO_PACC;");
            $stmt->bindValue(':idPresupuesto', $this->fechaPresupuestoAnual);
            if ($stmt->execute()) {
                return $stmt->fetchObject();
            } else {
                return false;
            }
        }
        public function getDatosPacc () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("WITH CTE_GENERA_PRESUPUESTOS_POR_DEPTO AS (SELECT PresupuestoDepartamento.montoPresupuesto, Departamento.nombreDepartamento FROM PresupuestoDepartamento INNER JOIN ControlPresupuestoActividad ON (PresupuestoDepartamento.idControlPresupuestoActividad = ControlPresupuestoActividad.idControlPresupuestoActividad) INNER JOIN Departamento ON (PresupuestoDepartamento.idDepartamento = Departamento.idDepartamento) WHERE ControlPresupuestoActividad.estadoLlenadoActividades = :estado AND Departamento.idEstadoDepartamento = :estadoDepartamento) SELECT * FROM CTE_GENERA_PRESUPUESTOS_POR_DEPTO;");
                $stmt->bindValue(':estado', ESTADO_ACTIVO);
                $stmt->bindValue(':estadoDepartamento', ESTADO_ACTIVO);
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error, los presupuestos no se listaron correctamente')
                    );
                }
            } catch (PDOException $ex) {
                return false;
            } finally {
                $this->conexionBD = null;
            }
        }

        public function generaPresupuestoAnualComparativa () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("WITH CTE_GENERA_PRESUPUESTOS_COMPARATIVA AS (SELECT SUM(PresupuestoDepartamento.montoPresupuesto) AS montoUtilizado, ControlPresupuestoActividad.presupuestoAnual, YEAR(ControlPresupuestoActividad.fechaPresupuestoAnual) AS fechaPresupuesto FROM PresupuestoDepartamento INNER JOIN ControlPresupuestoActividad ON (PresupuestoDepartamento.idControlPresupuestoActividad = ControlPresupuestoActividad.idControlPresupuestoActividad) WHERE ControlPresupuestoActividad.estadoLlenadoActividades = :estado GROUP BY ControlPresupuestoActividad.presupuestoAnual, fechaPresupuesto) SELECT * FROM CTE_GENERA_PRESUPUESTOS_COMPARATIVA;");
                $stmt->bindValue(':estado', ESTADO_ACTIVO);
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchObject()
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error, los presupuestos no se listaron correctamente')
                    );
                }
            } catch (PDOException $ex) {
                return false;
            } finally {
                $this->conexionBD = null;
            }
        }
        
        public function generaAniosPresupuestoAnual () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("WITH CTE_LISTA_ANIOS_PRESUPUESTOS AS (SELECT ControlPresupuestoActividad.idControlPresupuestoActividad, YEAR(ControlPresupuestoActividad.fechaPresupuestoAnual) AS anio FROM ControlPresupuestoActividad) SELECT * FROM CTE_LISTA_ANIOS_PRESUPUESTOS ORDER BY CTE_LISTA_ANIOS_PRESUPUESTOS.anio DESC; ");
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error, los presupuestos no se listaron correctamente')
                    );
                }
            } catch (PDOException $ex) {
                return false;
            } finally {
                $this->conexionBD = null;
            }
        }

        public function generaPaccFacultadIngenieria ($orderBy) {
            $this->conexionBD = new Conexion();
            $this->consulta = $this->conexionBD->connect();
            $stmt = $this->consulta->prepare("WITH CTE_QUERY_PACC_INGENIERIA AS (SELECT Actividad.CorrelativoActividad, ObjetoGasto.codigoObjetoGasto, DescripcionAdministrativa.nombreActividad, DescripcionAdministrativa.unidadDeMedida,DescripcionAdministrativa.cantidad, DescripcionAdministrativa.costo, DescripcionAdministrativa.costoTotal, Departamento.nombreDepartamento FROM DescripcionAdministrativa INNER JOIN  Actividad ON (DescripcionAdministrativa.idActividad = Actividad.idActividad) INNER JOIN ObjetoGasto ON (DescripcionAdministrativa.idObjetoGasto = ObjetoGasto.idObjetoGasto) INNER JOIN Usuario ON (Actividad.idPersonaUsuario = Usuario.idPersonaUsuario) INNER JOIN Departamento ON (Usuario.idDepartamento = Departamento.idDepartamento) WHERE DATE_FORMAT(Actividad.fechaCreacionActividad,'%Y') = (SELECT YEAR(ControlPresupuestoActividad.fechaPresupuestoAnual) FROM ControlPresupuestoActividad WHERE ControlPresupuestoActividad.idControlPresupuestoActividad = :idPresupuesto)) SELECT * FROM CTE_QUERY_PACC_INGENIERIA ORDER BY CTE_QUERY_PACC_INGENIERIA. " . $orderBy . " ASC;");
            $stmt->bindValue(':idPresupuesto', $this->fechaPresupuestoAnual);
            if ($stmt->execute()) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                return false;
            }
        }

        public function generaCostoObjetosGastoPaccGeneral () {
            $this->conexionBD = new Conexion();
            $this->consulta = $this->conexionBD->connect();
            $stmt = $this->consulta->prepare("WITH CTE_GENERA_COSTO_TOTAL_POR_OG AS (SELECT ObjetoGasto.codigoObjetoGasto, SUM(DescripcionAdministrativa.costoTotal) AS sumCostoActPorCodObjGasto FROM DescripcionAdministrativa INNER JOIN Actividad ON (DescripcionAdministrativa.idActividad = Actividad.idActividad) INNER JOIN ObjetoGasto ON (DescripcionAdministrativa.idObjetoGasto = ObjetoGasto.idObjetoGasto)WHERE DATE_FORMAT(Actividad.fechaCreacionActividad, '%Y') = (SELECT YEAR(ControlPresupuestoActividad.fechaPresupuestoAnual) FROM ControlPresupuestoActividad WHERE ControlPresupuestoActividad.idControlPresupuestoActividad = :idPresupuesto) GROUP BY ObjetoGasto.idObjetoGasto) SELECT * FROM CTE_GENERA_COSTO_TOTAL_POR_OG ORDER BY CTE_GENERA_COSTO_TOTAL_POR_OG.codigoObjetoGasto ASC;");
            $stmt->bindValue(':idPresupuesto', $this->fechaPresupuestoAnual);
            if ($stmt->execute()) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                return array(
                    'status'=> BAD_REQUEST,
                    'data' => array('message' => 'Ha ocurrido un error, los presupuestos no se listaroncorrectamente')
                );
            }
        }

        public function generaDescripcionObjetosGastoPaccGeneral () {
            $this->conexionBD = new Conexion();
            $this->consulta = $this->conexionBD->connect();
            $stmt = $this->consulta->prepare("WITH CTE_GENERA_COSTO_TOTAL_POR_OG AS (SELECT ObjetoGasto.codigoObjetoGasto, ObjetoGasto.descripcionCuenta, SUM(DescripcionAdministrativa.costoTotal) AS sumCostoActPorCodObjGasto FROM DescripcionAdministrativa INNER JOIN Actividad ON (DescripcionAdministrativa.idActividad = Actividad.idActividad) INNER JOIN ObjetoGasto ON (DescripcionAdministrativa.idObjetoGasto = ObjetoGasto.idObjetoGasto) WHERE DATE_FORMAT(Actividad.fechaCreacionActividad, '%Y') = (SELECT YEAR(ControlPresupuestoActividad.fechaPresupuestoAnual) FROM ControlPresupuestoActividad WHERE ControlPresupuestoActividad.idControlPresupuestoActividad = :idPresupuesto) GROUP BY ObjetoGasto.idObjetoGasto, ObjetoGasto.descripcionCuenta) SELECT * FROM CTE_GENERA_COSTO_TOTAL_POR_OG ORDER BY CTE_GENERA_COSTO_TOTAL_POR_OG.codigoObjetoGasto;");
            $stmt->bindValue(':idPresupuesto', $this->fechaPresupuestoAnual);
            if ($stmt->execute()) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                return false;
            }
        }

        public function generaCostoTotalDescripciones () {
            $this->conexionBD = new Conexion();
            $this->consulta = $this->conexionBD->connect();
            $stmt = $this->consulta->prepare("WITH CTE_QUERY_PACC_INGENIERIA AS (SELECT SUM(DescripcionAdministrativa.costoTotal) AS total FROM DescripcionAdministrativa INNER JOIN  Actividad ON (DescripcionAdministrativa.idActividad = Actividad.idActividad) INNER JOIN ObjetoGasto ON (DescripcionAdministrativa.idObjetoGasto = ObjetoGasto.idObjetoGasto) INNER JOIN Usuario ON (Actividad.idPersonaUsuario = Usuario.idPersonaUsuario) INNER JOIN Departamento ON (Usuario.idDepartamento = Departamento.idDepartamento) WHERE DATE_FORMAT(Actividad.fechaCreacionActividad,'%Y') = (SELECT YEAR(ControlPresupuestoActividad.fechaPresupuestoAnual) FROM ControlPresupuestoActividad WHERE ControlPresupuestoActividad.idControlPresupuestoActividad = :idPresupuesto)) SELECT * FROM CTE_QUERY_PACC_INGENIERIA;");
            $stmt->bindValue(':idPresupuesto', $this->fechaPresupuestoAnual);
            if ($stmt->execute()) {
                return $stmt->fetchObject();
            } else {
                return array(
                    'status'=> BAD_REQUEST,
                    'data' => array('message' => 'Ha ocurrido un error, los presupuestos no se listaroncorrectamente')
                );
            }
        }

        // Funciones/Metodos que generan el pacc por el departamento
        public function getDataDepartamento () {
            $this->conexionBD = new Conexion();
            $this->consulta = $this->conexionBD->connect();
            $stmt = $this->consulta->prepare("WITH CTE_LISTA_DATA_DEPARTAMENTO AS (SELECT idDepartamento, nombreDepartamento, idEstadoDepartamento FROM Departamento) SELECT * FROM CTE_LISTA_DATA_DEPARTAMENTO WHERE CTE_LISTA_DATA_DEPARTAMENTO.idDepartamento = :idDepartamento AND CTE_LISTA_DATA_DEPARTAMENTO.idEstadoDepartamento = :estado");
            $stmt->bindValue(':idDepartamento', $this->idDepartamento);
            $stmt->bindValue(':estado', ESTADO_ACTIVO);
            if ($stmt->execute()) {
                return $stmt->fetchObject();
            } else {
                return false;
            }
        }

        public function generaPaccPorDepartamento ($orderBy) {
            $this->conexionBD = new Conexion();
            $this->consulta = $this->conexionBD->connect();
            $stmt = $this->consulta->prepare("WITH CTE_QUERY_PACC_INGENIERIA AS (SELECT Actividad.CorrelativoActividad, ObjetoGasto.codigoObjetoGasto, DescripcionAdministrativa.nombreActividad, DescripcionAdministrativa.unidadDeMedida,DescripcionAdministrativa.cantidad, DescripcionAdministrativa.costo, DescripcionAdministrativa.costoTotal, Departamento.nombreDepartamento FROM DescripcionAdministrativa INNER JOIN  Actividad ON (DescripcionAdministrativa.idActividad = Actividad.idActividad) INNER JOIN ObjetoGasto ON (DescripcionAdministrativa.idObjetoGasto = ObjetoGasto.idObjetoGasto) INNER JOIN Usuario ON (Actividad.idPersonaUsuario = Usuario.idPersonaUsuario) INNER JOIN Departamento ON (Usuario.idDepartamento = Departamento.idDepartamento) WHERE DATE_FORMAT(Actividad.fechaCreacionActividad,'%Y') = (SELECT YEAR(ControlPresupuestoActividad.fechaPresupuestoAnual) FROM ControlPresupuestoActividad WHERE ControlPresupuestoActividad.idControlPresupuestoActividad = :idPresupuesto) AND Usuario.idDepartamento = :idDepartamento) SELECT * FROM CTE_QUERY_PACC_INGENIERIA ORDER BY CTE_QUERY_PACC_INGENIERIA." . $orderBy . " ASC;");
            $stmt->bindValue(':idPresupuesto', $this->fechaPresupuestoAnual);
            $stmt->bindValue(':idDepartamento', $this->idDepartamento);
            if ($stmt->execute()) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                return false;
            }
        }

        public function generaCostoObjetosGastoPaccDepartamento () {
            $this->conexionBD = new Conexion();
            $this->consulta = $this->conexionBD->connect();
            $stmt = $this->consulta->prepare("WITH CTE_GENERA_COSTO_TOTAL_POR_OG AS (SELECT ObjetoGasto.codigoObjetoGasto, SUM(DescripcionAdministrativa.costoTotal) AS sumCostoActPorCodObjGasto FROM DescripcionAdministrativa INNER JOIN Actividad ON (DescripcionAdministrativa.idActividad = Actividad.idActividad) INNER JOIN ObjetoGasto ON (DescripcionAdministrativa.idObjetoGasto = ObjetoGasto.idObjetoGasto) INNER JOIN Usuario ON (Actividad.idPersonaUsuario = Usuario.idPersonaUsuario) WHERE DATE_FORMAT(Actividad.fechaCreacionActividad, '%Y') = (SELECT YEAR(ControlPresupuestoActividad.fechaPresupuestoAnual) FROM ControlPresupuestoActividad WHERE ControlPresupuestoActividad.idControlPresupuestoActividad = :idPresupuesto AND Usuario.idDepartamento = :idDepartamento) GROUP BY ObjetoGasto.idObjetoGasto) SELECT * FROM CTE_GENERA_COSTO_TOTAL_POR_OG ORDER BY CTE_GENERA_COSTO_TOTAL_POR_OG.codigoObjetoGasto ASC;");
            $stmt->bindValue(':idPresupuesto', $this->fechaPresupuestoAnual);
            $stmt->bindValue(':idDepartamento', $this->idDepartamento);
            if ($stmt->execute()) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                return array(
                    'status'=> BAD_REQUEST,
                    'data' => array('message' => 'Ha ocurrido un error, los presupuestos no se listaroncorrectamente')
                );
            }
        }

        public function generaDescripcionObjetosGastoPaccDepartamento () {
            $this->conexionBD = new Conexion();
            $this->consulta = $this->conexionBD->connect();
            $stmt = $this->consulta->prepare("WITH CTE_GENERA_COSTO_TOTAL_POR_OG AS (SELECT ObjetoGasto.codigoObjetoGasto, ObjetoGasto.descripcionCuenta, SUM(DescripcionAdministrativa.costoTotal) AS sumCostoActPorCodObjGasto FROM DescripcionAdministrativa INNER JOIN Actividad ON (DescripcionAdministrativa.idActividad = Actividad.idActividad) INNER JOIN ObjetoGasto ON (DescripcionAdministrativa.idObjetoGasto = ObjetoGasto.idObjetoGasto) INNER JOIN Usuario ON (Actividad.idPersonaUsuario = Usuario.idPersonaUsuario) WHERE DATE_FORMAT(Actividad.fechaCreacionActividad, '%Y') = (SELECT YEAR(ControlPresupuestoActividad.fechaPresupuestoAnual) FROM ControlPresupuestoActividad WHERE ControlPresupuestoActividad.idControlPresupuestoActividad = :idPresupuesto AND Usuario.idDepartamento = :idDepartamento) GROUP BY ObjetoGasto.idObjetoGasto, ObjetoGasto.descripcionCuenta) SELECT * FROM CTE_GENERA_COSTO_TOTAL_POR_OG ORDER BY CTE_GENERA_COSTO_TOTAL_POR_OG.codigoObjetoGasto;");
            $stmt->bindValue(':idPresupuesto', $this->fechaPresupuestoAnual);
            $stmt->bindValue(':idDepartamento', $this->idDepartamento);
            if ($stmt->execute()) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                return false;
            }
        }

        public function generaCostoTotalDescripcionesDepartamento () {
            $this->conexionBD = new Conexion();
            $this->consulta = $this->conexionBD->connect();
            $stmt = $this->consulta->prepare("WITH CTE_QUERY_PACC_INGENIERIA AS (SELECT SUM(DescripcionAdministrativa.costoTotal) AS total FROM DescripcionAdministrativa INNER JOIN  Actividad ON (DescripcionAdministrativa.idActividad = Actividad.idActividad) INNER JOIN ObjetoGasto ON (DescripcionAdministrativa.idObjetoGasto = ObjetoGasto.idObjetoGasto) INNER JOIN Usuario ON (Actividad.idPersonaUsuario = Usuario.idPersonaUsuario) INNER JOIN Departamento ON (Usuario.idDepartamento = Departamento.idDepartamento) WHERE DATE_FORMAT(Actividad.fechaCreacionActividad,'%Y') = (SELECT YEAR(ControlPresupuestoActividad.fechaPresupuestoAnual) FROM ControlPresupuestoActividad WHERE ControlPresupuestoActividad.idControlPresupuestoActividad = :idPresupuesto AND Usuario.idDepartamento = :idDepartamento)) SELECT * FROM CTE_QUERY_PACC_INGENIERIA;");
            $stmt->bindValue(':idPresupuesto', $this->fechaPresupuestoAnual);
            $stmt->bindValue(':idDepartamento', $this->idDepartamento);
            if ($stmt->execute()) {
                return $stmt->fetchObject();
            } else {
                return array(
                    'status'=> BAD_REQUEST,
                    'data' => array('message' => 'Ha ocurrido un error, los presupuestos no se listaroncorrectamente')
                );
            }
        }

        public function getDataGastosPorDimnesionLlenada() {
            if (is_int($this->fechaPresupuestoAnual) && is_int($this->idDepartamento)) {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("SELECT Actividad.idDimension, DimensionEstrategica.dimensionEstrategica, SUM(Actividad.CostoTotal) AS sumatoriaCostosPorDimension, YEAR(Actividad.fechaCreacionActividad) AS anioActividades, Departamento.nombreDepartamento FROM Actividad INNER JOIN DimensionEstrategica ON (Actividad.idDimension = DimensionEstrategica.idDimension) INNER JOIN Usuario ON (Actividad.idPersonaUsuario = Usuario.idPersonaUsuario) INNER JOIN Departamento ON (Usuario.idDepartamento = Departamento.idDepartamento) WHERE Usuario.idDepartamento = :idDepartamento AND YEAR(Actividad.fechaCreacionActividad) = (SELECT YEAR(ControlPresupuestoActividad.fechaPresupuestoAnual) FROM ControlPresupuestoActividad WHERE ControlPresupuestoActividad.idControlPresupuestoActividad = :idPresupuesto) GROUP BY Actividad.idDimension, DimensionEstrategica.dimensionEstrategica, anioActividades, Departamento.nombreDepartamento;");
                $stmt->bindValue(':idDepartamento', $this->idDepartamento);
                $stmt->bindValue(':idPresupuesto', $this->fechaPresupuestoAnual);
                if ($stmt->execute()) {
                    return array(
                        'status'=> SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error, la informacion para generar la grafica de las dimensiones no se pudo ejecutar, intente nuevamente')
                    );
                }
            } else {
                return array(
                    'status'=> BAD_REQUEST,
                    'data' => array('message' => 'Ha ocurrido un error, la informacion digitada es erronea')
                );
            }
        }

        public function generaCostoPorCorrelativoGeneral () {
            $this->conexionBD = new Conexion();
            $this->consulta = $this->conexionBD->connect();
            $stmt = $this->consulta->prepare("WITH CTE_GENERA_COSTO_TOTAL_POR_CORRELATIVO AS (SELECT Actividad.correlativoActividad, Actividad.costoTotal FROM Actividad WHERE DATE_FORMAT(Actividad.fechaCreacionActividad, '%Y') = (SELECT YEAR(ControlPresupuestoActividad.fechaPresupuestoAnual) FROM ControlPresupuestoActividad WHERE ControlPresupuestoActividad.idControlPresupuestoActividad = :idPresupuesto)) SELECT * FROM CTE_GENERA_COSTO_TOTAL_POR_CORRELATIVO ORDER BY CTE_GENERA_COSTO_TOTAL_POR_CORRELATIVO.correlativoActividad ASC;");
            $stmt->bindValue(':idPresupuesto', $this->fechaPresupuestoAnual);
            if ($stmt->execute()) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                return array(
                    'status'=> BAD_REQUEST,
                    'data' => array('message' => 'Ha ocurrido un error, los presupuestos no se listaron correctamente')
                );
            }
        } 

        public function generaCostoPorCorrelativoDepartamento () {
            $this->conexionBD = new Conexion();
            $this->consulta = $this->conexionBD->connect();
            $stmt = $this->consulta->prepare("WITH CTE_GENERA_COSTO_TOTAL_POR_CORRELATIVO AS (SELECT Actividad.correlativoActividad, Actividad.costoTotal, Actividad.idPersonaUsuario, Usuario.idDepartamento FROM Actividad INNER JOIN Usuario ON (Actividad.idPersonaUsuario = Usuario.idPersonaUsuario) WHERE DATE_FORMAT(Actividad.fechaCreacionActividad, '%Y') = (SELECT YEAR(ControlPresupuestoActividad.fechaPresupuestoAnual) FROM ControlPresupuestoActividad WHERE ControlPresupuestoActividad.idControlPresupuestoActividad = :idPresupuesto)) SELECT CTE_GENERA_COSTO_TOTAL_POR_CORRELATIVO.correlativoActividad, CTE_GENERA_COSTO_TOTAL_POR_CORRELATIVO.costoTotal FROM CTE_GENERA_COSTO_TOTAL_POR_CORRELATIVO WHERE CTE_GENERA_COSTO_TOTAL_POR_CORRELATIVO.idDepartamento = :idDepartamento ORDER BY CTE_GENERA_COSTO_TOTAL_POR_CORRELATIVO.correlativoActividad ASC;");
            $stmt->bindValue(':idPresupuesto', $this->fechaPresupuestoAnual);
            $stmt->bindValue(':idDepartamento', $this->idDepartamento);
            if ($stmt->execute()) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                return array(
                    'status'=> BAD_REQUEST,
                    'data' => array('message' => 'Ha ocurrido un error, los presupuestos no se listaron correctamente')
                );
            }
        }

        public function getObjetosPorAnioDescripcion () {

            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("WITH CTE_GENERA_COSTO_POR_OBJETO AS (SELECT DescripcionAdministrativa.idObjetoGasto, ObjetoGasto.descripcionCuenta, ObjetoGasto.abrev, Actividad.idActividad, Actividad.fechaCreacionActividad FROM DescripcionAdministrativa INNER JOIN ObjetoGasto ON (DescripcionAdministrativa.idObjetoGasto = ObjetoGasto.idObjetoGasto) INNER JOIN Actividad ON (DescripcionAdministrativa.idActividad = Actividad.idActividad)) SELECT CTE_GENERA_COSTO_POR_OBJETO.idObjetoGasto, CTE_GENERA_COSTO_POR_OBJETO.descripcionCuenta, CTE_GENERA_COSTO_POR_OBJETO.abrev, YEAR(CTE_GENERA_COSTO_POR_OBJETO.fechaCreacionActividad) AS fecha FROM CTE_GENERA_COSTO_POR_OBJETO WHERE YEAR(CTE_GENERA_COSTO_POR_OBJETO.fechaCreacionActividad) = (SELECT YEAR(ControlPresupuestoActividad.fechaPresupuestoAnual) FROM ControlPresupuestoActividad WHERE ControlPresupuestoActividad.idControlPresupuestoActividad = :idPresupuesto) ORDER BY CTE_GENERA_COSTO_POR_OBJETO.idObjetoGasto;");
                    $stmt->bindValue(':idPresupuesto', $this->idPresupuesto);
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar los objetos de gasto')
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

        public function generaCostoPorObjetoGasto () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("WITH CTE_GENERA_COSTO_TOTAL_POR_OG AS (SELECT ObjetoGasto.codigoObjetoGasto, ObjetoGasto.idObjetoGasto, SUM(DescripcionAdministrativa.costoTotal) AS sumCostoActPorCodObjGasto, ObjetoGasto.descripcionCuenta FROM DescripcionAdministrativa INNER JOIN Actividad ON (DescripcionAdministrativa.idActividad = Actividad.idActividad) INNER JOIN ObjetoGasto ON (DescripcionAdministrativa.idObjetoGasto = ObjetoGasto.idObjetoGasto)WHERE DATE_FORMAT(Actividad.fechaCreacionActividad, '%Y') = (SELECT YEAR(ControlPresupuestoActividad.fechaPresupuestoAnual) FROM ControlPresupuestoActividad WHERE ControlPresupuestoActividad.idControlPresupuestoActividad = :idPresupuesto) GROUP BY ObjetoGasto.codigoObjetoGasto, ObjetoGasto.idObjetoGasto, ObjetoGasto.descripcionCuenta) SELECT CTE_GENERA_COSTO_TOTAL_POR_OG.idObjetoGasto, CTE_GENERA_COSTO_TOTAL_POR_OG.codigoObjetoGasto, CTE_GENERA_COSTO_TOTAL_POR_OG.sumCostoActPorCodObjGasto, CTE_GENERA_COSTO_TOTAL_POR_OG.descripcionCuenta FROM CTE_GENERA_COSTO_TOTAL_POR_OG WHERE CTE_GENERA_COSTO_TOTAL_POR_OG.idObjetoGasto = :idObjetoGasto ORDER BY CTE_GENERA_COSTO_TOTAL_POR_OG.codigoObjetoGasto ASC;");
                    $stmt->bindValue(':idPresupuesto', $this->idPresupuesto);
                    $stmt->bindValue(':idObjetoGasto', $this->idObjetoGasto);
                if ($stmt->execute()) {
                    if ($stmt->rowCount() == 1) {      
                        return array(
                            'status' => SUCCESS_REQUEST,
                            'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                        );
                    } else {
                        return array(
                            'status' => SUCCESS_REQUEST,
                            'data' => null
                        );
                    }
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar la informacion')
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

        public function generaCostoPorObjetoGastoDepto () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("WITH CTE_GENERA_COSTO_TOTAL_POR_OG AS (SELECT ObjetoGasto.codigoObjetoGasto, SUM(DescripcionAdministrativa.costoTotal) AS sumCostoActPorCodObjGasto, ObjetoGasto.descripcionCuenta FROM DescripcionAdministrativa INNER JOIN Actividad ON (DescripcionAdministrativa.idActividad = Actividad.idActividad) INNER JOIN ObjetoGasto ON (DescripcionAdministrativa.idObjetoGasto = ObjetoGasto.idObjetoGasto) INNER JOIN Usuario ON (Actividad.idPersonaUsuario = Usuario.idPersonaUsuario) WHERE DATE_FORMAT(Actividad.fechaCreacionActividad, '%Y') = (SELECT YEAR(ControlPresupuestoActividad.fechaPresupuestoAnual) FROM ControlPresupuestoActividad WHERE ControlPresupuestoActividad.idControlPresupuestoActividad = :idPresupuesto AND Usuario.idDepartamento = :idDepartamento AND ObjetoGasto.idObjetoGasto = :idObjetoGasto) GROUP BY ObjetoGasto.idObjetoGasto, ObjetoGasto.descripcionCuenta) SELECT * FROM CTE_GENERA_COSTO_TOTAL_POR_OG ORDER BY CTE_GENERA_COSTO_TOTAL_POR_OG.codigoObjetoGasto ASC;");
                    $stmt->bindValue(':idObjetoGasto', $this->idObjetoGasto);
                    $stmt->bindValue(':idPresupuesto', $this->idPresupuesto);
                    $stmt->bindValue(':idDepartamento', $this->idDepartamento);
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar los objetos de gasto')
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

        public function generaCorrelativosPorAnio () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("WITH CTE_GENERA_CORRELATIVOS_ACTIVIDAD AS (SELECT Actividad.idActividad, Actividad.correlativoActividad, Actividad.fechaCreacionActividad FROM Actividad) SELECT CTE_GENERA_CORRELATIVOS_ACTIVIDAD.idActividad, CTE_GENERA_CORRELATIVOS_ACTIVIDAD.correlativoActividad FROM CTE_GENERA_CORRELATIVOS_ACTIVIDAD WHERE YEAR(CTE_GENERA_CORRELATIVOS_ACTIVIDAD.fechaCreacionActividad) = (SELECT YEAR(ControlPresupuestoActividad.fechaPresupuestoAnual) FROM ControlPresupuestoActividad WHERE idControlPresupuestoActividad = :idPresupuesto) ORDER BY CTE_GENERA_CORRELATIVOS_ACTIVIDAD.correlativoActividad ASC;");
                    $stmt->bindValue(':idPresupuesto', $this->idPresupuesto);
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar los objetos de gasto')
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

        public function generaCostoPorCorrelativo () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("WITH CTE_GENERA_COSTO_POR_CORRELATIVO AS (SELECT Actividad.idActividad, Actividad.correlativoActividad, Departamento.nombreDepartamento, Actividad.costoTotal FROM Actividad INNER JOIN Usuario ON (Actividad.idPersonaUsuario = Usuario.idPersonaUsuario) INNER JOIN Departamento ON (Usuario.idDepartamento = Departamento.idDepartamento)) SELECT CTE_GENERA_COSTO_POR_CORRELATIVO.idActividad, CTE_GENERA_COSTO_POR_CORRELATIVO.correlativoActividad, CTE_GENERA_COSTO_POR_CORRELATIVO.nombreDepartamento , CTE_GENERA_COSTO_POR_CORRELATIVO.costoTotal FROM CTE_GENERA_COSTO_POR_CORRELATIVO WHERE CTE_GENERA_COSTO_POR_CORRELATIVO.idActividad = :idActividad");
                    $stmt->bindValue(':idActividad', $this->idActividad);
                if ($stmt->execute()) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar el costo de la actividad')
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