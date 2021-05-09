// Petición para listar las solicitudes de permisos recibidas segun los usuarios que tenga que recibirles permisos
// Cargar las solicitudes de permisos recibidas que ya fueron revisadas

const verSolicitudesPendientesSecAcademica = () => {
    $('#listado-solicitudes').dataTable().fnDestroy();

    $.ajax(`${ API }/control-recibir-solicitudes/listado-solicitudes-pendientesSecAcademica.php`, {
    type: 'POST',
    dataType: 'json',
    contentType: 'application/json',
    success:function(response) {
        const { data } = response;
        console.log(data);
        $('#listado-solicitudes tbody').html(``);
        
        for (let i=0;i<data.length; i++) {
            $('#listado-solicitudes tbody').append(`
                <tr>
                    <td scope="row">${ i + 1 }</td>
                    <td>${ data[i].tipoSolicitudSalida }</td>
                    <td><center>${ data[i].fechaRegistroSolicitud }</center></td>
                    <td>${ data[i].nombrePersona +' '+ data[i].apellidoPersona }</td>
                    <td class="text-center">
                            <button type="button" class="btn btn-info btn-sm"
                            onclick="visualizarSolicitud('${data[i].idSolicitud}')">
                                <img src="../img/menu/ver-icon.svg" alt="Ver Mas"/>
                            </button>
                        
                    </td>
                    <td class="text-center">
                            <button type="button" class="btn btn-info btn-sm"
                            onclick="visualizarAdjuntos('${data[i].idSolicitud}')">
                                <img src="../img/control-recibir-permisos/adjuntos-icono.svg" alt="Adjuntos"/>
                            </button>
                    </td>
                    <td class="my-auto">
                        <button type="button" class="btn btn-info btn-sm" 
                            onclick="obtenerObservacion(${ data[i].idSolicitud })">
                            <img src="../img/menu/visualizar-icon.svg" alt="hacerObservacion"/>
                        </button>
                    </td>

                    <td>
                        <center>
                            <select class="btn btn-warning select"  name="estado" id="estado">
                                <option value="1"
                                if(${ data[i].idTipoEstadoSolicitud === 1 })selected> Pendiente </option>  
                                <option value="2"
                                if(${ data[i].idTipoEstadoSolicitud === 2 })selected> Aceptado </option>    
                                <option value="3"
                                if(${ data[i].idTipoEstadoSolicitud === 3 })selected> Parcial </option>
                                <option value="4"
                                if(${ data[i].idTipoEstadoSolicitud === 4 })selected> Denegado </option> 
                            </select>
                        </center>
                    </td>
                    <td>
                        <center>
                            <button type="button" class="btn btn-primary"
                                value="Actualizar"
                                onclick="actualizarSolicitudSecAcademica('${data[i].idSolicitud}')">
                            Actualizar
                            </button>
                        </center>
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



const obtenerIdDepartamentoJefeConCoordinador = () => {
    const peticion = {
        idUsuario: document.querySelector('#idUsuario').value
    };

    $.ajax(`${ API }/control-recibir-solicitudes/obtenerIdDepartamentoJefeConCoordinador.php`, {
        type: 'POST',
        dataType: 'json',
        data:(peticion),
        success:function(response) {
            //console.log(data);
            let idDepartamentoUsuarioVeedor;
            idDepartamentoUsuarioVeedor = response.data[0].idDepartamento;
            verSolicitudesPendientesJefes(parseInt(idDepartamentoUsuarioVeedor));
            
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
//Petición para que el usuario de tipo Jefe de departamento pueda ver las solicitudes de permisos recibidas
//  por parte de usuario coordinador de departamento, esto correspondiente a al mismo departamento del jefe
const verSolicitudesPendientesJefes = (idDepartamentoUsuarioVeedor) => {
    
    const peticion = {
        idDepartamentoUsuario: idDepartamentoUsuarioVeedor
    };
    console.log(peticion);

    $('#listado-solicitudes').dataTable().fnDestroy();

    $.ajax(`${ API }/control-recibir-solicitudes/listado-solicitudes-pendientesJefes.php`, {
    type: 'POST',
    dataType: 'json',
    data: (peticion),
    //contentType: 'application/json',
    success:function(response) {
        const { data } = response;
        console.log(data);
        $('#listado-solicitudes tbody').html(``);
        
        for (let i=0;i<data.length; i++) {
            $('#listado-solicitudes tbody').append(`
                <tr>
                    <td scope="row">${ i + 1 }</td>
                    <td>${ data[i].tipoSolicitudSalida }</td>
                    <td><center>${ data[i].fechaRegistroSolicitud }</center></td>
                    <td>${ data[i].nombrePersona +' '+ data[i].apellidoPersona }</td>
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
                            onclick="obtenerObservacion(${ data[i].idSolicitud })">
                            <img src="../img/menu/visualizar-icon.svg" alt="hacerObservacion"/>
                        </button>
                    </td>

                    <td>
                        <center>
                            <select class="btn btn-warning select" name="tipo" id="estadoJ">
                                <option value="1"
                                if(${ data[i].idTipoEstadoSolicitud === 1 })selected> Pendiente </option>  
                                <option value="2"
                                if(${ data[i].idTipoEstadoSolicitud === 2 })selected> Aceptado </option>    
                                <option value="3"
                                if(${ data[i].idTipoEstadoSolicitud === 3 })selected> Parcial </option>
                                <option value="4"
                                if(${ data[i].idTipoEstadoSolicitud === 4 })selected> Denegado </option>          
                            </select>
                        </center>
                    </td>
                    <td>
                        <center>
                            <button type="button" class="btn btn-primary" 
                                value="Actualizar"
                                onclick="actualizarSolicitudJefe('${data[i].idSolicitud}')">
                                Actualizar
                            </button>
                        </center>
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


//Petición para que el usuario decano pueda ver las solicitudes de permisos recibidas por parte
// de usuario tipo estratega
const verSolicitudesPendientesDecano = () => {
    $('#listado-solicitudes').dataTable().fnDestroy();

    $.ajax(`${ API }/control-recibir-solicitudes/listado-solicitudes-pendientesDecano.php`, {
    type: 'POST',
    dataType: 'json',
    contentType: 'application/json',
    success:function(response) {
        const { data } = response;
        console.log(data);
        $('#listado-solicitudes tbody').html(``);
        
        for (let i=0;i<data.length; i++) {
            $('#listado-solicitudes tbody').append(`
                <tr>
                    <td scope="row">${ i + 1 }</td>
                    <td>${ data[i].tipoSolicitudSalida }</td>
                    <td><center>${ data[i].fechaRegistroSolicitud }</center></td>
                    <td>${ data[i].nombrePersona +' '+ data[i].apellidoPersona }</td>
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
                            onclick="obtenerObservacion(${ data[i].idSolicitud })">
                            <img src="../img/menu/visualizar-icon.svg" alt="hacerObservacion"/>
                        </button>
                    </td>

                    <td>
                        <center>
                            <select class="btn btn-warning select" name="tipo" id="estadoD">
                                <option value="1"
                                if(${ data[i].idTipoEstadoSolicitud === 1 })selected> Pendiente </option>  
                                <option value="2"
                                if(${ data[i].idTipoEstadoSolicitud === 2 })selected> Aceptado </option>    
                                <option value="3"
                                if(${ data[i].idTipoEstadoSolicitud === 3 })selected> Parcial </option>
                                <option value="4"
                                if(${ data[i].idTipoEstadoSolicitud === 4 })selected> Denegado </option>          
                            </select>
                        </center>
                    </td>
                    <td>
                        <center>
                            <button type="button" class="btn btn-primary" 
                                value="Actualizar"
                                onclick="actualizarSolicitudDecano('${data[i].idSolicitud}')">
                                Actualizar
                            </button>
                        </center>
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





const verHistorialSolicitudes = () => {
    const peticion = {
        idUsuario: document.querySelector('#idUsuario').value
    };
    $('#listado-solicitudes-revisadas').dataTable().fnDestroy();
    // Cargar listado de solicitudes revisadas
    $.ajax(`${ API }/control-recibir-solicitudes/historial-solicitudes-revisadas.php`, {
    type: 'POST',
	dataType: 'json',
    data: (peticion),
    //contentType: 'application/json',
    success:function(response) {
        const { data } = response;
        console.log(data);
        $('#listado-solicitudes-revisadas tbody').html(``);
        
        for (let i=0;i<data.length; i++) {
            $('#listado-solicitudes-revisadas tbody').append(`
            <tr>
                <td scope="row">${ i + 1 }</td>
                <td>${ data[i].tipoSolicitudSalida }</td>
                <td>${ data[i].fechaRevisionSolicitud }</td>
                <td>${ data[i].nombrePersona +' '+ data[i].apellidoPersona }</td>
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
                        <img src="../img/control-recibir-permisos/adjuntos-icono.svg" alt="Ver Adjuntos"/>
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
        $('#listado-solicitudes-revisadas').DataTable({
            language: i18nEspaniol,
            //dom: 'Blfrtip',
            //buttons: botonesExportacion,
            retrieve: true
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


const visualizarSolicitud = (idSolicitud) => {
    let parametros = { idSolicitud: parseInt(idSolicitud) };

    $.ajax(`${ API }/control-recibir-solicitudes/obtenerSolicitudPorId.php`, {
        type: 'POST',
        dataType: 'json',
        data:(parametros),
        success:function(response) {
            //console.log(data);
            $('#modalVerSolicitudRecibida').modal('show');
            $("#V-nombreEmpleado").val(response.data[0].nombrePersona +' '+ response.data[0].apellidoPersona ).trigger("change");
            $("#V-codigoEmpleado").val(response.data[0].codigoEmpleado).trigger("change");
            $("#V-departamentoLabora").val(response.data[0].nombreDepartamento).trigger("change");
            $("#V-motivoPermiso").val(response.data[0].motivoSolicitud).trigger("change");
            $("#V-edificioAsistencia").val(response.data[0].edificioAsistencia).trigger("change");
            $("#V-fechaInicio").val(response.data[0].fechaInicioPermiso).trigger("change");
            $("#V-fechaFin").val(response.data[0].fechaFinPermiso).trigger("change");
            $("#V-horaInicio").val(response.data[0].horaInicioSolicitudSalida).trigger("change");
            $("#V-horaFin").val(response.data[0].horaFinSolicitudSalida).trigger("change");
            $("#V-fechaRecibida").val(response.data[0].fechaRegistroSolicitud).trigger("change");

            let diasSolicitados = response.data[0].diasSolicitados;
            if(diasSolicitados == 0 ){
                $("#V-diasSolicitados").val("El permiso es por horas").trigger("change");
            }else{
                $("#V-diasSolicitados").val("El permiso se registra en"+" "+ diasSolicitados+" "+ "días").trigger("change");
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


const verObservacion = (idSolicitud) => {
    let parametros = { idSolicitud: parseInt(idSolicitud) };

    $.ajax(`${ API }/control-recibir-solicitudes/obtenerObservacionesPorId.php`, {
        type: 'POST',
        dataType: 'json',
        data:(parametros),
        success:function(response) {
            //console.log(data);
            $('#modalVerObservacionesSolicitudRecibida').modal('show');
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


const visualizarAdjuntos = (idSolicitud) => {
    let parametros = { idSolicitud: parseInt(idSolicitud) };

    $.ajax(`${ API }/control-recibir-solicitudes/obtenerImagenRespaldoPorId.php`, {
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




let idSolicitudSeleccionada;
let observacion = document.querySelector('#H-observacionesSolicitud');
let hO = { valorEtiqueta: observacion, id: 'H-observacionesSolicitud', name: 'Hacer Observacion', min: 1, max: 255, type: 'text' };

function obtenerObservacion (idSolicitud) {
    //console.log(idSolicitud);
    idSolicitudSeleccionada = idSolicitud;
    
    let parametros = { idSolicitud: parseInt(idSolicitud) };
    
    $.ajax(`${ API }/control-recibir-solicitudes/obtenerObservacionesPorId.php`, {
        type: 'POST',
        dataType: 'json',
        data:(parametros),
        success:function(response) {
            //console.log(data);
            $('#modalHacerObservacionesSolicitudRecibida').modal('show');
            $("#H-observacionesSolicitud").val(response.data[0].observacionesSolicitud).trigger("change"); 
        },
        error:function(error) {
            console.warn(error);
            const { status, data } = error.responseJSON;
            if (status === 401) {
                window.location.href = '../views/401.php';
            }
        }
    });

}


// Agregamos la observación a la solicitud en revisión
const hacerObservacion = () => {
    //console.log(idSolicitudSeleccionada);
    let isValidObservacion = verificarInputText(hO, letrasEspaciosCaracteresRegex);

    if (isValidObservacion===true) {
        $('#btn-hacer-observacion').prop('disabled', true);
        let parametros = {
            idSolicitud: parseInt(idSolicitudSeleccionada),
            observaciones: observacion.value
        };
        console.log(parametros);

        $.ajax(`${ API }/control-recibir-solicitudes/hacerObservacionesPorId.php`,{
            type: 'POST',
            dataType: 'json',
            //contentType: 'application/json',
            data: JSON.stringify(parametros),
            success:function(response) {
                const { data } = response;
                console.log(response);
                Swal.fire({
                    icon: 'success',
                    title: 'Accion realizada Exitosamente',
                    text: `${ data.message }`,
                });
                
                $('#btn-hacer-observacion').prop('disabled', false);
                $('#modalHacerObservacionesSolicitudRecibida').modal('hide');
            },
            error:function(error) {
                $('#btn-hacer-observacion').prop('disabled', false);
                const { status, data } = error.responseJSON;
                if (status === 401) {
                    window.location.href = '../views/401.php';
                }
                console.error(error);
                Swal.fire({
                    icon: 'error',
                    title: 'Ops...',
                    text: `${ data.message }`,
                    footer: '<b>Por favor verifique el formulario de registro</b>'
                });
            }
        });
        
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Ops...',
            text: 'El registro de la observación no se pudo realizar',
            footer: '<b>Por favor verifique el formulario de registro</b>'
        })
    }
}


/*
const obtenerEstadoSolicitud = () => {
    const peticion = {
        vacio: ""
    };
    $.ajax(`${ API }/control-recibir-solicitudes/obtenerEstadoSolicitud.php`, {
        type: 'POST',
        dataType: 'json',
        data: (peticion),
        success:function(response) {
            document.getElementById("estado").innerHTML = ``;
            for(let i = 0; i <response.data.length;i++){
                document.getElementById("estado").innerHTML+=`
                <option value="${response.data[i].idTipoEstadosolicitud}">${response.data[i].TipoEstadoSolicitudSalida}</option>  
            `;
                //$('#estado').html(`<option value="${response.data[i].idTipoEstadosolicitudsalida}">${response.data[i].TipoEstadoSolicitudSalida}</option>`);

            }
        },
        error:function(error) {
            console.warn(error);
        }
    });
};
*/

let idUsuarioVeedor = document.querySelector('#V-idUsuario');

const actualizarSolicitudSecAcademica = (idSolicitud) => {
    
    let parametros = { idSolicitud: parseInt(idSolicitud),
                        idEstadoSolicitud : parseInt($('#estado').val()),
                        idUsuario: parseInt(idUsuarioVeedor.value)
                     };
    console.log(parametros);
    
    $.ajax(`${ API }/control-recibir-solicitudes/actualizarSolicitudPorIdSecAcademica.php`, {
        type: 'POST',
        dataType: 'json',
        //contentType: 'application/json',
        data:(parametros),
        success:function(response) {
            const { data } = response;
            console.log(data);
            Swal.fire({
                icon: 'success',
                title: 'Accion realizada Exitosamente',
                text: `${ data.message }`,
            }); 
                verSolicitudesPendientesSecAcademica();
        },
        error:function(error) {
            console.warn(error);
            Swal.fire({
                icon: 'error',
                title: 'Ops...',
                text: `${ data.message }`,
                footer: '<b>Por favor verifique el formulario de registro</b>'
            });
            const { status, data } = error.responseJSON;
            if (status === 401) {
                window.location.href = '../views/401.php';
            }    
        }
    });

}



const actualizarSolicitudJefe = (idSolicitud) => {
    
    let parametros = { idSolicitud: parseInt(idSolicitud),
                        idEstadoSolicitud : parseInt($('#estadoJ').val()),
                        idUsuario: parseInt(idUsuarioVeedor.value)
                     };
    console.log(parametros);
    
    $.ajax(`${ API }/control-recibir-solicitudes/actualizarSolicitudPorIdJefe.php`, {
        type: 'POST',
        dataType: 'json',
        //contentType: 'application/json',
        data:(parametros),
        success:function(response) {
            const { data } = response;
            console.log(data);
            Swal.fire({
                icon: 'success',
                title: 'Accion realizada Exitosamente',
                text: `${ data.message }`,
            }); 
            obtenerIdDepartamentoJefeConCoordinador();
        },
        error:function(error) {
            console.warn(error);
            Swal.fire({
                icon: 'error',
                title: 'Ops...',
                text: `${ data.message }`,
                footer: '<b>Por favor verifique el formulario de registro</b>'
            });
            const { status, data } = error.responseJSON;
            if (status === 401) {
                window.location.href = '../views/401.php';
            }    
        }
    });

}


const actualizarSolicitudDecano = (idSolicitud) => {
    
    let parametros = { idSolicitud: parseInt(idSolicitud),
                        idEstadoSolicitud : parseInt($('#estadoD').val()),
                        idUsuario: parseInt(idUsuarioVeedor.value)
                     };
    console.log(parametros);
    
    $.ajax(`${ API }/control-recibir-solicitudes/actualizarSolicitudPorIdDecano.php`, {
        type: 'POST',
        dataType: 'json',
        //contentType: 'application/json',
        data:(parametros),
        success:function(response) {
            const { data } = response;
            console.log(data);
            Swal.fire({
                icon: 'success',
                title: 'Accion realizada Exitosamente',
                text: `${ data.message }`,
            }); 
                verSolicitudesPendientesDecano();
        },
        error:function(error) {
            console.warn(error);
            Swal.fire({
                icon: 'error',
                title: 'Ops...',
                text: `${ data.message }`,
                footer: '<b>Por favor verifique el formulario de registro</b>'
            });
            const { status, data } = error.responseJSON;
            if (status === 401) {
                window.location.href = '../views/401.php';
            }    
        }
    });

}



////cancelarGuardarOservacion
const cancelarGuardarOservacion = () => {
    limpiarCamposFormulario(hO);
    $(`#H-observacionesSolicitud`).trigger('reset');
    
}