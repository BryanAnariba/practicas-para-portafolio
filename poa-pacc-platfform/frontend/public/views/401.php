<?php
include('../partials/doctype.php');
?>
<title>Acceso No Autorizado</title>
<!--En esta zona podran poner estilos propios de la vista-->
</head>
<body id="body-pd">
    <div class="container">
        <div class="row mt-5">
            <div class="col-lg-12 mx-auto text-center">
                <h2 class="text-center text-danger font-weight-bolder">
                    Usted no esta autorizado para ver esta informacion
                </h2>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 mx-auto animate__animated animate__flipInY">
                <img src="../img/401/login.jpg" class="img-fluid" alt="Regresar al login">
            </div>
            <div class="col-lg-12 mx-auto text-center">
                <button class="btn btn-amber" onclick="document.location.href='../../../index.php'">
                    <img src="../img/401/login.svg" alt="Regresar al login">
                    Regresar a la pagina de login
                </button>
            </div>
        </div>
    </div>
    
    <script src="../js/libreria-bootstrap-mdb/jquery.min.js"></script>
<?php
    //include('../partials/endDoctype.php');
?>