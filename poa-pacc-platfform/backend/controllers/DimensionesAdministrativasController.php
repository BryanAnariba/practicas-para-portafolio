<?php
    require_once('../../helpers/Respuesta.php');
    require_once('../../models/DimensionAdministrativa.php');
    class DimensionesAdministrativasController {
        private $dimensionAdministrativaModel;
        private $data;

        public function __construct() {
            $this->dimensionAdministrativaModel = new Dimension();
        }

        public function listarDimensiones () {
            $this->data = $this->dimensionAdministrativaModel->getDimensiones();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function listarDimensionesActivas () {
            $this->data = $this->dimensionAdministrativaModel->getDimensionesActivas();
            
            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function insertaDimension ($dimensionAdministrativa) {
            $this->dimensionAdministrativaModel->setDimensionAdministrativa($dimensionAdministrativa);
            $this->dimensionAdministrativaModel->setIdEstadoDimension(ESTADO_ACTIVO);
            $this->data = $this->dimensionAdministrativaModel->insertaDimension();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function modificaEstadoDimension ($idDimensionAdministrativa) {
            $this->dimensionAdministrativaModel->setIdDimension($idDimensionAdministrativa);
            $this->data = $this->dimensionAdministrativaModel->modificaEstadoDimension();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function modificarDimension ($idDimensionAdministrativa, $dimensionAdministrativa) {
            $this->dimensionAdministrativaModel->setIdDimension($idDimensionAdministrativa);
            $this->dimensionAdministrativaModel->setDimensionAdministrativa($dimensionAdministrativa);
            $this->data = $this->dimensionAdministrativaModel->modificarDimension();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function peticionNoValida () {
            $this->data = array('status' => BAD_REQUEST, 'data' => array('message' => 'Tipo de peticion no valida'));

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