<?php
    require_once('../../config/config.php');
    require_once('../../database/Conexion.php');
    require_once('../../helpers/Respuesta.php');
    require_once('../../models/CalendarioActividades.php');
    
    class CalendarioController {
        private $CalendarioModel;
        private $data;

        public function __construct() {
            $this->CalendarioModel = new Calendario();
        }

        public function obtenerAnioPresupuesto () {

            $this->data = $this->CalendarioModel->getAnioPresupuesto();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function obtenerActividades ($idDimension,$Anio) {

            $this->CalendarioModel->setIdDimension($idDimension);
            $this->CalendarioModel->setAnio($Anio);
            $this->data = $this->CalendarioModel->getActividades();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function obtenerActividadDescripcionAdmin ($idActividad) {

            $this->CalendarioModel->setActividadAdmin($idActividad);
            $this->data = $this->CalendarioModel->getActividadDescripcionAdmin();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function obtenerActividadDescripcionAdminTodas () {

            $this->data = $this->CalendarioModel->getActividadDescripcionAdminTodas();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function obtenerDimensionEstrategicas () {

            $this->data = $this->CalendarioModel->getDimensionEstrategicas();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function obtenerActividadesPorId ($idActividad) {

            $this->CalendarioModel->setIdActividad($idActividad);
            $this->data = $this->CalendarioModel->getActividadPorId();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function obtenerActividadesPorMes ($Mes) {

            $this->CalendarioModel->setMes($Mes);
            $this->data = $this->CalendarioModel->getActividadesPorMes();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }
        
        public function obtenerActividadesPorDepa ($depa,$idDimension,$Anio) {

            $this->CalendarioModel->setDepa($depa);
            $this->CalendarioModel->setIdDimension($idDimension);
            $this->CalendarioModel->setAnio($Anio);
            $this->data = $this->CalendarioModel->getActividadesPorDepa();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function getInfoActPorId ($idActividad) {

            $this->CalendarioModel->setIdActividad($idActividad);
            $this->data = $this->CalendarioModel->getInfoActPorId();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function obtenerActividadesPlanif ($idActividad) {

            $this->CalendarioModel->setIdActividad($idActividad);
            $this->data = $this->CalendarioModel->obtenerActividadesPlanif();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function obtenerActividadesPlanifPorId ($idActividad) {

            $this->CalendarioModel->setIdActividad($idActividad);
            $this->data = $this->CalendarioModel->obtenerActividadesPlanifPorId();

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