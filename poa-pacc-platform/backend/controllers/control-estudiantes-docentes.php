<?php
    require_once('../../config/config.php');
    require_once('../../database/Conexion.php');
    require_once('../../helpers/Respuesta.php');
    require_once('../../models/control-estudiantes-docentes.php');
    
    class EstudianteDocenteController {

        private $data;

        public function ingresarPoblacion ($Trimestre,$numeroPoblacion,$respaldo,$poblacion,$idUsuario) {
            
            $EstudianteDocente = new EstudianteDocente();

            $EstudianteDocente->setTrimestre($Trimestre);
            $EstudianteDocente->setNumeroPoblacion($numeroPoblacion);
            $EstudianteDocente->setRespaldo($respaldo);
            $EstudianteDocente->setPoblacion($poblacion);
            $EstudianteDocente->setIdUsuario($idUsuario);

            $this->data = $EstudianteDocente->ingresarPoblacion();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function ingresarPoblacionSinDocumento ($Trimestre,$numeroPoblacion,$poblacion,$idUsuario) {
            
            $EstudianteDocente = new EstudianteDocente();

            $EstudianteDocente->setTrimestre($Trimestre);
            $EstudianteDocente->setNumeroPoblacion($numeroPoblacion);
            $EstudianteDocente->setRespaldo(null);
            $EstudianteDocente->setPoblacion($poblacion);
            $EstudianteDocente->setIdUsuario($idUsuario);

            $this->data = $EstudianteDocente->ingresarPoblacion();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function modificarRespaldo ($idGestion,$respaldo) {
            
            $EstudianteDocente = new EstudianteDocente();

            $EstudianteDocente->setRespaldo($respaldo);
            $EstudianteDocente->setIdGestion($idGestion);

            $this->data = $EstudianteDocente->modificarRespaldo();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function obtenerTrimestres () {

            $EstudianteDocente = new EstudianteDocente();

            $this->data = $EstudianteDocente->getTrimestres();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function getGestion ($idUsuario) {

            $EstudianteDocente = new EstudianteDocente();

            $EstudianteDocente->setIdUsuario($idUsuario);

            $this->data = $EstudianteDocente->getGestion();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function getImagen ($idGestion) {

            $EstudianteDocente = new EstudianteDocente();

            $EstudianteDocente->setIdGestion($idGestion);

            $this->data = $EstudianteDocente->getImagen();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function modificarPoblacion ($Trimestre,$numeroPoblacion,$idTipoGestion,$idGestion,$fechaRegistro) {
            
            $EstudianteDocente = new EstudianteDocente();

            $EstudianteDocente->setTrimestre($Trimestre);
            $EstudianteDocente->setNumeroPoblacion($numeroPoblacion);
            $EstudianteDocente->setIdGestion($idGestion);

            $this->data = $EstudianteDocente->modificarPoblacion($idTipoGestion,$fechaRegistro);

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function verImagenRespaldoPorId($idUsuario) {
            $EstudianteDocente = new EnvioSolicitudesPermisos();    
            
            $this->data = $EstudianteDocente->setIdUsuario($idUsuario);
            $this->data = $EstudianteDocente->verImagenRespaldoPorId();

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