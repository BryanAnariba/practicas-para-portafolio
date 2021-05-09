<?php
    require_once('../../config/config.php');
    require_once('../../database/Conexion.php');
    require_once('../../helpers/Respuesta.php');
    require_once('../../models/Departamentos.php');

    class DepartamentoController {
        
        private $data;
        
        public function VerDepartamentos () {
            $Departamentos = new Departamentos();
            //$this->data = $this->departamentoModel->getDepartamentos();
            $this->data = $Departamentos->getDepartamentos();
            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }


        public function obtenerEstados () {

            $Departamentos = new Departamentos();

            $this->data = $Departamentos->getEstados();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }


        public function registrarDepartamento ($idEstadoDepartamento,
                                                $nombreDepartamento,                                
                                                $telefonoDepartamento, 
                                                $abreviaturaDepartamento,
                                                $correoDepartamento) {
            
            $Departamentos = new Departamentos();

            $Departamentos->setIdDepartamento(0);
            $Departamentos->setIdEstadoDepartamento($idEstadoDepartamento); 
            $Departamentos->setNombreDepartamento($nombreDepartamento);
            $Departamentos->setTelefonoDepartamento($telefonoDepartamento);
            $Departamentos->setAbreviaturaDepartamento($abreviaturaDepartamento);
            $Departamentos->setCorreoDepartamento($correoDepartamento);
            
            $this->data = $Departamentos->registraDepartamento();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }


        public function obtenerDepartamentos () {

            $Departamentos = new Departamentos();

            $this->data = $Departamentos->Departamentos();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }


        public function obtenerDepartamentoPorId ($idDepartamento) {

            $Departamentos = new Departamentos();

            $Departamentos->setIdDepartamento($idDepartamento);
            $this->data = $Departamentos->getDepartamentosPorId();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }
        

        public function modificarDepartamento ($idDepartamento,
                                                $nombreDepartamento,
                                                $idEstadoDepartamento,
                                                $abreviaturaDepartamento,
                                                $telefonoDepartamento,    
                                                $correoDepartamento
                                                ) {
            
            $Departamentos = new Departamentos();

            $Departamentos->setIdDepartamento($idDepartamento);
            $Departamentos->setNombreDepartamento($nombreDepartamento);
            $Departamentos->setIdEstadoDepartamento($idEstadoDepartamento);
            $Departamentos->setAbreviaturaDepartamento($abreviaturaDepartamento);
            $Departamentos->setTelefonoDepartamento($telefonoDepartamento);
            $Departamentos->setCorreoDepartamento($correoDepartamento);
             

            $this->data = $Departamentos->modificarDepartamento();

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