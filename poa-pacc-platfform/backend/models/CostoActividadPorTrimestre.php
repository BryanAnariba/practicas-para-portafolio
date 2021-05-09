<?php
    if (!isset($_SESSION)) {
        session_start();
    }
    require_once('../../config/config.php');
    require_once('../../database/Conexion.php');
    require_once('../../validators/validators.php');
    class CostoActividadPorTrimestre {
        protected $idCostActPorTri;
        protected $porcentajeTrimestre1;
        protected $trimestre1;
        protected $abrevTrimestre1;
        protected $porcentajeTrimestre2;
        protected $trimestre2;
        protected $abrevTrimestre2;
        protected $porcentajeTrimestre3;
        protected $trimestre3;
        protected $abrevTrimestre3;
        protected $porcentajeTrimestre4;
        protected $trimestre4;
        protected $abrevTrimestre4;
        protected $sumatoriaPorcentaje;        

        private $conexionBD;
        private $consulta;
        private $tablaBaseDatos;

        public function __construct() {
            $this->tablaBaseDatos = TBL_COSTO_ACTIVIDAD_POR_TRIMESTRE;
        }

        public function getIdCostActPorTri() {
            return $this->idCostActPorTri;
        }

        public function setIdCostActPorTri($idCostActPorTri) {
            $this->idCostActPorTri = $idCostActPorTri;
            return $this;
        }

        public function getIdActividad() {
            return $this->idActividad;
        }

        public function setIdActividad($idActividad) {
            $this->idActividad = $idActividad;
            return $this;
        }

        public function getPorcentajeTrimestre1() {
            return $this->porcentajeTrimestre1;
        }

        public function setPorcentajeTrimestre1($porcentajeTrimestre1) {
            $this->porcentajeTrimestre1 = $porcentajeTrimestre1;
            return $this;
        }

        public function getTrimestre1(){
            return $this->trimestre1;
        }

        public function setTrimestre1($trimestre1) {
            $this->trimestre1 = $trimestre1;
            return $this;
        }

        public function getAbrevTrimestre1() {
            return $this->abrevTrimestre1;
        }

        public function setAbrevTrimestre1($abrevTrimestre1) {
            $this->abrevTrimestre1 = $abrevTrimestre1;
            return $this;
        }

        public function getPorcentajeTrimestre2() {
            return $this->porcentajeTrimestre2;
        }

        public function setPorcentajeTrimestre2($porcentajeTrimestre2) {
            $this->porcentajeTrimestre2 = $porcentajeTrimestre2;
            return $this;
        }

        public function getTrimestre2() {
            return $this->trimestre2;
        }

        public function setTrimestre2($trimestre2) {
            $this->trimestre2 = $trimestre2;
            return $this;
        }

        public function getAbrevTrimestre2() {
            return $this->abrevTrimestre2;
        }

        public function setAbrevTrimestre2($abrevTrimestre2) {
            $this->abrevTrimestre2 = $abrevTrimestre2;
            return $this;
        }

        public function getPorcentajeTrimestre3() {
            return $this->porcentajeTrimestre3;
        }

        public function setPorcentajeTrimestre3($porcentajeTrimestre3) {
            $this->porcentajeTrimestre3 = $porcentajeTrimestre3;
            return $this;
        }

        public function getTrimestre3() {
            return $this->trimestre3;
        }

        public function setTrimestre3($trimestre3) {
            $this->trimestre3 = $trimestre3;
            return $this;
        }

        public function getAbrevTrimestre3() {
            return $this->abrevTrimestre3;
        }

        public function setAbrevTrimestre3($abrevTrimestre3) {
            $this->abrevTrimestre3 = $abrevTrimestre3;
            return $this;
        }

        public function getPorcentajeTrimestre4() {
            return $this->porcentajeTrimestre4;
        }

        public function setPorcentajeTrimestre4($porcentajeTrimestre4) {
            $this->porcentajeTrimestre4 = $porcentajeTrimestre4;
            return $this;
        }

        public function getTrimestre4() {
            return $this->trimestre4;
        }

        public function setTrimestre4($trimestre4) {
            $this->trimestre4 = $trimestre4;
            return $this;
        }

        public function getAbrevTrimestre4() {
            return $this->abrevTrimestre4;
        }

        public function setAbrevTrimestre4($abrevTrimestre4) {
            $this->abrevTrimestre4 = $abrevTrimestre4;
            return $this;
        }

        public function getSumatoriaPorcentaje() {
            return $this->sumatoriaPorcentaje;
        }

        public function setSumatoriaPorcentaje($sumatoriaPorcentaje) {
            $this->sumatoriaPorcentaje = $sumatoriaPorcentaje;
            return $this;
        }
        
        public function insertaCostoActividadPorTrimestreDimension ($idActividad, $costoActividad) {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $this->consulta->prepare("
                    set @persona = {$_SESSION['idUsuario']};
                ")->execute();
                $pt1 = ($this->porcentajeTrimestre1)*$costoActividad;
                $pt2 = ($this->porcentajeTrimestre2)*$costoActividad;
                $pt3 = ($this->porcentajeTrimestre3)*$costoActividad;
                $pt4 = ($this->porcentajeTrimestre4)*$costoActividad;
                $this->sumatoriaPorcentaje = $this->porcentajeTrimestre1 + $this->porcentajeTrimestre2 + $this->porcentajeTrimestre3 + $this->porcentajeTrimestre4;
                $stmt = $this->consulta->prepare("CALL SP_INSERTA_COSTO_ACT_TRIMESTRE(:idActividad, :pT1, :t1, :pT2, :t2, :pT3, :t3, :pT4, :t4, :sumatoria)");
                $stmt->bindValue(':idActividad', $idActividad);
                $stmt->bindValue(':pT1', $this->porcentajeTrimestre1);
                $stmt->bindValue(':t1', $pt1);
                $stmt->bindValue(':pT2', $this->porcentajeTrimestre2);
                $stmt->bindValue(':t2', $pt2);
                $stmt->bindValue(':pT3', $this->porcentajeTrimestre3);
                $stmt->bindValue(':t3', $pt3);
                $stmt->bindValue(':pT4', $this->porcentajeTrimestre4);
                $stmt->bindValue(':t4', $pt4);
                $stmt->bindValue(':sumatoria', $this->sumatoriaPorcentaje);
                if ($stmt->execute()) {
                    return true;
                } else {
                    return false;
                }
            } catch (PDOException $ex) {
                return false;
            } finally {
                $this->conexionBD = null;
            }
        }

        public function modificaCostoActividadPorTrimestre ($idActividad, $costoActividad, $idCostActPorTri) {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $this->consulta->prepare("
                    set @persona = {$_SESSION['idUsuario']};
                ")->execute();
                $pt1 = ($this->porcentajeTrimestre1)*$costoActividad;
                $pt2 = ($this->porcentajeTrimestre2)*$costoActividad;
                $pt3 = ($this->porcentajeTrimestre3)*$costoActividad;
                $pt4 = ($this->porcentajeTrimestre4)*$costoActividad;
                $this->sumatoriaPorcentaje = $this->porcentajeTrimestre1 + $this->porcentajeTrimestre2 + $this->porcentajeTrimestre3 + $this->porcentajeTrimestre4;
                $stmt = $this->consulta->prepare("UPDATE CostoActividadPorTrimestre SET porcentajeTrimestre1 = :pT1, Trimestre1 = :t1, porcentajeTrimestre2 = :pT2, Trimestre2 = :t2, porcentajeTrimestre3 = :pT3, Trimestre3 = :t3, porcentajeTrimestre4 = :pT4, Trimestre4 = :t4, sumatoriaPorcentaje = :sumatoria WHERE idCostActPorTri = :idCostoActPorTri");
                $stmt->bindValue(':pT1', $this->porcentajeTrimestre1);
                $stmt->bindValue(':t1', $pt1);
                $stmt->bindValue(':pT2', $this->porcentajeTrimestre2);
                $stmt->bindValue(':t2', $pt2);
                $stmt->bindValue(':pT3', $this->porcentajeTrimestre3);
                $stmt->bindValue(':t3', $pt3);
                $stmt->bindValue(':pT4', $this->porcentajeTrimestre4);
                $stmt->bindValue(':t4', $pt4);
                $stmt->bindValue(':sumatoria', $this->sumatoriaPorcentaje);
                $stmt->bindValue(':idCostoActPorTri', $idCostActPorTri);
                if ($stmt->execute()) {
                    return true;
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