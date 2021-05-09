// Petición para listar las solicitudes de permisos enviadas segun el usuario que tiene activa la sesión
//cargar las solicitudes de permisos enviadas
const verSolicitudesPermisos = () => {
    const peticion = {
        idUsuario: document.querySelector('#R-idUsuario').value
    };
    $('#listado-solicitudes').dataTable().fnDestroy();
    
    $.ajax(`${ API }/control-envio-solicitudes/listado-envio-solicitudes.php`, {
    type: 'POST',
    dataType: 'json',
    data: (peticion),
    success:function(response) {
        const { data } = response;
        console.log(data);
        $('#listado-solicitudes tbody').html(``);
        
        for (let i=0;i<data.length; i++) {
            $('#listado-solicitudes tbody').append(`
                <tr>
                    <td scope="row">${ i + 1 }</td>
                    <td><center>${ data[i].tipoSolicitudSalida }</center></td>
                    <td><center>${ data[i].fechaRegistroSolicitud }</center></td>
                    <td>
                        <center>
                            <button type="button" style="width:150px;" ${ data[i].idTipoEstadoSolicitud === 1 ? `class="btn btn-warning" ` :
                                                                          data[i].idTipoEstadoSolicitud === 2 ? `class="btn btn-success" ` : 
                                                                          data[i].idTipoEstadoSolicitud === 3 ? `class="btn btn-primary" ` : 
                                                                                                                `class="btn btn-danger" ` 
                                                                        }
                            >
                                ${ data[i].TipoEstadoSolicitudSalida  }
                            </button>
                        </center>
                    </td>
                    <td class="text-center">
                            <button type="button" class="btn btn-info btn-sm"
                            onclick="visualizarSolicitud('${data[i].idSolicitud}')">
                                <img src="../img/menu/ver-icon.svg" alt="Ver Mas"/>
                            </button>
                        
                    </td>
                    <td class="text-center">
                            <button type="button" class="btn btn-info btn-sm"
                            onclick="visualizarAdjuntos('${data[i].idSolicitud}')">
                                <img src="../img/control-recibir-permisos/adjuntos-icono.svg" alt="Ver Mas"/>
                            </button>
                    </td>
                    <td class="my-auto">
                        <button type="button" class="btn btn-info btn-sm" 
                            onclick="verObservacion(${ data[i].idSolicitud })">
                            <img src="../img/menu/visualizar-icon.svg" alt="verObservacion"/>
                        </button>
                    </td>
                    

                </tr>
            `)
        }
        $('#listado-solicitudes').DataTable({
            language: i18nEspaniol,
            //dom: 'Blfrtip',
            //buttons: botonesExportacion,
            //retrieve: true
        });
    },
    error:function (error) {
        console.error(error);
        const { status, data } = error.responseJSON;
           if (status === 401) {
              window.location.href = '../views/401.php';
           }
    }});
}


//petición para obtener la información de la solicitud seleccionada
const visualizarSolicitud = (idSolicitud) => {
    let parametros = { idSolicitud: parseInt(idSolicitud) };

    $.ajax(`${ API }/control-envio-solicitudes/obtenerSolicitudPorId.php`, {
        type: 'POST',
        dataType: 'json',
        data:(parametros),
        success:function(response) {
            //console.log(data);
            $('#modalVerSolicitudEnviada').modal('show');
            $("#V-motivoPermiso").val(response.data[0].motivoSolicitud).trigger("change");
            $("#V-edificioAsistencia").val(response.data[0].edificioAsistencia).trigger("change");
            $("#V-fechaInicio").val(response.data[0].fechaInicioPermiso).trigger("change");
            $("#V-fechaFin").val(response.data[0].fechaFinPermiso).trigger("change");
            $("#V-horaInicio").val(response.data[0].horaInicioSolicitudSalida).trigger("change");
            $("#V-horaFin").val(response.data[0].horaFinSolicitudSalida).trigger("change");

            let diasSolicitados = response.data[0].diasSolicitados;
            if(diasSolicitados == 0 ){
                $("#V-diasSolicitados").val("El permiso es por horas").trigger("change");
            }else{
                $("#V-diasSolicitados").val("El permiso se registra en"+" "+ diasSolicitados+" "+ "días").trigger("change");
            }

            let fechaRevision = response.data[0].fechaRevisionSolicitud;
            if((fechaRevision != null) && (fechaRevision != " ") ){
                $("#V-fechaRevision").val(fechaRevision).trigger("change");
            }else{
                $("#V-fechaRevision").val("Su solicitud esta pendiente de revisión").trigger("change");
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


//petición para obtener las observaciones de la solicitud seleccionada
const verObservacion = (idSolicitud) => {
    let parametros = { idSolicitud: parseInt(idSolicitud) };

    $.ajax(`${ API }/control-envio-solicitudes/obtenerObservacionesPorId.php`, {
        type: 'POST',
        dataType: 'json',
        data:(parametros),
        success:function(response) {
            //console.log(data);
            $('#modalVerObservacionesSolicitudEnviada').modal('show');
            let Observaciones = response.data[0].observacionesSolicitud;
            if((Observaciones != null ) && (Observaciones != " " )){
                $("#V-observacionesSolicitud").val(Observaciones).trigger("change");
            }else{
                $("#V-observacionesSolicitud").val("NO HAY OBSERVACIONES").trigger("change"); 
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


//petición para obtener la imagen adjunta de la solicitud seleccionada
const visualizarAdjuntos = (idSolicitud) => {
    let parametros = { idSolicitud: parseInt(idSolicitud) };

    $.ajax(`${ API }/control-envio-solicitudes/obtenerImagenRespaldoPorId.php`, {
        type: 'POST',
        dataType: 'json',
        data:(parametros),
        success:function(response) {
            //console.log(data);
            $('#modalRespaldoAdjunto').modal('show');
            let respaldo = response.data[0].documentoRespaldo;
            let firmaDigital = response.data[0].firmaDigital;
            if (respaldo != null){
                $('#V-respaldoAdjunto').html(`<img src="../../../backend/uploads/images/envioSolicitudesPermisos/${respaldo}" class="mx-auto img-fluid  img-responsive" /> `);
            }else {
                $('#V-respaldoAdjunto').html(`<h6 align="center" style="color:#ffc107" > No se adjunto imagen de respaldo <h6/> `);
            }

            if (firmaDigital != null){
                $('#V-firmaDigitalAdjunta').html(`<img src="../../../backend/uploads/images/envioSolicitudesPermisos/${firmaDigital}" class="mx-auto img-fluid  img-responsive" /> `);
            }else {
                $('#V-firmaDigitalAdjunta').html(`<h6 align="center" style="color:#ffc107" > No se adjunto imagen de firma digital <h6/> `);
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





// Peticiones para la sección de enviar la solicitud de permiso


//obteniendo los valores de las etiquetas inputs para el registro de nueva solicitud 
let idUsuario = document.querySelector('#R-idUsuario');
let motivoPermiso = document.querySelector('#R-motivoPermiso');
let edificioAsistencia = document.querySelector('#R-edificioAsistencia');
let fechaInicio = document.querySelector('#R-fechaInicio');
let fechaFin = document.querySelector('#R-fechaFin');
let horaInicio = document.querySelector('#R-horaInicio');
let horaFin = document.querySelector('#R-horaFin');


let respaldo = document.getElementById('respaldo');
let firmaDigital = document.getElementById('firmaDigital');

//verificando longitud de campos a ingresar en el registro de nueva solicitud de permiso
let iUsu = { valorEtiqueta: idUsuario, id: 'R-idUsuario', name: 'id Usuario', min: 1, max: 80, type: 'number' };
let mPer = { valorEtiqueta: motivoPermiso, id: 'R-motivoPermiso', name: 'Motivo Permiso', min: 1, max: 1000, type: 'text' };
let eAsi = { valorEtiqueta: edificioAsistencia, id: 'R-edificioAsistencia', name: 'Edificio Asistencia', min: 1, max: 80, type: 'text' };
let fIni = { valorEtiqueta: fechaInicio, id: 'R-fechaInicio', name: 'Fecha Inicicio', min: 1, max: 60, type: 'date' };
let fFin = { valorEtiqueta: fechaFin, id: 'R-fechaFin', name: 'Fecha Fin', min: 1, max: 60, type: 'date' };
let hIni = { valorEtiqueta: horaInicio, id: 'R-horaInicio', name: 'Hora Inicio',type: 'time' };
let hFin = { valorEtiqueta: horaFin, id: 'R-horaFin', name: 'Hora Fin', type: 'time' };


//Limpiar los campos del formulario de registro de departamentos
const cancelarRegistroSolicitud = () => {
    limpiarCamposFormulario(mPer);
    $(`#R-motivoPermiso`).trigger('reset');
    limpiarCamposFormulario(eAsi);
    $(`#R-edificioAsistencia`).trigger('reset');
    limpiarCamposFormulario(hIni);
    document.querySelector('#R-horaInicio').value = '';
    limpiarCamposFormulario(hFin);
    document.querySelector('#R-horaFin').value = '';
    limpiarCamposFormulario(fIni);
    document.querySelector('#R-fechaInicio').value = '';
    limpiarCamposFormulario(fFin);
    document.querySelector('#R-fechaFin').value = '';

    document.querySelector('#respaldo').value = '';
    document.querySelector('#firmaDigital').value = '';
    
}

//esta es la petición que valida si el formulario de solicitud tiene todo los campos validos,
//tambien revisa si se suben o no las imagenes que son opcionales y segun esta revisión llama 
//a una u otra función segun lo requiera el caso.
const registrarSolicitud = () => {
    //verificando los campos con las funciones de verificación
    let isValidMotivoPermiso = verificarInputText(mPer, letrasEspaciosCaracteresRegex);
    let isValidEdificioAsistencia = verificarInputText(eAsi, letrasEspaciosCaracteresRegex);
    let isValidFechaInicio = verificarFechaSolicitud(fIni);
    let isValidFechaFin = verificarFechaSolicitud(fFin);
    let isValidHoraInicio = verificarHoraSolicitud(hIni);
    let isValidHoraFin = verificarHoraSolicitud(hFin);

    // Si todos los campos son  true se realiza la operación 
    if (
        (isValidMotivoPermiso === true) &&
        (isValidEdificioAsistencia === true) &&
        (isValidFechaInicio === true) &&
        (isValidFechaFin === true) && 
        (isValidHoraInicio === true) &&
        (isValidHoraFin === true)
             
    ) { if((respaldo.files.length == 0) && (firmaDigital.files.length == 0) ){
            registrarSolicitudSinImagenes();
        }else if((respaldo.files.length != 0) && (firmaDigital.files.length != 0)){
            registrarSolicitudConImagenes();
        }else if(respaldo.files.length == 0){
            registrarSolicitudConFirma();
        }else {
            registrarSolicitudConRespaldo();
        }


    } else {
        Swal.fire({
            icon: 'error',
            title: 'Ops...',
            text: 'El registro de la solicitud no se pudo realizar',
            footer: '<b>Por favor verifique el formulario de registro</b>'
        });
        const { status, data } = error.responseJSON;
           if (status === 401) {
              window.location.href = '../views/401.php';
           }
    }
}



const registrarSolicitudSinImagenes = () => {
    
    $('#btn-registrar-solicitud').prop('disabled', true);

    let parametros = {
        idUsuario: parseInt(idUsuario.value),
        motivoPermiso: motivoPermiso.value,
        edificioAsistencia: edificioAsistencia.value,
        fechaInicio: fechaInicio.value,
        fechaFin: fechaFin.value,
        horaInicio: horaInicio.value,
        horaFin: horaFin.value
        
    };
    console.log(parametros);
    $.ajax(`${ API }/control-envio-solicitudes/registrarSolicitudSinImagenes.php`, {
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify(parametros),
        success:function (response) {
            Swal.fire({
                icon: 'success',
                title: 'Accion realizada Exitosamente',
                footer: '<b>Por favor verifique el formulario de registro</b>',
            });
            $('#btn-registrar-solicitud').prop('disabled', false);
            cancelarRegistroSolicitud();
        },
        error:function (error) {
            $('#btn-registrar-solicitud').prop('disabled', false);
            const { status, data } = error.responseJSON;
            if (status === 401) {
                window.location.href = '../views/401.php';
            }
            Swal.fire({
                icon: 'error',
                title: 'Ops...',
                text: 'El registro de la solicitud no se pudo realizar',
                footer: '<b>Por favor verifique el formulario de registro</b>'
            });
        }
    });

}



const registrarSolicitudConImagenes = () => {

    $('#btn-registrar-solicitud').prop('disabled', true);
    
    const formData = new FormData($("#formulario-registro-solicitud")[0]);
    
    $.ajax(`${ API }/control-envio-solicitudes/registrarSolicitudConImagenes.php`, {
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
                $('#btn-registrar-solicitud').prop('disabled', false);
                cancelarRegistroSolicitud();
        }, 
        error:function(error) {
            $('#btn-registrar-solicitud').prop('disabled', false);
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

const registrarSolicitudConFirma = () => {

    $('#btn-registrar-solicitud').prop('disabled', true);
    
    const formData = new FormData($("#formulario-registro-solicitud")[0]);
    
    $.ajax(`${ API }/control-envio-solicitudes/registrarSolicitudConFirma.php`, {
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
                $('#btn-registrar-solicitud').prop('disabled', false);
                cancelarRegistroSolicitud();
        }, 
        error:function(error) {
            $('#btn-registrar-solicitud').prop('disabled', false);
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


const registrarSolicitudConRespaldo = () => {
    
    $('#btn-registrar-solicitud').prop('disabled', true);

    const formData = new FormData($("#formulario-registro-solicitud")[0]);
   
    $.ajax(`${ API }/control-envio-solicitudes/registrarSolicitudConRespaldo.php`, {
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
                $('#btn-registrar-solicitud').prop('disabled', false);
                cancelarRegistroSolicitud();
        }, 
        error:function(error) {
            $('#btn-registrar-solicitud').prop('disabled', false);
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
