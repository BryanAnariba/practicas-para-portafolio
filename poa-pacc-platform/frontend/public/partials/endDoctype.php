        
        <!--script src="../js/libreria-bootstrap-mdb/jquery-ui.min.js"></script-->
        <script src="../js/libreria-bootstrap-mdb/popper.min.js"></script>
        <script src="../js/libreria-bootstrap-mdb/bootstrap.min.js"></script>
        <script src="../js/libreria-bootstrap-mdb/mdb.min.js"></script>
        <script src="../js/menu/menu-principal.js"></script>
        <script src="../js/navbar/navbar.js"></script>
        <script src="../js/notificaciones/notificaciones-controlador.js"></script>
        
    <?php 
        //Revisión de tipo de usuario para generar notificaciones segun cada usuario conectado
        if ($_SESSION['abrevTipoUsuario'] === 'S_AC') {
            echo '
                <script type="text/javascript">
                    setInterval(notificacionesSecAcademica,8000);
                </script>';
            
        }else if($_SESSION['abrevTipoUsuario'] === 'J_D'){
            echo '
                <script type="text/javascript">
                    setInterval(notificacionesJefes,8000);
                </script>';

        }else if($_SESSION['abrevTipoUsuario'] === 'D_F'){
            echo '
                <script type="text/javascript">
                    setTimeOut(notificacionesDecano,8000);
                </script>';
        }else if($_SESSION['abrevTipoUsuario'] === 'SE_AD'){
            echo '
                <script type="text/javascript">
                    setInterval(notificacionesSecAdministrativa,8000);
                </script>';
        }else if($_SESSION['abrevTipoUsuario'] === 'C_C'){
            echo '
                <script type="text/javascript">
                    setInterval(notificacionesCoordinadores,8000);
                </script>';

        }else if($_SESSION['abrevTipoUsuario'] === 'SU_AD'){
            echo '
                <script type="text/javascript">
                    setInterval(notificacionesSuperAdmin,8000);
                </script>';
        }else if($_SESSION['abrevTipoUsuario'] === 'U_E'){
            echo '
                <script type="text/javascript">
                    setInterval(notificacionesEstratega,8000);
                </script>';
        }

    ?>
    </body>
</html>