const iniciarSesion = () => {
    $('#span-campos').html(``);
    $('#span-passwordEmpleado').html(``);
    $('#span-correoInstitucional').html(``);
    let correoInstitucional =$('#correoInstitucional').val();
    let passwordEmpleado =$('#passwordEmpleado').val();
    let parametros = {
        correoInstitucional: correoInstitucional,
        passwordEmpleado: passwordEmpleado
    };
    //console.log(parametros);
    $.ajax(`${ APILogin }/usuarios/login.php`, {
        type: 'POST',
        dataType: 'json',
        data: JSON.stringify(parametros),
        contentType: 'application/json',
        success:function(response) {
            if (response.status === 200) {
                window.location.href = './frontend/public/views/menu.php';
            }
        },
        error:function(error) {
            console.log(error.responseText);
            const { data } = error.responseJSON;
            const { message } = data;
            console.log(message);
            if (message === error1 ) {
                $('#span-campos').removeClass('d-none');
                $('#span-campos').html(message + ' debe escribir sus credenciales');
            } else if (message === error2){
                $('#span-passwordEmpleado').removeClass('d-none');
                $('#span-passwordEmpleado').html(message);
            } else {
                $('#span-correoInstitucional').removeClass('d-none');
                $('#span-correoInstitucional').html(message);
            }
            
        }
    });
}

const openModalRecuperaCuenta = async () => {
    const { value: correoInstitucional } = await Swal.fire({
        customClass: 'sweetalert-lg',
        title: 'Generar nueva clave de acceso',
        input: 'email',
        inputLabel: 'Escriba su Correo Institucional',
        inputPlaceholder: 'Escriba su correo institucional',
        confirmButtonText: "Enviar nuevas credenciales",
        showCancelButton: true,
        cancelButtonColor: '#f7505a',
        cancelButtonText: "Cancelar"
    })  
    if (unahEmailRegex.test(correoInstitucional)) {
        let parametros = { correoInstitucional: correoInstitucional };
        $.ajax(`${ APILogin }/usuarios/recuperacion-credenciales.php`, {
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
            }, 
            error:function(error) {
                console.error(error);
                const { data } = error.responseJSON;
                Swal.fire({
                    icon: 'error',
                    title: 'Ops...',
                    text: `${ data.message }`,
                    footer: '<b>Por favor verifique el formulario de registro</b>'
                });
            }
        });
    } else {
        Swal.fire(`El correo escrito, no es un correo institucional`);
    }
}