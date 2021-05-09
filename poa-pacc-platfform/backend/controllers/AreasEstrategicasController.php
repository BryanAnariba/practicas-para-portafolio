<?php
    require_once('../../helpers/Respuesta.php');
    require_once('../../models/AreaEstrategica.php');
    class AreasEstrategicasController {
        private $areaEstrategicaModel;
        private $data;

        public function __construct() {
            $this->areaEstrategicaModel = new AreaEstrategica();   
        }

        public function listarAreasPorObjetivo ($idObjetivo) {
            $this->areaEstrategicaModel->setIdObjetivoInstitucional($idObjetivo);
            $this->data = $this->areaEstrategicaModel->getAreasEstrategicas();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function listarAreasActivasPorObjetivo($idObjetivo) {
            $this->areaEstrategicaModel->setIdObjetivoInstitucional($idObjetivo);
            $this->areaEstrategicaModel->setIdEstadoAreaEstrategica(ESTADO_ACTIVO);
            $this->data = $this->areaEstrategicaModel->getAreasActivasPorObjetivo();
            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }
        public function insertarAreaPorObjetivo ($idObjetivo, $areaEstrategica) {
            $this->areaEstrategicaModel->setIdObjetivoInstitucional($idObjetivo);
            $this->areaEstrategicaModel->setAreaEstrategica($areaEstrategica);
            $this->data = $this->areaEstrategicaModel->insertaArea();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function modificaEstadoArea ($idArea, $idEstadoAarea) {
            $this->areaEstrategicaModel->setIdAreaEstrategica($idArea);
            $this->areaEstrategicaModel->setIdEstadoAreaEstrategica($idEstadoAarea);
            $this->data = $this->areaEstrategicaModel->modificarEstadoAreaEstrategica();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function modificarArea($idArea, $area) {
            $this->areaEstrategicaModel->setIdAreaEstrategica($idArea);
            $this->areaEstrategicaModel->setAreaEstrategica($area);
            $this->data = $this->areaEstrategicaModel->modificarAreaEstrategica();

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