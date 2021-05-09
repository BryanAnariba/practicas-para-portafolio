<?php
    require_once('../../config/config.php');
    class GeneradorClaves {
        private $tamanioClave;
        private $claveGenerada;

        public function __construct() {
            $this->tamanioClave = MIN_TAMANIO_CLAVE;
        }
        
        function generarClave() {
            // Conjunto de caracteres a usar para generar el key
            $charset = "abcdefghijklmnopqrstuvwxyzABCDEFJHIJKLMNOPQRSTUVWXYZ1234567890@$";
            for ($i=0;$i<$this->tamanioClave;$i++){
                $aleatorio = rand() % strlen($charset);
    
                //va añadiendo un caracter aleatorio por cada iteracion
                $this->claveGenerada .= substr($charset,$aleatorio,1);
            }
            return $this->claveGenerada;
        }
    }
?>