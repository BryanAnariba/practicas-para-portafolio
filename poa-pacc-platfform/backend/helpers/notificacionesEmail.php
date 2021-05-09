<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;

    require_once 'phpmailer/src/Exception.php';
    require_once 'phpmailer/src/PHPMailer.php';
    require_once 'phpmailer/src/SMTP.php';
    require_once '../../config/config.php';
    class notificacionesEmail {
        private $emailDestino;
        private $nombre;
        private $apellido;
        private $nombreUsuario;
        private $headerMensaje;
        private $tituloMensaje;
        private $contenido;
        private $appURI;

        
        public function getEmailDestino() {
            return $this->emailDestino;
        }

        public function setEmailDestino($emailDestino) {
            $this->emailDestino = $emailDestino;

            return $this;
        }

        public function getNombre() {
            return $this->nombre;
        }

        public function setNombre($nombre) {
            $this->nombre = $nombre;
            return $this;
        }

        public function getApellido() {
            return $this->apellido;
        }

        public function setApellido($apellido) {
            $this->apellido = $apellido;
            return $this;
        }

        public function getNombreUsuario() {
            return $this->nombreUsuario;
        }

        public function setNombreUsuario($nombreUsuario) {
            $this->nombreUsuario = $nombreUsuario;
            return $this;
        }

        public function getTituloMensaje() {
            return $this->tituloMensaje;
        }

        public function setTituloMensaje($tituloMensaje) {
            $this->tituloMensaje = $tituloMensaje;
            return $this;
        }

        public function getContenido()
        {
            return $this->contenido;
        }

        public function setContenido($contenido) {
            $this->contenido = $contenido;
            return $this;
        }

        
        public function getHeaderMensaje() {
            return $this->headerMensaje;
        }

        public function setHeaderMensaje($headerMensaje) {
            $this->headerMensaje = $headerMensaje;
            return $this;
        }

        public function notificarViaCorreo () {
            $mail = new PHPMailer(true); // Instancia PHPmAILER 
            try {
                //Server settings
                $mail->SMTPDebug = 0;                      // Enable verbose debug output
                $mail->isSMTP();                                            // Send using SMTP
                $mail->Host = 'smtp.office365.com'; // IR a correo institucional/configuraciones/correo/sincronizar correo/ configuracion smtp
                $mail->SMTPAuth = true;                                   // Enable SMTP authentication
                $mail->Username = EMAIL_ADMIN_USERNAME;// Email usuario remitente
                $mail->Password = EMAIL_ADMIN_PASSWORD;// Clave usuario remitente
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                $mail->Port = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
            
                //Recipients
                $mail->setFrom(EMAIL_ADMIN_USERNAME, 'Admin POA PACC'); // Desde mi correo envio a
                $mail->addAddress($this->emailDestino, 'Usuario POA PACC'); // Para
            
                // Attachments
                //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
            
                // Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = $this->headerMensaje;
                $mail->Body = '
                    <h2> ' . $this->tituloMensaje . '</h2> <br/>' .
                    '<h4>' . $this->contenido . '</h4> <br/>';
                $mail->AltBody = 'Mantente siempre conectado para que puedas ver las siguientes notificaciones';
            
                if ($mail->send()) {
                    return true;
                } else {
                    return false;
                }
            } catch (Exception $e) {
                return false;
            }
        }

        public function enviarArchivo () {

        }
    }
?>