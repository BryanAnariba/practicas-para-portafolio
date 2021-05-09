<?php
    require_once('../../helpers/Respuesta.php');
    require_once('../../models/EnvioSolicitudesPermisos.php');
    class EnvioSolicitudesController {
        private $envioSolicitudesPermisosModel;
        private $data;

        //Función para listar las solicitudes enviadas
        public function listarSolicitudesEnviadas($idUsuario) {
            $this->envioSolicitudesPermisosModel = new EnvioSolicitudesPermisos();    
            
            $this->data = $this->envioSolicitudesPermisosModel->setIdUsuario($idUsuario);
            $this->data = $this->envioSolicitudesPermisosModel->getSolicitudesEnviadas();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }


        public function verSolicitudPorId($idSolicitud) {
            $this->envioSolicitudesPermisosModel = new EnvioSolicitudesPermisos();    
            
            $this->data = $this->envioSolicitudesPermisosModel->setIdSolicitud($idSolicitud);
            $this->data = $this->envioSolicitudesPermisosModel->ObtenerSolicitudPorId();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

     

        //verObservacionesPorId
        public function verObservacionesPorId($idSolicitud) {
            $this->envioSolicitudesPermisosModel = new EnvioSolicitudesPermisos();    
            
            $this->data = $this->envioSolicitudesPermisosModel->setIdSolicitud($idSolicitud);
            $this->data = $this->envioSolicitudesPermisosModel->ObtenerObservacionesPorId();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }
    

        
        // registrarSolicitudSinImagenes
        public function registrarSolicitudSinImagenes($idUsuario,
                                                    $motivoPermiso,
                                                    $edificioAsistencia,
                                                    $fechaInicio,
                                                    $fechaFin,
                                                    $horaInicio,
                                                    $horaFin
                                                    ) {
            $this->envioSolicitudesPermisosModel = new EnvioSolicitudesPermisos();    
            
            $this->data = $this->envioSolicitudesPermisosModel->setIdTipoSolicitud(1);
            $this->data = $this->envioSolicitudesPermisosModel->setIdUsuario($idUsuario);
            $this->data = $this->envioSolicitudesPermisosModel->setMotivoSolicitud($motivoPermiso);
            $this->data = $this->envioSolicitudesPermisosModel->setEdificioAsistencia($edificioAsistencia);
            $this->data = $this->envioSolicitudesPermisosModel->setFechaInicio($fechaInicio);
            $this->data = $this->envioSolicitudesPermisosModel->setFechaFin($fechaFin);
            $this->data = $this->envioSolicitudesPermisosModel->setHoraInicio($horaInicio);
            $this->data = $this->envioSolicitudesPermisosModel->setHoraFin($horaFin);

            $this->data = $this->envioSolicitudesPermisosModel->registrarSolicitudSinImagenes();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        // registrarSolicitudConImagenes
        public function registrarSolicitudConImagenes($idUsuario,
                                                    $motivoPermiso,
                                                    $edificioAsistencia,
                                                    $fechaInicio,
                                                    $fechaFin,
                                                    $horaInicio,
                                                    $horaFin,
                                                    $respaldo,
                                                    $firma
                                                    ) {
        
            $this->envioSolicitudesPermisosModel = new EnvioSolicitudesPermisos();    
            
            $this->data = $this->envioSolicitudesPermisosModel->setIdTipoSolicitud(1);
            $this->data = $this->envioSolicitudesPermisosModel->setIdUsuario($idUsuario);
            $this->data = $this->envioSolicitudesPermisosModel->setMotivoSolicitud($motivoPermiso);
            $this->data = $this->envioSolicitudesPermisosModel->setEdificioAsistencia($edificioAsistencia);
            $this->data = $this->envioSolicitudesPermisosModel->setFechaInicio($fechaInicio);
            $this->data = $this->envioSolicitudesPermisosModel->setFechaFin($fechaFin);
            $this->data = $this->envioSolicitudesPermisosModel->setHoraInicio($horaInicio);
            $this->data = $this->envioSolicitudesPermisosModel->setHoraFin($horaFin);
            $this->data = $this->envioSolicitudesPermisosModel->setFirmaDigital($firma);
            $this->data = $this->envioSolicitudesPermisosModel->setDocumentoRespaldo($respaldo);

            $this->data = $this->envioSolicitudesPermisosModel->registrarSolicitudConImagenes();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }


        //registrarSolicitudConFirma
        public function registrarSolicitudConFirma($idUsuario,
                                                    $motivoPermiso,
                                                    $edificioAsistencia,
                                                    $fechaInicio,
                                                    $fechaFin,
                                                    $horaInicio,
                                                    $horaFin,
                                                    $firma
                                                    ) {
        
            $this->envioSolicitudesPermisosModel = new EnvioSolicitudesPermisos();    
            
            $this->data = $this->envioSolicitudesPermisosModel->setIdTipoSolicitud(1);
            $this->data = $this->envioSolicitudesPermisosModel->setIdUsuario($idUsuario);
            $this->data = $this->envioSolicitudesPermisosModel->setMotivoSolicitud($motivoPermiso);
            $this->data = $this->envioSolicitudesPermisosModel->setEdificioAsistencia($edificioAsistencia);
            $this->data = $this->envioSolicitudesPermisosModel->setFechaInicio($fechaInicio);
            $this->data = $this->envioSolicitudesPermisosModel->setFechaFin($fechaFin);
            $this->data = $this->envioSolicitudesPermisosModel->setHoraInicio($horaInicio);
            $this->data = $this->envioSolicitudesPermisosModel->setHoraFin($horaFin);
            $this->data = $this->envioSolicitudesPermisosModel->setFirmaDigital($firma);

            $this->data = $this->envioSolicitudesPermisosModel->registrarSolicitudConFirma();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }


        //registrarSolicitudConRespaldo
        public function registrarSolicitudConRespaldo($idUsuario,
                                                    $motivoPermiso,
                                                    $edificioAsistencia,
                                                    $fechaInicio,
                                                    $fechaFin,
                                                    $horaInicio,
                                                    $horaFin,
                                                    $respaldo
                                                    ) {
        
            $this->envioSolicitudesPermisosModel = new EnvioSolicitudesPermisos();    
            
            $this->data = $this->envioSolicitudesPermisosModel->setIdTipoSolicitud(1);
            $this->data = $this->envioSolicitudesPermisosModel->setIdUsuario($idUsuario);
            $this->data = $this->envioSolicitudesPermisosModel->setMotivoSolicitud($motivoPermiso);
            $this->data = $this->envioSolicitudesPermisosModel->setEdificioAsistencia($edificioAsistencia);
            $this->data = $this->envioSolicitudesPermisosModel->setFechaInicio($fechaInicio);
            $this->data = $this->envioSolicitudesPermisosModel->setFechaFin($fechaFin);
            $this->data = $this->envioSolicitudesPermisosModel->setHoraInicio($horaInicio);
            $this->data = $this->envioSolicitudesPermisosModel->setHoraFin($horaFin);
            $this->data = $this->envioSolicitudesPermisosModel->setDocumentoRespaldo($respaldo);

            $this->data = $this->envioSolicitudesPermisosModel->registrarSolicitudConRespaldo();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }


        //verImagenRespaldoPorId
        public function verImagenRespaldoPorId($idSolicitud) {
            $this->envioSolicitudesPermisosModel = new EnvioSolicitudesPermisos();    
            
            $this->data = $this->envioSolicitudesPermisosModel->setIdSolicitud($idSolicitud);
            $this->data = $this->envioSolicitudesPermisosModel->verImagenRespaldoPorId();

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