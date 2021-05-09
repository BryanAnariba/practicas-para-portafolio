<?php
    require_once('../../helpers/Respuesta.php');
    require_once('../../models/EstadoDCDUOAO.php');
    class EstadoDCDUOAOController {
        private $estadoModel;
        private $data;

        public function __construct() {
            $this->estadoModel = new EstadoDCDUOAO();
        }

        public  function listarEstados () {
            $this->data = $this->estadoModel->getEstados();

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