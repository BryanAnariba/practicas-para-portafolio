<?php 
    class Imagen {
        private $imagen;
        private $rutaDestino;
        private $rutaUpload;
        private $nombreImagen;
        private $tipoImagen;
        private $tamanio;
        private $carpeta;

        public function getImagen() {
            return $this->imagen;
        }

        public function setImagen($imagen) {
            $this->imagen = $imagen;
            return $this;
        }
        
        public function verificaExistenciaCarpeta ($directorio) {
            mkdir($directorio, 0777, true);
        }

        public function __construct($carpeta) {
            $this->carpeta = $carpeta;
            //ruta destino imagen DOCUMENT_ROOT->HTDOCS
            $this->rutaUpload = "../../../backend/uploads";

            if (!file_exists($this->rutaUpload)) {
                $this->verificaExistenciaCarpeta($this->rutaUpload);
            } 
            if (!file_exists($this->rutaUpload . '/images')) {
                $this->verificaExistenciaCarpeta($this->rutaUpload . '/images');
            } 
            if (!file_exists($this->rutaUpload . '/images/' . $this->carpeta)) {
                $this->verificaExistenciaCarpeta($this->rutaUpload . '/images/' . $this->carpeta);
            } 

            $this->rutaDestino = ($this->rutaUpload . '/images/' . $this->carpeta . '/');
        }

        // Sube la imagen 
        public function subirImagen () {
            
            //si existe un fichero con nombre file que capture lo siguiente
            if(isset($this->imagen['avatarUsuario']) || isset($_SESSION['codigoEmpleado'])) {
                //Propiedades de la imagen
                $this->nombreImagen = $this->imagen['avatarUsuario']['name'];
                $this->tipoImagen = $this->imagen['avatarUsuario']['type'];
                $this->tamanio = $this->imagen['avatarUsuario']['size'];

                //si el tamaño de la imagen es mayor a 1 MB
                if($this->tamanio <= 1000000) {
                    //si los archivos no concuerdan con este formato que envie un mensaje
                    if (
                        $this->tipoImagen != 'image/jpg' && 
                        $this->tipoImagen != 'image/jpeg' && 
                        $this->tipoImagen != 'image/png' && 
                        $this->tipoImagen != 'image/gif'
                    ) {
                        return false;
                    } else { //caso contrario que procese la imagen
                        

                        //primer parametro la ruta temporal donde se almacena y segundo la carpeta de destino
                        //la movemos del directorio temporal al escogido
                        $ruta = $this->rutaDestino . date("YmdHis") . '-' . $this->nombreImagen;
                        move_uploaded_file($this->imagen['avatarUsuario']['tmp_name'] , $ruta);
                        return $ruta;
                    }

                } else {
                    return false;              
                }
            } else {//caso contrario
                return false;
            }
        }

        public function removerImagen ($nombreArchivo) {
            if (file_exists($nombreArchivo)) {
                $remover = unlink($nombreArchivo);
                if ($remover) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return true;
            }
        }
    }