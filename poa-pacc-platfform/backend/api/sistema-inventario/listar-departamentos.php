<?php
    require_once('../request-headers.php');
    require_once('../../controllers/DepartamentosController.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $departamentos = new DepartamentosController();
            $departamentos->listarDepartamentos(ESTADO_ACTIVO);
        break;
        default: 
            $departamentos = new DepartamentosController();
            $departamentos->peticionNoValida();
        break;
    }
?>