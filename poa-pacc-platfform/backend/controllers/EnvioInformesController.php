<?php
    require_once('../../helpers/Respuesta.php');
    require_once('../../models/EnvioInformes.php');
    class EnvioInformesController {
        private $envioInformesModel;
        private $data;
     

        //registrarInforme
        public function registrarInforme($tituloInforme,
                                        $descripcionInforme,
                                        $informe
                                        ) {
        
            $this->envioInformesModel = new EnvioInformes();    
            
            $this->data = $this->envioInformesModel->setTituloInforme($tituloInforme);
            $this->data = $this->envioInformesModel->setDescripcionInforme($descripcionInforme);
            $this->data = $this->envioInformesModel->setInforme($informe);

            $this->data = $this->envioInformesModel->registrarInforme();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }



        //listarInformesAprobados
        public function listarInformesAprobados() {
        
            $this->envioInformesModel = new EnvioInformes();    
            
            $this->data = $this->envioInformesModel->listarInformesAprobados();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }


        //listarInformesPendientes
        public function listarInformesPendientes() {
        
            $this->envioInformesModel = new EnvioInformes();    
            
            $this->data = $this->envioInformesModel->listarInformesPendientes();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }



        //verDescripcionPorId
        public function verDescripcionPorId($idInforme) {
            $this->envioInformesModel = new EnvioInformes();    
            
            $this->data = $this->envioInformesModel->setIdInforme($idInforme);
            $this->data = $this->envioInformesModel->ObtenerDescripcionPorId();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }




        //verInformePorId
        public function verInformePorId($idInforme) {
            $this->envioInformesModel = new EnvioInformes();    
            
            $this->data = $this->envioInformesModel->setIdInforme($idInforme);
            $this->data = $this->envioInformesModel->verInformePorId();

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