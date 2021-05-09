<?php
    if (!isset($_SESSION)) {
        session_start();
    }
    require_once('../../models/Persona.php');
    require_once('../../config/config.php');
    require_once('../../validators/validators.php');
    require_once('../../helpers/GeneradorClaves.php');
    require_once('../../helpers/Email.php');
    require_once('../../database/Conexion.php');
    class Usuario extends Persona {

        // Propiedades clase
        private $idTipoUsuario;
        private $idDepartamento;
        private $idEstadoUsuario;
        private $nombreUsuario;
        private $correoInstitucional;
        private $codigoEmpleado;
        private $passwordEmpleado;
        private $avatarUsuario;

        private $conexionBD;
        private $consulta;
        private $tablaBaseDatos;

        // constructor
        public function __construct() {
            $this->tablaBaseDatos = TBL_USUARIO;
        }

        // Metodos setters y getters
        public function getIdTipoUsuario() {
            return $this->idTipoUsuario;
        }

        public function setIdTipoUsuario($idTipoUsuario) {
            $this->idTipoUsuario = $idTipoUsuario;
            return $this;
        }

        public function getIdDepartamento() {
            return $this->idDepartamento;
        }

        public function setIdDepartamento($idDepartamento) {
            $this->idDepartamento = $idDepartamento;
            return $this;
        }

        public function getIdEstadoUsuario() {
            return $this->idEstadoUsuario;
        }

        public function setIdEstadoUsuario($idEstadoUsuario) {
            $this->idEstadoUsuario = $idEstadoUsuario;
            return $this;
        }

        public function getNombreUsuario() {
            return $this->nombreUsuario;
        }

        public function setNombreUsuario($nombreUsuario) {
            $this->nombreUsuario = $nombreUsuario;
            return $this;
        }

        public function getCorreoInstitucional() {
            return $this->correoInstitucional;
        }

        public function setCorreoInstitucional($correoInstitucional) {
            $this->correoInstitucional = $correoInstitucional;
            return $this;
        }

        public function getCodigoEmpleado() {
            return $this->codigoEmpleado;
        }

        public function setCodigoEmpleado($codigoEmpleado) {
            $this->codigoEmpleado = $codigoEmpleado;
            return $this;
        }

        public function getPasswordEmpleado() {
            return $this->passwordEmpleado;
        }

        public function setPasswordEmpleado($passwordEmpleado) {
            $this->passwordEmpleado = $passwordEmpleado;
            return $this;
        }

        public function getAvatarUsuario() {
            return $this->avatarUsuario;
        }

        public function setAvatarUsuario($avatarUsuario) {
            $this->avatarUsuario = $avatarUsuario;
            return $this;
        }
        //                                                 Metodos clase Usuario -> America/Tegucigalpa

        private function insertarToken ($idUsuario) {
            $banderaToken = true;
            $token = bin2hex(openssl_random_pseudo_bytes(16, $banderaToken));
            $vigenciaToken = new DateTime();
            $vigenciaToken->add(new DateInterval(TIEMPO_VIDA_TOKEN));
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();

                $this->consulta->prepare("
                    set @persona = $idUsuario;
                ")->execute();
                $this->consulta->prepare("
                    set @tipoBitacora = 4;
                ")->execute();

                $stmt = $this->consulta->prepare('CALL SP_GENERAR_TOKEN_ACCESO(:idUsuario, :token, :fecha)');
                $stmt->bindValue(':idUsuario', $idUsuario);
                $stmt->bindValue(':token', $token);
                $stmt->bindValue(':fecha',$vigenciaToken->format('Y-m-d h:i:s'));
                if ($stmt->execute()) {
                    $_SESSION['access-token'] = $token;
                    return true;
                } else {
                    return false;
                }
            } catch (PDOException $ex) {
                return array(
                    'status'=> INTERNAL_SERVER_ERROR,
                    'data' => array('message' => $ex->getMessage())
                );
            } finally {
                $this->conexionBD = null;
            } 
        }

        public function destruirToken () {
            if (isset($_SESSION['idUsuario']) && isset($_SESSION['access-token']) && isset($_SESSION['tipoUsuario']) && isset($_SESSION['nombrePersona']) && isset($_SESSION['apellidoPersona']) && isset($_SESSION['correoInstitucional'])) {
                try {
                    $this->conexionBD = new Conexion();
                    $this->consulta = $this->conexionBD->connect();
                    
                    $token = $_SESSION['access-token'];

                    $this->consulta->prepare("
                        set @persona = {$_SESSION['idUsuario']};
                    ")->execute();
                    $this->consulta->prepare("
                        set @tipoBitacora = 4;
                    ")->execute();

                    $stmt = $this->consulta->prepare('CALL SP_REMOVER_TOKEN(:idUsuario, :token)');
                    $stmt->bindValue(':idUsuario', $_SESSION['idUsuario']);
                    $stmt->bindValue(':token', $_SESSION['access-token']);
                    if ($stmt->execute()) {
                        return array(
                            'status'=> SUCCESS_REQUEST,
                            'data' => array('message' => 'Saliste del sistema exitosamente')
                        );
                        //require_once('../api/destruir-sesiones.php');
                    } else {
                        return array(
                            'status'=> BAD_REQUEST,
                            'data' => array('message' => 'Ha ocurrido un erro al hacer el cierre de sesion')
                        );
                    }
                } catch (PDOException $ex) {
                    return array(
                        'status'=> INTERNAL_SERVER_ERROR,
                        'data' => array('message' => $ex->getMessage())
                    );
                } finally {
                    $this->conexionBD = null;
                } 
            } else {
                return array(
                    'status'=> SUCCESS_REQUEST,
                    'data' => array('message' => 'Su token ya ha expirado, el sistema cerro su cuenta automaticamente')
                );
            }
        }

        public function verificaEmailUsuario () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                $stmt = $this->consulta->prepare('CALL SP_VERIFICA_EMAIL_USUARIO(:emailUsuario)');
                $stmt->bindValue(':emailUsuario', $this->correoInstitucional);
                //  Si la consulta se ejecuto y las filas que retorna la busqueda del email es 0 guarde si no notifique
                if ($stmt->execute() && ($stmt->rowCount() == 0)) {
                    return true;
                } else {
                    return false;
                }
            } catch (PDOException $ex) {
                return array(
                    'status'=> INTERNAL_SERVER_ERROR,
                    'data' => array('message' => $ex->getMessage())
                );
            } finally {
                $this->conexionBD = null;
            }            
        }

        public function registrarUsuario () {
            if (validaCampoNombreApellido($this->getNombrePersona(), 1, 80) && validaCampoNombreApellido($this->getApellidoPersona(), 1, 80) && is_int($this->getIdLugar()) && is_int($this->idTipoUsuario) && is_int($this->idDepartamento) && validaCampoEmail($this->correoInstitucional) && validaCampoCodigoEmpleado($this->codigoEmpleado)) {
                // Clave sin hashear aun solo generada
                $generadorClaves = new GeneradorClaves();
                $password = $generadorClaves->generarClave();
                // Clave Hasheada
                $passwordEncriptada = password_hash($password, PASSWORD_DEFAULT);
                $noEmailEnUso = $this->verificaEmailUsuario();
                if ($noEmailEnUso) {
                    try {
                        $idUsuario = parent::registrarPersona();
                        $this->conexionBD = new Conexion();
                        $this->consulta = $this->conexionBD->connect();

                        $this->consulta->prepare("
                            set @persona = {$_SESSION['idUsuario']};
                        ")->execute();

                        if ($idUsuario != null) {
                            $stmt = $this->consulta->prepare('CALL SP_INSERTA_USUARIO(:idUsuario, :idTUsuario, :idDepto, :idEstado, :usuario, :correo, :codigo, :password)');
                            $stmt->bindValue(':idUsuario', $idUsuario);
                            $stmt->bindValue(':idTUsuario', $this->idTipoUsuario);
                            $stmt->bindValue(':idDepto', $this->idDepartamento);
                            $stmt->bindValue(':idEstado', ESTADO_ACTIVO);
                            $stmt->bindValue(':usuario', $this->nombreUsuario);
                            $stmt->bindValue(':correo', $this->correoInstitucional);
                            $stmt->bindValue(':codigo', $this->codigoEmpleado);
                            $stmt->bindValue(':password', $passwordEncriptada);
    
                            if ($stmt->execute()) {
                                $email = new Email();
                                $email->setEmailDestino($this->correoInstitucional);
                                $email->setNombreUsuario($this->nombreUsuario);
                                $email->setNombre($this->nombrePersona);
                                $email->setApellido($this->apellidoPersona);
                                $email->setHeaderMensaje('Bienvenido Al Sistema POA PACC');
                                $email->setTituloMensaje('Estas son tus credenciales de acceso al sistema');
                                $email->setContenido($password);
                                $enviado = $email->notificarViaCorreo();
                                if ($enviado) {
                                    return array(
                                        'status'=> SUCCESS_REQUEST,
                                        'data' => array('message' => 'Usuario ' . $this->correoInstitucional . ' insertado con exito, se envio una notificacion con credenciales via correo ' . $password ) 
                                    );
                                } else {
                                    return array(
                                        'status'=> BAD_REQUEST,
                                        'data' => array('message' => 'Ha ocurrido un error, el correo no fue enviado')
                                    );
                                }
                            } else {
                                return array(
                                    'status'=> INTERNAL_SERVER_ERROR,
                                    'data' => array('message' => 'El registro no se pudo completar')
                                );
                            }
    
                        } else {
                            return array(
                                'status'=> SUCCESS_REQUEST,
                                'data' => array('message' => 'El registro no se pudo completar')
                            );
                        }
                    } catch (PDOException $ex) {
                        return array(
                            'status'=> INTERNAL_SERVER_ERROR,
                            'data' => array('message' => $idUsuario . $ex->getMessage())
                        );
                    } finally {
                        $this->conexionBD = null;
                    } 
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'El correo institucional '. $this->correoInstitucional . ' ya se encuentra en uso')
                    );
                }
            } else {
                return array(
                    'status'=> BAD_REQUEST,
                    'data' => array('message' => 'Ha ocurrido un error, los datos insertados son erroneos')
                );
            }
        }

        public function getInformacionUsuarios () {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();
                
                $stmt = $this->consulta->prepare('CALL SP_LISTAR_USUARIOS()');
                if ($stmt->execute() && ($stmt->rowCount() >= 1)) {
                    return array(
                        'status' => SUCCESS_REQUEST,
                        'data' => $stmt->fetchAll(PDO::FETCH_OBJ)
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al listar los usuarios del sistema')
                    );
                }
            } catch (PDOException $ex) {
                return array(
                    'status'=> INTERNAL_SERVER_ERROR,
                    'data' => array('message' => $ex->getMessage())
                );
            } finally {
                $this->conexionBD = null;
            } 
        }

        public function modificarEstadoUsuario() {
            if (is_int($this->idPersona) && is_int($this->idEstadoUsuario)) {
                if ($this->idEstadoUsuario == ESTADO_ACTIVO) {
                    $this->idEstadoUsuario = ESTADO_INACTIVO;
                } else {
                    $this->idEstadoUsuario = ESTADO_ACTIVO;
                }
                try {
                    $this->conexionBD = new Conexion();
                    $this->consulta = $this->conexionBD->connect();

                    $this->consulta->prepare("
                        set @persona = {$_SESSION['idUsuario']};
                    ")->execute();
                    $this->consulta->prepare("
                        set @tipoBitacora = 2;
                    ")->execute();

                    $stmt = $this->consulta->prepare('CALL SP_CAMBIA_ESTADO_USUARIO(:idPersonaUsuario,:idEstadoUsuario)');
                    $stmt->bindValue(':idPersonaUsuario', $this->idPersona);
                    $stmt->bindValue(':idEstadoUsuario', $this->idEstadoUsuario);
                    if ($stmt->execute()) {
                        return array(
                            'status'=> SUCCESS_REQUEST,
                            'data' => array('message' => 'El estado del usuario se actualizo con exito')
                        );
                    } else {
                        return array(
                            'status'=> INTERNAL_SERVER_ERROR,
                            'data' => array('message' => 'Ha ocurrido un error, el estado del usuario se no se pudo actualizar')
                        );
                    }
                } catch (PDOException $ex) {
                    return array(
                        'status'=> INTERNAL_SERVER_ERROR,
                        'data' => array('message' => $ex->getMessage())
                    );
                } finally {
                    $this->conexionBD = null;
                } 
            } else {
                return array(
                    'status'=> BAD_REQUEST,
                    'data' => array('message' => 'Ha ocurrido un error al actualizar el estado del usuario')
                );
            }
        }

        public function modificaInformacionGeneralUsuario() {
            if (validaCampoNombreApellido($this->getNombrePersona(), 1, 80) && validaCampoNombreApellido($this->getApellidoPersona(), 1, 80) && is_int($this->idTipoUsuario) && is_int($this->idDepartamento) && validaCampoCodigoEmpleado($this->codigoEmpleado) && is_int($this->idPersona)) {
                $resultadoConsulta = parent::modificaInformacionGeneralPersona();
                if ($resultadoConsulta) {
                    try {
                        $this->conexionBD = new Conexion();
                        $this->consulta = $this->conexionBD->connect();

                        $this->consulta->prepare("
                            set @persona = {$_SESSION['idUsuario']};
                        ")->execute();
                        $this->consulta->prepare("
                            set @tipoBitacora = 2;
                        ")->execute();

                        $stmt = $this->consulta->prepare('CALL SP_MODIF_DATOS_GEN_USUARIO(:idUsuario, :idDepto, :idRole, :codigo)');
                        $stmt->bindValue(':idUsuario', $this->idPersona);
                        $stmt->bindValue(':idDepto', $this->idDepartamento);
                        $stmt->bindValue(':idRole', $this->idTipoUsuario);
                        $stmt->bindValue(':codigo', $this->codigoEmpleado);
                        if ($stmt->execute()) {
                            return array(
                                'status'=> SUCCESS_REQUEST,
                                'data' => array('message' => 'El usuario se actualizo exitosamente')
                            );
                        } else {
                            return array(
                                'status'=> INTERNAL_SERVER_ERROR,
                                'data' => array('message' => 'Ha ocurrido un error, el usuario se no se pudo actualizar')
                            );
                        }
                    } catch (PDOException $ex) {
                        return array(
                            'status'=> INTERNAL_SERVER_ERROR,
                            'data' => array('message' => $ex->getMessage())
                        );
                    } finally {
                        $this->conexionBD = null;
                    } 
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al actualizar el usuario')
                    );
                }
            } else {
                return array(
                    'status'=> BAD_REQUEST,
                    'data' => array('message' => 'Ha ocurrido un error, los datos ha modificar son erroneos')
                );
            }
        }

        public function reenviarCredenciales () {
            if (is_int($this->getIdPersona()) && validaCampoNombreApellido($this->getNombrePersona(), 1, 80) && validaCampoNombreApellido($this->getApellidoPersona(), 1, 80) && validaCampoEmail($this->correoInstitucional)) {
                $noExisteEmail = $this->verificaEmailUsuario();
                if ($noExisteEmail == false) {
                    // Clave sin hashear aun solo generada
                    $generadorClaves = new GeneradorClaves();
                    $password = $generadorClaves->generarClave();
                    // Clave Hasheada
                    $passwordEncriptada = password_hash($password, PASSWORD_DEFAULT);
                    try {
                        $this->conexionBD = new Conexion();
                        $this->consulta = $this->conexionBD->connect();

                        if(!isset($_SESSION)){
                            $this->consulta->prepare("
                                set @persona = {$_SESSION['idUsuario']};
                            ")->execute();
                        }else{
                            $this->consulta->prepare("
                                set @persona = {$this->getIdPersona()};
                            ")->execute();
                        }
                        $this->consulta->prepare("
                            set @tipoBitacora = 5;
                        ")->execute();
                        
                        $stmt = $this->consulta->prepare('UPDATE ' . $this->tablaBaseDatos . ' SET passwordUsuario = :password WHERE idPersonaUsuario = :idPersonaUsuario');
                        $stmt->bindValue(':password', $passwordEncriptada);
                        $stmt->bindValue(':idPersonaUsuario', $this->idPersona);
                        if ($stmt->execute()) {
                            $email = new Email();
                            $email->setEmailDestino($this->correoInstitucional);
                            $email->setNombreUsuario($this->nombreUsuario);
                            $email->setNombre($this->nombrePersona);
                            $email->setApellido($this->apellidoPersona);
                            $email->setHeaderMensaje('Bienvenido Al Sistema POA PACC');
                            $email->setTituloMensaje('Reenvio de credenciales: Estas son tus credenciales de acceso al sistema');
                            $email->setContenido($password);
                            $enviado = $email->notificarViaCorreo();
                            if ($enviado) {
                                return array(
                                    'status'=> SUCCESS_REQUEST,
                                    'data' => array('message' => 'El reenvio de credenciales se realizo con exito')
                                );
                            } else {
                                return array(
                                    'status'=> BAD_REQUEST,
                                    'data' => array('message' => 'Ha ocurrido un error, el correo no fue enviado')
                                );
                            }
                        } else {
                            return array(
                                'status'=> BAD_REQUEST,
                                'data' => array('message' => 'Ha ocurrido un error, el envio de credenciales no se pudo realizar')
                            );
                        }
                    } catch (PDOException $ex) {
                        return array(
                            'status'=> INTERNAL_SERVER_ERROR,
                            'data' => array('message' => $ex->getMessage())
                        );
                    } finally {
                        $this->conexionBD = null;
                    } 
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => array('message' => 'Ha ocurrido un error, el reevio de credenciales no se pudo realizar')
                        )
                    );
                }
            } else {
                return array(
                    'status'=> BAD_REQUEST,
                    'data' => array('message' => 'Ha ocurrido un error, el reevio de credenciales no se pudo realizar')
                );
            }
        }

        public function modificaCorreo () {
            if ((is_int($this->getIdPersona()) == true) && 
                (validaCampoNombreApellido($this->getNombrePersona(), 1, 80) == true) && 
                (validaCampoNombreApellido($this->getApellidoPersona(), 1, 80) == true)&& 
                (validaCampoEmail($this->correoInstitucional) == true)
            ) {
                $noExisteEmail = $this->verificaEmailUsuario();
                if ($noExisteEmail == false) { // ->debe retornar true
                    // Clave sin hashear aun solo generada
                    $generadorClaves = new GeneradorClaves();
                    $password = $generadorClaves->generarClave();
                    // Clave Hasheada
                    $passwordEncriptada = password_hash($password, PASSWORD_DEFAULT);
                    try {
                        $this->conexionBD = new Conexion();
                        $this->consulta = $this->conexionBD->connect();

                        $this->consulta->prepare("
                            set @persona = {$_SESSION['idUsuario']};
                        ")->execute();
                        $this->consulta->prepare("
                            set @tipoBitacora = 5;
                        ")->execute();

                        $stmt = $this->consulta->prepare('UPDATE ' . $this->tablaBaseDatos . ' SET passwordUsuario = :password, correoInstitucional = :correoInstitucional WHERE idPersonaUsuario = :idPersonaUsuario');
                        $stmt->bindValue(':password', $passwordEncriptada);
                        $stmt->bindValue(':correoInstitucional', $this->correoInstitucional);
                        $stmt->bindValue(':idPersonaUsuario', $this->idPersona);
                        if ($stmt->execute()) {
                            $email = new Email();
                            $email->setEmailDestino($this->correoInstitucional);
                            $email->setNombreUsuario($this->nombreUsuario);
                            $email->setNombre($this->nombrePersona);
                            $email->setApellido($this->apellidoPersona);
                            $email->setHeaderMensaje('Bienvenido Al Sistema POA PACC');
                            $email->setTituloMensaje('Modificacion y Reenvio de credenciales: Estas son tus credenciales de acceso al sistema');
                            $email->setContenido($password);
                            $enviado = $email->notificarViaCorreo();
                            if ($enviado) {
                                return array(
                                    'status'=> SUCCESS_REQUEST,
                                    'data' => array('message' => 'La modificacion y reenvio de credenciales se realizo con exito ' . $password)
                                );
                            } else {
                                return array(
                                    'status'=> BAD_REQUEST,
                                    'data' => array('message' => 'Ha ocurrido un error, el correo no fue enviado ')
                                );
                            }
                        } else {
                            return array(
                                'status'=> BAD_REQUEST,
                                'data' => array('message' => 'Ha ocurrido un error, la modificacion y  envio de credenciales no se pudo realizar')
                            );
                        }
                    } catch (PDOException $ex) {
                        return array(
                            'status'=> INTERNAL_SERVER_ERROR,
                            'data' => array('message' => $ex->getMessage())
                        );
                    } finally {
                        $this->conexionBD = null;
                    } 
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => array('message' => 'Ha ocurrido un error, la modificacion y  envio de credenciales no se pudo realizar' . validaCampoEmail($this->correoInstitucional))
                        )
                    );
                }
            } else {
                return array(
                    'status'=> BAD_REQUEST,
                    'data' => array('message' => 'Ha ocurrido un error, la modificacion y  envio de credenciales no se pudo realizar')
                );
            }
        }
        
        
        public function logIn () {
            if (validaCampoEmail($this->correoInstitucional)){
                try {
                    $this->conexionBD = new Conexion();
                    $this->consulta = $this->conexionBD->connect();
                    $stmt = $this->consulta->prepare('CALL SP_VERIF_CREDENCIALES_USUARIO(:correo)');
                    $stmt->bindValue(':correo', $this->correoInstitucional);
                    if ($stmt->execute() && ($stmt->rowCount() == 1)) {
                        // Transformamos la data que da la consulta a object para acceder a cada parametro
                        $data = $stmt->fetchObject();
                        // Verificamos clave que viene desde el frontend con la que esta en el
                        $verificaPassword = password_verify($this->passwordEmpleado, $data->passwordUsuario);
                        if ($verificaPassword) {
                            if (($data->idEstadoUsuario == ESTADO_ACTIVO)) {
                                $tokenGenerado = $this->insertarToken($data->idPersonaUsuario);
                                if ($tokenGenerado) {
                                    $_SESSION['idUsuario'] = $data->idPersonaUsuario;
                                    $_SESSION['idDepartamento'] = $data->idDepartamento;
                                    $_SESSION['idTipoUsuario'] = $data->idTipoUsuario;
                                    $_SESSION['tipoUsuario'] = $data->tipoUsuario;
                                    $_SESSION['nombrePersona'] = $data->nombrePersona;
                                    $_SESSION['apellidoPersona'] = $data->apellidoPersona;
                                    $_SESSION['nombreUsuario'] = $data->nombreUsuario;
                                    $_SESSION['correoInstitucional'] = $data->correoInstitucional;
                                    $_SESSION['tipoUsuario'] = $data->tipoUsuario;
                                    $_SESSION['nombreDepartamento'] = $data->nombreDepartamento;
                                    $_SESSION['abrev'] = $data->abrev;
                                    $_SESSION['avatarUsuario'] = $data->avatarUsuario;
                                    $_SESSION['telefonoDepartamento'] = $data->telefonoDepartamento;
                                    $_SESSION['codigoEmpleado'] = $data->codigoEmpleado;
                                    $_SESSION['abrevTipoUsuario'] = $data->abrevTipoUsuario;
                                    return array(
                                        'status'=> SUCCESS_REQUEST,
                                        'data' => array('message' => 'Estas Logueado -> ' . $_SESSION['access-token'])
                                    );
                                } else {
                                    return array(
                                        'status'=> SUCCESS_REQUEST,
                                        'data' => array('message' => 'Ha ocurrido un error con la generacion del token, por favor loguearse de nuevo')
                                    );
                                }
                            } else {
                                return array(
                                    'status'=> BAD_REQUEST,
                                    'data' => array('message' => 'Su usuario esta inactivo, comunicarse con el super administrador para mas informacion')
                                );
                            }
                            
                        } else {
                            return array(
                                'status'=> BAD_REQUEST,
                                'data' => array('message' => 'La clave digitada es incorrecta, escriba su clave nuevamente')
                            );
                        }
                    } else {
                        return array(
                            'status'=> BAD_REQUEST,
                            'data' => array('message' => 'El correo no existe, escriba nuevamente su correo institucional')
                        );
                    }
                } catch (PDOException $ex) {
                    return array(
                        'status'=> INTERNAL_SERVER_ERROR,
                        'data' => array('message' => $ex->getMessage())
                    );
                } finally {
                    $this->conexionBD = null;
                } 
                    
            } else {
                return array(
                    'status'=> BAD_REQUEST,
                    'data' => array('message' => 'El correo escrito no es un correo institucional, escriba nuevamente el correo')
                );
            }
        }

        public function recuperacionCredenciales () {
            if (validaCampoEmail($this->correoInstitucional)) {
                try {
                    $this->conexionBD = new Conexion();
                    $this->consulta = $this->conexionBD->connect();                    
                    $stmt = $this->consulta->prepare('SELECT Usuario.idPersonaUsuario, Persona.nombrePersona, Persona.apellidoPersona, Usuario.correoInstitucional, Usuario.nombreUsuario FROM Usuario INNER JOIN Persona ON (Usuario.idPersonaUsuario = Persona.idPersona) WHERE Usuario.correoInstitucional = :correo');
                    $stmt->bindValue(':correo', $this->correoInstitucional);
                    if ($stmt->execute() && $stmt->rowCount() >= 1) {
                        $data = $stmt->fetchObject();
                        $this->setIdPersona($data->idPersonaUsuario);
                        $this->setNombrePersona($data->nombrePersona);
                        $this->setApellidoPersona($data->apellidoPersona);
                        $this->nombreUsuario = $data->nombreUsuario;

                        $generaNuevaCredencialAcceso = $this->reenviarCredenciales();
                        return $generaNuevaCredencialAcceso;
                    } else {
                        return array(
                            'status'=> BAD_REQUEST,
                            'data' => array('message' => 'Ha ocurrido un error al enviar las credenciales')
                        );
                    }
                } catch (PDOException $ex) {
                    return array(
                        'status'=> INTERNAL_SERVER_ERROR,
                        'data' => array('message' => $ex->getMessage())
                    );
                } finally {
                    $this->conexionBD = null;
                } 
            } else {
                return array(
                    'status'=> BAD_REQUEST,
                    'data' => array('message' => 'El correo escrito no es un correo institucional, escriba nuevamente el correo')
                );
            }
        }

        public function cambiarClaveAcceso() {
            if (validaCampoPassword($this->passwordEmpleado))  {
                $passwordEncriptada = password_hash($this->passwordEmpleado, PASSWORD_DEFAULT);
                try {
                    $this->conexionBD = new Conexion();
                    $this->consulta = $this->conexionBD->connect();

                    $this->consulta->prepare("
                        set @persona = {$_SESSION['idUsuario']};
                    ")->execute();
                    $this->consulta->prepare("
                        set @tipoBitacora = 5;
                    ")->execute();

                    $stmt = $this->consulta->prepare('UPDATE ' . TBL_USUARIO . ' SET passwordUsuario = :password WHERE idPersonaUsuario = :idUsuario');
                    $stmt->bindValue(':password', $passwordEncriptada);
                    $stmt->bindValue(':idUsuario', $_SESSION['idUsuario']);
                    if ($stmt->execute()) {
                            $email = new Email();
                            $email->setEmailDestino($_SESSION['correoInstitucional']);
                            $email->setNombreUsuario($_SESSION['nombreUsuario']);
                            $email->setNombre($_SESSION['nombrePersona']);
                            $email->setApellido($_SESSION['apellidoPersona']);
                            $email->setHeaderMensaje('Sistema POA PACC');
                            $email->setTituloMensaje('Modificacion y Reenvio de clave de acceso: Estas son tus credenciales de acceso al sistema');
                            $email->setContenido($this->passwordEmpleado);
                            $enviado = $email->notificarViaCorreo();
                            if ($enviado) {
                                return array(
                                    'status'=> SUCCESS_REQUEST,
                                    'data' => array('message' => 'La modificacion y reenvio de credenciales se realizo con exito')
                                );
                            } else {
                                return array(
                                    'status'=> BAD_REQUEST,
                                    'data' => array('message' => 'Ha ocurrido un error, el correo no fue enviado')
                                );
                            }
                        return array(
                            'status'=> SUCCESS_REQUEST,
                            'data' => array('message' => 'Clave de acceso cambiada exitosamente, la clave se envio al correo')
                        );
                    } else {
                        return array(
                            'status'=> BAD_REQUEST,
                            'data' => array('message' => 'Ha ocurrido un error cambiar la clave de accesso')
                        );
                    }
                } catch (PDOException $ex) {
                    return array(
                        'status'=> INTERNAL_SERVER_ERROR,
                        'data' => array('message' => $ex->getMessage())
                    );
                } finally {
                    $this->conexionBD = null;
                } 
            } else {
                return array(
                    'status'=> BAD_REQUEST,
                    'data' => array('message' => 'La clave no cumple con las reglas minimas de seguridad, debe contener al menos una letra y un número y un carácter especial de !@#$%^&*()_+ y tener de 8 a 16 caracteres')
                );
            }
        }

        public function modificarAvatar() {
            try {
                $this->conexionBD = new Conexion();
                $this->consulta = $this->conexionBD->connect();

                $this->consulta->prepare("
                    set @persona = {$_SESSION['idUsuario']};
                ")->execute();
                $this->consulta->prepare("
                    set @tipoBitacora = 2;
                ")->execute();

                $stmt = $this->consulta->prepare('UPDATE ' . TBL_USUARIO . ' SET avatarUsuario = :avatar WHERE idPersonaUsuario = :idUsuario');
                $stmt->bindValue(':avatar', $this->avatarUsuario);
                $stmt->bindValue(':idUsuario', $_SESSION['idUsuario']);
                if ($stmt->execute()) {
                    $_SESSION['avatarUsuario'] = $this->avatarUsuario;
                    return array(
                        'status'=> SUCCESS_REQUEST,
                        'data' => array('message' => 'Fotografia cambiada con exito')
                    );
                } else {
                    return array(
                        'status'=> BAD_REQUEST,
                        'data' => array('message' => 'Ha ocurrido un error al procesar el guardado de la fotografia')
                    );
                }
            } catch (PDOException $ex) {
                return array(
                    'status'=> INTERNAL_SERVER_ERROR,
                    'data' => array('message' => $ex->getMessage())
                );
            } finally {
                $this->conexionBD = null;
            } 
        }
    }
?>