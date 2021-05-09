<?php
    require_once('../../helpers/Respuesta.php');
    require_once('../../models/RecibirSolicitudesPermisos.php');
    class RecibirSolicitudesController {
        private $recibirSolicitudesPermisosModel;
        private $data;

        //Función para listar las solicitudes enviadas
        public function listarSolicitudesPendientesSecAcademica() {
            $this->recibirSolicitudesPermisosModel = new RecibirSolicitudesPermisos();    
            
            $this->data = $this->recibirSolicitudesPermisosModel->getSolicitudesRecibidasSecAcademica();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }


        //obtenerIdDepartamentoJefeConCoordinador
        public function obtenerIdDepartamentoJefeConCoordinador($idUsuario) {
            $this->RecibirSolicitudesPermisosModel = new RecibirSolicitudesPermisos();    
            
            $this->data = $this->RecibirSolicitudesPermisosModel->setIdUsuario($idUsuario);
            $this->data = $this->RecibirSolicitudesPermisosModel->getIdDepartamentoJefeConCoordinador();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }


        //listarSolicitudesPendientesJefe
        public function listarSolicitudesPendientesJefe($idDepartamentoUsuario) {
            $this->RecibirSolicitudesPermisosModel = new RecibirSolicitudesPermisos();    
            
            $this->data = $this->RecibirSolicitudesPermisosModel->setIdDepartamento($idDepartamentoUsuario);
            $this->data = $this->RecibirSolicitudesPermisosModel->getSolicitudesRecibidasJefe();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        
        //listarSolicitudesPendientesDecano
        public function listarSolicitudesPendientesDecano() {
            $this->recibirSolicitudesPermisosModel = new RecibirSolicitudesPermisos();    
            
            $this->data = $this->recibirSolicitudesPermisosModel->getSolicitudesRecibidasDecano();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }



        //listarSolicitudesRevisadas
        public function listarSolicitudesRevisadas($idUsuario) {
            $this->recibirSolicitudesPermisosModel = new RecibirSolicitudesPermisos();    
            
            $this->data = $this->recibirSolicitudesPermisosModel->setIdUsuario($idUsuario);
            $this->data = $this->recibirSolicitudesPermisosModel->getSolicitudesRevisadas();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }



        public function verSolicitudPorId($idSolicitud) {
            $this->recibirSolicitudesPermisosModel = new RecibirSolicitudesPermisos();    
            
            $this->data = $this->recibirSolicitudesPermisosModel->setIdSolicitud($idSolicitud);
            $this->data = $this->recibirSolicitudesPermisosModel->ObtenerSolicitudPorId();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

     

        //verObservacionesPorId
        public function verObservacionesPorId($idSolicitud) {
            $this->recibirSolicitudesPermisosModel = new RecibirSolicitudesPermisos();    
            
            $this->data = $this->recibirSolicitudesPermisosModel->setIdSolicitud($idSolicitud);
            $this->data = $this->recibirSolicitudesPermisosModel->ObtenerObservacionesPorId();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }
    
        //hacerObservacion
        public function hacerObservaciones ($idSolicitud, $observaciones) {
            $this->recibirSolicitudesPermisosModel = new RecibirSolicitudesPermisos(); 

            $this->data = $this->recibirSolicitudesPermisosModel->setIdSolicitud($idSolicitud);
            $this->data = $this->recibirSolicitudesPermisosModel->setObservaciones($observaciones);
            $this->data = $this->recibirSolicitudesPermisosModel->hacerObservaciones();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }


        public function verImagenRespaldoPorId($idSolicitud) {
            $this->recibirSolicitudesPermisosModel = new RecibirSolicitudesPermisos();    
            
            $this->data = $this->recibirSolicitudesPermisosModel->setIdSolicitud($idSolicitud);
            $this->data = $this->recibirSolicitudesPermisosModel->verImagenRespaldoPorId();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }


        public function obtenerEstadoSolicitud () {

            $this->recibirSolicitudesPermisosModel = new RecibirSolicitudesPermisos(); 

            $this->data = $this->recibirSolicitudesPermisosModel->getEstados();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        //actualizarSolicitud     
        public function actualizarSolicitud($idSolicitud, $idEstadoSolicitud, $idUsuario) {
            $this->recibirSolicitudesPermisosModel = new RecibirSolicitudesPermisos(); 

            $this->data = $this->recibirSolicitudesPermisosModel->setIdSolicitud($idSolicitud);
            $this->data = $this->recibirSolicitudesPermisosModel->setIdEstadoSolicitud($idEstadoSolicitud);
            $this->data = $this->recibirSolicitudesPermisosModel->setIdUsuario($idUsuario);
            $this->data = $this->recibirSolicitudesPermisosModel->actualizarSolicitud();

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