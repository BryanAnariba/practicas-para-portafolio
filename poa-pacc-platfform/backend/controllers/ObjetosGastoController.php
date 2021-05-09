<?php
    require_once('../../config/config.php');
    require_once('../../database/Conexion.php');
    require_once('../../helpers/Respuesta.php');
    require_once('../../models/ObjetosGasto.php');
    
    class ObjetosController {

        private $data;

        public function registrarObjeto ($ObjetoDeGasto,$Abreviatura,$CodigoObjeto,$idEstado) {
            
            $Objetos = new Objeto();

            $Objetos->setIdObjetoGasto(0);
            $Objetos->setObjetoDeGasto($ObjetoDeGasto);
            $Objetos->setAbreviatura($Abreviatura);
            $Objetos->setCodigoObjeto($CodigoObjeto);
            $Objetos->setidEstado($idEstado);

            $this->data = $Objetos->insertarObjeto();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function obtenerEstados () {

            $Objetos = new Objeto();

            $this->data = $Objetos->getEstados();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function obtenerObjetos () {
            $Objetos = new Objeto();

            $this->data = $Objetos->getObjetos();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function obtenerObjetosActivos() {
            $Objetos = new Objeto();

            $Objetos->setidEstado(ESTADO_ACTIVO);
            $this->data = $Objetos->getObjetosActivos();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function ActualizarObjeto ($idObjetoGasto,$ObjetoDeGasto,$Abreviatura,$CodigoObjeto,$idEstado) {
            
            $Objetos = new Objeto();

            $Objetos->setIdObjetoGasto($idObjetoGasto);
            $Objetos->setObjetoDeGasto($ObjetoDeGasto);
            $Objetos->setAbreviatura($Abreviatura);
            $Objetos->setCodigoObjeto($CodigoObjeto);
            $Objetos->setidEstado($idEstado);

            $this->data = $Objetos->modificarObjeto();

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