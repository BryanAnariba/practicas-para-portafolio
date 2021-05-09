<?php
    // Funciones para verificar campos que vienen desde el frontend
    
    function campoTexto ($parametro, $min, $max) {
        if ((preg_match("/^[ñA-Za-z _]*[ñA-Za-z][ñA-Za-z _]/", $parametro) == true) &&  (strlen($parametro) >= $min) && (strlen($parametro) <= $max)) {
            return true;
        } else {
            return false;
        }
    }

    function campoParaTodo ($parametro, $min, $max) {
        if ((preg_match("/^[\w]/", $parametro)==true) && (strlen($parametro) >= $min) && (strlen($parametro) <= $max)) {
            return true;
        } else {
            return false;
        }
    }
    function campoCodigo ($parametro, $min, $max) {
        if ((preg_match("/^[0-9]{5}(-[0-9]{2})?$/", $parametro) == true) &&  (strlen($parametro) >= $min) && (strlen($parametro) <= $max)) {
            return true;
        } else {
            return false;
        }
    }
    function campoAbrevCodigo ($parametro, $min, $max) {
        if ((preg_match("/[0-9-]/", $parametro) == true) &&  (strlen($parametro) >= $min) && (strlen($parametro) <= $max)) {
            return true;
        } else {
            return false;
        }
    }
    function validaCampoCodigoEmpleado ($parametro) {
        if (preg_match("/^\d{1,5}$/", $parametro)) {
            return true;
        } else {
            return false;
        }
    }

    function validaCampoEmail ($parametro) {
        if (preg_match("/[A-Za-z][\w]*@unah(\.edu)?\.hn/", $parametro)) {
            return true;
        } else {
            return false;
        }
    }

    function validaCampoNumerico ($parametro) {

    }

    function validaCampoNombreApellido ($parametro, $min, $max) {
        $regex = '/^([a-z ñáéíóú]{1,80})$/i';
        if ((preg_match($regex, $parametro) == true) &&  (strlen($parametro) >= $min) && (strlen($parametro) <= $max)) {
            return true;
        } else {
            return false;
        }
    }

    function validaCampotelefono ($parametro) {
        if (preg_match("/^(2\d{3})(-)?\d{4}/", $parametro)) {
            return true;
        } else {
            return false;
        }
    }

    function validaCampoPassword ($parametro) {
        if (preg_match("/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[!@#$%^&*()_+])[A-Za-z\d][A-Za-z\d!@#$%^&*()_+]{8,16}$/", $parametro)) {
            return true;
        } else {
            return false;
        }
    }

    function validaCampoMonetario ($parametro) {
        if (preg_match("/^[+]?([0-9]+(?:[\.][0-9]*)?|\.[0-9]+)$/", $parametro)) {
            return true;
        } else {
            return false;
        }
    }

    function validaNumerZPositivo($parametro) {
        if (preg_match("/^[0-9]{1,3}$/", $parametro)) {
            return true;
        } else {
            return false;
        }
    }
?>