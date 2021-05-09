<?php
include('../partials/doctype.php');;
?>
<title>informes Pendientes</title>
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
                    <button type="button" class="btn btn-light-green btn-rounded">
                        Ver el historial de informes
                    </button>

                    <button 
                        type="button" 
                        class="btn btn-light-green btn-rounded"
                        href='informes-aprobados-decano.php' 
                        onclick = " location.href='informes-aprobados-decano.php' "
                    >              
                        Ver Informes Aprobados
                    </button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card animate__animated animate__fadeInDown">
                    <div class="card-header indigo darken-4 text-white">
                        <h5 class="font-weight-bolder">Informes Pendientes</h5>
                    </div>
                    <div class="card-body  blue lighten-5">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                   <tr>                                       
                                        <th scope="col">Codigo</th>
                                        <th scope="col">Fecha Recibido</th>
                                        
                                        <th scope="col">Enviado Por</th>
                                       
                                        <th scope="col"><center>Estado</center></th>
                                        <th scope="col">
                                            <center><img src="../img/control-informes-decano/icono-actualizar-refrescar.svg" alt=""></center>    
                                        </th>
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
                                        
                                        <td>NombreUsuarioEnvia</td>
                                        
                                        <td>
                                            <center>
                                                <select name="estadoInformes">
                                                    <option value="1">Pendiente</option>  
                                                    <option value="2" >Aprobado</option>           
                                                </select>
                                            </center>
                                        </td>
                                        <td>
                                            <center><button  type="button" class="btn-primary">Actualizar</button></center>
                                        </td>
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
                                        <td>NombreUsuarioEnvia</td>
                                        <td>
                                            <center>
                                                <select name="estadoInformes">
                                                    <option value="1">Pendiente</option>  
                                                    <option value="2" >Aprobado</option>           
                                                </select>
                                            </center>
                                        </td>
                                        <td>
                                            <center><button  type="button" class="btn-primary">Actualizar</button></center>
                                        </td>
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
                                        <td>NombreUsuarioEnvia</td>
                                        <td>
                                            <center>
                                                <select name="estadoInformes">
                                                    <option value="1">Pendiente</option>  
                                                    <option value="2" >Aprobado</option>           
                                                </select>
                                            </center>
                                        </td>
                                        <td>
                                            <center><button  type="button" class="btn-primary">Actualizar</button></center>
                                        </td>
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
    <!--Visualizar documento-->
    <div 
        class="modal fade" 
        id="modalRegistroDimension" 
        tabindex="-1" role="dialog" 
        aria-labelledby="myModalLabel" 
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header indigo darken-4 text-white">
                    <h4 class="modal-title w-100" id="myModalLabel">Vista Documento</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-danger btn-rounded" data-dismiss="modal" aria-label="Close">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--En esta zona podran poner javascripts propios de la vista-->
    <?php
        include('../partials/endDoctype.php');
    ?>