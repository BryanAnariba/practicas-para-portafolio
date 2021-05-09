<?php
    require_once('../../helpers/Respuesta.php');
    require_once('../../models/LlenadoActividadDimension.php');
    class LlenadoDimensionActividadController {
        private $llenadoActividadDimensionModel;
        private $data;
        public function __construct() {
            $this->llenadoActividadDimensionModel = new LlenadoActividadDimension();
        }

        public function listaControlLlenadoActividades () {
            $this->data = $this->llenadoActividadDimensionModel->getLlenadosActividadDimension();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function insertaNuevoRangoLlenadoActividad($idTipoUsuario, $valorLlenadoDimensionInicial, $valorLlenadoDimensionFinal) {
            $this->llenadoActividadDimensionModel->setTipoUsuario_idTipoUsuario($idTipoUsuario);
            $this->llenadoActividadDimensionModel->setValorLlenadoDimensionFinal($valorLlenadoDimensionFinal);
            $this->llenadoActividadDimensionModel->setValorLlenadoDimensionInicial($valorLlenadoDimensionInicial);

            $this->data = $this->llenadoActividadDimensionModel->insertarNuevoLlenadoDimension();
            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function modificaNuevoRangoLlenadoActividad($idLlenadoDimension, $valorLlenadoDimensionInicial, $valorLlenadoDimensionFinal) {
            $this->llenadoActividadDimensionModel->setIdLlenadoDimension($idLlenadoDimension);
            $this->llenadoActividadDimensionModel->setValorLlenadoDimensionFinal($valorLlenadoDimensionFinal);
            $this->llenadoActividadDimensionModel->setValorLlenadoDimensionInicial($valorLlenadoDimensionInicial);

            $this->data = $this->llenadoActividadDimensionModel->modificaLlenadoDimension();
            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function eliminarRangoDimension ($idLlenadoDimension) {
            $this->llenadoActividadDimensionModel->setIdLlenadoDimension($idLlenadoDimension);

            $this->data = $this->llenadoActividadDimensionModel->eliminaLlenadoDimension();
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