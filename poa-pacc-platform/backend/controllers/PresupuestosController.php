<?php
    require_once('../../helpers/Respuesta.php');
    require_once('../../models/ControlPresupuestoActividad.php');
    class PresupuestosController {
        private $controlPresupuestoActividadModel;
        private $data;

        public function registrarPresupuestoAnual ($presupuestoAnual, $estadoPresupuesto, $fechaPresupuesto) {
            $this->controlPresupuestoActividadModel = new ControlPresupuestoActividad(); 
            $this->controlPresupuestoActividadModel->setPresupuestoAnual($presupuestoAnual);   
            $this->controlPresupuestoActividadModel->setEstadoPresupuestoAnual($estadoPresupuesto);
            $this->controlPresupuestoActividadModel->setFechaPresupuestoAnual($fechaPresupuesto);

            $this->data = $this->controlPresupuestoActividadModel->registrarPresupuesto();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function listarPresupuestos() {
            $this->controlPresupuestoActividadModel = new ControlPresupuestoActividad();    

            $this->data = $this->controlPresupuestoActividadModel->getPresupuestosAnuales();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function verificaPresupuestoAntesDeModif($idPresupuestoAnual) {
            $this->controlPresupuestoActividadModel = new ControlPresupuestoActividad();    
            $this->controlPresupuestoActividadModel->setIdControlPresupuestoActividad($idPresupuestoAnual);

            $this->data = $this->controlPresupuestoActividadModel->verificarPresupuestoModificar();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function cambiaPresupuesto ($idPresupuestoAnual, $presupuestoAnual, $estadoPresupuesto) {
            $this->controlPresupuestoActividadModel = new ControlPresupuestoActividad();    
            $this->controlPresupuestoActividadModel->setIdControlPresupuestoActividad($idPresupuestoAnual);
            $this->controlPresupuestoActividadModel->setPresupuestoAnual($presupuestoAnual);
            $this->controlPresupuestoActividadModel->setEstadoPresupuestoAnual($estadoPresupuesto);

            $this->data = $this->controlPresupuestoActividadModel->modificaPresupuesto();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function modificaEstadoLlenadoPresupuesto ($idPresupuestoAnual, $estadoPresupuesto) {
            $this->controlPresupuestoActividadModel = new ControlPresupuestoActividad();
            if ($estadoPresupuesto == 1) {
                $this->controlPresupuestoActividadModel->setEstadoLlenadoActividades(0);
            } else {
                $this->controlPresupuestoActividadModel->setEstadoLlenadoActividades(1);
            } 
            $this->controlPresupuestoActividadModel->setIdControlPresupuestoActividad($idPresupuestoAnual);
            $this->data = $this->controlPresupuestoActividadModel->cambiarEstadoPresupuesto();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function listarAniosPresupuestos () {
            $this->controlPresupuestoActividadModel = new ControlPresupuestoActividad();
            $this->data = $this->controlPresupuestoActividadModel->getAniosPresupuestoPoa();

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