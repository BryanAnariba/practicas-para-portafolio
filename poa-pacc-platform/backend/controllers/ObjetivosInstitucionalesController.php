<?php
    require_once('../../helpers/Respuesta.php');
    require_once('../../models/ObjetivoInstitucional.php');

    class ObjetivosIntitucionalesController {
        private $objetivoInstitucionalModel;
        private $data;

        public function __construct() {
            $this->objetivoInstitucionalModel = new ObjetivoInstitucional();   
        }

        public function listarObjetivosPorDimension ($idDimension) {
            $this->objetivoInstitucionalModel->setIdDimensionEstrategica($idDimension);
            $this->data = $this->objetivoInstitucionalModel->getObjetivosPorDimension();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function listarObjetivosActivosPorDimension($idDimension) {
            $this->objetivoInstitucionalModel->setIdDimensionEstrategica($idDimension);
            $this->objetivoInstitucionalModel->setIdEstadoObjetivoInstitucional(ESTADO_ACTIVO);
            $this->data = $this->objetivoInstitucionalModel->getObjetivosActivosPorDimension();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function insertarObjetivoPorDimension ($idDimension, $objetivoInstitucional) {
            $this->objetivoInstitucionalModel->setIdDimensionEstrategica($idDimension);
            $this->objetivoInstitucionalModel->setObjetivoInstitucional($objetivoInstitucional);
            $this->data = $this->objetivoInstitucionalModel->registrarObjetivoPorDimension();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function modificarEstadoObjetivo ($idObjetivo, $idEstadoObjetivo) {
            $this->objetivoInstitucionalModel->setIdObjetivoInstitucional($idObjetivo);
            $this->objetivoInstitucionalModel->setIdEstadoObjetivoInstitucional($idEstadoObjetivo);
            $this->data = $this->objetivoInstitucionalModel->modificaEstadoObjetivo();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }  
        
        public function modificarObjetivo ($idObjetivo, $objetivoInstitucional) {
            $this->objetivoInstitucionalModel->setIdObjetivoInstitucional($idObjetivo);
            $this->objetivoInstitucionalModel->setObjetivoInstitucional($objetivoInstitucional);
            $this->data = $this->objetivoInstitucionalModel->modificarObjetivo();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function peticionNoAutorizada () {
            $this->data = array('status' => UNAUTHORIZED_REQUEST, 'data' => array(
                'message' => 'No esta autorizado para realizar esta peticion o su token de acceso ha caducado, debes cerrar sesion y loguearse nuevamente'));

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }
        
        public function peticionNoValida () {
            $this->data = array('status' => BAD_REQUEST, 'data' => array('message' => 'Tipo de peticion no valida'));

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }
    }
?>