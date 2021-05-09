<?php
if (!isset($_SESSION)) {
    session_start();
}
$secretaria = 'SE_AD';
if ($_SESSION['abrevTipoUsuario'] != $secretaria) {
    header('Location: 401.php');
}
if (!isset($_SESSION['correoInstitucional'])) {
    header('Location: 401.php');
}
?>
<title>Control de Permisos</title>
<!--En esta zona podran poner estilos propios de la vista-->
<link rel="stylesheet" href="../css/animaciones/animate.min.css" />
</head>

<body id="body-pd">
    <?php include('../layouts/Nabvar.php'); ?>
    <?php include('../layouts/Sidebar.php'); ?>

    <div id="profile-card" class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header indigo darken-4 text-white">
                        <h5 class="font-weight-bolder"> Maneja el control de permisos/solicitudes desde este panel</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Empleado Facultad</th>
                                        <th scope="col">Detalles del permiso</th>
                                        <th scope="col">Estado Confirmacion</th>
                                        <th scope="col">Estado Solicitud</th>
                                        <th scope="col">Evidencia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">1</th>
                                        <td>Cell</td>
                                        <td>Cell</td>
                                        <td>Cell</td>
                                        <td>Cell</td>
                                        <td>Cell</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">2</th>
                                        <td>Cell</td>
                                        <td>Cell</td>
                                        <td>Cell</td>
                                        <td>Cell</td>
                                        <td>Cell</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">3</th>
                                        <td>Cell</td>
                                        <td>Cell</td>
                                        <td>Cell</td>
                                        <td>Cell</td>
                                        <td>Cell</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer amber accent-4">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--modales-->


    <!--Scripts-->
    <script src="../js/sweet-alert-two/sweetalert2.min.js"></script>
    <script src="../js/libreria-bootstrap-mdb/jquery.min.js"></script>

    <script src="../js/data-tables/datatables.min.js"></script>

    <script src="../js/data-tables/Buttons/js/dataTables.buttons.min.js"></script>
    <script src="../js/data-tables/JSZip/jszip.min.js"></script>
    <script src="../js/data-tables/pdfmake/pdfmake.min.js"></script>
    <script src="../js/data-tables/pdfmake/vfs_fonts.js"></script>
    <script src="../js/data-tables/Buttons/js/buttons.html5.min.js"></script>

    <script src="../js/config/config.js"></script>
    <script src="../js/validators/form-validator.js"></script>

    <?php
    include('../partials/endDoctype.php');
    ?>