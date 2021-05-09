<?php
    require_once('../../helpers/Respuesta.php');
    require_once('../../models/DescripcionAdministrativa.php');
    class DescripcionAdministrativaController {
        private $descripcionAdministrativaModel;
        private $data;

        public function __construct () {
            $this->descripcionAdministrativaModel = new DescripcionAdministrativa();
        }

        public function listarDescripcionesAdministrativas ($idActividad, $idDimensionAdmin) {
            $this->descripcionAdministrativaModel->setIdDimensionAdministrativa($idDimensionAdmin);
            $this->descripcionAdministrativaModel->setIdActividad($idActividad);

            $this->data = $this->descripcionAdministrativaModel->getDescripcionAdministrativaPorActividad();
            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function insertaNuevaDescripcionAdministrativa ($idObjetoGasto, $idTipoPresupuesto, $idActividad, $idDimension, $nombreActividad, $cantidad, $costo, $costoTotal, $mesRequerido, $descripcion, $unidadMedida) {
            $this->descripcionAdministrativaModel->setIdObjetoGasto($idObjetoGasto);
            $this->descripcionAdministrativaModel->setIdTipoPresupuesto($idTipoPresupuesto);
            $this->descripcionAdministrativaModel->setIdActividad($idActividad);
            $this->descripcionAdministrativaModel->setIdDimensionAdministrativa($idDimension);
            $this->descripcionAdministrativaModel->setNombreActividad($nombreActividad);
            $this->descripcionAdministrativaModel->setCantidad($cantidad);
            $this->descripcionAdministrativaModel->setCosto($costo);
            $this->descripcionAdministrativaModel->setCostoTotal($costoTotal);
            $this->descripcionAdministrativaModel->setMesRequerido($mesRequerido);
            $this->descripcionAdministrativaModel->setDescripcion($descripcion);
            if (!empty($unidadMedida)) {
                $this->descripcionAdministrativaModel->setUnidadDeMedida($unidadMedida);
            } else {
                $this->descripcionAdministrativaModel->setUnidadDeMedida('Unidad');
            }

            $this->data = $this->descripcionAdministrativaModel->insertaDescripcionAdministrativa();
            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function modificaDescripcionAdministrativa ($idDescripcionAdministrativa, $idObjetoGasto, $idTipoPresupuesto, $idActividad, $idDimension, $nombreActividad, $cantidad, $costo, $costoTotal, $mesRequerido, $descripcion, $unidadMedida) {
            $this->descripcionAdministrativaModel->setIdDescripcionAdministrativa($idDescripcionAdministrativa);
            $this->descripcionAdministrativaModel->setIdObjetoGasto($idObjetoGasto);
            $this->descripcionAdministrativaModel->setIdTipoPresupuesto($idTipoPresupuesto);
            $this->descripcionAdministrativaModel->setIdActividad($idActividad);
            $this->descripcionAdministrativaModel->setIdDimensionAdministrativa($idDimension);
            $this->descripcionAdministrativaModel->setNombreActividad($nombreActividad);
            $this->descripcionAdministrativaModel->setCantidad($cantidad);
            $this->descripcionAdministrativaModel->setCosto($costo);
            $this->descripcionAdministrativaModel->setCostoTotal($costoTotal);
            $this->descripcionAdministrativaModel->setMesRequerido($mesRequerido);
            $this->descripcionAdministrativaModel->setDescripcion($descripcion);
            if (!empty($unidadMedida)) {
                $this->descripcionAdministrativaModel->setUnidadDeMedida($unidadMedida);
            } else {
                $this->descripcionAdministrativaModel->setUnidadDeMedida('Unidades');
            }
            

            $this->data = $this->descripcionAdministrativaModel->modifDescripcionAdministrativa();
            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }

        public function listaDescripcion ($idDescripcionAdministrativa) {
            $this->descripcionAdministrativaModel->setIdDescripcionAdministrativa($idDescripcionAdministrativa);
            $this->data = $this->descripcionAdministrativaModel->generaDescripcionAdmin();
            $_Respuesta = new Respuesta($this->data);
            $_Respuesta->respuestaPeticion();
        }
        
        public function modificaDescripcion () {

        }

        public function getDataTotalParaCompraInventario ($anioPlaninicacion, $valorInicial, $valorFinal) {
            $this->descripcionAdministrativaModel->setAnioPlanificacion($anioPlaninicacion);
            $this->descripcionAdministrativaModel->setValorInicial($valorInicial);
            $this->descripcionAdministrativaModel->setValorFinal($valorFinal);
            $articulos = $this->descripcionAdministrativaModel->getItemsParaCompraInventario();
            $cantidadRegistros = $this->descripcionAdministrativaModel->getCantidadRegistrosTotales();
                echo json_encode(array(
                    'status' => SUCCESS_REQUEST, 
                    'totalItemsPOA' => $cantidadRegistros,
                    'articulos' => $articulos));
        
        }

        public function getDataParaCompraInventarioPorDepto ($anioPlaninicacion, $valorInicial, $valorFinal, $idDepartamento) {
            $this->descripcionAdministrativaModel->setAnioPlanificacion($anioPlaninicacion);
            $this->descripcionAdministrativaModel->setValorInicial($valorInicial);
            $this->descripcionAdministrativaModel->setValorFinal($valorFinal);
            $this->descripcionAdministrativaModel->setIdDepartamento($idDepartamento);
            $articulos = $this->descripcionAdministrativaModel->getItemsCompraPorDeparamento();
            $cantidadRegistros = $this->descripcionAdministrativaModel->getCantidadRegistrosPorDepartamento();

            
                echo json_encode(array(
                    'status' => SUCCESS_REQUEST, 
                    'totalItemsPorDepto' => $cantidadRegistros,
                    'articulos' => $articulos));
            
        }

        public function getDataParaCompraInventarioPorObjeto ($anioPlaninicacion, $valorInicial, $valorFinal, $idObjetoGasto) {
            $this->descripcionAdministrativaModel->setAnioPlanificacion($anioPlaninicacion);
            $this->descripcionAdministrativaModel->setValorInicial($valorInicial);
            $this->descripcionAdministrativaModel->setValorFinal($valorFinal);
            $this->descripcionAdministrativaModel->setIdObjetoGasto($idObjetoGasto);
            $articulos = $this->descripcionAdministrativaModel->getItemsCompraPorObjeto();
            $cantidadRegistros = $this->descripcionAdministrativaModel->getCantidadRegistrosPorObjeto();
                echo json_encode(array(
                    'status' => SUCCESS_REQUEST, 
                    'totalItemsPorObjetoGasto' => $cantidadRegistros,
                    'articulos' => $articulos));
            
        }

        public function getDataParaCompraInventarioPorObjetoDepto ($anioPlaninicacion, $valorInicial, $valorFinal, $idObjetoGasto, $idDepartamento) {
            $this->descripcionAdministrativaModel->setAnioPlanificacion($anioPlaninicacion);
            $this->descripcionAdministrativaModel->setValorInicial($valorInicial);
            $this->descripcionAdministrativaModel->setValorFinal($valorFinal);
            $this->descripcionAdministrativaModel->setIdObjetoGasto($idObjetoGasto);
            $this->descripcionAdministrativaModel->setIdDepartamento($idDepartamento);
            $articulos = $this->descripcionAdministrativaModel->getItemsCompraPorObjetoYDepto();
            $cantidadRegistros = $this->descripcionAdministrativaModel->getCantidadRegistrosPorObjetoYDepto();

                echo json_encode(array(
                    'status' => SUCCESS_REQUEST, 
                    'itemsPorObjetoGastoYDepto' => $cantidadRegistros,
                    'articulos' => $articulos));
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
?>