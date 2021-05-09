let imagenSeleccionada = null;
const cerrarSesion = () => {
    $.ajax(`${ API }/usuarios/logOut.php`,{
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        success:function (response) {
            const { status, data } = response;
            if (status === 200) {
                window.location.href = '../../../index.php';
            }
        }, 
        error: function(error) {
            const { status, data } = error.responseJSON;
            Swal.fire({
                icon: 'error',
                title: 'Ops...',
                text: `${ data.message }`,
                footer: '<b>Ha ocurrido un error</b>'
            });
        }
    });
}

const cambiarCredenciales = () => {
    let nuevaPasswordEmpleado = document.querySelector('#R-nuevaPasswordEmpleado');
    let repeatNuevaPasswordEmpleado = document.querySelector('#R-repeatNuevaPasswordEmpleado');
    let nPE = { valorEtiqueta: nuevaPasswordEmpleado, id: 'R-nuevaPasswordEmpleado', name: 'Clave Usuario', min: 8, max: 16, type: 'text' };    
    let rNPE = { valorEtiqueta: repeatNuevaPasswordEmpleado, id: 'R-repeatNuevaPasswordEmpleado', name: 'Repetir Clave Usuario', min: 8, max: 16, type: 'text' };

    let isValidClave = verificarInputText(nPE, claveUsuarioRegex);
    let isValidClaveRepetida = verificarInputText(rNPE, claveUsuarioRegex);

    if ((isValidClave === true) && (isValidClaveRepetida === true)) {
        if(nuevaPasswordEmpleado.value === repeatNuevaPasswordEmpleado.value) {
            $('.loading-registro').addClass('d-none');
            $('#btn-cambia-clave').prop('disabled', true);
            let parametros = {
                nuevaPasswordEmpleado: nuevaPasswordEmpleado.value,
                repeatNuevaPasswordEmpleado: repeatNuevaPasswordEmpleado.value
            };
            //console.log(parametros);
            $.ajax(`${ API }/usuarios/cambiar-clave-acceso.php`,{
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify(parametros),
                success:function (response) {
                    const { data } = response;
                    $('.loading-registro').removeClass('d-none');
                    $('#btn-cambia-clave').prop('disabled', false);
                    $('#modalCambioClave').modal('hide');
                    limpiaFormularioCambioClave();
                    Swal.fire({
                        icon: 'success',
                        title: 'Accion realizada Exitosamente, se requerida de la nueva clave para el proximo logueo a la plataforma',
                        text: `${ data.message }`,
                    });
                }, 
                error: function(error) {
                    $('.loading-registro').removeClass('d-none');
                    $('#btn-cambia-clave').prop('disabled', false);
                    $('#modalCambioClave').modal('hide');
                    limpiaFormularioCambioClave();
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
            
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Ops...',
                text: 'El cambio de credenciales no se pudo realizar, las Claves no coinciden',
                footer: '<b>Por favor verifique el formulario de registro</b>'
            });
        }
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Ops...',
            text: 'El cambio de credenciales no se pudo realizar',
            footer: '<b>Por favor verifique el formulario de registro</b>'
        });
    }
}

const limpiaFormularioCambioClave = () => {
    $('#R-nuevaPasswordEmpleado').trigger('reset');
    $('#R-repeatNuevaPasswordEmpleado').trigger('reset');
}

const cancelarCambioClave = () => {
    let nuevaPasswordEmpleado = document.querySelector('#R-nuevaPasswordEmpleado');
    let repeatNuevaPasswordEmpleado = document.querySelector('#R-repeatNuevaPasswordEmpleado');
    let nPE = { valorEtiqueta: nuevaPasswordEmpleado, id: 'R-nuevaPasswordEmpleado', name: 'Clave Usuario', min: 8, max: 16, type: 'text' };    
    let rNPE = { valorEtiqueta: repeatNuevaPasswordEmpleado, id: 'R-repeatNuevaPasswordEmpleado', name: 'Repetir Clave Usuario', min: 8, max: 16, type: 'text' };
    limpiarCamposFormulario(nPE);
    limpiarCamposFormulario(rNPE);
}

const avatarPrevio = document.getElementById('avatarPrevia');
const avatarUsuario = document.getElementById('avatarUsuario');

avatarUsuario.addEventListener('change', (e) => {
    const { target } = e;
    const avatarUsuario = target.files[0]; 
    if (!(/\.(jpg|png|gif)$/i).test(avatarUsuario.name)) {
        Swal.fire({
            icon: 'error',
            title: 'Ops...',
            text: 'El archivo seleccionado no es una imagen',
            footer: '<b>Por favor seleccione una imagen</b>'
        });
    } else if (avatarUsuario.size > 1000000) { // Si el tamanio de la imagen es mayor a 1 mb
            Swal.fire({
                icon: 'error',
                title: 'Ops...',
                text: 'El tamaño de la imagen es muy grande',
                footer: '<b>Por favor seleccione una imagen de maximo 1MB</b>'
            });    
    } else {   
        imagenSeleccionada = avatarUsuario; 
    }
});

const subirImagen = () => {
    if (imagenSeleccionada===null) {
        Swal.fire({
            icon: 'error',
            title: 'Ops...',
            text: 'Debe seleccionar una imagen primero',
            footer: '<b>Por favor seleccione una imagen de maximo 1MB</b>'
        });
    } else {
        const formData = new FormData();
        formData.append('avatarUsuario', imagenSeleccionada);
        $.ajax(`${ API }/usuarios/guardar-fotografia.php`, {
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success:function (response) {

                const { data } = response;
                imagenSeleccionada = null;
                    Swal.fire({
                        icon: 'success',
                        title: 'Accion realizada Exitosamente',
                        text: `${ data.message }`,
                        confirmButtonText: 'Ir a menu'
                    }).then((result) => { window.location.href = '../views/menu.php'; });

            }, 
            error:function(error) {
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
}