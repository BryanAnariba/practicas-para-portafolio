<?php
    require_once('../../helpers/Respuesta.php');
    require_once('../../models/Notificaciones.php');
    class NotificacionesController {
        private $notificacionesModel;
        private $data;

        //verNotificacionSecAcademica Función para listar las notificaciones en el sistema
        //del usuario Secretaria Academica cuando recibe nuevas solicitudes de permisos
        public function verNotificacionSecAcademica () {
            $this->notificacionesModel = new Notificaciones();  
            $this->data = $this->notificacionesModel->verNotificacionSecAcademica();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }


        //verNotificacionJefes Función para listar las notificaciones en el sistema
        //del usuario Jefe Departamento cuando recibe nuevas solicitudes de permisos
        //segun cada coordinador de cada departamento
        public function verNotificacionJefes () {
            $this->notificacionesModel = new Notificaciones();  
            $this->data = $this->notificacionesModel->verNotificacionJefes();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }


        //verNotificacionDecano Función para listar las notificaciones en el sistema
        //del usuario decano cuando recibe nuevas solicitudes de permisos
        public function verNotificacionDecano () {
            $this->notificacionesModel = new Notificaciones();  
            $this->data = $this->notificacionesModel->verNotificacionDecano();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }


        //verNotificacionEstratega Función para listar las notificaciones en el sistema
        //del usuario estratega cuando recibe nuevos informes
        public function verNotificacionEstratega () {
            $this->notificacionesModel = new Notificaciones();  
            $this->data = $this->notificacionesModel->verNotificacionEstratega();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }


        //verNotificacionCoordinador Función para listar las notificaciones en el sistema
        //del usuario coordinador cuando recibe nuevos informes
        public function verNotificacionCoordinador () {
            $this->notificacionesModel = new Notificaciones();  
            $this->data = $this->notificacionesModel->verNotificacionCoordinador();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }


        //verNotificacionActividadesJefes Función para listar las notificaciones en el sistema
        //del usuario jefe respecto a actividades
        public function verNotificacionActividadesJefes () {
            $this->notificacionesModel = new Notificaciones();  
            $this->data = $this->notificacionesModel->verNotificacionActividadesJefes();

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