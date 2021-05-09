// Petición para listar losregistros de estudiantes matriculados, egresados y docentes con maestría 
const verTodos = () => {
    verRegistrosTotales();
    Swal.fire({
        icon: 'success',
        title: `Mostrando Todos los Registros`
    });
}

//Petición para mostrar todos los registros  
const verRegistrosTotales = () => {
    $('#listadoRegistros').dataTable().fnDestroy();

    $.ajax(`${ API }/control-estudiantes-docentes-vistaEstratega/registrosTotales.php`, {
    type: 'POST',
    dataType: 'json',
    contentType: 'application/json',
    success:function(response) {
        const { data } = response;
        console.log(data);

        $('#listadoRegistros tbody').html(``);
        
        for (let i=0;i<data.length; i++) {
            $('#listadoRegistros tbody').append(`
                <tr>
                    <td scope="row">${ i + 1 }</td>
                    <td scope="col"><center class="m-auto">${ data[i].nombreDepartamento}</center></td>
                    <td scope="col"><center class="m-auto">${ data[i].nombreTrimeste }</center></td>
                    <td scope="col"><center class="m-auto">${ data[i].fechaRegistro }</center></td>
                    <td scope="col"><center class="m-auto">${ data[i].fechaModificacion === null ? `Sin Modificar` : data[i].fechaModificacion }</td>
                    <td scope="col"><center class="m-auto">${ data[i].poblacion }</center></td>
                    <td scope="col"><center class="m-auto">${ data[i].cantidad }</center></td>
                    <td scope="col"><center class="m-auto">${ data[i].registro }</center></td>
                    
                    <td scope="col"><center class="m-auto">${ data[i].modifico === null ? `Sin Modificar` : data[i].modifico}</td>

                    <td class="text-center">
                        <button type="button" class="btn btn-info btn-sm m-auto"
                        onclick="visualizarAdjuntos('${data[i].idGestion}')">
                        <img src="../img/control-recibir-permisos/adjuntos-icono.svg" alt="Ver Mas"/>
                        </button>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-info btn-sm m-auto"
                            onclick="verModificacionRespaldo('${data[i].idGestion}')"
                        >
                            <img src="../img/control-estudiantes-docentes/upload.svg" alt="Ver Mas"/>
                        </button>
                    </td>
                    <td class="my-auto">
                        <button type="button" class="btn btn-info btn-sm m-auto" 
                            onclick="verModificar('${data[i].idGestion}','${data[i].cantidad}','${data[i].nombreTrimeste}','${data[i].poblacion}')">
                            <img src="../img/menu/visualizar-icon.svg" alt="verModificacion"/>
                        </button>
                    </td>
                    

                </tr>
            `)
        }
        $('#listadoRegistros').DataTable({
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



//petición para obtener la imagen adjunta del registro seleccionado
const visualizarAdjuntos = (idGestion) => {
    let parametros = { idGestion: parseInt(idGestion) };

    $.ajax(`${ API }/control-estudiantes-docentes-vistaEstratega/obtenerImagenRespaldo.php`, {
        type: 'POST',
        dataType: 'json',
        data:(parametros),
        success:function(response) {
            const { data } = response;
            $('#modalRespaldoAdjunto').modal('show');
            let respaldo = data[0].documentoRespaldo;
            if (respaldo.length != 0){
                $('#V-respaldoAdjunto').html(`<img src="../../../backend/uploads/documentoRespaldo/departamento/${respaldo}" class="mx-auto img-fluid  img-responsive" /> `);
            }else {
                $('#V-respaldoAdjunto').html(`<h6 align="center" style="color:#ffc107" > No se adjunto imagen de respaldo <h6/> `);
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
}



// Visualización modal Modificar Registro seleccionado
const verModificar = (id,Cantidad,trimestre,poblacion) =>{
    $("#modalModificarDocentesEstudiantes").modal('toggle');
    $("#cerrarM").attr("onclick",`CerrarModalModificacion('${poblacion}')`);
    $("#modificarNumero").attr("onclick",`Modificar('${poblacion}','${id}')`);
    obtenerTrimestre(Cantidad,trimestre);
};

const verModificarPorDepartamento = (id,Cantidad,trimestre,poblacion,idDepartamento) =>{
    $("#modalModificarDocentesEstudiantes").modal('toggle');
    $("#cerrarM").attr("onclick",`CerrarModalModificacion('${poblacion}')`);
    $("#modificarNumero").attr("onclick",`ModificarPorDepartamento('${poblacion}','${id}','${idDepartamento}')`);
    obtenerTrimestre(Cantidad,trimestre);
};

const verModificarPorTipoRegistro = (id,Cantidad,trimestre,poblacion,idTipoGestion) =>{
    $("#modalModificarDocentesEstudiantes").modal('toggle');
    $("#cerrarM").attr("onclick",`CerrarModalModificacion('${poblacion}')`);
    $("#modificarNumero").attr("onclick",`ModificarPorTipoRegistros('${poblacion}','${id}','${idTipoGestion}')`);
    obtenerTrimestre(Cantidad,trimestre);
};

// Capturando las etiquetas completas de los inputs para despues obtener el valor
let Trimestre = document.querySelector('#TrimestreModificacion');
let numeroPoblacion = document.querySelector('#Cantidad');

let tR = { valorEtiqueta: Trimestre, id: 'TrimestreModificacion', name: 'TrimestreModificacion', type: 'select' };
let nP = { valorEtiqueta: numeroPoblacion, id: 'Cantidad', name: 'Cantidad', min: 1, max: 4, type: 'text' };

//Petición para modificar el registro seleccionado
const Modificar = (poblacion,idGestion)=>{
    
    // Llamando a las funciones para realizar la verificacion de los campos retorna true o false
    let isValidTrimestre = verificarSelect(tR);
    let isValidnumeroPoblacion = verificarInputNumber(nP,numerosRegex);

   // Si todos los campos que llevan validaciones estan correctos se realiza la petición
   if (
       (isValidTrimestre === true) &&
       (isValidnumeroPoblacion === true) 
   ) {
        const dataPoblacionRegistro = {
           Trimestre: Trimestre.value,
           numeroPoblacion: JSON.parse(numeroPoblacion.value),
           idGestion: parseInt(idGestion)
        };
        $.ajax(`${ API }/control-estudiantes-docentes-vistaEstratega/modificarDocentesEstudiantesEstratega.php`, {
            type: 'POST',
            dataType: 'json',
            data: (dataPoblacionRegistro),
            success:function(response) {
                    const { data } = response;
                    console.log(data)
                    $("#TrimestreModificacion").val('').trigger("change");
                    $('#Cantidad').val('').trigger("change");
                    Swal.fire({
                        icon: 'success',
                        title: 'Acción realizada Exitosamente',
                        text: `Cantidad de ${poblacion} se modifico con exito`,
                        footer: '<b></b>'
                    });
                    $('#modalModificarDocentesEstudiantes').modal('toggle');
                    verRegistrosTotales();
            },
            error:function(error) {
                const { status, data } = error.responseJSON;
                console.error(error);
                if (status === 401) {
                    window.location.href = '../views/401.php';
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Ops...',
                        text: `${ data.message }`,
                        footer: '<b>Por favor verifique el formulario de registro</b>'
                    })
                };
            }
        });
   } else { // caso contrario mostrar alerta y notificar al usuario 
       Swal.fire({
           icon: 'error',
           title: 'Ops...',
           text: 'La Modificación del registro no se pudo realizar',
           footer: '<b>Por favor verifique el formulario de registro</b>'
       })
   }
}



//Petición para modificar el registro seleccionado
const ModificarPorDepartamento = (poblacion,idGestion,idDepartamento)=>{
    let idDepartamentoVer = idDepartamento;
    // Llamando a las funciones para realizar la verificacion de los campos retorna true o false
    let isValidTrimestre = verificarSelect(tR);
    let isValidnumeroPoblacion = verificarInputNumber(nP,numerosRegex);

   // Si todos los campos que llevan validaciones estan correctos se realiza la petición
   if (
       (isValidTrimestre === true) &&
       (isValidnumeroPoblacion === true) 
   ) {
        const dataPoblacionRegistro = {
           Trimestre: Trimestre.value,
           numeroPoblacion: JSON.parse(numeroPoblacion.value),
           idGestion: parseInt(idGestion)
        };
        $.ajax(`${ API }/control-estudiantes-docentes-vistaEstratega/modificarDocentesEstudiantesEstratega.php`, {
            type: 'POST',
            dataType: 'json',
            data: (dataPoblacionRegistro),
            success:function(response) {
                    const { data } = response;
                    console.log(data)
                    $("#TrimestreModificacion").val('').trigger("change");
                    $('#Cantidad').val('').trigger("change");
                    Swal.fire({
                        icon: 'success',
                        title: 'Acción realizada Exitosamente',
                        text: `Cantidad de ${poblacion} se modifico con exito`,
                        footer: '<b></b>'
                    });
                    $('#modalModificarDocentesEstudiantes').modal('toggle');
                    verRegistrosPorDepartamento(idDepartamentoVer);
            },
            error:function(error) {
                const { status, data } = error.responseJSON;
                console.error(error);
                if (status === 401) {
                    window.location.href = '../views/401.php';
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Ops...',
                        text: `${ data.message }`,
                        footer: '<b>Por favor verifique el formulario de registro</b>'
                    })
                };
            }
        });
   } else { // caso contrario mostrar alerta y notificar al usuario 
       Swal.fire({
           icon: 'error',
           title: 'Ops...',
           text: 'La Modificación del registro no se pudo realizar',
           footer: '<b>Por favor verifique el formulario de registro</b>'
       })
   }
}


const ModificarPorTipoRegistros = (poblacion,idGestion,idTipoGestion)=>{
    
    // Llamando a las funciones para realizar la verificacion de los campos retorna true o false
    let isValidTrimestre = verificarSelect(tR);
    let isValidnumeroPoblacion = verificarInputNumber(nP,numerosRegex);

   // Si todos los campos que llevan validaciones estan correctos se realiza la petición
   if (
       (isValidTrimestre === true) &&
       (isValidnumeroPoblacion === true) 
   ) {
        const dataPoblacionRegistro = {
           Trimestre: Trimestre.value,
           numeroPoblacion: JSON.parse(numeroPoblacion.value),
           idGestion: parseInt(idGestion)
        };
        $.ajax(`${ API }/control-estudiantes-docentes-vistaEstratega/modificarDocentesEstudiantesEstratega.php`, {
            type: 'POST',
            dataType: 'json',
            data: (dataPoblacionRegistro),
            success:function(response) {
                    const { data } = response;
                    console.log(data)
                    $("#TrimestreModificacion").val('').trigger("change");
                    $('#Cantidad').val('').trigger("change");
                    Swal.fire({
                        icon: 'success',
                        title: 'Acción realizada Exitosamente',
                        text: `Cantidad de ${poblacion} se modifico con exito`,
                        footer: '<b></b>'
                    });
                    $('#modalModificarDocentesEstudiantes').modal('toggle');
                    verRegistrosPorTipo(idTipoGestion);
            },
            error:function(error) {
                const { status, data } = error.responseJSON;
                console.error(error);
                if (status === 401) {
                    window.location.href = '../views/401.php';
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Ops...',
                        text: `${ data.message }`,
                        footer: '<b>Por favor verifique el formulario de registro</b>'
                    })
                };
            }
        });
   } else { // caso contrario mostrar alerta y notificar al usuario 
       Swal.fire({
           icon: 'error',
           title: 'Ops...',
           text: 'La Modificación del registro no se pudo realizar',
           footer: '<b>Por favor verifique el formulario de registro</b>'
       })
   }
}

//Petición para obtener los trimestres de los registros 
const obtenerTrimestre = (Cantidad,trimestre) =>{

    $.ajax(`${ API }/control-estudiantes-docentes-vistaEstratega/obtenerTrimestres.php`, {
        type: 'POST',
        dataType: 'json',
        success:function(response) {
            const { data } = response;
            document.getElementById("TrimestreModificacion").innerHTML=`<option value="" disabled>Seleccione un trimestre</option>`;

            for(let i = 0; i < data.length;i++){
                if(trimestre===data[i].nombreTrimeste){
                    document.getElementById("TrimestreModificacion").innerHTML+=`<option value="${data[i].idTrimestre}" selected>${data[i].nombreTrimeste}</option>`;
                }else{
                    document.getElementById("TrimestreModificacion").innerHTML+=`<option value="${data[i].idTrimestre}">${data[i].nombreTrimeste}</option>`;
                }
            }

            $(`#Cantidad`).val(Cantidad).trigger("change");
            
        },
        error:function(error) {
            console.log(error);
            const { status, data } = error.responseJSON;
            if (status === 401) {
                window.location.href = '../views/401.php';
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Ops...',
                    text: `${ data.message }`,
                    footer: '<b>Por favor verifique el formulario de registro</b>'
                })
            };
        }
    });
};


// Visualización modal Modificar respaldo
const verModificacionRespaldo = (idGestion) =>{
    $("#modalModificarRespaldo").modal('toggle');
    $("#modificarRespaldo").attr("onclick",`modificacionRespaldo('${idGestion}')`);
};


//Petición para modificar el repaldo adjunto al registro seleccionado
const modificacionRespaldo = (idGestion) =>{
    const formData = new FormData($(`#formulario-modificacion-respaldo`)[0]);
    formData.append('idGestion', parseInt(idGestion));
    for (var value of formData.values()) {
        console.log(value);
    }
    $.ajax(`${ API }/control-estudiantes-docentes-vistaEstratega/modificarRespaldo.php`, {
         type: 'POST',
         data: formData,
         cache: false,
         processData: false,
         contentType: false,
        success:function(response) {
            //  const { data } = response;
             Swal.fire({
                 icon: 'success',
                 title: 'Acción realizada Exitosamente',
                 text: `El documento de respaldo se modifico con exito.`,
                 footer: '<b></b>'
             });
             $("#docUpload").val('');
             $("#respaldoM").val('').trigger('change');
             $("#modalModificarRespaldo").modal('toggle');
        },
        error:function(error) {
            console.error(error)
            const { status, data } = error.responseJSON;
            if (status === 401) {
                window.location.href = '../views/401.php';
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Ops...',
                    text: `${ data.message }`,
                    footer: '<b>Por favor verifique el formulario de registro</b>'
                })
            };
        }
    });
}


// mostrar valor subido, modal modificar respaldo
const mostrarValorRespaldoASubir = () =>{
    let file = $('#documentoSubidoM').prop('files');
    $('#respaldoM').val(file[0].name);
}


//limpiar el fromulario de modificación
const CerrarModalModificacion = () => {
    limpiarCamposFormulario(nP);
    document.querySelector('#Cantidad').value = '';
}


//Petición para listar los departamentos
const listarDepartamentos = () => {
    const peticion = {
        vacio: ""
    };
    $.ajax(`${ API }/control-estudiantes-docentes-vistaEstratega/obtenerDepartamentos.php`, {
        type: 'POST',
        dataType: 'json',
        data: (peticion),
        success:function(response) {
            document.getElementById("Departamento").innerHTML="<option value= disabled selected></option>";
            for(let i = 0; i < response.data.length;i++){
                document.getElementById("Departamento").innerHTML+=`<option value="${response.data[i].idDepartamento}">${response.data[i].nombreDepartamento}</option>`;
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


//Obtener el id del departamento para filtrar los registros por deparatamento
const GuardarIdDepartamento = () => {
    idDepartamento = document.querySelector("#Departamento").value;
    departamento = $('select[name="Departamento"] option:selected').text();
    $("#modalSeleccionDepartamento").modal('toggle');
    verRegistrosPorDepartamento(idDepartamento);
    Swal.fire({
        icon: 'success',
        title: `Mostrando registros respecto al departartamento ${departamento}`
    });
}


//Petición para mostrar los registros por departamento
const verRegistrosPorDepartamento = (idDepartamento) => {
    const peticion = {
        idDepartamentoVer: idDepartamento
    };
    $('#listadoRegistros').dataTable().fnDestroy();

    $.ajax(`${ API }/control-estudiantes-docentes-vistaEstratega/registrosPorDepartamento.php`, {
    type: 'POST',
    dataType: 'json',
    data: (peticion),
    
    success:function(response) {
        const { data } = response;
        console.log(data);
        
        $('#listadoRegistros tbody').html(``);
        
        for (let i=0;i<data.length; i++) {
            $('#listadoRegistros tbody').append(`
                <tr>
                    <td scope="row">${ i + 1 }</td>
                    <td scope="col"><center class="m-auto">${ data[i].nombreDepartamento}</td>
                    <td scope="col"><center class="m-auto">${ data[i].nombreTrimeste }</center></td>
                    <td scope="col"><center class="m-auto">${ data[i].fechaRegistro }</center></td>
                    <td scope="col"><center class="m-auto">${ data[i].fechaModificacion === null ? `Sin Modificar` : data[i].fechaModificacion }</td>
                    <td scope="col"><center class="m-auto">${ data[i].poblacion }</center></td>
                    <td scope="col"><center class="m-auto">${ data[i].cantidad }</center></td>
                    <td scope="col"><center class="m-auto">${ data[i].registro }</center></td>
                    
                    <td scope="col"><center class="m-auto">${ data[i].modifico === null ? `Sin Modificar` : data[i].modifico}</td>

                    <td class="text-center">
                        <button type="button" class="btn btn-info btn-sm m-auto"
                        onclick="visualizarAdjuntos('${data[i].idGestion}')">
                        <img src="../img/control-recibir-permisos/adjuntos-icono.svg" alt="Ver Mas"/>
                        </button>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-info btn-sm m-auto"
                            onclick="verModificacionRespaldo('${data[i].idGestion}')"
                        >
                            <img src="../img/control-estudiantes-docentes/upload.svg" alt="Ver Mas"/>
                        </button>
                    </td>
                    <td class="my-auto">
                        <button type="button" class="btn btn-info btn-sm m-auto" 
                            onclick="verModificarPorDepartamento('${data[i].idGestion}','${data[i].cantidad}','${data[i].nombreTrimeste}','${data[i].poblacion}','${idDepartamento}')">
                            <img src="../img/menu/visualizar-icon.svg" alt="verModificacion"/>
                        </button>
                    </td> 
                    
                </tr>
            `)
        }
        $('#listadoRegistros').DataTable({
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


//Petición para obtener los tipos de gestiones 
const listarTipoRegistros = () => {
    const peticion = {
        vacio: ""
    };
    $.ajax(`${ API }/control-estudiantes-docentes-vistaEstratega/obtenerTipoGestion.php`, {
        type: 'POST',
        dataType: 'json',
        data: (peticion),
        success:function(response) {
            document.getElementById("tipoRegistro").innerHTML="<option value= disabled selected></option>";
            for(let i = 0; i < response.data.length;i++){
                document.getElementById("tipoRegistro").innerHTML+=`<option value="${response.data[i].idTipoGestion}">${response.data[i].nombre}</option>`;
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


//Obtener el id del tipo de registro seleccionado para filtrar los registros por tipo
const GuardarIdTipoRegistro = () => {
    idTipoGestion = document.querySelector("#tipoRegistro").value;
    nombreGestion = $('select[name="tipoRegistro"] option:selected').text();
    $("#modalSeleccionTipoRegistro").modal('toggle');
    verRegistrosPorTipo(idTipoGestion);
    Swal.fire({
        icon: 'success',
        title: `Mostrando registros respecto al tipo ${nombreGestion}`
    });
}


// Petición para mostrar los registros por tipo de gestión
const verRegistrosPorTipo = (idTipoGestion) => {
    const peticion = {
        idTipoGestionVer: idTipoGestion
    };
    $('#listadoRegistros').dataTable().fnDestroy();

    $.ajax(`${ API }/control-estudiantes-docentes-vistaEstratega/registrosPorTipoGestion.php`, {
    type: 'POST',
    dataType: 'json',
    data: (peticion),
    
    success:function(response) {
        const { data } = response;
        console.log(data);
        
        $('#listadoRegistros tbody').html(``);
        
        for (let i=0;i<data.length; i++) {
            $('#listadoRegistros tbody').append(`
                <tr>
                    <td scope="row">${ i + 1 }</td>
                    <td scope="col"><center class="m-auto">${ data[i].nombreDepartamento}</td>
                    <td scope="col"><center class="m-auto">${ data[i].nombreTrimeste }</center></td>
                    <td scope="col"><center class="m-auto">${ data[i].fechaRegistro }</center></td>
                    <td scope="col"><center class="m-auto">${ data[i].fechaModificacion === null ? `Sin Modificar` : data[i].fechaModificacion }</td>
                    <td scope="col"><center class="m-auto">${ data[i].poblacion }</center></td>
                    <td scope="col"><center class="m-auto">${ data[i].cantidad }</center></td>
                    <td scope="col"><center class="m-auto">${ data[i].registro }</center></td>
                    
                    <td scope="col"><center class="m-auto">${ data[i].modifico === null ? `Sin Modificar` : data[i].modifico}</td>

                    <td class="text-center">
                        <button type="button" class="btn btn-info btn-sm m-auto"
                        onclick="visualizarAdjuntos('${data[i].idGestion}')">
                        <img src="../img/control-recibir-permisos/adjuntos-icono.svg" alt="Ver Mas"/>
                        </button>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-info btn-sm m-auto"
                            onclick="verModificacionRespaldo('${data[i].idGestion}')"
                        >
                            <img src="../img/control-estudiantes-docentes/upload.svg" alt="Ver Mas"/>
                        </button>
                    </td>
                    <td class="my-auto">
                        <button type="button" class="btn btn-info btn-sm m-auto" 
                            onclick="verModificarPorTipoRegistro('${data[i].idGestion}','${data[i].cantidad}','${data[i].nombreTrimeste}','${data[i].poblacion}','${idTipoGestion}')">
                            <img src="../img/menu/visualizar-icon.svg" alt="verModificacion"/>
                        </button>
                    </td> 
                    
                </tr>
            `)
        }
        $('#listadoRegistros').DataTable({
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