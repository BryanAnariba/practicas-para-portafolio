<?php
    require_once('../../helpers/Respuesta.php');
    require_once('../../models/Lugar.php');
    class LugaresController {
        private $lugarModel;
        private $data;
        public function __construct() {
            $this->lugarModel = new Lugar();
        }

        public function listarPaises ($idPais) {
            $this->lugarModel->setIdTipoLugar($idPais);
            $this->data = $this->lugarModel->getPaises();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function listarCiudades ($idPais) {
            $this->lugarModel->setIdLugarPadre($idPais);
            $this->data = $this->lugarModel->getCiudades();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function listarMunicipios($idCiudad) {
            $this->lugarModel->setIdLugarPadre($idCiudad);
            $this->data = $this->lugarModel->getMunicipios();

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