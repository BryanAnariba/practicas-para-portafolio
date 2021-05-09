// Capturando las etiquetas completas de los inputs para despues obtener el valor
let nombrePersona = document.querySelector('#R-nombrePersona');
let apellidoPersona = document.querySelector('#R-apellidoPersona');
let codigoEmpleado = document.querySelector('#R-codigoEmpleado');
let fechaNacimiento = document.querySelector('#R-fechaNacimiento');
let idDepartamento = document.querySelector('#R-idDepartamento');
let idTipoUsuario = document.querySelector('#R-idTipoUsuario');
let correoInstitucional = document.querySelector('#R-correoInstitucional');
let idPais = document.querySelector('#R-idPais');
let idDepartamentoPais = document.querySelector('#R-idDepartamentoPais');
let idMunicipioCiudad = document.querySelector('#R-idMunicipiosCiudad');
let nombreLugar = document.querySelector('#R-nombreLugar');

    // Tipando los atributos con los valores de la base de datos bueno algunos -> nP = nombrePersona, REGISTRO
let nP = { valorEtiqueta: nombrePersona, id: 'R-nombrePersona', name: 'Nombre Persona', min: 1, max: 80, type: 'text' };
let aP = { valorEtiqueta: apellidoPersona, id: 'R-apellidoPersona', name: 'Apellido Persona', min: 1, max: 80, type: 'text' };
let cE = { valorEtiqueta: codigoEmpleado, id: 'R-codigoEmpleado', name: 'Codigo Empleado', min: 1, max: 5, type: 'number' };
let fN = { valorEtiqueta: fechaNacimiento, id: 'R-fechaNacimiento', name: 'Fecha de Nacimiento', type: 'date' };
let iD = { valorEtiqueta: idDepartamento, id: 'R-idDepartamento', name: 'Departamento Facultad', type: 'select' };
let iTU = { valorEtiqueta: idTipoUsuario, id: 'R-idTipoUsuario', name: 'Tipo Usuario', type: 'select' };
let iP = { valorEtiqueta: idPais, id: 'R-idPais', name: 'Pais de residencia', type: 'select' };
let iDP = { valorEtiqueta: idDepartamentoPais, id: 'R-idDepartamentoPais', name: 'Ciudad Pais de residencia', type: 'select' };
let iMC = { valorEtiqueta: idMunicipioCiudad, id: 'R-idMunicipiosCiudad', name: 'Municipio Ciudad de residencia', type: 'select' };
let cI = { valorEtiqueta: correoInstitucional, id: 'R-correoInstitucional', name: 'Correo Institucional', type: 'email'};
                        // Peticiones que se ejecutan al abrir el formulario de registro usuarios
const cargarCiudadesPais = () => {
    // Limpiamos select
    $('#R-idDepartamentoPais').html(``);

    // Cargamos spinner de loading y esconcemos select
    $('#spinneridDepartamentoPais').removeClass('d-none');
    $('#R-idDepartamentoPais').addClass('d-none');

    // Mandamos los parametros por metodo post
    let parametros = { idPais: parseInt($('#R-idPais').val()) };
    
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
            $('#R-idDepartamentoPais').removeClass('d-none');
            $('#R-idDepartamentoPais').append(`<option value='' selected>Seleccione Ciudad/Provincia Pais</option>`);

            // Rellenamos la informacion del select
            for(let i=0;i<data.length;i++) {
                $('#R-idDepartamentoPais').append(
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

const cargarMunicipios = () => {
    // Capturamos id departamentos seleccionado
    let id =$("#R-idDepartamentoPais").val();
    // Limpiamos select
    $('#R-idMunicipiosCiudad').html(``);

    // Cargamos spinner de loading y esconcemos select
    $('#spinneridMunicipiosCiudad').removeClass('d-none');
    $('#R-idMunicipiosCiudad').addClass('d-none');

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
            $('#R-idMunicipiosCiudad').removeClass('d-none');
            $('#R-idMunicipiosCiudad').append(`<option value='' selected>Seleccione Municipio Ciudad</option>`);
            // Rellenamos la informacion del select
            for(let i=0;i<data.length;i++) {
                $('#R-idMunicipiosCiudad').append(`
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
const cargarModalRegistro = () => {

    // Mostramos loadings por mientras se ejecutan las peticiones
    $('.loading-registro').removeClass('d-none');
    $('#modalContentRegistro').addClass('d-none');

    // Zona de parametros
    let idTipoLugar = { idTipoLugar: 1 };
    let estadoDeptoFacultad = { idEstadoDepartamento: 1 };

    // Ejecucion function
    setTimeout(() => {
        $.when(
            $.ajax(`${ API }/lugares/listar-paises.php`, {
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify(idTipoLugar)
            }),
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
            .done(function(paisesResponse, departamentosResponse, tiposUsuariosResponse) {
                let paises = paisesResponse[0].data;
                let deptosFacultad = departamentosResponse[0].data;
                let rolesUsuario = tiposUsuariosResponse[0].data;
                console.log('Paises => ', paises);
                console.log('Departamentos Facultad => ', deptosFacultad);
                console.log('Roles Usuarios => ' , rolesUsuario);
                
                // Una vez entrando aqui removemos los loadings y empezamos a pintar la informacion
                $('.loading-registro').addClass('d-none');
                $('#modalContentRegistro').removeClass('d-none');
                
                // Paises
                $('#R-idPais').html(`<option value='' selected>Seleccione Direcion Pais</option>`);
                for(let i=0;i<paises.length;i++) {
                    $('#R-idPais').append(`
                        <option value=${ paises[i].idLugar } >${ paises[i].nombreLugar }</option>
                    `);             
                }

                // Departamentos facultad
                $('#R-idDepartamento').html(`<option value='' selected>Seleccione departamento</option>`);
                for(let i=0;i<deptosFacultad.length;i++) {
                    $('#R-idDepartamento').append(`
                        <option value=${ deptosFacultad[i].idDepartamento } >${ deptosFacultad[i].nombreDepartamento }</option>
                    `);             
                }

                // Tipos usuarios
                $('#R-idTipoUsuario').html(`<option value='' selected>Seleccione tipo usuario</option>`);
                for(let i=0;i<rolesUsuario.length;i++) {
                    $('#R-idTipoUsuario').append(`
                        <option value=${ rolesUsuario[i].idTipoUsuario } >${ rolesUsuario[i].tipoUsuario }</option>
                    `);             
                }
            })
            .fail(function(error) {
                console.log('Something went wrong', error);
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
            });
    }, 1000);
}

//                                  Peticiones de verificacion, guardado, y actualizado
const verificarCamposRegistro = () => {
    // Llamando a las funciones para realizar la verificacion de los campos retorna true o false
    let isValidNombrePersona = verificarInputText(nP, nombresApellidosRegex);
    let isValidApellidoPersona = verificarInputText(aP, nombresApellidosRegex);
    let isValidCodigoEmpleado = verificarInputNumber(cE, codigoEmpleadoRegex);
    let isValidFecha = verificarFecha(fN);
    let isValidIdDepartamento = verificarSelect(iD);
    let isValidIdTipoUsuario = verificarSelect(iTU);
    let isValidIdPais = verificarSelect(iP);
    let isValidIdDepartamentoPais = verificarSelect(iDP);
    let isValidIdMunicipioCiudad = verificarSelect(iMC);
    let isValidCorreoInstitucional = verificarEmail(cI);
    

    // Si todos los campos que llevan validaciones estan okey o true que realice el ajax o fetch o axios o lo que sea
    if (
        (isValidNombrePersona === true) &&
        (isValidApellidoPersona === true) &&
        (isValidCodigoEmpleado === true) && 
        (isValidFecha === true) &&
        (isValidIdDepartamento === true) && 
        (isValidIdTipoUsuario === true) &&
        (isValidIdPais === true) &&
        (isValidIdDepartamentoPais === true) &&
        (isValidIdMunicipioCiudad === true) &&
        (isValidCorreoInstitucional === true)
    ) {
        // Desabilitamos boton de registro usuarios
        $('#btn-registrar-usuario').prop('disabled', true);

        // Mostramos loadings por mientras se ejecutan las peticiones
        $('.loading-registro').removeClass('d-none');
        $('#modalContentRegistro').addClass('d-none');

        // Extraemos el nombreUsuario del correo
        const emailSanitizado = generaNombreUsuario(cI.valorEtiqueta.value);
        //console.log(emailSanitizado);
        const [ , nombreUsuario, , ,] = emailSanitizado

        // JSON A ENVIAR A LA PETICION
        const parametros = {
            nombrePersona: nombrePersona.value,
            apellidoPersona: apellidoPersona.value,
            codigoEmpleado: parseInt(codigoEmpleado.value),
            fechaNacimiento: fechaNacimiento.value,
            idDepartamento: parseInt(idDepartamento.value),
            idTipoUsuario: parseInt(idTipoUsuario.value),
            correoInstitucional: correoInstitucional.value,
            nombreUsuario: nombreUsuario,
            idPais: parseInt(idPais.value),
            idDepartamentoPais: parseInt(idDepartamentoPais.value),
            idMunicipioCiudad: parseInt(idMunicipioCiudad.value),
            nombreLugar: ((nombreLugar.value.length != 0) ? nombreLugar.value : 'Omitio Direccion')
        };

        console.log('User data => ', parametros);

        $.ajax(`${ API }/usuarios/registrar-usuario.php`, {
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify(parametros),
        success:function(response) {
            // Mostramos loadings por mientras se ejecutan las peticiones
            $('.loading-registro').addClass('d-none');
            $('#modalContentRegistro').removeClass('d-none');
            $('#modalRegistrarUsuarios').modal('hide');
            $('#btn-registrar-usuario').prop('disabled', false);
            const { data } = response;
            console.log(response);
            Swal.fire({
                icon: 'success',
                title: 'Accion realizada Exitosamente',
                text: `${ data.message }`,
            });
            cancelarOperacion();
        },
        error:function(error) {
            console.log(error.responseText);
            console.log(error.responseJSON);
            console.log(error);
            // Mostramos loadings por mientras se ejecutan las peticiones
            $('.loading-registro').addClass('d-none');
            $('#modalContentRegistro').removeClass('d-none');
            $('#modalRegistrarUsuarios').modal('hide');
            $('#btn-registrar-usuario').prop('disabled', false);
            cancelarOperacion();
            const { status,data } = error.responseJSON;
            if (status === 401) {
                window.location.href = '../views/401.php';
            }
            console.log(status);
            console.error(error.responseText);
            
            Swal.fire({
                icon: 'error',
                title: 'Ops...',
                text: `${ data.message }`,
                footer: '<b>Por favor verifique el formulario de registro</b>'
            }, function() {
                if (status === 401) {
                    console.log('saliendo');
                    window.location = '../../../views/401.php';
                }
            });
        }});
    } else { // caso contrario mostrar alerta y notificar al usuario 
        Swal.fire({
            icon: 'error',
            title: 'Ops...',
            text: 'El registro de la persona no se pudo realizar',
            footer: '<b>Por favor verifique el formulario de registro</b>'
        });
    }
}

const cancelarOperacion = () => {
    limpiarCamposFormulario(nP);
    limpiarCamposFormulario(aP);
    limpiarCamposFormulario(cE);
    limpiarCamposFormulario(fN);
    limpiarCamposFormulario(iD);
    limpiarCamposFormulario(iTU);
    limpiarCamposFormulario(cI);
    limpiarCamposFormulario(iP);
    limpiarCamposFormulario(iDP);    
    limpiarCamposFormulario(iMC);    
    
    $('#R-nombrePersona').trigger('reset');
    $('#R-apellidoPersona').trigger('reset');
    $('#R-codigoEmpleado').trigger('reset');
    document.querySelector('#R-fechaNacimiento').value = '';
    //$('#R-fechaNacimiento').trigger('reset');
    $('#R-idDepartamento').trigger('reset');
    $('#R-idTipoUsuario').trigger('reset');
    $('#R-correoInstitucional').trigger('reset');
    $('#R-idPais').trigger('reset');
    $('#R-idDepartamentoPais').trigger('reset');
    $('#R-idMunicipiosCiudad').trigger('reset');
    $('#R-nombreLugar').trigger('reset');
}
