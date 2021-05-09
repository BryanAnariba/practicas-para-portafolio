<?php
    if (!isset($_SESSION)) {
        session_start();
    }
    require_once('../../config/config.php');
    require_once('../../database/Conexion.php');
    require_once('../../validators/validators.php');
    require_once('CostoActividadPorTrimestre.php');

    class Actividad extends CostoActividadPorTrimestre {
        private $idActividad;
        private $idPersonaUsuario;
        private $idDimension;
        private $idObjetivoInstitucional;
        private $idResultadoInstitucional;
        private $idAreaEstrategica;
        private $idTipoActividad;
        private $idEstadoActividad;
        private $resultadosUnidad;
        private $indicadoresResultado;
        private $actividad;
        private $correlativoActividad;
        private $justificacionActividad;
        private $medioVerificacionActividad;
        private $poblacionOjetivoActividad;
        private $responsableActividad;
        private $fechaCreacionActividad;
        private $costoTotal;

        private $conexionBD;
        private $consulta;
        private $tablaBaseDatos;

        public function __construct() {
            $this->tablaBaseDatos = TBL_ACTIVIDADES;
        }

        public function getIdActividad() {
            return $this->idActividad;
        }

        public function setIdActividad($idActividad) {
            $this->idActividad = $idActividad;
            return $this;
        }
        
        public function getIdPersonaUsuario() {
            return $this->idPersonaUsuario;
        }

        public function setIdPersonaUsuario($idPersonaUsuario) {
            $this->idPersonaUsuario = $idPersonaUsuario;
            return $this;
        }

        public function getIdDimension() {
            return $this->idDimension;
        }

        public function setIdDimension($idDimension) {
            $this->idDimension = $idDimension;
            return $this;
        }

        public function getIdObjetivoInstitucional() {
            return $this->idObjetivoInstitucional;
        }

        public function setIdObjetivoInstitucional($idObjetivoInstitucional) {
            $this->idObjetivoInstitucional = $idObjetivoInstitucional;
            return $this;
        }

        public function getIdResultadoInstitucional() {
            return $this->idResultadoInstitucional;
        }

        public function setIdResultadoInstitucional($idResultadoInstitucional) {
            $this->idResultadoInstitucional = $idResultadoInstitucional;
            return $this;
        }

        public function getIdAreaEstrategica() {
            return $this->idAreaEstrategica;
        }

        public function setIdAreaEstrategica($idAreaEstrategica) {
            $this->idAreaEstrategica = $idAreaEstrategica;
            return $this;
        }

        public function getIdTipoActividad() {
            return $this->idTipoActividad;
        }

        public function setIdTipoActividad($idTipoActividad) {
            $this->idTipoActividad = $idTipoActividad;
            return $this;
        }

        public function getResultadosUnidad() {
            return $this->resultadosUnidad;
        }

        public function setResultadosUnidad($resultadosUnidad) {
            $this->resultadosUnidad = $resultadosUnidad;
            return $this;
        }

        public function getIndicadoresResultado() {
            return $this->indicadoresResultado;
        }

        public function setIndicadoresResultado($indicadoresResultado) {
            $this->indicadoresResultado = $indicadoresResultado;
            return $this;
        }

        public function getActividad() {
            return $this->actividad;
        }

        public function setActividad($actividad) {
            $this->actividad = $actividad;
            return $this;
        }

        public function getCorrelativoActividad() {
            return $this->correlativoActividad;
        }

        public function setCorrelativoActividad($correlativoActividad) {
            $this->correlativoActividad = $correlativoActividad;
            return $this;
        }

        public function getJustificacionActividad() {
            return $this->justificacionActividad;
        }

        public function setJustificacionActividad($justificacionActividad) {
            $this->justificacionActividad = $justificacionActividad;
            return $this;
        }

        public function getMedioVerificacionActividad() {
            return $this->medioVerificacionActividad;
        }

        public function setMedioVerificacionActividad($medioVerificacionActividad) {
            $this->medioVerificacionActividad = $medioVerificacionActividad;
            return $this;
        }

        public function getPoblacionOjetivoActividad() {
            return $this->poblacionOjetivoActividad;
        }

        public function setPoblacionOjetivoActividad($poblacionOjetivoActividad) {
            $this->poblacionOjetivoActividad = $poblacionOjetivoActividad;
            return $this;
        }

        public function getResponsableActividad() {
            return $this->responsableActividad;
        }

        public function setResponsableActividad($responsableActividad) {
            $this->responsableActividad = $responsableActividad;
            return $this;
        }

        public function getFechaCreacionActividad() {
            return $this->fechaCreacionActividad;
        }

        public function setFechaCreacionActividad($fechaCreacionActividad) {
            $this->fechaCreacionActividad = $fechaCreacionActividad;
            return $this;
        }

        public function getCostoTotal() {
            return $this->costoTotal;
        }

        public function setCostoTotal($costoTotal) {
            $this->costoTotal = $costoTotal;
            return $this;
        }

        public function getIdEstadoActividad() {
            return $this->idEstadoActividad;
        }

        public function setIdEstadoActividad($idEstadoActividad) {
            $this->idEstadoActividad = $idEstadoActividad;
            return $this;
        }

        public function verificaEstadoPresupuesto () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("WITH CTE_VER_ESTADO_ANUAL_PRESUPUESTO AS (SELECT ControlPresupuestoActividad.fechaPresupuestoAnual, DATE_FORMAT(ControlPresupuestoActividad.fechaPresupuestoAnual, '%Y') AS anioPresupuesto,ControlPresupuestoActividad.idEstadoPresupuestoAnual, EstadoDCDUOAO.estado FROM ControlPresupuestoActividad INNER JOIN EstadoDCDUOAO ON (ControlPresupuestoActividad.idEstadoPresupuestoAnual = EstadoDCDUOAO.idEstado)) SELECT * FROM  CTE_VER_ESTADO_ANUAL_PRESUPUESTO WHERE DATE_FORMAT(CTE_VER_ESTADO_ANUAL_PRESUPUESTO.fechaPresupuestoAnual,'%Y') = (SELECT DATE_FORMAT(fechaPresupuestoAnual, '%Y') FROM ControlPresupuestoActividad WHERE EstadoLlenadoActividades = 1);");

                if ($stmt->execute()) {
                    $data = $stmt->fetchObject();
                    if (!empty($data->idEstadoPresupuestoAnual)) {
                        if ($data->idEstadoPresupuestoAnual == ESTADO_ACTIVO) {
                            return true;
                        } else {
                            return false;
                        }
                    } else {
                        return false;
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

        public function verificaEstadoPresupuestoAnual () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("SELECT * FROM ControlPresupuestoActividad WHERE estadoLlenadoActividades = :estadoLlenado");
                $stmt->bindValue(':estadoLlenado', ESTADO_ACTIVO);
                if ($stmt->execute() && ($stmt->rowCount() == 0)) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => array('message' => true)
                    );
                } else {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => array('message' => false)
                    );
                }
            } catch (PDOException $ex) {
                return array(
                    'status' => SUCCESS_REQUEST,
                    'data' => array('message' => false)
                );
            } finally {
                $this->conexionBD = null;
            }
        }

        public function getActividadesPorDimension () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("SELECT DimensionEstrategica.idDimension AS idDimension, DimensionEstrategica.dimensionEstrategica AS dimensionEstrategica, DimensionEstrategica.idEstadoDimension AS estado, (SELECT DATE_FORMAT(ControlPresupuestoActividad.fechaPresupuestoAnual, '%Y') FROM ControlPresupuestoActividad WHERE ControlPresupuestoActividad.estadoLlenadoActividades = :estadoLlenado) AS anioActividad, (SELECT COUNT(*) FROM Actividad WHERE Actividad.idDimension = DimensionEstrategica.idDimension AND YEAR(Actividad.fechaCreacionActividad) = (SELECT DATE_FORMAT(ControlPresupuestoActividad.fechaPresupuestoAnual, '%Y') FROM ControlPresupuestoActividad WHERE ControlPresupuestoActividad.estadoLlenadoActividades = :estadoLlenadoActividades) AND Actividad.idPersonaUsuario = :idUsuario) AS cantidadActividadesPorDimension, (SELECT Usuario.idDepartamento FROM Usuario WHERE Usuario.idPersonaUsuario = :idUsuarioLog) AS idDepartamento FROM DimensionEstrategica WHERE DimensionEstrategica.idDimension BETWEEN (SELECT LlenadoActividadDimension.valorLlenadoDimensionInicial FROM LlenadoActividadDimension INNER JOIN TipoUsuario ON (LlenadoActividadDimension.TipoUsuario_idTipoUsuario = TipoUsuario.idTipoUsuario) WHERE LlenadoActividadDimension.TipoUsuario_idTipoUsuario = :tipoUsuario) AND (SELECT LlenadoActividadDimension.valorLlenadoDimensionFinal FROM LlenadoActividadDimension INNER JOIN TipoUsuario ON (LlenadoActividadDimension.TipoUsuario_idTipoUsuario = TipoUsuario.idTipoUsuario) WHERE LlenadoActividadDimension.TipoUsuario_idTipoUsuario = :tipoUsuario2);");
                $stmt->bindValue(':estadoLlenado', ESTADO_ACTIVO);
                $stmt->bindValue(':estadoLlenadoActividades', ESTADO_ACTIVO);
                //$stmt->bindValue(':idEstado', ESTADO_ACTIVO);
                $stmt->bindValue(':idUsuario', $_SESSION['idUsuario']);
                //$stmt->bindValue(':idDepartamento', $_SESSION['idDepartamento']);
                $stmt->bindValue(':idUsuario', $_SESSION['idUsuario']);
                $stmt->bindValue(':idUsuarioLog', $_SESSION['idUsuario']);
                $stmt->bindValue(':tipoUsuario', $_SESSION['idTipoUsuario']);
                $stmt->bindValue(':tipoUsuario2', $_SESSION['idTipoUsuario']);
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
            } catch (PDOException $ex) {
                return array(
                    'status'=> INTERNAL_SERVER_ERROR,
                    'data' => array('message' => $ex->getMessage())
                );
            } finally {
                $this->conexionBD = null;
            }
        }

        public function generaFechaParaCorrelativo() {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("WITH CTE_GENERA_ANIO_PARA_CORRELATIVO AS (SELECT DATE_FORMAT(fechaPresupuestoAnual, '%Y') as anioCorrelativo FROM ControlPresupuestoActividad  WHERE EstadoLlenadoActividades = 1 ) SELECT * FROM CTE_GENERA_ANIO_PARA_CORRELATIVO; ");
                if ($stmt->execute()) {
                    $data = $stmt->fetchObject();
                    return $data->anioCorrelativo;
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

        // AQUIII
        public function generaCorrelativoActividad () {
            if (is_int($this->idDimension)) {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("WITH CTE_GENERA_CORRELATIVO AS (SELECT (COUNT(Actividad.idActividad) + 1) AS numeroActividad, Actividad.fechaCreacionActividad, date_format(Actividad.fechaCreacionActividad,'%Y') as anioActividad, Actividad.idDimension, Actividad.idPersonaUsuario, Usuario.idDepartamento, Departamento.abrev, (SELECT DATE_FORMAT(fechaPresupuestoAnual, '%Y') FROM ControlPresupuestoActividad WHERE EstadoLlenadoActividades = 1) AS anioCorrelativo FROM DimensionEstrategica INNER JOIN Actividad ON (DimensionEstrategica.idDimension = Actividad.idDimension) INNER JOIN Usuario ON (Actividad.idPersonaUsuario = Usuario.idPersonaUsuario) INNER JOIN Departamento ON (Usuario.idDepartamento = Departamento.idDepartamento) GROUP BY Actividad.fechaCreacionActividad, anioActividad, Actividad.idDimension, Actividad.idPersonaUsuario, Usuario.idDepartamento, Departamento.abrev, anioCorrelativo) SELECT * FROM CTE_GENERA_CORRELATIVO WHERE CTE_GENERA_CORRELATIVO.idDimension = :idDimension AND CTE_GENERA_CORRELATIVO.idPersonaUsuario = :idUsuario AND date_format(CTE_GENERA_CORRELATIVO.fechaCreacionActividad,'%Y') = (SELECT DATE_FORMAT(fechaPresupuestoAnual, '%Y') FROM ControlPresupuestoActividad WHERE EstadoLlenadoActividades = 1) AND CTE_GENERA_CORRELATIVO.idDepartamento = :idDepartamento;");
                $stmt->bindValue(':idDimension', $this->idDimension);
                $stmt->bindValue(':idUsuario', $_SESSION['idUsuario']);
                $stmt->bindValue(':idDepartamento', $_SESSION['idDepartamento']);
                if ($stmt->execute()) {
                    $data = $stmt->fetchObject();
                    if (empty($data->numeroActividad)) {
                        $correlativoGenerado = $this->generaFechaParaCorrelativo();
                        if ($correlativoGenerado != null) {
                            $correlativoActividad = $_SESSION['abrev'] . '_' . $correlativoGenerado . '_' . $this->idDimension . '.' . 1;
                            return array(
                                'status' => SUCCESS_REQUEST,
                                'data' => array('correlativoActividad' => $correlativoActividad)
                            );
                        } else {
                            return array(
                                'status' => BAD_REQUEST,
                                'data' => array('message' => 'El correlativo no fue generado por favor comuniquese con el usuario super administrador')
                            );
                        }
                        
                    } else {
                        $correlativoActividad = $_SESSION['abrev'] . '_' . $data->anioCorrelativo . '_' . $this->idDimension . '.' . $data->numeroActividad;
                        return array(
                            'status' => SUCCESS_REQUEST,
                            'data' => array('correlativoActividad' => $correlativoActividad)
                        );
                    }
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error')
                    );
                }
            } else {
                return array(
                    'status'=> BAD_REQUEST,
                    'data' => array('message' => 'Ha ocurrido un error al generar el correlativo, por favor recargue la pagina')
                );
            }
        }

        public function getComparativaDePresupuestos() {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("SELECT SUM(Actividad.CostoTotal) AS costoActividadesDepto, DATE_FORMAT(Actividad.fechaCreacionActividad, '%Y') AS anioSumaActividades, Usuario.idDepartamento, Departamento.nombreDepartamento, PresupuestoDepartamento.idControlPresupuestoActividad, ControlPresupuestoActividad.estadoLlenadoActividades, (SELECT PresupuestoDepartamento.montoPresupuesto FROM PresupuestoDepartamento WHERE PresupuestoDepartamento.idDepartamento = :idDepartamento AND DATE_FORMAT(PresupuestoDepartamento.fechaAprobacionPresupuesto, '%Y') = (SELECT DATE_FORMAT(fechaPresupuestoAnual, '%Y') FROM ControlPresupuestoActividad WHERE EstadoLlenadoActividades = 1)) AS PresupuestoTotalDepartamento FROM Actividad RIGHT JOIN Usuario ON (Actividad.idPersonaUsuario = Usuario.idPersonaUsuario) INNER JOIN Departamento ON (Usuario.idDepartamento = Departamento.idDepartamento)
                LEFT JOIN PresupuestoDepartamento ON (PresupuestoDepartamento.idDepartamento = Departamento.idDepartamento) LEFT JOIN ControlPresupuestoActividad ON (PresupuestoDepartamento.idControlPresupuestoActividad = ControlPresupuestoActividad.idControlPresupuestoActividad) WHERE Departamento.idDepartamento = :idDepto AND ControlPresupuestoActividad.estadoLlenadoActividades = 1 AND (DATE_FORMAT(Actividad.fechaCreacionActividad, '%Y') = (SELECT DATE_FORMAT(fechaPresupuestoAnual, '%Y') FROM ControlPresupuestoActividad WHERE EstadoLlenadoActividades = 1)) GROUP BY anioSumaActividades, Usuario.idDepartamento, Departamento.nombreDepartamento, PresupuestoDepartamento.idControlPresupuestoActividad, ControlPresupuestoActividad.estadoLlenadoActividades, PresupuestoTotalDepartamento;");
                $stmt->bindValue(':idDepartamento', $_SESSION['idDepartamento']);
                $stmt->bindValue(':idDepto', $_SESSION['idDepartamento']);
                if ($stmt->execute()) {
                    $data = $stmt->fetchObject();
                    if ($data->costoActividadesDepto == null) {
                        $this->conexionBD = new Conexion();
                        $this->consulta = $this->conexionBD->connect();
                        $presupuestoDeptoX = $this->consulta->prepare("SELECT PresupuestoDepartamento.montoPresupuesto FROM PresupuestoDepartamento WHERE PresupuestoDepartamento.idDepartamento = :departamento AND DATE_FORMAT(PresupuestoDepartamento.fechaAprobacionPresupuesto, '%Y') = (SELECT DATE_FORMAT(fechaPresupuestoAnual, '%Y') FROM ControlPresupuestoActividad WHERE estadoLlenadoActividades = 1)");
                        $presupuestoDeptoX->bindValue(':departamento', $_SESSION['idDepartamento']);
                        $presupuestoDeptoX->execute();
                        $data2 = $presupuestoDeptoX->fetchObject();
                        $presupuestoTotalPorDepartamento = $data2->montoPresupuesto;
                        $presupuestoDeptoConsumido = 0;
                    } else {
                        $presupuestoDeptoConsumido = $data->costoActividadesDepto;
                        $presupuestoTotalPorDepartamento = $data->PresupuestoTotalDepartamento;
                    }
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => array(
                            'presupuestoTotalDepartamento' => ($presupuestoTotalPorDepartamento - $presupuestoDeptoConsumido),
                            'presupuestoConsumidoPorDepto' => $presupuestoDeptoConsumido
                        )
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error')
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

        
        public function verificaCostoActividadModificar () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("SELECT SUM(Actividad.CostoTotal) AS costoActividadesDepto, DATE_FORMAT(Actividad.fechaCreacionActividad, '%Y') AS anioSumaActividades, Usuario.idDepartamento, 
                Departamento.nombreDepartamento, ControlPresupuestoActividad.EstadoLlenadoActividades, (SELECT PresupuestoDepartamento.montoPresupuesto FROM PresupuestoDepartamento WHERE PresupuestoDepartamento.idDepartamento = :idDepartamento AND DATE_FORMAT(PresupuestoDepartamento.fechaAprobacionPresupuesto, '%Y') = DATE_FORMAT(NOW(), '%Y')) AS PresupuestoTotalDepartamento, (SELECT costoTotal FROM Actividad WHERE idActividad = :idActividad) AS costoDeActividad FROM Actividad RIGHT JOIN Usuario ON (Actividad.idPersonaUsuario = Usuario.idPersonaUsuario) INNER JOIN Departamento ON (Usuario.idDepartamento = Departamento.idDepartamento) 
                LEFT JOIN PresupuestoDepartamento ON (PresupuestoDepartamento.idDepartamento = Departamento.idDepartamento) LEFT JOIN ControlPresupuestoActividad ON (PresupuestoDepartamento.idControlPresupuestoActividad = ControlPresupuestoActividad.idControlPresupuestoActividad) WHERE Departamento.idDepartamento = :idDepto AND (DATE_FORMAT(Actividad.fechaCreacionActividad, '%Y') = (SELECT DATE_FORMAT(fechaPresupuestoAnual, '%Y') FROM ControlPresupuestoActividad WHERE EstadoLlenadoActividades = 1)) AND ControlPresupuestoActividad.EstadoLlenadoActividades = 1 GROUP BY anioSumaActividades, Usuario.idDepartamento, Departamento.nombreDepartamento, ControlPresupuestoActividad.EstadoLlenadoActividades, costoDeActividad");
                $stmt->bindValue(':idDepartamento', $_SESSION['idDepartamento']);
                $stmt->bindValue(':idActividad', $this->idActividad);
                $stmt->bindValue(':idDepto', $_SESSION['idDepartamento']);
                if ($stmt->execute()) {
                    $data = $stmt->fetchObject();
                    if ((($data->costoActividadesDepto - $data->costoDeActividad) + $this->costoTotal) <= $data->PresupuestoTotalDepartamento) {
                        return true;
                    } else {
                        return false;
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

        public function verificaCostosActividadInsertar () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("SELECT 
                SUM(Actividad.CostoTotal) AS costoActividadesDepto, DATE_FORMAT(Actividad.fechaCreacionActividad, '%Y') AS anioSumaActividades, Usuario.idDepartamento, Departamento.nombreDepartamento, PresupuestoDepartamento.idControlPresupuestoActividad, ControlPresupuestoActividad.estadoLlenadoActividades, (SELECT PresupuestoDepartamento.montoPresupuesto FROM PresupuestoDepartamento WHERE PresupuestoDepartamento.idDepartamento = :idDepartamento AND DATE_FORMAT(PresupuestoDepartamento.fechaAprobacionPresupuesto, '%Y') = (SELECT DATE_FORMAT(fechaPresupuestoAnual, '%Y') FROM ControlPresupuestoActividad WHERE EstadoLlenadoActividades = 1)) AS PresupuestoTotalDepartamento FROM Actividad RIGHT JOIN Usuario ON (Actividad.idPersonaUsuario = Usuario.idPersonaUsuario) INNER JOIN Departamento ON (Usuario.idDepartamento = Departamento.idDepartamento)
                LEFT JOIN PresupuestoDepartamento ON (PresupuestoDepartamento.idDepartamento = Departamento.idDepartamento) LEFT JOIN ControlPresupuestoActividad ON (PresupuestoDepartamento.idControlPresupuestoActividad = ControlPresupuestoActividad.idControlPresupuestoActividad) WHERE Departamento.idDepartamento = :idDepto AND ControlPresupuestoActividad.estadoLlenadoActividades = 1 AND (DATE_FORMAT(Actividad.fechaCreacionActividad, '%Y') = (SELECT DATE_FORMAT(fechaPresupuestoAnual, '%Y') FROM ControlPresupuestoActividad WHERE EstadoLlenadoActividades = 1)) GROUP BY anioSumaActividades, Usuario.idDepartamento, Departamento.nombreDepartamento, PresupuestoDepartamento.idControlPresupuestoActividad, ControlPresupuestoActividad.estadoLlenadoActividades, PresupuestoTotalDepartamento;");
                $stmt->bindValue(':idDepartamento', $_SESSION['idDepartamento']);
                $stmt->bindValue(':idDepto', $_SESSION['idDepartamento']);
                if ($stmt->execute()) {
                    $data = $stmt->fetchObject();
                    if ($data->costoActividadesDepto == null) {
                        $this->conexionBD = new Conexion();
                        $this->consulta = $this->conexionBD->connect();
                        $queryX = $this->consulta->prepare("SELECT PresupuestoDepartamento.montoPresupuesto FROM PresupuestoDepartamento WHERE PresupuestoDepartamento.idDepartamento = :deptoId AND DATE_FORMAT(PresupuestoDepartamento.fechaAprobacionPresupuesto, '%Y') = (SELECT DATE_FORMAT(fechaPresupuestoAnual, '%Y') FROM ControlPresupuestoActividad WHERE EstadoLlenadoActividades = 1)");
                        $queryX->bindValue(':deptoId', $_SESSION['idDepartamento']);
                        $queryX->execute();
                        $dataPresupuesto = $queryX->fetchObject();
                        $presuDeptoSeleccionado = $dataPresupuesto->montoPresupuesto;
                        $costoActividadesActual = 0;
                    } else {
                        $presuDeptoSeleccionado = $data->PresupuestoTotalDepartamento;
                        $costoActividadesActual = $data->costoActividadesDepto;
                    }
                    if (($this->costoTotal + $costoActividadesActual) <= $presuDeptoSeleccionado) {
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

        public function verificaDatosPresupuestoActividad() {
            if (
                is_numeric($this->costoTotal) && 
                is_numeric($this->porcentajeTrimestre1) && 
                is_numeric($this->porcentajeTrimestre2) && 
                is_numeric($this->porcentajeTrimestre3) &&
                is_numeric($this->porcentajeTrimestre4)
                ) {
                    $estadoPresupuesto = $this->verificaEstadoPresupuesto();
                    // Si el estado del presupuesto esta inactivo y el costo el mayor que 0 notifique
                    try {
                        $this->conexionBD = new Conexion();
                        $this->consulta = $this->conexionBD->connect();
                        $stmt = $this->consulta->prepare("SELECT 
                        SUM(Actividad.CostoTotal) AS costoActividadesDepto, DATE_FORMAT(Actividad.fechaCreacionActividad, '%Y') AS anioSumaActividades, Usuario.idDepartamento, Departamento.nombreDepartamento, PresupuestoDepartamento.idControlPresupuestoActividad, ControlPresupuestoActividad.estadoLlenadoActividades, (SELECT PresupuestoDepartamento.montoPresupuesto FROM PresupuestoDepartamento WHERE PresupuestoDepartamento.idDepartamento = :idDepartamento AND DATE_FORMAT(PresupuestoDepartamento.fechaAprobacionPresupuesto, '%Y') = (SELECT DATE_FORMAT(fechaPresupuestoAnual, '%Y') FROM ControlPresupuestoActividad WHERE EstadoLlenadoActividades = 1)) AS PresupuestoTotalDepartamento FROM Actividad RIGHT JOIN Usuario ON (Actividad.idPersonaUsuario = Usuario.idPersonaUsuario) INNER JOIN Departamento ON (Usuario.idDepartamento = Departamento.idDepartamento) LEFT JOIN PresupuestoDepartamento ON (PresupuestoDepartamento.idDepartamento = Departamento.idDepartamento) LEFT JOIN ControlPresupuestoActividad ON (PresupuestoDepartamento.idControlPresupuestoActividad = ControlPresupuestoActividad.idControlPresupuestoActividad) WHERE Departamento.idDepartamento = :idDepto AND ControlPresupuestoActividad.estadoLlenadoActividades = 1 AND (DATE_FORMAT(Actividad.fechaCreacionActividad, '%Y') = (SELECT DATE_FORMAT(fechaPresupuestoAnual, '%Y') FROM ControlPresupuestoActividad WHERE EstadoLlenadoActividades = 1)) GROUP BY anioSumaActividades, Usuario.idDepartamento, Departamento.nombreDepartamento, PresupuestoDepartamento.idControlPresupuestoActividad, ControlPresupuestoActividad.estadoLlenadoActividades, PresupuestoTotalDepartamento;");
                        $stmt->bindValue(':idDepartamento', $_SESSION['idDepartamento']);
                        $stmt->bindValue(':idDepto', $_SESSION['idDepartamento']);
                        if ($stmt->execute()) {
                            $data = $stmt->fetchObject();

                            if (($estadoPresupuesto == false) && ($this->costoTotal > 0)) {
                                return array(
                                    'status'=> BAD_REQUEST,
                                    'data' => array('message' => 'Ha ocurrido un error, el periodo de llenado de las actividades con presupuesto ya cerro, solo puedes guardar actividades que no tengan costo o que no dependan del presupuesto, por favor modifique el campo Costo Actividad a 0 en el paso Metas Trimestrales')
                                );
                            }

                            if ($data->costoActividadesDepto == null) {
                                $this->conexionBD = new Conexion();
                                $this->consulta = $this->conexionBD->connect(); 
                                $queryx = $this->consulta->prepare("SELECT PresupuestoDepartamento.montoPresupuesto FROM PresupuestoDepartamento WHERE PresupuestoDepartamento.idDepartamento = :deptoId AND DATE_FORMAT(PresupuestoDepartamento.fechaAprobacionPresupuesto, '%Y') = (SELECT DATE_FORMAT(fechaPresupuestoAnual, '%Y') FROM ControlPresupuestoActividad WHERE EstadoLlenadoActividades = 1)");
                                $queryx->bindValue(':deptoId', $_SESSION['idDepartamento']);
                                $queryx->execute();
                                $data2 = $queryx->fetchObject();
                                $presuDepto = $data2->montoPresupuesto;
                                $costoActividadesActual = 0;
                            } else {
                                $presuDepto = $data->PresupuestoTotalDepartamento;
                                $costoActividadesActual = $data->costoActividadesDepto;
                            }
    
                            if (($this->costoTotal + $costoActividadesActual) > $presuDepto) {
                                return array(
                                    'status'=> BAD_REQUEST,
                                    'data' => array('message' => 'Ha ocurrido un error, el costo de la actividad supera el costo disponible del departamento, por favor cambie el monto de la actividad a uncosto dentro del rango, REGRESE NUEVAMENTE AL PASO ANTERIOR Metas Trimestrales ' . $presuDepto . ' ' . $costoActividadesActual)
                                );
                            } else {
                                $pt1 = $this->porcentajeTrimestre1/100; 
                                $pt2 = $this->porcentajeTrimestre2/100; 
                                $pt3 = $this->porcentajeTrimestre3/100; 
                                $pt4 = $this->porcentajeTrimestre4/100; 
                                if (($pt1 + $pt2 + $pt3 + $pt4) == 1) {
                                    $sumatoriaValorActividad = $pt1*$this->costoTotal + $pt2*$this->costoTotal +$pt3*$this->costoTotal + $pt4*$this->costoTotal;
                                    if ($sumatoriaValorActividad == $this->costoTotal) {
                                        return array(
                                            'status' => SUCCESS_REQUEST,
                                            'data' => true
                                        );
                                    } else {
                                        return array(
                                            'status'=> BAD_REQUEST,
                                            'data' => array('message' => 'Ha ocurrido un error, la distribucion del dineroen los trimestres excede al costo de la actividad, por favor verifiquenuevamente los porcentajes, REGRESE NUEVAMENTE AL PASO ANTERIOR MetasTrimestrales')
                                        );
                                    }
                                } else {
                                    return array(
                                        'status'=> BAD_REQUEST,
                                        'data' => array('message' => 'Ha ocurrido un error, la sumatoria de losporcentajes no da 1, por favor verifique nuevamente los porcentajes, REGRESENUEVAMENTE AL PASO ANTERIOR Metas Trimestrales')
                                    );
                                }
                            }
                        } else {
                            return array(
                                'status'=> BAD_REQUEST,
                                'data' => array('message' => 'Ha ocurrido un error')
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
                    'data' => array('message' => 'Ha ocurrido un error, los datos incluidos en el Paso de Metas Trimestrales no son correctos')
                );
            }
        }

        public function generaAnioPresupuestoAbierto () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("(SELECT fechaPresupuestoAnual FROM ControlPresupuestoActividad WHERE EstadoLlenadoActividades = 1)");
                if ($stmt->execute()) {
                    $data = $stmt->fetchObject();
                    return $data->fechaPresupuestoAnual;
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

        public function insertaActividad() {
            if (
                is_int($this->idDimension) &&
                is_int($this->idObjetivoInstitucional) &&
                is_int($this->idResultadoInstitucional) &&
                is_int($this->idAreaEstrategica) &&
                is_int($this->idTipoActividad) &&
                campoParaTodo($this->actividad, 1, 255) && 
                campoParaTodo($this->resultadosUnidad, 1, 300) && 
                campoParaTodo($this->indicadoresResultado, 1, 300) &&
                campoParaTodo($this->medioVerificacionActividad, 1, 255) &&
                campoParaTodo($this->poblacionOjetivoActividad, 1, 255) &&
                campoParaTodo($this->responsableActividad, 1, 255) &&
                is_numeric($this->costoTotal) &&
                is_numeric($this->porcentajeTrimestre1) &&
                is_numeric($this->porcentajeTrimestre2) &&
                is_numeric($this->porcentajeTrimestre3) &&
                is_numeric($this->porcentajeTrimestre4)
            ) {
                $estadoPresupuesto = $this->verificaEstadoPresupuesto();
                $verificaRangoPresupuesto = $this->verificaCostosActividadInsertar();
                if (($estadoPresupuesto == false) && ($this->costoTotal > 0)) {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error, el periodo de llenado de las actividades con presupuesto ya cerro, solo puedes guardar actividades que no tengan costo o que no dependan del presupuesto, por favor modifique el campo Costo Actividad a 0 en el paso Metas Trimestrales')
                    );
                } else {
                    if ($verificaRangoPresupuesto == true) {
                        $this->fechaCreacionActividad = $this->generaAnioPresupuestoAbierto();
                        try {
                            $this->conexionBD = new Conexion();
                            $this->consulta = $this->conexionBD->connect();
                            $this->consulta->prepare("
                                set @persona = {$_SESSION['idUsuario']};
                            ")->execute();
                            $stmt = $this->consulta->prepare("INSERT INTO Actividad (idPersonaUsuario, idDimension, idObjetivoInstitucional, idResultadoInstitucional, idAreaEstrategica, idTipoActividad, idEstadoActividad, resultadosUnidad, indicadoresResultado, actividad, correlativoActividad, justificacionActividad, medioVerificacionActividad, poblacionObjetivoActividad, responsableActividad, fechaCreacionActividad, CostoTotal) VALUES (:idUsuario, :idDimension, :idObjetivo, :idResultado, :idArea, :idTipoCosto, :idEstado , :resultado, :indicadores, :nombreActividad, :correlativo, :justificacion, :medio, :poblacion, :responsable, :fecha ,:costo)");
                            $stmt->bindValue(':idUsuario', $_SESSION['idUsuario']);
                            $stmt->bindValue(':idDimension', $this->idDimension);
                            $stmt->bindValue(':idObjetivo', $this->idObjetivoInstitucional);
                            $stmt->bindValue(':idResultado', $this->idResultadoInstitucional);
                            $stmt->bindValue(':idArea', $this->idAreaEstrategica);
                            $stmt->bindValue(':idTipoCosto', $this->idTipoActividad);
                            $stmt->bindValue(':idEstado', $this->idEstadoActividad);
                            $stmt->bindValue(':resultado', $this->resultadosUnidad);
                            $stmt->bindValue(':indicadores', $this->indicadoresResultado);
                            $stmt->bindValue(':nombreActividad', $this->actividad);
                            $stmt->bindValue(':correlativo', $this->correlativoActividad);
                            $stmt->bindValue(':justificacion', $this->justificacionActividad);
                            $stmt->bindValue(':medio', $this->medioVerificacionActividad);
                            $stmt->bindValue(':poblacion', $this->poblacionOjetivoActividad);
                            $stmt->bindValue(':responsable', $this->responsableActividad);
                            $stmt->bindValue(':fecha', $this->fechaCreacionActividad);
                            $stmt->bindValue(':costo', $this->costoTotal);
                            if ($stmt->execute()) {
                                $insertaCostos = parent::insertaCostoActividadPorTrimestreDimension($this->consulta->lastInsertId(), $this->costoTotal);
                                if($insertaCostos==true) {
                                    return array(
                                        'status'=> SUCCESS_REQUEST,
                                        'data' => array('message' => 'La actividad fue insertada exitosamente, por favor desglose los items de la actividad en el siguiente modal de Dimensiones Administrativas',
                                        "idDimension" => $this->idDimension,
                                        "idActividad" => $this->consulta->lastInsertId(),
                                        "costoActividad" => $this->costoTotal)
                                    );
                                } else {
                                    return array(
                                        'status'=> BAD_REQUEST,
                                        'data' => array('message' => 'Ha ocurrido un error, los costos no se insertaron')
                                    );
                                }
                            } else {
                                return array(
                                    'status'=> BAD_REQUEST,
                                    'data' => array('message' => 'Ha ocurrido un error')
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
                            'data' => array('message' => 'Ha ocurrido un error, el costo de la actividad supera elcosto disponible del departamento, por favor cambie el monto de la actividad a uncosto dentro del rango, REGRESE NUEVAMENTE AL PASO ANTERIOR Metas Trimestrales')
                        );
                    }
                }
            } else {
                return array(
                    'status'=> BAD_REQUEST,
                    'data' => array('message' => 'Ha ocurrido un error, la informacion de la actividad no se pudo registrar, verifique los campos nuevamente', is_int($this->idDimension) ,
                    is_int($this->idObjetivoInstitucional) ,
                    is_int($this->idResultadoInstitucional) ,
                    is_int($this->idAreaEstrategica) ,
                    is_int($this->idTipoActividad))
                );
            }
        }

        public function generaActividad () {
            if (is_int($this->idActividad)) {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare("WITH CTE_GENERA_ACTIVIDAD AS (SELECT Actividad.idActividad, Actividad.idDimension, 
                    DimensionEstrategica.dimensionEstrategica, Actividad.idObjetivoInstitucional, ObjetivoInstitucional.objetivoInstitucional, Actividad.idResultadoInstitucional, ResultadoInstitucional.resultadoInstitucional, Actividad.idAreaEstrategica, AreaEstrategica.areaEstrategica, Actividad.resultadosUnidad, Actividad.indicadoresResultado, Actividad.actividad, Actividad.correlativoActividad, Actividad.justificacionActividad, Actividad.medioVerificacionActividad, Actividad.poblacionObjetivoActividad, Actividad.responsableActividad, Actividad.fechaCreacionActividad, Actividad.CostoTotal, CostoActividadPorTrimestre.porcentajeTrimestre1,CostoActividadPorTrimestre.porcentajeTrimestre2, CostoActividadPorTrimestre.porcentajeTrimestre3, 
                    CostoActividadPorTrimestre.porcentajeTrimestre4, CostoActividadPorTrimestre.idCostActPorTri FROM Actividad INNER JOIN DimensionEstrategica 
                    ON (Actividad.idDimension = DimensionEstrategica.idDimension) INNER JOIN ObjetivoInstitucional ON (Actividad.idObjetivoInstitucional = ObjetivoInstitucional.idObjetivoInstitucional) INNER JOIN AreaEstrategica ON (Actividad.idAreaEstrategica = AreaEstrategica.idAreaEstrategica) INNER JOIN ResultadoInstitucional ON (Actividad.idResultadoInstitucional = ResultadoInstitucional.idResultadoInstitucional) INNER JOIN TipoActividad ON (Actividad.idTipoActividad = TipoActividad.idTipoActividad) INNER JOIN CostoActividadPorTrimestre ON (CostoActividadPorTrimestre.idActividad = Actividad.idActividad)) SELECT * FROM CTE_GENERA_ACTIVIDAD WHERE CTE_GENERA_ACTIVIDAD.idActividad = :idActividad");
                $stmt->bindValue(':idActividad', $this->idActividad);
                    if ($stmt->execute()) {
                        return array(
                            'status'=> SUCCESS_REQUEST,
                            'data' => $stmt->fetchObject()
                        );
                    } else {
                        return array(
                            'status'=> BAD_REQUEST,
                            'data' => array('message' => 'Ha ocurrido un error')
                        );
                    }
            } else {
                return array(
                    'status'=> BAD_REQUEST,
                    'data' => array('message' => 'Ha ocurrido un error, la informacion de la actividade no se pudo visualizar')
                );
            }
        }

        public function getActividadesDimension () {
            if (
                is_int($this->idDimension)
            ) {
                try {
                    $this->conexionBD = new Conexion();
                    $this->consulta = $this->conexionBD->connect();
                    $stmt = $this->consulta->prepare("WITH CTE_LISTA_ACTIVIDADES_DIMENSION AS (SELECT Actividad.idActividad, Actividad.idPersonaUsuario, Usuario.nombreUsuario, Usuario.idDepartamento, Departamento.nombreDepartamento,PresupuestoDepartamento.montoPresupuesto, ControlPresupuestoActividad.estadoLlenadoActividades, Actividad.idDimension, DimensionEstrategica.dimensionEstrategica, Actividad.idObjetivoInstitucional, ObjetivoInstitucional.objetivoInstitucional, Actividad.idResultadoInstitucional, ResultadoInstitucional.resultadoInstitucional, Actividad.idAreaEstrategica, AreaEstrategica.areaEstrategica, Actividad.idTipoActividad, TipoActividad.tipoActividad, Actividad.resultadosUnidad, Actividad.indicadoresResultado, Actividad.actividad, Actividad.correlativoActividad, Actividad.justificacionActividad, Actividad.medioVerificacionActividad, Actividad.poblacionObjetivoActividad, Actividad.responsableActividad, Actividad.fechaCreacionActividad, Actividad.CostoTotal, CostoActividadPorTrimestre.idCostActPorTri, CostoActividadPorTrimestre.porcentajeTrimestre1, CostoActividadPorTrimestre.Trimestre1, CostoActividadPorTrimestre.abrevTrimestre1, CostoActividadPorTrimestre.porcentajeTrimestre2, CostoActividadPorTrimestre.Trimestre2, CostoActividadPorTrimestre.abrevTrimestre2, CostoActividadPorTrimestre.porcentajeTrimestre3, CostoActividadPorTrimestre.Trimestre3, CostoActividadPorTrimestre.abrevTrimestre3, CostoActividadPorTrimestre.porcentajeTrimestre4, CostoActividadPorTrimestre.Trimestre4, CostoActividadPorTrimestre.abrevTrimestre4, CostoActividadPorTrimestre.sumatoriaPorcentaje FROM Actividad INNER JOIN DimensionEstrategica ON (Actividad.idDimension = DimensionEstrategica.idDimension) INNER JOIN ObjetivoInstitucional ON (Actividad.idObjetivoInstitucional = ObjetivoInstitucional.idObjetivoInstitucional) INNER JOIN AreaEstrategica ON (Actividad.idAreaEstrategica = AreaEstrategica.idAreaEstrategica) INNER JOIN ResultadoInstitucional ON (Actividad.idResultadoInstitucional = ResultadoInstitucional.idResultadoInstitucional) INNER JOIN TipoActividad ON (Actividad.idTipoActividad = TipoActividad.idTipoActividad) INNER JOIN CostoActividadPorTrimestre ON (CostoActividadPorTrimestre.idActividad = Actividad.idActividad) INNER JOIN Usuario ON (Actividad.idPersonaUsuario = Usuario.idPersonaUsuario) INNER JOIN Departamento ON (Usuario.idDepartamento = Departamento.idDepartamento) INNER JOIN PresupuestoDepartamento ON (Departamento.idDepartamento = PresupuestoDepartamento.idDepartamento) INNER JOIN ControlPresupuestoActividad ON (PresupuestoDepartamento.idControlPresupuestoActividad = ControlPresupuestoActividad.idControlPresupuestoActividad)) SELECT * FROM CTE_LISTA_ACTIVIDADES_DIMENSION WHERE date_format(CTE_LISTA_ACTIVIDADES_DIMENSION.fechaCreacionActividad, '%Y') = (SELECT DATE_FORMAT(fechaPresupuestoAnual, '%Y') FROM ControlPresupuestoActividad WHERE EstadoLlenadoActividades = 1) AND CTE_LISTA_ACTIVIDADES_DIMENSION.idDimension = :idDimension AND CTE_LISTA_ACTIVIDADES_DIMENSION.idPersonaUsuario = :idUsuario AND CTE_LISTA_ACTIVIDADES_DIMENSION.idDepartamento = :idDepto AND CTE_LISTA_ACTIVIDADES_DIMENSION.estadoLlenadoActividades = 1 ORDER BY CTE_LISTA_ACTIVIDADES_DIMENSION.idActividad DESC;");
                    $stmt->bindValue(':idDimension', $this->idDimension);
                    $stmt->bindValue(':idUsuario', $_SESSION['idUsuario']);
                    $stmt->bindValue(':idDepto', $_SESSION['idDepartamento']);
                    if ($stmt->execute()) {
                        return array(
                            'status'=> SUCCESS_REQUEST,
                            'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                        );
                    } else {
                        return array(
                            'status'=> BAD_REQUEST,
                            'data' => array('message' => 'Ha ocurrido un error')
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
                    'data' => array('message' => 'Ha ocurrido un error, la informacion de las actividades no se pudo visualizar')
                );
            }
        }

        public function verificaPresupuestoActividadModificar () {
            if (is_int($this->idActividad) && is_numeric($this->costoTotal)) {
                $verificandoPresupuestoVSCosto = $this->verificaCostoActividadModificar();
                if ($verificandoPresupuestoVSCosto == true) {
                    return array(
                        'status'=> SUCCESS_REQUEST,
                        'data' => array('message' => 'Puedes seguir Modificando')
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'El nuevo costo de la actividad a modificar excede el presupuesto por el departamento disponible, por favor dirigase al paso de Metas Trimestrales y adecue el presupuesto')
                    );
                }
            } else {
                return array(
                    'status'=> BAD_REQUEST,
                    'data' => array('message' => 'Ha ocurrido un error, El presupuesto a modificar de la actividad no se pudo verificar')
                );
            }
        }

        public function modificaRegistroActividad() {
            if (
                is_int($this->idActividad) &&
                is_int($this->idDimension) &&
                is_int($this->idObjetivoInstitucional) &&
                is_int($this->idResultadoInstitucional) &&
                is_int($this->idAreaEstrategica) &&
                is_int($this->idTipoActividad) &&
                campoParaTodo($this->actividad, 1, 200) && 
                campoParaTodo($this->resultadosUnidad, 1, 300) && 
                campoParaTodo($this->indicadoresResultado, 1, 300) &&
                campoParaTodo($this->medioVerificacionActividad, 1, 255) &&
                campoParaTodo($this->poblacionOjetivoActividad, 1, 255) &&
                campoParaTodo($this->responsableActividad, 1, 255) &&
                is_numeric($this->costoTotal) &&
                is_numeric($this->porcentajeTrimestre1) &&
                is_numeric($this->porcentajeTrimestre2) &&
                is_numeric($this->porcentajeTrimestre3) &&
                is_numeric($this->porcentajeTrimestre4)
            ) {
                $verificandoPresupuestoVSCosto = $this->verificaCostoActividadModificar();
                if ($verificandoPresupuestoVSCosto == true) {
                    $pt1 = $this->porcentajeTrimestre1; 
                    $pt2 = $this->porcentajeTrimestre2; 
                    $pt3 = $this->porcentajeTrimestre3; 
                    $pt4 = $this->porcentajeTrimestre4; 
                    if (($pt1 + $pt2 + $pt3 + $pt4) == 1) {
                        $sumatoriaValorActividad = $pt1*$this->costoTotal + $pt2*$this->costoTotal + $pt3*$this->costoTotal + $pt4*$this->costoTotal;
                        if ($sumatoriaValorActividad == $this->costoTotal) {
                            try {
                                $this->conexionBD = new Conexion();
                                $this->consulta = $this->conexionBD->connect();
                                $this->consulta->prepare("
                                    set @persona = {$_SESSION['idUsuario']};
                                ")->execute();
                                $stmt = $this->consulta->prepare("UPDATE Actividad SET idPersonaUsuario = :idUsuario, idDimension = :idDimension, idObjetivoInstitucional = :idObjetivo, idResultadoInstitucional = :idResultado, idAreaEstrategica = :idArea, idTipoActividad = :idTipoCosto , resultadosUnidad = :resultado, indicadoresResultado = :indicadores, actividad = :nombreActividad,  justificacionActividad = :justificacion, medioVerificacionActividad = :medio , poblacionObjetivoActividad = :poblacion, responsableActividad = :responsable, CostoTotal = :costo WHERE idActividad = :idActividad ");
                                $stmt->bindValue(':idUsuario', $_SESSION['idUsuario']);
                                $stmt->bindValue(':idDimension', $this->idDimension);
                                $stmt->bindValue(':idObjetivo', $this->idObjetivoInstitucional);
                                $stmt->bindValue(':idResultado', $this->idResultadoInstitucional);
                                $stmt->bindValue(':idArea', $this->idAreaEstrategica);
                                $stmt->bindValue(':idTipoCosto', $this->idTipoActividad);
                                $stmt->bindValue(':resultado', $this->resultadosUnidad);
                                $stmt->bindValue(':indicadores', $this->indicadoresResultado);
                                $stmt->bindValue(':nombreActividad', $this->actividad);
                                $stmt->bindValue(':justificacion', $this->justificacionActividad);
                                $stmt->bindValue(':medio', $this->medioVerificacionActividad);
                                $stmt->bindValue(':poblacion', $this->poblacionOjetivoActividad);
                                $stmt->bindValue(':responsable', $this->responsableActividad);
                                $stmt->bindValue(':costo', $this->costoTotal);
                                $stmt->bindValue(':idActividad', $this->idActividad);
                                if ($stmt->execute()) {
                                    $insertaCostos = parent::modificaCostoActividadPorTrimestre($this->idActividad, $this->costoTotal, $this->idCostActPorTri);
                                    if($insertaCostos==true) {
                                        return array(
                                            'status'=> SUCCESS_REQUEST,
                                            'data' => array('message' => 'La actividad fue modifcada exitosamente')
                                        );
                                    } else {
                                        return array(
                                            'status'=> BAD_REQUEST,
                                            'data' => array('message' => 'Ha ocurrido un error, los costos no se insertaron')
                                        );
                                    }
                                } else {
                                    return array(
                                        'status'=> BAD_REQUEST,
                                        'data' => array('message' => 'Ha ocurrido un error')
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
                                'data' => array('message' => 'Ha ocurrido un error, la distribucion del dinero en los trimestres excede al costo de la actividad, por favor verifiquenuevamente los porcentajes, regrese nuevamente  al paso de Metas Trimestrales')
                            );
                        }
                    } else {
                        return array(
                            'status'=> BAD_REQUEST,
                            'data' => array('message' => 'Ha ocurrido un error, la sumatoria de losporcentajes da 1, por favor verifique nuevamente los porcentajes, regrese al paso Metas Trimestrales y modifique los porcentajes')
                        );
                    }
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'El nuevo costo de la actividad a modificar excede el presupuesto por el departamento disponible, por favor dirigase al paso de Metas Trimestrales y adecue el presupuesto')
                    );
                }
            } else {
                return array(
                    'status'=> BAD_REQUEST,
                    'data' => array('message' => 'Ha ocurrido un error, alguno de los campos digitados son erroneos')
                );
            }
        }

        public function getEstadoPresupuestoAnual() {
            $estadoPresupuesto = $this->verificaEstadoPresupuesto();
            if ($estadoPresupuesto == true) {
                return array(
                    'status'=> SUCCESS_REQUEST,
                    'data' => array('message' => 'Puedes seguir')
                );
            } else {
                return array(
                    'status'=> BAD_REQUEST,
                    'data' => array('message' => 'El presupuesto de este año ya fue cerrado, se encuentra inactivo por ende no puedes modificar actividades')
                );
            }
        }

        public function cambiaEstadoActividad () {
            
        }
    }
?>