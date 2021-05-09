$(document).ready(function(){
    verRegistro();
});

// visualizacion en dataTable

const obtenerDocentesEstudiantes = () =>{
    let idUsuario = { 'idUsuario': Usuario['idUsuario'] };

    $.ajax(`${ API }/control-estudiantes-docentes/obtenerCantidadPersonas.php`, {
        type: 'POST',
        dataType: 'json',
        data: (idUsuario),
        success:function(response) {
                const { data } = response;
                $('#DocentesEstudiantes').dataTable().fnDestroy();
                $('#DocentesEstudiantes tbody').html(``);
                for (let i=0;i<data.length; i++) {
                    let fecha;
                    let usuario;
                    if(data[i].fechaModificacion==null || data[i].modifico==null){
                        fecha="No modificado";
                        usuario="No modificado"
                    }else{
                        fecha=data[i].fechaModificacion
                        usuario=data[i].modifico
                    }
                    $('#DocentesEstudiantes tbody').append(`
                        <tr>
                            <td scope="col"><center class="m-auto">${ i + 1 }</center></td>
                            <td scope="col"><center class="m-auto">${ data[i].nombreTrimeste }</center></td>
                            <td scope="col"><center class="m-auto">${ data[i].fechaRegistro }</center></td>
                            <td scope="col"><center class="m-auto">${ fecha }</center></td>
                            <td scope="col"><center class="m-auto">${ data[i].poblacion }</center></td>
                            <td scope="col"><center class="m-auto">${ data[i].cantidad }</center></td>
                            <td scope="col"><center class="m-auto">${ data[i].registro }</center></td>
                            <td scope="col"><center class="m-auto">${ usuario }</center></td>
                            <td class="text-center" scope="col">
                                <button type="button" class="btn btn-info btn-sm m-auto"
                                onclick="visualizarAdjuntos('${data[i].idGestion}')">
                                <img src="../img/control-recibir-permisos/adjuntos-icono.svg" alt="Ver Mas"/>
                                </button>
                            </td>
                            <td class="text-center" scope="col">
                                    <button type="button" class="btn btn-info btn-sm m-auto"
                                        onclick="verModificacionRespaldo('${data[i].idGestion}')"
                                    >
                                        <img src="../img/control-estudiantes-docentes/upload.svg" alt="Ver Mas"/>
                                    </button>
                            </td>
                            <td class="my-auto" scope="col">
                                <button type="button" class="btn btn-info btn-sm m-auto" 
                                    onclick="verModificar('${data[i].idTipoGestion}','${data[i].idGestion}','${data[i].cantidad}','${data[i].nombreTrimeste}','${data[i].poblacion}','${data[i].fechaRegistro}')">
                                    <img src="../img/menu/visualizar-icon.svg" alt="verObservacion"/>
                                </button>
                            </td>
                            
                        </tr>
                    `)
                }
                $('#DocentesEstudiantes').DataTable({
                    language: i18nEspaniol,
                    retrieve: true
                });
        },
        error:function(error) {
            const { status, data } = error.responseJSON;
            console.warn(error);
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

// llenado de select de poblacion en modal registro

const verRegistro = () =>{
    Poblacion = ["Estudiantes Matriculados","Egresados","Docentes con Maestria"];
        
    document.querySelector("#Poblacion").innerHTML='<option value="" selected disabled>Seleccione poblacion a ingresar</option>';

    for(let i = 1; i <= Poblacion.length;i++){
        document.querySelector("#Poblacion").innerHTML+=`<option value="${i}">${Poblacion[i-1]}</option>`;
    }

    var element = document.querySelector("#Poblacion");

    element.addEventListener('focus', function () {
        this.size=3;
    });
    element.addEventListener('change', function () {
        this.size=1;
        this.blur();
    });
    element.addEventListener('blur', function () {
        this.size=1;
    });
}

// 1) Cambiar modal en registro cuando se seleccione que poblacion se registrara.
// 2) Obtener trimestres y colocar en select.

const cambiarFormulario = () =>{
    $("#tabla").css("display","block");
    $("#registrarNumero").prop("disabled",false);

    $("#respaldo").val('').trigger("change");
    $("#documentoSubido").val('');
    document.querySelector(`#labelrespaldo`).classList.remove('text-danger')
    document.querySelector('#respaldo').classList.remove('text-danger');
    document.querySelector('#respaldo').classList.remove('is-invalid')
    document.querySelector(`#errorsrespaldo`).classList.add('d-none');

    document.querySelector('#Trimestre').classList.remove('text-danger');
    document.querySelector('#Trimestre').classList.remove('is-invalid')
    document.querySelector(`#errorsTrimestre`).classList.add('d-none');

    $.ajax(`${ API }/control-estudiantes-docentes/obtenerTrimestres.php`, {
        type: 'POST',
        dataType: 'json',
        success:function(response) {
            const { data } = response;
            document.getElementById("Trimestre").innerHTML=`<option value="" selected disabled>Seleccione un trimestre</option>`;

            for(let i = 0; i < data.length;i++){
                document.getElementById("Trimestre").innerHTML+=`<option value="${data[i].idTrimestre}">${data[i].nombreTrimeste}</option>`;
            }

            var element = document.querySelector("#Trimestre");

            element.addEventListener('focus', function () {
                this.size=4;
            });
            element.addEventListener('change', function () {
                this.size=1;
                this.blur();
            });
            element.addEventListener('blur', function () {
                this.size=1;
            });
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

    idPoblacion = $('select[id="Poblacion"] option:selected').text();
    switch(idPoblacion) {
        case 'Estudiantes Matriculados':
            $("#tituloRegistro").html('Estudiantes Matriculados');
            $("#campoNum").html(`
                <!-- numero estudiantes matriculados -->
                <div class="md-form">
                    <input type="number" id="NumEstudiantesMatriculados" class="form-control" maxlength="2">
                    <span id="errorsNumEstudiantesMatriculados" class="text-danger text-small d-none">
                    </span>
                    <label 
                        for="NumEstudiantesMatriculados"
                        id="labelNumEstudiantesMatriculados"
                    >
                    Cantidad estudiantes matriculados
                    </label>
                </div>`
            );
            // $("#registrarNumero").attr("onclick",`enviar('NumEstudiantesMatriculados')`);
          break;
        case 'Egresados':
            $("#tituloRegistro").html('Egresados');
            $("#campoNum").html(`
                <!-- numero egresados -->
                <div class="md-form">
                    <input type="number" id="NumEgresados" class="form-control" maxlength="2">
                    <span id="errorsNumEgresados" class="text-danger text-small d-none">
                    </span>
                    <label 
                        for="NumEgresados"
                        id="labelNumEgresados"
                    >
                    Cantidad egresados
                    </label>
                </div>`
            );
            // $("#registrarNumero").attr("onclick",`enviar('NumEgresados')`);
          break;
        case 'Docentes con Maestria':
            $("#tituloRegistro").html('Docentes con Maestria');
            $("#campoNum").html(`
                <!-- numero docentes con maestria -->
                <div class="md-form">
                    <input type="number" id="NumDocentes" class="form-control" maxlength="2">
                    <span id="errorsNumDocentes" class="text-danger text-small d-none">
                    </span>
                    <label 
                        for="NumDocentes"
                        id="labelNumDocentes"
                    >
                    Cantidad docentes con maestria
                    </label>
                </div>`
            );
            // $("#registrarNumero").attr("onclick",`enviar('NumDocentes')`);
          break;
        default:
          break;
        }
    poblacionTemp=null;
};

// visualizacion modal Modificar
const verModificar = (idTipoGestion,id,Cantidad,trimestre,poblacion,fechaRegistro) =>{
    $("#modalModificarDocentesEstudiantes").modal('toggle');
    $("#cerrarM").attr("onclick",`CerrarModalM('${poblacion}')`);
    $("#modificarNumero").attr("onclick",`Modificar('${idTipoGestion}','${poblacion}','${id}','${fechaRegistro}')`);
    cambiarFormularioM(id,Cantidad,trimestre,poblacion);
};

// visualizacion modal Modificar respaldo
const verModificacionRespaldo = (idGestion) =>{
    $("#modalModificarRespaldo").modal('toggle');
    $("#modificarRespaldo").attr("onclick",`modificacionRespaldo('${idGestion}')`);
};

// cambiar valores formulario modificacion

const cambiarFormularioM = (id,Cantidad,trimestre,poblacion) =>{

    $.ajax(`${ API }/control-estudiantes-docentes/obtenerTrimestres.php`, {
        type: 'POST',
        dataType: 'json',
        success:function(response) {
            const { data } = response;
            document.getElementById("TrimestreM").innerHTML=`<option value="" disabled>Seleccione un trimestre</option>`;

            for(let i = 0; i < data.length;i++){
                if(trimestre===data[i].nombreTrimeste){
                    document.getElementById("TrimestreM").innerHTML+=`<option value="${data[i].idTrimestre}" selected>${data[i].nombreTrimeste}</option>`;
                }else{
                    document.getElementById("TrimestreM").innerHTML+=`<option value="${data[i].idTrimestre}">${data[i].nombreTrimeste}</option>`;
                }
            }

            var element = document.querySelector("#TrimestreM");

            element.addEventListener('focus', function () {
                this.size=4;
            });
            element.addEventListener('change', function () {
                this.size=1;
                this.blur();
            });
            element.addEventListener('blur', function () {
                this.size=1;
            });

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

// enviar resumen, formulario registrar poblacion

const enviar = () =>{
    // Capturando las etiquetas completas de los inputs para despues obtener el valor
    let Trimestre = document.querySelector('#Trimestre');
    let numeroPoblacion = null;
    let respaldo = document.querySelector('#respaldo');

    let poblacion = null;

    if(document.querySelector("#Poblacion").value==1){
        numeroPoblacion=document.querySelector('#NumEstudiantesMatriculados');
        poblacion="NumEstudiantesMatriculados";
    }else if(document.querySelector("#Poblacion").value==2){
        numeroPoblacion=document.querySelector('#NumEgresados');
        poblacion="NumEgresados";
    }else if(document.querySelector("#Poblacion").value==3){
        numeroPoblacion=document.querySelector('#NumDocentes');
        poblacion="NumDocentes";
    }
    // Tipando los atributos con los valores de la base de datos bueno algunos
    let tR = { valorEtiqueta: Trimestre, id: 'Trimestre', name: 'Trimestre', type: 'select' };
    let nP = { valorEtiqueta: numeroPoblacion, id: poblacion, name: poblacion, min: 1, max: 4, type: 'text' };
   

    // Llamando a las funciones para realizar la verificacion de los campos retorna true o false
    let isValidTrimestre = verificarSelect(tR);
    let isValidnumeroPoblacion = verificarInputNumber(nP,numerosRegex);

   // Si todos los campos que llevan validaciones estan okey o true que realice el ajax o fetch o axios o lo que sea
   if (
       (isValidTrimestre === true) &&
       (isValidnumeroPoblacion === true)
   ) {
       if(document.getElementById("respaldo").value!=null){
            const formData = new FormData($("#formulario-registro-poblacion")[0]);
            formData.append('Trimestre', Trimestre.value);
            formData.append('numeroPoblacion', JSON.parse(numeroPoblacion.value));
            formData.append('respaldo', respaldo.value);
            formData.append('poblacion', poblacion);
            formData.append('idUsuario', Usuario['idUsuario']);
            $.ajax(`${ API }/control-estudiantes-docentes/ingresarDocentesEstudiantes.php`, {
                    type: 'POST',
                    data: formData,
                    cache: false,
                    processData: false,
                    contentType: false,
                success:function(response) {
                        const { data } = response;

                        $("#Trimestre").val('').trigger("change");
                        $("#respaldo").val('').trigger("change");
                        $("#documentoSubido").val('');
                        $(`#${poblacion}`).val('').trigger("change");
                        $("#tabla").css("display","none");

                        Swal.fire({
                            icon: 'success',
                            title: 'Listo!',
                            text: `El registro de cantidad de ${data.message} se realizo con exito`,
                            footer: '<b></b>'
                        });

                        verRegistro();
                        
                        document.querySelector(`#labelrespaldo`).classList.remove('text-danger')
                        document.querySelector('#respaldo').classList.remove('text-danger');
                        document.querySelector('#respaldo').classList.remove('is-invalid')
                        document.querySelector(`#errorsrespaldo`).classList.add('d-none');
                        $("#registrarNumero").prop("disabled",true);
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
       }else{
            const dataPoblacionRegistro = {
                Trimestre: Trimestre.value,
                numeroPoblacion: JSON.parse(numeroPoblacion.value),
                idUsuario: Usuario['idUsuario'],
                poblacion: poblacion
            };
            console.log(dataPoblacionRegistro)
            $.ajax(`${ API }/control-estudiantes-docentes/ingresarDocentesEstudiantesSinDocumento.php`, {
                type: 'POST',
                dataType: 'json',
                data: (dataPoblacionRegistro),
                success:function(response) {
                        const { data } = response;

                        $("#Trimestre").val('').trigger("change");
                        $("#respaldo").val('').trigger("change");
                        $("#documentoSubido").val('');
                        $(`#${poblacion}`).val('').trigger("change");
                        $("#tabla").css("display","none");

                        Swal.fire({
                            icon: 'success',
                            title: 'Listo!',
                            text: `El registro de cantidad de ${data.message} se realizo con exito`,
                            footer: '<b></b>'
                        });

                        verRegistro();
                        
                        document.querySelector(`#labelrespaldo`).classList.remove('text-danger')
                        document.querySelector('#respaldo').classList.remove('text-danger');
                        document.querySelector('#respaldo').classList.remove('is-invalid')
                        document.querySelector(`#errorsrespaldo`).classList.add('d-none');
                        $("#registrarNumero").prop("disabled",true);
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
       }
   } else { // caso contrario mostrar alerta y notificar al usuario 
       Swal.fire({
           icon: 'error',
           title: 'Ops...',
           text: 'El registro de la carrera no se pudo realizar',
           footer: '<b>Por favor verifique el formulario de registro</b>'
       })
   }
}

// Modificacion de respaldos

const Modificar=(idTipoGestion,poblacion,idGestion,fechaRegistro)=>{
    // Capturando las etiquetas completas de los inputs para despues obtener el valor
    let Trimestre = document.querySelector('#TrimestreM');
    let numeroPoblacion = document.querySelector('#Cantidad');

    // Tipando los atributos con los valores de la base de datos bueno algunos
    let tR = { valorEtiqueta: Trimestre, id: 'TrimestreM', name: 'TrimestreM', type: 'select' };
    let nP = { valorEtiqueta: numeroPoblacion, id: 'Cantidad', name: 'Cantidad', min: 1, max: 4, type: 'text' };
   

    // Llamando a las funciones para realizar la verificacion de los campos retorna true o false
    let isValidTrimestre = verificarSelect(tR);
    let isValidnumeroPoblacion = verificarInputNumber(nP,numerosRegex);

   // Si todos los campos que llevan validaciones estan okey o true que realice el ajax o fetch o axios o lo que sea
   if (
       (isValidTrimestre === true) &&
       (isValidnumeroPoblacion === true) 
   ) {
        const dataPoblacionRegistro = {
           Trimestre: Trimestre.value,
           numeroPoblacion: JSON.parse(numeroPoblacion.value),
           idGestion: parseInt(idGestion),
           fechaRegistro: fechaRegistro,
           idTipoGestion:idTipoGestion
        };
        $.ajax(`${ API }/control-estudiantes-docentes/modificarDocentesEstudiantes.php`, {
            type: 'POST',
            dataType: 'json',
            data: (dataPoblacionRegistro),
            success:function(response) {
                    const { data } = response;
                    console.log(data)
                    $("#TrimestreM").val('').trigger("change");
                    $('#Cantidad').val('').trigger("change");
                    Swal.fire({
                        icon: 'success',
                        title: 'Listo!',
                        text: `Cantidad de ${poblacion} se modifico con exito`,
                        footer: '<b></b>'
                    });
                    $('#modalModificarDocentesEstudiantes').modal('toggle');
                    obtenerDocentesEstudiantes();
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
           text: 'El registro de la carrera no se pudo realizar',
           footer: '<b>Por favor verifique el formulario de registro</b>'
       })
   }
}

// registrar cambios en respaldo

const modificacionRespaldo = (idGestion) =>{
    const formData = new FormData($(`#formulario-modificacion-respaldo`)[0]);
    formData.append('idGestion', parseInt(idGestion));
    for (var value of formData.values()) {
        console.log(value);
    }
    $.ajax(`${ API }/control-estudiantes-docentes/modificarRespaldo.php`, {
         type: 'POST',
         data: formData,
         cache: false,
         processData: false,
         contentType: false,
        success:function(response) {
            //  const { data } = response;
             Swal.fire({
                 icon: 'success',
                 title: 'Listo!',
                 text: `El documento de respaldo se modifico con exito.`,
                 footer: '<b></b>'
             });
             $("#docUpload").val('');
             $("#respaldoM").val('').trigger('change');
             $("#modalModificarRespaldo").modal('toggle');
             obtenerDocentesEstudiantes();
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

// cerrar modal registro

const CerrarModal=()=>{
    let poblacionVar='';
    if(document.querySelector("#Poblacion").value==1){
        poblacionVar="NumEstudiantesMatriculados";
    }else if(document.querySelector("#Poblacion").value==2){
        poblacionVar="NumEgresados";
    }else if(document.querySelector("#Poblacion").value==3){
        poblacionVar="NumDocentes";
    }
    $("#Trimestre").val('').trigger("change");
    $("#respaldo").val('').trigger("change");
    $(`#${poblacionVar}`).val('').trigger("change");
    $("#tabla").css("display","none");
    verRegistro();
    document.querySelector(`#labelrespaldo`).classList.remove('text-danger')
    document.querySelector('#respaldo').classList.remove('text-danger');
    document.querySelector('#respaldo').classList.remove('is-invalid')
    document.querySelector(`#errorsrespaldo`).classList.add('d-none');
    document.querySelector('#Trimestre').classList.remove('text-danger');
    document.querySelector('#Trimestre').classList.remove('is-invalid')
    document.querySelector(`#errorsTrimestre`).classList.add('d-none');
    document.querySelector(`#label${poblacionVar}`).classList.remove('text-danger')
    document.querySelector(`#${poblacionVar}`).classList.remove('text-danger');
    document.querySelector(`#${poblacionVar}`).classList.remove('is-invalid')
    document.querySelector(`#errors${poblacionVar}`).classList.add('d-none');
    $("#registrarNumero").prop("disabled",true);
}

// cerrar modal modificacion

const CerrarModalM=(poblacion)=>{
    let poblacionVar='';
    if(poblacion=='Estudiantes Matriculados'){
        poblacionVar="NumEstudiantesMatriculados";
    }else if(poblacion=='Egresados'){
        poblacionVar="NumEgresados";
    }else if(poblacion=='Docentes con Maestria'){
        poblacionVar="NumDocentes";
    }
    $("#TrimestreM").val('').trigger("change");
    verRegistro();
    document.querySelector('#Trimestre').classList.remove('text-danger');
    document.querySelector('#Trimestre').classList.remove('is-invalid')
    document.querySelector(`#errorsTrimestre`).classList.add('d-none');
    document.querySelector(`#label${poblacionVar}`).classList.remove('text-danger')
    document.querySelector(`#${poblacionVar}`).classList.remove('text-danger');
    document.querySelector(`#${poblacionVar}`).classList.remove('is-invalid')
    document.querySelector(`#errors${poblacionVar}`).classList.add('d-none');
}

// cerrar modal modificacion respaldo

const CerrarModalM2=(poblacion)=>{
    $("#documentoSubidoM").val('');
    $("#respaldoM").val('').trigger('change');
    $("#modalModificarRespaldo").modal('toggle');
}

// mostrar valor subido, modal registrar poblacion

const mostrarValorASubir = () =>{
    let file = $('#documentoSubido').prop('files');
    $('#respaldo').val(file[0].name);
}

// mostrar valor subido, modal modificar respaldo

const mostrarValorRespaldoASubir = () =>{
    let file = $('#documentoSubidoM').prop('files');
    $('#respaldoM').val(file[0].name);
}

//petición para obtener la imagen adjunta de la solicitud seleccionada
const visualizarAdjuntos = (idGestion) => {
    let parametros = { idGestion: parseInt(idGestion) };

    $.ajax(`${ API }/control-estudiantes-docentes/obtenerImagenRespaldo.php`, {
        type: 'POST',
        dataType: 'json',
        data:(parametros),
        success:function(response) {
            const { data } = response;

            let respaldo = data[0].documentoRespaldo;
            $('#modalRespaldoAdjunto').modal('show');
            if (respaldo != null){
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