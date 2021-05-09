<?php
include('../partials/doctype.php');;
?>
<title>Informes Aprobados</title>
<!--En esta zona podran poner estilos propios de la vista-->
<link rel="stylesheet" href="../css/animaciones/animate.min.css" />
</head>

<body id="body-pd">
    <?php include('../layouts/Nabvar.php'); ?>
    <?php include('../layouts/Sidebar.php'); ?>

    <div id="profile-card" class="container">
        <div class="row mb-4">
            <div class="col-xl-12 mx-auto">
                <div class="text-center mt-4">
                    <button 
                        type="button" 
                        class="btn btn-light-green btn-rounded"
                        href='' 
                        onclick = " location.href='' "                       
                    >
                    
                        Ver el historial de informes
                    </button>

                    <button 
                        type="button" 
                        class="btn btn-light-green btn-rounded"
                        href='informes-pendientes-decano.php' 
                        onclick = " location.href='informes-pendientes-decano.php' "
                    >                     
                        Ver Informes Pendientes
                    </button>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-12">
                <div class="card animate__animated animate__fadeInDown">
                    <div class="card-header indigo darken-4 text-white">
                        <h5 class="font-weight-bolder">Informes Aprobados</h5>
                    </div>
                    <div class="card-body  blue lighten-5">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                   <tr>                                       
                                        <th scope="col">Codigo</th>
                                        <th scope="col">Fecha Recibido</th>
                                        <th scope="col">Fecha Aprobación</th>
                                        <th scope="col">Enviado Por</th>
                                        <th scope="col">Aprobado Por</th>
                                        <th scope="col">Estado</th>
                                        <th scope="col">
                                            <center><img src="../img/control-informes-decano/icono-ver-informe.svg" alt=""></center> 
                                        </th>
                                        <th scope="col">
                                            <center><img src="../img/control-informes-decano/icono-para-borrar.svg" alt=""></center> 
                                        </th>    
                                   </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">1</th>
                                        <td>05-10-2020</td>
                                        <td>08-10-2020</td>
                                        <td>NombreUsuarioEnvia</td>
                                        <td>NombreUsuarioAprobador</td>
                                        <td>Aprobado</td>
                                        <td>
                                            <center><button  type="button" class="btn-green">Ver</button></center>
                                        </td>
                                        <td>
                                            <center><button type="button" class="btn-danger" >Borrar</button></center>
                                       </td>                                       
                                    </tr>

                                    <tr>
                                        <th scope="row">2</th>
                                        <td>05-12-2020</td>
                                        <td>09-12-2020</td>
                                        <td>NombreUsuarioEnvia</td>
                                        <td>NombreUsuarioAprobador</td>
                                        <td>Aprobado</td>
                                         <td>
                                            <center><button  type="button" class="btn-green">Ver</button></center>
                                        </td>
                                        <td>
                                            <center><button type="button" class="btn-danger" >Borrar</button></center>
                                       </td>  
                                        
                                    </tr>
                                    
                                    <tr>
                                        <th scope="row">3</th>
                                        <td>05-12-2020</td>
                                        <td>09-12-2020</td>
                                        <td>NombreUsuarioEnvia</td>
                                        <td>NombreUsuarioAprobador</td>
                                        <td>Aprobado</td>
                                        <td>
                                            <center><button  type="button" class="btn-green">Ver</button></center>
                                        </td>
                                        <td>
                                            <center><button type="button" class="btn-danger" >Borrar</button></center>
                                       </td>  
                                        
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
    

    <!--En esta zona podran poner javascripts propios de la vista-->
    <?php
        include('../partials/endDoctype.php');
    ?>