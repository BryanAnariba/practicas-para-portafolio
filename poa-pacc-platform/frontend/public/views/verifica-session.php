<?php
    if(
        !isset($_SESSION['idUsuario']) && 
        !isset($_SESSION['access-token']) && 
        !isset($_SESSION['tipoUsuario']) &&
        !isset($_SESSION['nombrePersona']) && 
        !isset($_SESSION['apellidoPersona']) && 
        !isset($_SESSION['correoInstitucional'])) {
            header('Location: 401.php');
        }
?>