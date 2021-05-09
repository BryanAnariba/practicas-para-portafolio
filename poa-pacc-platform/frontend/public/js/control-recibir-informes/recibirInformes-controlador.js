
//petición para mostrar informes pendientes listarInformesPendientes
const listarInformesPendientes = () => {
    $('#listado-informesPendientes').dataTable().fnDestroy();

    $.ajax(`${ API }/control-recibir-informes/listar-InformesPendientes.php`, {
    type: 'POST',
	dataType: 'json',
    //contentType: 'application/json',
    success:function(response) {
        const { data } = response;
        console.log(data);
        $('#listado-informesPendientes tbody').html(``);
        
        for (let i=0;i<data.length; i++) {
            $('#listado-informesPendientes tbody').append(`
                <tr>
                    <td scope="row">${ i + 1 }</td>
                    <td><center>${ data[i].tituloInforme }</center></td>
                    <td><center>${ data[i].fechaRecibido }</center></td>
                    <td><center>${ data[i].nombrePersona +' '+ data[i].apellidoPersona }</center></td>
                    <td class="text-center">
                        <button type="button" ${data[i].idEstadoInforme === 1 ? `class="btn btn-warning" ` : `class="btn btn-danger" `}
                        onclick=(modificaEstadoInforme(${data[i].idInforme}))
                        >
                            ${ data[i].Estado }
                        </button>
                    </td>
                    
                    <td class="text-center">
                        <button type="button" class="btn btn-info btn-sm" 
                            onclick="verDescripcion(${ data[i].idInforme })">
                            <img src="../img/menu/visualizar-icon.svg" alt="verDescripcion"/>
                        </button>
                    </td>

                    <td class="text-center">
                        <button type="button" class="btn btn-info btn-sm" 
                            onclick="verInformeAdjunto(${ data[i].idInforme })">
                            <img src="../img/control-recibir-permisos/adjuntos-icono.svg" alt="Ver Mas"/>
                        </button>
                    </td>
                </tr>
            `)
        }
        $('#listado-informesPendientes').DataTable({
            language: i18nEspaniol,
            //dom: 'Blfrtip',
            //buttons: botonesExportacion,
            //retrieve: true
        });
    },
    error:function (error) {
        console.error(error);
    }});
}




//petición para mostrar informes aprobados
const listarInformesAprobados = () => {
    $('#listado-informesAprobados').dataTable().fnDestroy();
   
    $.ajax(`${ API }/control-recibir-informes/listar-InformesAprobados.php`, {
    type: 'POST',
	dataType: 'json',
    //contentType: 'application/json',
    success:function(response) {
        const { data } = response;
        console.log(data);
        $('#listado-informesAprobados tbody').html(``);
        
        for (let i=0;i<data.length; i++) {
            $('#listado-informesAprobados tbody').append(`
                <tr>
                    <td scope="row">${ i + 1 }</td>
                    <td><center>${ data[i].tituloInforme }</center></td>
                    <td>${ data[i].fechaRecibido }</td>
                    <td>${ data[i].fechaAprobado }</td>
                    <td><center>${ data[i].nombrePersonaEnvia +' '+ data[i].apellidoPersonaEnvia }</center></td>
                    <td><center>${ data[i].nombrePersonaAprueba +' '+ data[i].apellidoPersonaAprueba }</center></td>
                    <td>
                        <button type="button" ${data[i].idEstadoInforme === 2 ? `class="btn btn-success" ` : `class="btn btn-danger" `}
                        >
                            ${ data[i].Estado }
                        </button>
                    </td>
                    
                    <td class="text-center">
                        <button type="button" class="btn btn-info btn-sm" 
                            onclick="verDescripcion(${ data[i].idInforme })">
                            <img src="../img/menu/visualizar-icon.svg" alt="verDescripcion"/>
                        </button>
                    </td>

                    <td class="text-center">
                        <button type="button" class="btn btn-info btn-sm" 
                            onclick="verInformeAdjunto(${ data[i].idInforme })">
                            <img src="../img/control-recibir-permisos/adjuntos-icono.svg" alt="Ver Mas"/>
                        </button>
                    </td>
                </tr>
            `)
        }
        $('#listado-informesAprobados').DataTable({
            language: i18nEspaniol,
            //dom: 'Blfrtip',
            //buttons: botonesExportacion,
            //retrieve: true
        });
    },
    error:function (error) {
        console.error(error);
    }});
}





//petición para obtener la descripción de la solicitud seleccionada
const verDescripcion = (idInforme) => {
    let parametros = { idInforme: parseInt(idInforme) };

    $.ajax(`${ API }/control-recibir-informes/obtenerDescripcionPorId.php`, {
        type: 'POST',
        dataType: 'json',
        data:(parametros),
        success:function(response) {
            //console.log(data);
            $('#modalVerDescripcionInformeEnviado').modal('show');
            let Descripcion = response.data[0].descripcionInforme;
            if((Descripcion != null ) && (Descripcion != " " )){
                $("#V-descripcionInforme").val(Descripcion).trigger("change");
            }else{
                $("#V-descripcionInforme").val("NO HAY DESCRIPCION").trigger("change"); 
            }   
            
        },
        error:function(error) {
            console.warn(error);
            const { status, data } = error.responseJSON;
            if (status === 401) {
                window.location.href = '../views/401.php';
            }
        }
    });
};




//petición para obtener la visualización del informe seleccionado
const verInformeAdjunto = (idInforme) => {
    let parametros = { idInforme: parseInt(idInforme) };

    $.ajax(`${ API }/control-recibir-informes/obtenerInformePorId.php`, {
        type: 'POST',
        dataType: 'json',
        data:(parametros),
        success:function(response) {
            //console.log(data);
            $('#modalInformeAdjunto').modal('show');
            let Informe = response.data[0].informe;
            if (Informe != null){
                $('#V-informeAdjunto').html(`<iframe src="../../../backend/uploads/documentosSubidos/envioInformes/${Informe}" style="width:1000px; height:1000px;"></iframe> `);
            }
            
        },
        error:function(error) {
            console.warn(error);
            const { status, data } = error.responseJSON;
            if (status === 401) {
                window.location.href = '../views/401.php';
            }
        }
    });
};



let idInformeSeleccionado;
//petición para confirmar la modigicacion del estado del informe
const modificaEstadoInforme = (idInforme) => {
    idInformeSeleccionado = idInforme;
    //
    $('#modalConfirmarModificacionEstadoInforme').modal('show');

};



// Modificación Estado Informe
const modificaEstadoInformeSeleccionado = () => {
    
    let parametros = { idInforme: parseInt(idInformeSeleccionado) };
    
    $.ajax(`${ API }/control-recibir-informes/cambiar-estado-informe.php`, {
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify(parametros),
        success:function(response) {
            const { data } = response;
            console.log(response);
            Swal.fire({
                icon: 'success',
                title: 'Accion realizada Exitosamente',
                //text: `${ data.message }`,
            });

            // Carga y llenado de Informes Pendientes 
            $('#modalConfirmarModificacionEstadoInforme').modal('hide');
            listarInformesPendientes();
        },
        error:function(error) {
            console.error(error);
            const { status, data } = error.responseJSON;
            if (status === 401) {
                window.location.href = '../views/401.php';
            }
            Swal.fire({
                icon: 'error',
                title: 'Ops...',
                text: `${ data.message }`,
                footer: '<b>Por favor verifique el formulario de registro</b>'
            });
        }
    });
}