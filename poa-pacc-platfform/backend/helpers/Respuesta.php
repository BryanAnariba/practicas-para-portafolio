<?php
    class Respuesta {
        private $codigoEstadoHttp;
        private $data;
        private $respuesta;

        public function __construct($data) {
            $this->codigoEstadoHttp = $data['status'];
            $this->data = $data['data'];
        }

        public function respuestaPeticion () {
            http_response_code($this->codigoEstadoHttp);
            $this->respuesta = array(
                'status' => $this->codigoEstadoHttp,
                'data' => $this->data
            );
            echo json_encode($this->respuesta);
        }
    }
?>