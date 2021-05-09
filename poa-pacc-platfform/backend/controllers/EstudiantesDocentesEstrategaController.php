<?php
    require_once('../../config/config.php');
    require_once('../../database/Conexion.php');
    require_once('../../helpers/Respuesta.php');
    require_once('../../models/EstudiantesDocentesEstratega.php');
    
    class EstudiantesDocentesEstrategaController {

        private $data;


        public function modificarRespaldo ($idGestion,$respaldo) {
            
            $EstudiantesDocentesEstratega = new EstudiantesDocentesEstratega();

            $EstudiantesDocentesEstratega->setRespaldo($respaldo);
            $EstudiantesDocentesEstratega->setIdGestion($idGestion);

            $this->data = $EstudiantesDocentesEstratega->modificarRespaldo();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function obtenerTrimestres () {

            $EstudiantesDocentesEstratega = new EstudiantesDocentesEstratega();

            $this->data = $EstudiantesDocentesEstratega->getTrimestres();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }


        public function getGestionRegistrosTotales () {

            $EstudiantesDocentesEstratega = new EstudiantesDocentesEstratega();

            $this->data = $EstudiantesDocentesEstratega->getGestionRegistrosTotales();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }


        //getGestionRegistrosPorDepartamento
        public function getGestionRegistrosPorDepartamento ($idDepartamentoVer) {

            $EstudiantesDocentesEstratega = new EstudiantesDocentesEstratega();

            $EstudiantesDocentesEstratega->setIdDepartamento($idDepartamentoVer);

            $this->data = $EstudiantesDocentesEstratega->getGestionRegistrosPorDepartamento();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }


        //getGestionRegistrosPorTipo
        public function getGestionRegistrosPorTipo ($idTipoGestionVer) {

            $EstudiantesDocentesEstratega = new EstudiantesDocentesEstratega();

            $EstudiantesDocentesEstratega->setIdTipoGestion($idTipoGestionVer);

            $this->data = $EstudiantesDocentesEstratega->getGestionRegistrosPorTipo();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }



        public function getImagen ($idGestion) {

            $EstudiantesDocentesEstratega = new EstudiantesDocentesEstratega();

            $EstudiantesDocentesEstratega->setIdGestion($idGestion);

            $this->data = $EstudiantesDocentesEstratega->getImagen();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function modificarPoblacion ($Trimestre,$numeroPoblacion,$idGestion) {
            
            $EstudiantesDocentesEstratega = new EstudiantesDocentesEstratega();

            $EstudiantesDocentesEstratega->setTrimestre($Trimestre);
            $EstudiantesDocentesEstratega->setNumeroPoblacion($numeroPoblacion);
            $EstudiantesDocentesEstratega->setIdGestion($idGestion);

            $this->data = $EstudiantesDocentesEstratega->modificarPoblacion();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function verImagenRespaldoPorId($idUsuario) {
            $EstudiantesDocentesEstratega = new EnvioSolicitudesPermisos();    
            
            $this->data = $EstudiantesDocentesEstratega->setIdUsuario($idUsuario);
            $this->data = $EstudiantesDocentesEstratega->verImagenRespaldoPorId();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }


        public function obtenerDepartamentos () {

            $Departamentos = new EstudiantesDocentesEstratega();

            $this->data = $Departamentos->Departamentos();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }


        //obtenerTipoGestion
        public function obtenerTipoGestion () {

            $Gestiones = new EstudiantesDocentesEstratega();

            $this->data = $Gestiones->obtenerTipoGestion();

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