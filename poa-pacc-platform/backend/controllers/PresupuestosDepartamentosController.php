<?php
    require_once('../../helpers/Respuesta.php');
    require_once('../../models/PresupuestoDepartamento.php');
    class PresupuestosController {
        private $presupuestoDepartamentoModel;
        private $data;

        public function __construct() {
            $this->presupuestoDepartamentoModel = new PresupuestoDepartamento();
        }
        public function listarPresupuestosDepartamentos() {
            $this->data = $this->presupuestoDepartamentoModel->getPresupuestoDeptos();
            
            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function listarInformacionPresupuestosDepartamentos() {
            $this->data = $this->presupuestoDepartamentoModel->getInformacionPresupuestoAnual();
            
            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function registrarNuevoPresupuestoDepartamento ($montoPresupuesto, $idControlPresupuestoActividad, $idDepartamento) {
            $this->presupuestoDepartamentoModel->setIdControlPresupuestoActividad($idControlPresupuestoActividad);
            $this->presupuestoDepartamentoModel->setIdDepartamento($idDepartamento);
            $this->presupuestoDepartamentoModel->setMontoPresupuesto($montoPresupuesto);
            $this->data = $this->presupuestoDepartamentoModel->registrarPresupuestoDepartamento();
            
            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function modifcarPresupuestoDepartamento($montoPresupuesto, $idControlPresupuestoActividad, $idDepartamento) {
            $this->presupuestoDepartamentoModel->setIdControlPresupuestoActividad($idControlPresupuestoActividad);
            $this->presupuestoDepartamentoModel->setIdDepartamento($idDepartamento);
            $this->presupuestoDepartamentoModel->setMontoPresupuesto($montoPresupuesto);
            $this->data = $this->presupuestoDepartamentoModel->modificarPresupuesto();
            
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