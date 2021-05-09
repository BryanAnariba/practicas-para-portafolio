let usuarioAModificar;
let nombre;
let apellido;
let nUsuario;

const listarCiudadesPais = () => {
    // Limpiamos select
    $('#M-idDepartamentoPais').html(``);

    // Cargamos spinner de loading y esconcemos select
    $('#spinneridDepartamentoPais').removeClass('d-none');
    $('#M-idDepartamentoPais').addClass('d-none');

    // Mandamos los parametros por metodo post
    let parametros = { idPais: parseInt($('#M-idPais').val()) };
    
    // Test de parametros
    //console.log(parametros);

    // Peticion al servidor
    $.ajax(`${ API }/lugares/listar-ciudades.php`, {
		type: 'POST',
		dataType: 'json',
        contentType: 'application/json',
		data: JSON.stringify(parametros),
		success:function(response){
            const { data } = response;
            console.log('Ciudades Pais => ', data);

            // Quitamos spinner y cargamos select
            $('#spinneridDepartamentoPais').addClass('d-none');
            $('#M-idDepartamentoPais').removeClass('d-none');
            $('#M-idDepartamentoPais').append(`<option value='' selected>Seleccione Ciudad/Provincia Pais</option>`);

            // Rellenamos la informacion del select
            for(let i=0;i<data.length;i++) {
                $('#M-idDepartamentoPais').append(
                    `
                        <option value="${ data[i].idLugar }">${ data[i].ciudad }</option>
                    `
                );
            }
		},
		error:function(error){
            const { status, data } = error.responseJSON;
            if (status === 401) {
                window.location.href = '../views/401.php';
            }
            console.log(data);
            Swal.fire({
                icon: 'error',
                title: 'Ops...',
                text: `${ data.message }`,
                footer: '<b>Por favor verifique el formulario de registro</b>'
            });
		}
    });
}

const listarMunicipios = () => {
    // Capturamos id departamentos seleccionado
    let id = $("#M-idDepartamentoPais").val();
    // Limpiamos select
    $('#M-idMunicipiosCiudad').html(``);

    // Cargamos spinner de loading y esconcemos select
    $('#spinneridMunicipiosCiudad').removeClass('d-none');
    $('#M-idMunicipiosCiudad').addClass('d-none');

    // Mandamos los parametros por metodo post
    let parametros = { idCiudad: parseInt(id) };

    // Peticion al servidor
    $.ajax(`${ API }/lugares/listar-municipios.php`, {
		type: 'POST',
		dataType: 'json',
        contentType: 'application/json',
		data: JSON.stringify(parametros),
		success:function(response){
            const { data } = response;
            console.log('Municipios Ciudad => ', data);

            // Quitamos spinner y cargamos select
            $('#spinneridMunicipiosCiudad').addClass('d-none');
            $('#M-idMunicipiosCiudad').removeClass('d-none');
            $('#M-idMunicipiosCiudad').append(`<option value='' selected>Seleccione Municipio Ciudad</option>`);
            // Rellenamos la informacion del select
            for(let i=0;i<data.length;i++) {
                $('#M-idMunicipiosCiudad').append(`
                    <option value="${ data[i].idLugar }">${ data[i].municipio }</option>
                `);
            }
		},
		error:function(error){
            const { status, data } = error.responseJSON;
            if (status === 401) {
                window.location.href = '../views/401.php';
            }
            console.log(data);
            Swal.fire({
                icon: 'error',
                title: 'Ops...',
                text: `${ data.message }`,
                footer: '<b>Por favor verifique el formulario de registro</b>'
            })
		}
    });
}


const listarUsuarios = () => {
    $('#listado-usuarios').dataTable().fnDestroy();
    $.ajax(`${ API }/usuarios/listado-usuarios.php`, {
        type: 'POST',
		dataType: 'json',
        contentType: 'application/json',
		success:function(response){
            const { data } = response;
            console.log('Usuarios registrados -> ', data);
            $('#listado-usuarios tbody').html(``);
        
            for (let i=0;i<data.length; i++) {
                $('#listado-usuarios tbody').append(`
                    <tr>
                        <td scope="row">${ i + 1 }</td>
                        <td>${ data[i].codigoEmpleado }</td>
                        <td>${ data[i].nombrePersona } ${ data[i].apellidoPersona }</td>
                        <td>
                            <button type="button" ${ data[i].idEstadoUsuario === 1 ? `class="btn btn-success" ` : `class="btn btn-danger" ` }
                            onclick="cambiarEstadoUsuario('${ data[i].idPersona }', '${ data[i].idEstadoUsuario }')">
                                ${ data[i].estado }
                            </button>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-info"
                            onclick="visualizarInformacionGeneral('${ data[i].idPersona}' , '${ data[i].nombrePersona }','${ data[i].apellidoPersona }','${ data[i].codigoEmpleado }', '${ data[i].fechaNacimiento }' ,'${ data[i].idDepartamento }','${ data[i].nombreDepartamento }','${ data[i].idTipoUsuario }','${ data[i].tipoUsuario }')">
                                <img src="../img/menu/visualizar-icon.svg" alt="Ver informacion Empleado"/>
                            </button>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-info"
                            onclick="visualizarDireccionUsuario('${ data[i].idPersona }','${ data[i].pais }','${ data[i].ciudad }', '${ data[i].municipio }','${ data[i].direccion }')">
                                <img src="../img/menu/visualizar-icon.svg" alt="Ver informacion Direccion Empleado"/>
                            </button>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-info"
                            onclick="accionesCorreoElectronico('${ data[i].idPersona }','${ data[i].correoInstitucional }', '${ data[i].nombreUsuario }', '${ data[i].nombrePersona }', '${ data[i].apellidoPersona }')"
                            >
                                <img src="../img/menu/visualizar-icon.svg" alt="Ver informacion Correo Empleado"/>
                            </button>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-amber"
                            >
                                <img src="../img/menu/ver-icon.svg" alt="Ver informacion Correo Empleado"/>
                            </button>
                        </td>
                    </tr>
                `)
            }
            $('#listado-usuarios').DataTable({
                language: i18nEspaniol,
                //dom: 'Blfrtip',
                //buttons: botonesExportacion,
                retrieve: true
            });
        },
        error:function (error) {
            const { status, data } = error.responseJSON;
            if (status === 401) {
                window.location.href = '../views/401.php';
            }
            console.log(data);
            Swal.fire({
                icon: 'error',
                title: 'Ops...',
                text: `${ data.message }`,
                footer: '<b>Por favor verifique el formulario de registro</b>'
            });
        }
    });
}
const cambiarEstadoUsuario = (idPersonaUsuario, idEstadoUsuario) => {
    let parametros = { idPersonaUsuario: parseInt(idPersonaUsuario), idEstadoUsuario: parseInt(idEstadoUsuario) };
    console.log(parametros);
    $.ajax(`${ API }/usuarios/cambiar-estado-usuario.php`, {
        type: 'POST',
		dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify(parametros),
		success:function(response){
            const { data } = response;
            Swal.fire({
                icon: 'success',
                title: 'Accion realizada Exitosamente',
                text: `${ data.message }`,
            });
            listarUsuarios();
        },
        error:function (error) {
            const { status, data } = error.responseJSON;
            if (status === 401) {
                window.location.href = '../views/401.php';
            }
            console.log(data);
            Swal.fire({
                icon: 'error',
                title: 'Ops...',
                text: `${ data.message }`,
                footer: '<b>Por favor verifique el formulario de registro</b>'
            });
        }
    });
}
const cargarInfoSelectslModificacion = (
    idDepartamentoActual, 
    departamentoActual, 
    idTipoUsuarioActual, 
    tipoUsuarioActual
    ) => {
    // Mostramos loadings por mientras se ejecutan las peticiones
    $('#modalContentModificacion').addClass('d-none');
    
    let estadoDeptoFacultad = { idEstadoDepartamento: 1 };

    // Ejecucion function
    $.when(
        $.ajax(`${ API }/departamentos/listar-departamentos.php`, {
            type: 'POST',
            dataType: 'json',
            data: JSON.stringify(estadoDeptoFacultad),
            contentType: 'application/json'
        }),
        $.ajax(`${ API }/usuarios/listar-tipos-usuarios.php`, {
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json'
        }))
        .done(function(departamentosResponse, tiposUsuariosResponse) {
            let deptosFacultad = departamentosResponse[0].data;
            let rolesUsuario = tiposUsuariosResponse[0].data;
            console.log('Departamentos Facultad => ', deptosFacultad);
            console.log('Roles Usuarios => ' , rolesUsuario);
            
            // Una vez entrando aqui removemos los loadings y empezamos a pintar la informacion
            $('#modalContentModificacion').removeClass('d-none');

             // Departamentos facultad
            $('#M-idDepartamento').html(`<option value='${ idDepartamentoActual }' selected>${ departamentoActual }</option>`);
            for(let i=0;i<deptosFacultad.length;i++) {
                if (deptosFacultad[i].idDepartamento != idDepartamentoActual) {
                    $('#M-idDepartamento').append(`<option value=${ deptosFacultad[i].idDepartamento } >${ deptosFacultad[i].nombreDepartamento }</option>`);
                }           
            }

            // Tipos usuarios
            $('#M-idTipoUsuario').html(`<option value='${ idTipoUsuarioActual }' selected>${ tipoUsuarioActual }</option>`);
            for(let i=0;i<rolesUsuario.length;i++) {
                if (rolesUsuario[i].idTipoUsuario != idTipoUsuarioActual) {
                    $('#M-idTipoUsuario').append(`
                        <option value=${ rolesUsuario[i].idTipoUsuario } >${ rolesUsuario[i].tipoUsuario }</option>
                    `);  
                }        
            }
        })
        .fail(function(error) {
            console.log('Something went wrong', error);
            const { status, data } = error.responseJSON;
            if (status === 401) {
                window.location.href = '../views/401.php';
            }
            Swal.fire({
                icon: 'error',
                title: 'Ops...',
                text: `${ data.message }`,
                footer: '<b>Por favor verifique el formulario de registro</b>'
            })
        });
}


const visualizarInformacionGeneral = (idPersona, nombrePersona, apellidoPersona, codigoEmpleado, fechaNacimiento, idDepartamento, departamento, idTipoUsuario, tipoUsuario) => {
    usuarioAModificar = idPersona;
    $('#modalModificarInfoEmpleado').modal('show');
    $('#M-nombrePersona').val(nombrePersona).trigger('change');
    $('#M-apellidoPersona').val(apellidoPersona).trigger('change');
    $('#M-fechaNacimiento').val(fechaNacimiento).trigger('change');
    $('#M-codigoEmpleado').val(codigoEmpleado).trigger('change');
    cargarInfoSelectslModificacion(
        idDepartamento, 
        departamento,
        idTipoUsuario, 
        tipoUsuario
    );
}


const actualizarRegistroUsuario = () => {
    let nombrePersona = document.querySelector('#M-nombrePersona');
    let apellidoPersona = document.querySelector('#M-apellidoPersona');
    let codigoEmpleado = document.querySelector('#M-codigoEmpleado');
    let fechaNacimiento = document.querySelector('#M-fechaNacimiento');
    let idDepartamento = document.querySelector('#M-idDepartamento');
    let idTipoUsuario = document.querySelector('#M-idTipoUsuario');
    let nP = { valorEtiqueta: nombrePersona, id: 'M-nombrePersona', name: 'Nombre Persona', min: 1, max: 80, type: 'text' };
    let aP = { valorEtiqueta: apellidoPersona, id: 'M-apellidoPersona', name: 'Apellido Persona', min: 1, max: 80, type: 'text' };
    let cE = { valorEtiqueta: codigoEmpleado, id: 'M-codigoEmpleado', name: 'Codigo Empleado', min: 1, max: 5, type: 'number' };
    let fN = { valorEtiqueta: fechaNacimiento, id: 'M-fechaNacimiento', name: 'Fecha de Nacimiento', type: 'date' };
    let iD = { valorEtiqueta: idDepartamento, id: 'M-idDepartamento', name: 'Departamento Facultad', type: 'select' };
    let iTU = { valorEtiqueta: idTipoUsuario, id: 'M-idTipoUsuario', name: 'Tipo Usuario', type: 'select' };
    let isValidNombrePersona = verificarInputText(nP, nombresApellidosRegex);
    let isValidApellidoPersona = verificarInputText(aP, nombresApellidosRegex);
    let isValidCodigoEmpleado = verificarInputNumber(cE, codigoEmpleadoRegex);
    let isValidFecha = verificarFecha(fN);
    let isValidIdDepartamento = verificarSelect(iD);
    let isValidIdTipoUsuario = verificarSelect(iTU);
    if (
        (isValidNombrePersona === true) &&
        (isValidApellidoPersona === true) &&
        (isValidCodigoEmpleado === true) && 
        (isValidFecha === true) &&
        (isValidIdDepartamento === true) && 
        (isValidIdTipoUsuario === true) 
    ) { 
        $('#btn-actualizar-registro-usuario').prop('disabled', true);
        let parametros = {
            idUsuario: parseInt(usuarioAModificar),
            nombrePersona: nombrePersona.value,
            apellidoPersona: apellidoPersona.value,
            codigoEmpleado: codigoEmpleado.value,
            fechaNacimiento: fechaNacimiento.value,
            idDepartamento: parseInt(idDepartamento.value),
            idTipoUsuario: parseInt(idTipoUsuario.value)
        };
        console.log(parametros);
        $.ajax(`${ API }/usuarios/modificar-datos-generales.php`, {
            type: 'POST',
            dataType: 'json',
            data: JSON.stringify(parametros),
            contentType: 'application/json',
            success:function(response) {
                $('#btn-actualizar-registro-usuario').prop('disabled', false);
                $('#modalModificarInfoEmpleado').modal('hide');
                const { data } = response;
                console.log(response);
                Swal.fire({
                    icon: 'success',
                    title: 'Accion realizada Exitosamente',
                    text: `${ data.message }`,
                });
                listarUsuarios();
            }, 
            error:function(error) {
                const { status, data } = error;
                if (status === 401) {
                    window.location.href = '../views/401.php';
                }
                console.error(error);
                Swal.fire({
                    icon: 'error',
                    title: 'Ops...',
                    text: `${ data.message }`
                });
            }
        });

    } else { // caso contrario mostrar alerta y notificar al usuario 
        Swal.fire({
            icon: 'error',
            title: 'Ops...',
            text: 'La modificacion de los datos generales del usuario no se pudieron realizar',
            footer: '<b>Por favor verifique el formulario de registro</b>'
        });
    }
} 


const accionesCorreoElectronico = (idPersona, correoInstitucional, nombreUsuario, nombrePersona, apellidoPersona) => {
    $('.loading-registro').addClass('d-none');
    $('#modalContentReenvioCredenciales').removeClass('d-none');
    nombre = nombrePersona;
    apellido = apellidoPersona;
    nUsuario = nombreUsuario;
    usuarioAModificar = idPersona;
    $('#modalModificarCorreoInstitucional').modal('show');
    $('#M-correoInstitucional').val(correoInstitucional).trigger('change');
}

const reenviarCredencialesCorreo = () => {
    let correoInstitucional = document.querySelector('#M-correoInstitucional');
    let cI = { valorEtiqueta: correoInstitucional, id: 'M-correoInstitucional', name: 'Correo Institucional', type: 'email'};
    let isValidCorreoInstitucional = verificarEmail(cI);
    if(isValidCorreoInstitucional === true) {
        
        // Mostramos loadings por mientras se ejecutan las peticiones
        $('#modalVisualizarUsuarios').modal('hide');
        $('.modalDeCarga').modal('show');
        $('#btn-reenvio-credenciales').prop('disabled', true);
        $('.loading-registro').removeClass('d-none');
        $('#modalContentReenvioCredenciales').addClass('d-none');
        let parametros = { 
            idUsuario: parseInt(usuarioAModificar), 
            correoInstitucional: correoInstitucional.value,
            nombreUsuario: nUsuario,
            nombrePersona: nombre,
            apellidoPersona: apellido
        };
        console.log(parametros);
        $.ajax(`${ API }/usuarios/reenviar-credenciales-acceso.php`, {
            type: 'POST',
            dataType: 'json',
            data: JSON.stringify(parametros),
            contentType: 'application/json',
            success:function(response) {
                $('#btn-reenvio-credenciales').prop('disabled', false);
                $('.loading-registro').addClass('d-none');
                $('#modalContentReenvioCredenciales').removeClass('d-none');
                $('#modalModificarCorreoInstitucional').modal('hide');
                const { data } = response;
                console.log(response);
                Swal.fire({
                    icon: 'success',
                    title: 'Accion realizada Exitosamente',
                    text: `${ data.message }`,
                });
                $('.modalDeCarga').modal('hide');
                listarUsuarios();
            }, 
            error:function(error) {
                $('#btn-reenvio-credenciales').prop('disabled', false);
                $('.loading-registro').addClass('d-none');
                $('#modalContentReenvioCredenciales').removeClass('d-none');
                $('#modalModificarCorreoInstitucional').modal('hide');
                const { status, data } = error;
                if (status === 401) {
                    window.location.href = '../views/401.php';
                }
                console.error(error);
                Swal.fire({
                    icon: 'error',
                    title: 'Ops...',
                    text: `${ data.message }`
                });
            }
        });
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Ops...',
            text: 'El reenvio de credenciales no se pudo realizar',
            footer: '<b>Por favor verifique el formulario de registro</b>'
        });
    }
    
}

const modificarCorreoUsuario = () => {
    let correoInstitucional = document.querySelector('#M-correoInstitucional');
    let cI = { valorEtiqueta: correoInstitucional, id: 'M-correoInstitucional', name: 'Correo Institucional', type: 'email'};

    let isValidCorreoInstitucional = verificarEmail(cI);

    if ((isValidCorreoInstitucional === true)) {
        $('#btn-modificacion-correo-electronico').prop('disabled', true);
        $('#modalVisualizarUsuarios').modal('hide');
        $('.loading-registro').removeClass('d-none');
        $('#modalContentReenvioCredenciales').addClass('d-none');

        // Extraemos el nombreUsuario del correo
        const emailSanitizado = generaNombreUsuario(cI.valorEtiqueta.value);
        //console.log(emailSanitizado);
        const [ , nombreUsuario, , ,] = emailSanitizado;
        let parametros = {
            idUsuario: parseInt(usuarioAModificar),
            correoInstitucional: correoInstitucional.value,
            nombreUsuario: nombreUsuario,
            nombrePersona: nombre,
            apellidoPersona: apellido
        };
        console.log(parametros);
        $.ajax(`${ API }/usuarios/modificar-correo-electronico.php`, {
            type: 'POST',
            dataType: 'json',
            data: JSON.stringify(parametros),
            contentType: 'application/json',
            success:function(response) {
                $('#btn-modificacion-correo-electronico').prop('disabled', false);
                
                $('#modalModificarCorreoInstitucional').modal('hide');
                $('#modalContentReenvioCredenciales').removeClass('d-none');
                const { data } = response;
                console.log(response);
                Swal.fire({
                    icon: 'success',
                    title: 'Accion realizada Exitosamente',
                    text: `${ data.message }`,
                });
                $('.modalDeCarga').modal('hide');
                listarUsuarios();
            }, 
            error:function(error) {
                console.log(error.responseText);
                $('#btn-modificacion-correo-electronico').prop('disabled', false);
                $('#modalContentReenvioCredenciales').removeClass('d-none');
                $('#modalModificarCorreoInstitucional').modal('hide');
                const { status, data } = error;
                if (status === 401) {
                    window.location.href = '../views/401.php';
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Ops...',
                    text: `${ data.message }`
                });
            }
        });
    } else { // caso contrario mostrar alerta y notificar al usuario 
        Swal.fire({
            icon: 'error',
            title: 'Ops...',
            text: 'La modificacion del correo del usuario no se pudo realizar',
            footer: '<b>Por favor verifique el formulario de registro</b>'
        });
    }
}

const visualizarDireccionUsuario = (idPersona, pais, ciudad, municipio, direccion) => {
    $('#modalModificarDireccion').modal('show');
    $('#pais').prop('disabled', true);
    $('#ciudad').prop('disabled', true);
    $('#municipio').prop('disabled', true);
    $('#direccionActual').prop('disabled', true);
    $('#pais').val(pais).trigger('change');
    $('#ciudad').val(ciudad).trigger('change');
    $('#municipio').val(municipio).trigger('change');
    $('#direccionActual').val(direccion).trigger('change');
    usuarioAModificar = idPersona;
}


const modalCambioDireccion = () => {
    
    // Zona de parametros
    let idTipoLugar = { idTipoLugar: 1 };
    $('#modalModificarDireccionActualPersona').modal('show');
    $.ajax(`${ API }/lugares/listar-paises.php`, {
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify(idTipoLugar),
        success:function (response) {
            const { data } = response;
            let paises = data;
            console.log('Paises => ', paises);
            // Paises
            $('#M-idPais').html(`<option value='' selected>Seleccione Direccion Pais</option>`);
            for(let i=0;i<paises.length;i++) {
                $('#M-idPais').append(`
                    <option value=${ paises[i].idLugar } >${ paises[i].nombreLugar }</option>
                `);             
            }
        },
        error:function (error) {
            const { status, data } = error.responseJSON;
            if (status === 401) {
                window.location.href = '../views/401.php';
            }
            Swal.fire({
                icon: 'error',
                title: 'Ops...',
                text: 'La informacion de paises no se cargo correctamente',
                footer: '<b>Por favor recarge la pagina</b>'
            });
        }
    });
}

const cambiarDireccion = () => {
    let idPais = document.querySelector('#M-idPais');
    let idDepartamentoPais = document.querySelector('#M-idDepartamentoPais');
    let idMunicipioCiudad = document.querySelector('#M-idMunicipiosCiudad');

    let iP = { valorEtiqueta: idPais, id: 'M-idPais', name: 'Pais de residencia', type: 'select' };
    let iDP = { valorEtiqueta: idDepartamentoPais, id: 'M-idDepartamentoPais', name: 'Ciudad Pais de residencia', type: 'select' };
    let iMC = { valorEtiqueta: idMunicipioCiudad, id: 'M-idMunicipiosCiudad', name: 'Municipio Ciudad de residencia', type: 'select' };


    let isValidIdPais = verificarSelect(iP);
    let isValidIdDepartamentoPais = verificarSelect(iDP);
    let isValidIdMunicipioCiudad = verificarSelect(iMC);

    if (
        (isValidIdPais === true) &&
        (isValidIdDepartamentoPais === true) &&
        (isValidIdMunicipioCiudad === true) 
    ) {
        $('#btn-cambiar-direccion-usuario-persona').prop('disabled', true);
        $('#modalModificarDireccionActualPersona').modal('hide');
        $('#modalModificarDireccion').modal('hide');
        //$('.modalDeCarga').modal('show');
        let parametros = {
            idUsuario: parseInt(usuarioAModificar),
            idPais: parseInt(idPais.value),
            idCiudad: parseInt(idDepartamentoPais.value),
            idMunicipio: parseInt(idMunicipioCiudad.value),
            direccion: document.querySelector('#M-direccionActual').value.length === 0 ? 'Omitio Direccion' : document.querySelector('#M-direccionActual').value
        };
        console.log(parametros);
        $.ajax(`${ API }/usuarios/modificar-direccion.php` , {
            type: 'POST',
            dataType: 'json',
            data: JSON.stringify(parametros),
            contentType: 'application/json',
            success:function(response) {
                const { data } = response;
                console.log(response);
                Swal.fire({
                    icon: 'success',
                    title: 'Accion realizada Exitosamente',
                    text: `${ data.message }`,
                });
                
                $('#btn-cambiar-direccion-usuario-persona').prop('disabled', false);
                //$('.modalDeCarga').modal('hide');
                listarUsuarios();
            }, 
            error:function(error) {
                $('#btn-cambiar-direccion-usuario').prop('disabled', false);
                //$('.modalDeCarga').modal('hide');
                const { status, data } = error;
                if (status === 401) {
                    window.location.href = '../views/401.php';
                }
                console.error(error);
                Swal.fire({
                    icon: 'error',
                    title: 'Ops...',
                    text: `${ data.message }`
                });
            }
        });
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Ops...',
            text: 'El registro de la persona no se pudo realizar',
            footer: '<b>Por favor verifique el formulario de registro de modificacion de direccion</b>'
        });
    }
}




const cancelarModificacionDireccion = () => {
    let idPais = document.querySelector('#M-idPais');
    let idDepartamentoPais = document.querySelector('#M-idDepartamentoPais');
    let idMunicipioCiudad = document.querySelector('#M-idMunicipiosCiudad');
    let iP = { valorEtiqueta: idPais, id: 'M-idPais', name: 'Pais de residencia', type: 'select' };
    let iDP = { valorEtiqueta: idDepartamentoPais, id: 'M-idDepartamentoPais', name: 'Ciudad Pais de residencia', type: 'select' };
    let iMC = { valorEtiqueta: idMunicipioCiudad, id: 'M-idMunicipiosCiudad', name: 'Municipio Ciudad de residencia', type: 'select' };

    limpiarCamposFormulario(iP);
    limpiarCamposFormulario(iDP);    
    limpiarCamposFormulario(iMC); 

    $('#M-idPais').trigger('reset');
    $('#M-idDepartamentoPais').trigger('reset');
    $('#M-idMunicipiosCiudad').trigger('reset');
    $('#M-direccionActual').trigger('reset');
}

const visualizarFotografia = () => {

}
