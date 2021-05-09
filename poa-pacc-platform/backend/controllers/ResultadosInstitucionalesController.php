<?php
    require_once('../../helpers/Respuesta.php');
    require_once('../../models/ResultadoInstitucional.php');
    class ResultadoInstitucionalController {
        private $resultadoInstitucionalModel;
        private $data;
    

        public function __construct () {
            $this->resultadoInstitucionalModel = new ResultadoInstitucional();
        }

        public function listarResultados ($idAreaEstrategica) {
            $this->resultadoInstitucionalModel->setIdAreaEstrategica($idAreaEstrategica);
            $this->data = $this->resultadoInstitucionalModel->getResultadosInstitucionales();
            
            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function listarResultadosActivos ($idAreaEstrategica) {
            $this->resultadoInstitucionalModel->setIdAreaEstrategica($idAreaEstrategica);
            $this->resultadoInstitucionalModel->setIdEstadoResultadoInstitucional(ESTADO_ACTIVO);
            $this->data = $this->resultadoInstitucionalModel->getResultadosInstitucionalesActivos();
            
            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function registrarResultado ($idAreaEstrategica, $resultadoInstitucional) {
            $this->resultadoInstitucionalModel->setIdEstadoResultadoInstitucional(ESTADO_ACTIVO);
            $this->resultadoInstitucionalModel->setIdAreaEstrategica($idAreaEstrategica);
            $this->resultadoInstitucionalModel->setResultadoInstitucional($resultadoInstitucional);
            $this->data = $this->resultadoInstitucionalModel->registroResultadoInstitucional();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function cambiarEstadoResultado ($idResultadoInstitucional, $idEstadoResultadoInsitucional) {
            if ($idEstadoResultadoInsitucional == ESTADO_ACTIVO) {
                $this->resultadoInstitucionalModel->setIdEstadoResultadoInstitucional(ESTADO_INACTIVO); 
            } else {
                $this->resultadoInstitucionalModel->setIdEstadoResultadoInstitucional(ESTADO_ACTIVO);
            } 

            $this->resultadoInstitucionalModel->setIdResultadoInstitucional($idResultadoInstitucional);
            $this->data = $this->resultadoInstitucionalModel->modificarEstadoResultadoInstitucional();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function cambiarResultado ($idResultadoInstitucional, $resultadoInstitucional) {
            $this->resultadoInstitucionalModel->setIdResultadoInstitucional($idResultadoInstitucional);
            $this->resultadoInstitucionalModel->setResultadoInstitucional($resultadoInstitucional);
            $this->data = $this->resultadoInstitucionalModel->modificarResultadoInstitucional();

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