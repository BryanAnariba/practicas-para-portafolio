// 
let tituloInforme = document.querySelector('#R-tituloInforme');
let descripcionInforme = document.querySelector('#R-descripcionInforme');

let informe = document.getElementById('informe');

//verificando longitud de campos a ingresar en el registro de nuevo informe
let tInf = { valorEtiqueta: tituloInforme, id: 'R-tituloInforme', name: 'Titulo Informe ', min: 1, max: 150, type: 'text' };
let dInf = { valorEtiqueta: descripcionInforme, id: 'R-descripcionInforme', name: 'Descripcion Informe', min: 1, max: 255, type: 'text' };



const cancelarRegistroInforme = () => {
    limpiarCamposFormulario(tInf);
    $(`#R-tituloInforme`).trigger('reset');
    limpiarCamposFormulario(dInf);
    $(`#R-descripcionInforme`).trigger('reset');

    document.querySelector('#informe').value = '';
    
}


//esta es la petición que valida si el formulario de registrar informe tiene todo los campos validos,
//tambien revisa si se sube o no el documento de informe como tal  y segun esta revisión llama 
//a la función subirInforme.
const registrarInforme = () => {
    //verificando los campos con las funciones de verificación
    let isValidTituloInforme = verificarInputText(tInf, letrasEspaciosCaracteresRegex);
    let isValidDescripcionInforme = verificarInputText(dInf, letrasEspaciosCaracteresRegex);

    // Si todos los campos son  true se realiza la operación 
    if (
        (isValidTituloInforme === true) &&
        (isValidDescripcionInforme === true)
             
    ) { if(informe.files.length != 0 ){
            subirInforme();
        }else if(informe.files.length == 0){
            Swal.fire({
                icon: 'error',
                title: 'Ops...',
                text: 'El registro del informe no se pudo realizar, porque no se adjunto el documento',
                footer: '<b>Por favor verifique el formulario de registro</b>'
            });
        }

    } else {
        Swal.fire({
            icon: 'error',
            title: 'Ops...',
            text: 'El registro del informe no se pudo realizar',
            footer: '<b>Por favor verifique el formulario de registro</b>'
        });
        const { status, data } = error.responseJSON;
           if (status === 401) {
              window.location.href = '../views/401.php';
           }
    }
}



const subirInforme = () => {

    $('#btn-registrar-informe').prop('disabled', true);
    
    const formData = new FormData($("#formulario-registro-informe")[0]);
    
    $.ajax(`${ API }/control-envio-informes/subirInforme.php`, {
        type: 'POST',
        data: formData,
        cache: false,
        processData: false,
        contentType: false,
        success:function (response) {
            const { data } = response;
                Swal.fire({
                    icon: 'success',
                    title: 'Accion realizada Exitosamente',
                    text: `${ data.message }`,
                });
                $('#btn-registrar-informe').prop('disabled', false);
                cancelarRegistroInforme();
        }, 
        error:function(error) {
            $('#btn-registrar-informe').prop('disabled', false);
            console.log(error.responseText);
            const { status, data } = error.responseJSON;
                if (status === 401) {
                    window.location.href = '../views/401.php';
                }
            Swal.fire({
                icon: 'error',
                title: 'Ops...',
                text: `${ data.message }`,
                footer: '<b>Ha ocurrido un error</b>'
            });
        }
    });
    
}



//petición para mostrar informes pendientes listarInformesPendientes
const listarInformesPendientes = () => {
    $('#listado-informesPendientes').dataTable().fnDestroy();

    $.ajax(`${ API }/control-envio-informes/listar-InformesPendientes.php`, {
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
                    <td class="text-center">
                        <button type="button" ${data[i].idEstadoInforme === 1 ? `class="btn btn-warning" ` : `class="btn btn-danger" `}
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
   
    $.ajax(`${ API }/control-envio-informes/listar-InformesAprobados.php`, {
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
                    <td><center>${ data[i].fechaRecibido }</center></td>
                    <td><center>${ data[i].fechaAprobado }</center></td>
                    <td><center>${ data[i].nombrePersona +' '+ data[i].apellidoPersona }</center></td>
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

    $.ajax(`${ API }/control-envio-informes/obtenerDescripcionPorId.php`, {
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




//petición para obtener la vusualización del informe seleccionado
const verInformeAdjunto = (idInforme) => {
    let parametros = { idInforme: parseInt(idInforme) };

    $.ajax(`${ API }/control-envio-informes/obtenerInformePorId.php`, {
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

