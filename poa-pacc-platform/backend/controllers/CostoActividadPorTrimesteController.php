<?php
    require_once('../../helpers/Respuesta.php');
    require_once('../../models/CostoActividadPorTrimestre.php');
    class CostoActividadPorTrimestreController {
        private $costoActividadPorTrimestreModel;
        private $data;

        public function __construct () {
            $this->costoActividadPorTrimestreModel = new CostoActividadPorTrimestre();
        }

        public function insertaNuevoCosto () {

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