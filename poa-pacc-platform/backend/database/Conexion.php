<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . '/poa-pacc/backend/config/config.php');
    Class Conexion {
        private $host;
        private $dataBase;
        private $user;
        private $password;
        private $charset;
        private $conexion;

        public function __construct() {
            $this->host = constant('HOST');
            $this->dataBase = constant('DB');
            $this->user = constant('USER');
            $this->password = constant('PASSWORD');
            $this->charset = constant('CHARSET');
        }

        // Metodo que realiza la conexion
        public function connect () {
            try{
                $this->conexion = "mysql:host=" . $this->host . ";dbname=" . $this->dataBase . ";charset=" . $this->charset;
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_EMULATE_PREPARES => true
                    //PDO::MYSQL_ATTR_INIT_COMMAND => "SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));"
                ];
                
                $this->conexion = new PDO($this->conexion, $this->user, $this->password, $options);
                //$this->conexion->exec("SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");


                // Fixeando un fallo del group by, esto es temporal, por que no tocare el servidor ya qu eno tengo acceso
                // $initialquery = $this->conexion->prepare("");
                // $initialquery->execute();

                //echo('Conexión a MYSQL BD exitosa');
                return $this->conexion;
            }catch(PDOException $e){
                echo('Error connection: ' . $e->getMessage());
                die();
            }
        }
    }
    // Testeo de la conexion
    //$miConexion = new Conexion();
    //$miConexion->connect();
?>