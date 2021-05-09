<?php
    require_once('../../config/config.php');
    require_once('../../database/Conexion.php');
    require_once('../../helpers/Respuesta.php');
    require_once('../../models/Carrera.php');
    
    class CarrerasController {

        private $data;

        public function registrarCarrera ($Carrera,$Abreviatura,$idDepartamento,$idEstado) {
            
            $Carreras = new Carrera();

            $Carreras->setIdCarrera(0);
            $Carreras->setCarrera($Carrera);
            $Carreras->setAbreviatura($Abreviatura);
            $Carreras->setidDepartamento($idDepartamento);
            $Carreras->setidEstado($idEstado);

            $this->data = $Carreras->insertaCarrera();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function obtenerDepartamentos () {

            $Carreras = new Carrera();

            $this->data = $Carreras->getDepartamentos();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function obtenerEstados () {

            $Carreras = new Carrera();

            $this->data = $Carreras->getEstados();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function obtenerCarreras () {
            $Carreras = new Carrera();

            $this->data = $Carreras->getCarreras();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function obtenerCarrerasPorDepa ($idDepartamento) {

            $Carreras = new Carrera();

            $this->data = $Carreras->getCarrerasPorDepa($idDepartamento);

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function obtenerCarreraPorId ($idCarrera) {

            $Carreras = new Carrera();

            $Carreras->setIdCarrera($idCarrera);
            $this->data = $Carreras->getCarrerasPorId();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }
        public function ActualizarCarrera ($idCarrera,$Carrera,$Abreviatura,$idDepartamento,$idEstado) {
            
            $Carreras = new Carrera();

            $Carreras->setIdCarrera($idCarrera);
            $Carreras->setCarrera($Carrera);
            $Carreras->setAbreviatura($Abreviatura);
            $Carreras->setidDepartamento($idDepartamento);
            $Carreras->setidEstado($idEstado);

            $this->data = $Carreras->modificarCarrera();

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