<?php
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
    session_unset();
    session_destroy();
    // $fecha = new DateTime();
    // echo var_dump($fecha);
?>