<?php
    require_once('../../helpers/Respuesta.php');
    require_once('../../models/RecibirInformes.php');
    class RecibirInformesController {
        private $recibirInformesModel;
        private $data;
     

        //registrarInforme
        public function registrarInforme($tituloInforme,
                                        $descripcionInforme,
                                        $informe
                                        ) {
        
            $this->recibirInformesModel = new RecibirInformes();    
            
            $this->data = $this->recibirInformesModel->setTituloInforme($tituloInforme);
            $this->data = $this->recibirInformesModel->setDescripcionInforme($descripcionInforme);
            $this->data = $this->recibirInformesModel->setInforme($informe);

            $this->data = $this->recibirInformesModel->registrarInforme();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }



        //listarInformesAprobados
        public function listarInformesAprobados() {
        
            $this->recibirInformesModel = new RecibirInformes();    
            
            $this->data = $this->recibirInformesModel->listarInformesAprobados();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }


        //listarInformesPendientes
        public function listarInformesPendientes() {
        
            $this->recibirInformesModel = new RecibirInformes();    
            
            $this->data = $this->recibirInformesModel->listarInformesPendientes();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }



        //verDescripcionPorId
        public function verDescripcionPorId($idInforme) {
            $this->recibirInformesModel = new RecibirInformes();    
            
            $this->data = $this->recibirInformesModel->setIdInforme($idInforme);
            $this->data = $this->recibirInformesModel->ObtenerDescripcionPorId();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }




        //verInformePorId
        public function verInformePorId($idInforme) {
            $this->recibirInformesModel = new RecibirInformes();    
            
            $this->data = $this->recibirInformesModel->setIdInforme($idInforme);
            $this->data = $this->recibirInformesModel->verInformePorId();

            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }


        //modificaEstadoInforme
        public function modificaEstadoInforme ($idInforme) {
            $this->recibirInformesModel = new RecibirInformes();  

            $this->data = $this->recibirInformesModel->setIdInforme($idInforme);
            $this->data = $this->recibirInformesModel->modificaEstadoInforme();

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