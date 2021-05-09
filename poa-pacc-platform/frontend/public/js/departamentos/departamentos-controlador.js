
//obteniendo los valores de las etiquetas inputs para el registro de departamentos
let nombreDepartamento = document.querySelector('#R-nombreDepartamento');
let estadoDepartamento = document.querySelector('#R-estadoDepartamento');
let telefonoDepartamento = document.querySelector('#R-numeroTelefonoDepartamento');
let abreviaturaDepartamento = document.querySelector('#R-abreviaturaDepartamento');
let correoDepartamento = document.querySelector('#R-correoDepartamento');


//verificando longitud de campos a ingresar en el registro de departamentos
let nDep = { valorEtiqueta: nombreDepartamento, id: 'R-nombreDepartamento', name: 'Nombre Departamento', min: 1, max: 80, type: 'text' };
let eDep = { valorEtiqueta: estadoDepartamento, id: 'R-estadoDepartamento', name: 'Estado Departamento', type: 'select' };
let tDep = { valorEtiqueta: telefonoDepartamento, id: 'R-numeroTelefonoDepartamento', name: 'Telefono Departamento', min: 1, max: 9, type: 'number' };
let aDep = { valorEtiqueta: abreviaturaDepartamento, id: 'R-abreviaturaDepartamento', name: 'Abreviatura Departamento', min: 1, max: 2, type: 'text' };
let cDep = { valorEtiqueta: correoDepartamento, id: 'R-correoDepartamento', name: 'Correo Departamento', min: 1, max: 60, type: 'email' };



//Limpiar los campos del formulario de registro de departamentos
const cancelarRegistroDepartamento = () => {
    limpiarCamposFormulario(nDep);
    $(`#R-nombreDepartamento`).trigger('reset');
    limpiarCamposFormulario(eDep);
    $(`#R-estadoDepartamento`).trigger('reset');
    limpiarCamposFormulario(tDep);
    $(`#R-numeroTelefonoDepartamento`).trigger('reset'); 
    limpiarCamposFormulario(aDep);
    $(`#R-abreviaturaDepartamento`).trigger('reset');  
    limpiarCamposFormulario(cDep);
    $(`#R-correoDepartamento`).trigger('reset');
       
}



//cargar los departamentos registrados 
const verDepartamentos = () => {
    $('#listado-departamentos').dataTable().fnDestroy();
    $.ajax(`${ API }/control-departamentos/listar-departamentos.php`, {
    type: 'POST',
    dataType: 'json',
    contentType: 'application/json',
    success:function(response) {
        const { data } = response;
        console.log(data);
        $('#listado-departamentos tbody').html(``);
        
        for (let i=0;i<data.length; i++) {
            $('#listado-departamentos tbody').append(`
                <tr>
                    <td scope="row">${ i + 1 }</td>
                    <td>${ data[i].nombreDepartamento }</td>
                    <td>${ data[i].abrev }</td>
                    <td>${ data[i].telefonoDepartamento }</td>
                    <td>${ data[i].correoDepartamento }</td>
                    <td>${ data[i].estado }</td>
                </tr>
            `)
        }
        $('#listado-departamentos').DataTable({
            language: i18nEspaniol,
            dom: 'Blfrtip',
            buttons: botonesExportacion,
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



//Registrar nuevo departamento
const registrarDepartamento = () => {
    //verificando los campos con las funciones de verificación
    let isValidNombreDepartamento = verificarInputText(nDep, letrasEspaciosCaracteresRegex);
    let isValidEstadoDepartamento = verificarSelect(eDep);
    let isValidTelefonoDepartamento = verificarInputNumber(tDep, numeroTelefonoRefex);
    let isValidAbreviaturaDepartamento = verificarInputText(aDep, letrasEspaciosCaracteresRegex);  
    let isValidCorreoDepartamento = verificarEmail(cDep);

    // Si todos los campos son  true se realiza la operación 
    if (
        (isValidNombreDepartamento === true) &&
        (isValidEstadoDepartamento === true) &&
        (isValidTelefonoDepartamento === true) &&
        (isValidAbreviaturaDepartamento === true) && 
        (isValidCorreoDepartamento === true) 
             
    ) {
        let parametros = {
            nombreDepartamento: nombreDepartamento.value,
            estadoDepartamento: estadoDepartamento.value,
            telefonoDepartamento: telefonoDepartamento.value,
            abreviaturaDepartamento: abreviaturaDepartamento.value,
            correoDepartamento: correoDepartamento.value
        };
        //console.log(parametros);
        $.ajax(`${ API }/control-departamentos/registrar-departamento.php`, {
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(parametros),
            success:function (response) {
                const { data } = response;
                Swal.fire({
                    icon: 'success',
                    title: 'Accion realizada Exitosamente',
                    footer: '<b>Por favor verifique el formulario de registro</b>',
                });
                cancelarRegistroDepartamento();
            },
            error:function (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Ops...',
                    text: 'El registro del departamento no se pudo realizar',
                    footer: '<b>Por favor verifique el formulario de registro</b>'
                });
                const { status, data } = error.responseJSON;
                if (status === 401) {
                    window.location.href = '../views/401.php';
                }
            }
        });
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Ops...',
            text: 'El registro del departamento no se pudo realizar',
            footer: '<b>Por favor verifique el formulario de registro</b>'
        });
        
    }
}


//Petición para listar estados de departamentos en la sección de registro de nuevo departamento 
const cambiarEstado = () => {
    const peticion = {
        vacio: ""
    };
    $.ajax(`${ API }/control-departamentos/obtenerEstadoDepartamento.php`, {
        type: 'POST',
        dataType: 'json',
        data: (peticion),
        success:function(response) {
            document.getElementById("R-estadoDepartamento").innerHTML = ``;
            for(let i = 0; i < response.data.length;i++){
                document.getElementById("R-estadoDepartamento").innerHTML+=`<option value="${response.data[i].idEstado}">${response.data[i].estado}</option>`;
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



//petición para mostrar los departamentos a modificar
const cambiarDepartamento = () => {
    const peticion = {
        vacio: ""
    };
    $.ajax(`${ API }/control-departamentos/obtenerDepartamentos.php`, {
        type: 'POST',
        dataType: 'json',
        data: (peticion),
        success:function(response) {
            document.getElementById("M-Departamento").innerHTML="<option value= disabled selected></option>";
            for(let i = 0; i < response.data.length;i++){
                document.getElementById("M-Departamento").innerHTML+=`<option value="${response.data[i].idDepartamento}">${response.data[i].nombreDepartamento}</option>`;
            }
            $("#modificacion").css({'display':'none'});
            $("#botonModificarDepartamento").attr("disabled", true);
            
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


//petición para listar estados de departamentos en la sección de modificación y/o actualización de departamento 
const cambiarEstadoDepartamento = (departamento) => {
    const peticion = {
        vacio: ""
    };

    $.ajax(`${ API }/control-departamentos/obtenerEstadoDepartamento.php`, {
        type: 'POST',
        dataType: 'json',
        data: (peticion),
        success:function(response) {
            document.getElementById("M-estadoDepartamento").innerHTML = ``;
            for(let i = 0; i < response.data.length;i++){
                if(departamento.idEstadoDepartamento==response.data[i].idEstado){
                    document.getElementById("M-estadoDepartamento").innerHTML+=`<option value="${response.data[i].idEstado}" selected>${response.data[i].estado}</option>`;
                }else{
                    document.getElementById("M-estadoDepartamento").innerHTML+=`<option value="${response.data[i].idEstado}">${response.data[i].estado}</option>`;
                }
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


//petición para obtener la información del departamento segun el id seleccionado
const cambiarDepartamentoModificado = () => {
    const peticion = {
        idDepartamento: document.querySelector('#M-Departamento').value
    };
    $.ajax(`${ API }/control-departamentos/obtenerDepartamentoPorId.php`, {
        type: 'POST',
        dataType: 'json',
        data: (peticion),
        success:function(response) {
            $("#modificacion").css({'display':'block'});
            $("#botonModificarDepartamento").attr("disabled", false);
            $("#M-nombreDepartamento").val(response.data[0].nombreDepartamento).trigger("change");
            $("#M-abreviaturaDepartamento").val(response.data[0].abrev).trigger("change");
            $("#M-numeroTelefonoDepartamento").val(response.data[0].telefonoDepartamento).trigger("change");
            $("#M-correoDepartamento").val(response.data[0].correoDepartamento).trigger("change");
            //cambiarDepaModificado(response.data[0]);
            cambiarEstadoDepartamento(response.data[0]);
        },
        error:function(error) {
            console.warn(error);
            
        }
    });
};


//obteniendo los valores de las etiquetas inputs para la modificación y/o de departamentos
let nombreDepartamentoM = document.querySelector('#M-nombreDepartamento');
let estadoDepartamentoM = document.querySelector('#M-estadoDepartamento');
let telefonoDepartamentoM = document.querySelector('#M-numeroTelefonoDepartamento');
let abreviaturaDepartamentoM = document.querySelector('#M-abreviaturaDepartamento');
let correoDepartamentoM = document.querySelector('#M-correoDepartamento');

//verificando longitud de campos a modificar en la sección de modificación y/o actualización de departamentos
let nDepM = { valorEtiqueta: nombreDepartamentoM, id: 'M-nombreDepartamento', name: 'Nombre Departamento', min: 1, max: 80, type: 'text' };
let eDepM = { valorEtiqueta: estadoDepartamentoM, id: 'M-estadoDepartamento', name: 'Estado Departamento', type: 'select' };
let tDepM = { valorEtiqueta: telefonoDepartamentoM, id: 'M-numeroTelefonoDepartamento', name: 'Telefono Departamento', min: 1, max: 9, type: 'number' };
let aDepM = { valorEtiqueta: abreviaturaDepartamentoM, id: 'M-abreviaturaDepartamento', name: 'Abreviatura Departamento', min: 1, max: 2, type: 'text' };
let cDepM = { valorEtiqueta: correoDepartamentoM, id: 'M-correoDepartamento', name: 'Correo Departamento', min: 1, max: 60, type: 'email' };

//Petición pata actulizar y/o modificar departamento
const modificarDepartamento = () => {

    //verificando los campos con las funciones de verificación
    let isValidNombreDepartamentoM = verificarInputText(nDepM, letrasEspaciosCaracteresRegex);
    let isValidEstadoDepartamentoM = verificarSelect(eDepM);
    let isValidTelefonoDepartamentoM = verificarInputNumber(tDepM, numeroTelefonoRefex);
    let isValidAbreviaturaDepartamentoM = verificarInputText(aDepM, letrasEspaciosCaracteresRegex);  
    let isValidCorreoDepartamentoM = verificarEmail(cDepM);

    // Si todos los campos que llevan validaciones son true, se realiza la operación
    if (
        (isValidNombreDepartamentoM === true) &&
        (isValidEstadoDepartamentoM === true) &&
        (isValidTelefonoDepartamentoM === true) &&
        (isValidAbreviaturaDepartamentoM === true) && 
        (isValidCorreoDepartamentoM === true) 
            
    ) {
        const dataNuevoDepartamento = {
            idDepartamentoM: document.querySelector('#M-Departamento').value,
            nombreDepartamentoM: nombreDepartamentoM.value,
            estadoDepartamentoM: estadoDepartamentoM.value,
            telefonoDepartamentoM: telefonoDepartamentoM.value,
            abreviaturaDepartamentoM: abreviaturaDepartamentoM.value,
            correoDepartamentoM: correoDepartamentoM.value 
        };
        
        $.ajax(`${ API }/control-departamentos/modificarDepartamento.php`, {
            type: 'POST',
            dataType: 'json',
            data: (dataNuevoDepartamento),
            success:function(response) {
                console.log(response);
                $("#botonModificarDepartamento").attr("disabled", true);
                cancelarModificacionDepartamento();
                $("#modificacion").css({'display':'none'});
                Swal.fire({
                    icon: 'success',
                    title: 'Listo',
                    text: 'Registro actualizado con exito',
                })
            },
            error:function(error) {
                console.log(error);
                Swal.fire({
                    icon: 'error',
                    title: 'Ops...',
                    text: 'La actualización del Departamento no se pudo realizar',
                    footer: '<b>Por favor verifique el formulario de registro</b>'
                })
            }
        });
    } else { // caso contrario mostrar alerta 
        Swal.fire({
            icon: 'error',
            title: 'Ops...',
            text: 'La actualización del Departamento no se pudo realizar',
            footer: '<b>Por favor verifique el formulario de registro</b>'
        });
        const { status, data } = error.responseJSON;
           if (status === 401) {
              window.location.href = '../views/401.php';
           }
    }
};




//Limpia los campos del formulario de modificación de departamentos
const cancelarModificacionDepartamento = () => {
    document.querySelector('#M-Departamento').value = '';
    limpiarCamposFormulario(nDepM);
    $(`#M-nombreDepartamento`).trigger('reset');
    limpiarCamposFormulario(aDepM);
    $(`#M-abreviaturaDepartamento`).trigger('reset');
    limpiarCamposFormulario(tDepM);
    $(`#M-numeroTelefonoDepartamento`).trigger('reset');
    limpiarCamposFormulario(cDepM);
    $(`#M-correoDepartamento`).trigger('reset');
    limpiarCamposFormulario(eDepM);
    $(`#M-estadoDepartamento`).trigger('reset');   
}
