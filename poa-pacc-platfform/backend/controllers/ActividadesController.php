<?php
    require_once('../../helpers/Respuesta.php');
    require_once('../../models/Actividad.php');
    class ActividadesController {
        private $actividadesModel;
        private $data;

        public function __construct() {
            $this->actividadesModel = new Actividad();
        }

        public function listarActividadesPorDimension () {
            $this->data = $this->actividadesModel->getActividadesPorDimension();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function peticionNoValida () {
            $this->data = array('status' => BAD_REQUEST, 'data' => array('message' => 'Tipo de peticion no valida'));

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function generarComparativoPresupuestos () {
            $this->data = $this->actividadesModel->getComparativaDePresupuestos();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function verificaCostoRangoActividad($costoActividad, $porcentajeTrimestre1, $porcentajeTrimestre2, $porcentajeTrimestre3, $porcentajeTrimestre4) {
            $this->actividadesModel->setCostoTotal($costoActividad);
            $this->actividadesModel->setPorcentajeTrimestre1($porcentajeTrimestre1);
            $this->actividadesModel->setPorcentajeTrimestre2($porcentajeTrimestre2);
            $this->actividadesModel->setPorcentajeTrimestre3($porcentajeTrimestre3);
            $this->actividadesModel->setPorcentajeTrimestre4($porcentajeTrimestre4);

            $this->data = $this->actividadesModel->verificaDatosPresupuestoActividad();
            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function verificaRangoPresupuestoParaModificar ($costoTotal, $idActividad) {
            $this->actividadesModel->setCostoTotal($costoTotal);
            $this->actividadesModel->setIdActividad($idActividad);

            $this->data = $this->actividadesModel->verificaPresupuestoActividadModificar();
            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function generarCorrelativo ($idDimension) {
            $this->actividadesModel->setIdDimension($idDimension);
            $this->data = $this->actividadesModel->generaCorrelativoActividad();
            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function insertarNuevaActividad(
            $actividad, 
            $correlativoActividad, 
            $idAreaEstrategica, 
            $idDimension, 
            $idObjetivoInstitucional, 
            $idResultadoInstitucional,
            $indicadoresResultado,
            $justificacionActividad,
            $medioVerificacionActividad,
            $poblacionObjetivoActividad,
            $resultadosUnidad,
            $responsableActividad,
            $costoTotal,
            $porcentajeTrimestre1,
            $porcentajeTrimestre2,
            $porcentajeTrimestre3,
            $porcentajeTrimestre4
        ) {
            $this->actividadesModel->setActividad($actividad);
            $this->actividadesModel->setCorrelativoActividad($correlativoActividad);
            $this->actividadesModel->setIdAreaEstrategica($idAreaEstrategica);
            $this->actividadesModel->setIdDimension($idDimension);
            $this->actividadesModel->setIdObjetivoInstitucional($idObjetivoInstitucional);
            $this->actividadesModel->setIdResultadoInstitucional($idResultadoInstitucional);
            if ($costoTotal > 0) {
                $this->actividadesModel->setIdTipoActividad(1);
            } else {
                $this->actividadesModel->setIdTipoActividad(2);
            }
            $this->actividadesModel->setIdEstadoActividad(ESTADO_INACTIVO);
            $this->actividadesModel->setIndicadoresResultado($indicadoresResultado);
            $this->actividadesModel->setJustificacionActividad($justificacionActividad);
            $this->actividadesModel->setMedioVerificacionActividad($medioVerificacionActividad);
            $this->actividadesModel->setPoblacionOjetivoActividad($poblacionObjetivoActividad);
            $this->actividadesModel->setResultadosUnidad($resultadosUnidad);
            $this->actividadesModel->setResponsableActividad($responsableActividad);
            $this->actividadesModel->setCostoTotal($costoTotal);
            $this->actividadesModel->setPorcentajeTrimestre1($porcentajeTrimestre1);
            $this->actividadesModel->setPorcentajeTrimestre2($porcentajeTrimestre2);
            $this->actividadesModel->setPorcentajeTrimestre3($porcentajeTrimestre3);
            $this->actividadesModel->setPorcentajeTrimestre4($porcentajeTrimestre4);

            $this->data = $this->actividadesModel->insertaActividad();
            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }
        
        public function modificarDataActividad(
            $idActividad,
            $actividad, 
            $correlativoActividad, 
            $idAreaEstrategica, 
            $idDimension, 
            $idObjetivoInstitucional, 
            $idResultadoInstitucional,
            $indicadoresResultado,
            $justificacionActividad,
            $medioVerificacionActividad,
            $poblacionObjetivoActividad,
            $resultadosUnidad,
            $responsableActividad,
            $costoTotal,
            $porcentajeTrimestre1,
            $porcentajeTrimestre2,
            $porcentajeTrimestre3,
            $porcentajeTrimestre4,
            $idCostoActTrimestre
        ) {
            $this->actividadesModel->setIdActividad($idActividad);
            $this->actividadesModel->setActividad($actividad);
            $this->actividadesModel->setCorrelativoActividad($correlativoActividad);
            $this->actividadesModel->setIdAreaEstrategica($idAreaEstrategica);
            $this->actividadesModel->setIdDimension($idDimension);
            $this->actividadesModel->setIdObjetivoInstitucional($idObjetivoInstitucional);
            $this->actividadesModel->setIdResultadoInstitucional($idResultadoInstitucional);
            if ($costoTotal > 0) {
                $this->actividadesModel->setIdTipoActividad(1);
            } else {
                $this->actividadesModel->setIdTipoActividad(2);
            }
            
            $this->actividadesModel->setIndicadoresResultado($indicadoresResultado);
            $this->actividadesModel->setJustificacionActividad($justificacionActividad);
            $this->actividadesModel->setMedioVerificacionActividad($medioVerificacionActividad);
            $this->actividadesModel->setPoblacionOjetivoActividad($poblacionObjetivoActividad);
            $this->actividadesModel->setResultadosUnidad($resultadosUnidad);
            $this->actividadesModel->setResponsableActividad($responsableActividad);
            $this->actividadesModel->setCostoTotal($costoTotal);
            $this->actividadesModel->setPorcentajeTrimestre1($porcentajeTrimestre1);
            $this->actividadesModel->setPorcentajeTrimestre2($porcentajeTrimestre2);
            $this->actividadesModel->setPorcentajeTrimestre3($porcentajeTrimestre3);
            $this->actividadesModel->setPorcentajeTrimestre4($porcentajeTrimestre4);
            $this->actividadesModel->setIdCostActPorTri($idCostoActTrimestre);

            $this->data = $this->actividadesModel->modificaRegistroActividad();
            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function listarActividadesPorDimensionEstrategica ($idDimension) {
            $this->actividadesModel->setIdDimension($idDimension);
            $this->data = $this->actividadesModel->getActividadesDimension();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function verificaEstadoPresupuesto() {
            $this->data = $this->actividadesModel->getEstadoPresupuestoAnual();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function modifEstadoActividad ($idActividad, $isEstadoActividad) {
            $this->actividadesModel->setIdActividad($idActividad);
            if ($isEstadoActividad == ESTADO_ACTIVO) {
                $this->actividadesModel->setIdEstadoActividad(ESTADO_INACTIVO);
            } else {
                $this->actividadesModel->setIdEstadoActividad(ESTADO_ACTIVO);
            }

            $this->data = $this->actividadesModel->cambiaEstadoActividad();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function verEstadoPresupuestoAnual () {
            $this->data = $this->actividadesModel->verificaEstadoPresupuestoAnual();
            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function generaDataActividad ($idActividad) {
            $this->actividadesModel->setIdActividad($idActividad);

            $this->data = $this->actividadesModel->generaActividad();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function peticionNoAutorizada () {
            $this->data = array('status' => UNAUTHORIZED_REQUEST, 'data' => array(
                'message' => 'No esta autorizado para realizar esta peticion o su token de acceso ha caducado, debes cerrar sesion y loguearse nuevamente'));

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }
    }
?>