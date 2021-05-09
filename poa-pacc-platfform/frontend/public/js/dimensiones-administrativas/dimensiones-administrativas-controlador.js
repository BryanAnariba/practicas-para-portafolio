$(document).ready(function (){
    dimensionesAdministrativas();
});

//obteniendo los valores de las etiquetas inputs para el registro de 
//verificando longitud de campos a ingresar en el registro de departamentos
let idDimensionSeleccionada;

let nombreDimension = document.querySelector('#R-nombreDimension');
let nD = { valorEtiqueta: nombreDimension, id: 'R-nombreDimension', name: 'Nombre Dimension', min: 1, max: 150, type: 'text' };
let nombreDimensionModificar = document.querySelector('#M-nombreDimension');
let nDM = { valorEtiqueta: nombreDimensionModificar, id: 'M-nombreDimension', name: 'Nombre Dimension', min: 1, max: 150, type: 'text' };

const cancelarRegistroDimension = () => {
    limpiarCamposFormulario(nD);
    $(`#R-nombreDimension`).trigger('reset');
}

const cancelarModificacionDimension = () => {
    limpiarCamposFormulario(nDM);
    $(`#M-nombreDimension`).trigger('reset');
}

const cancelarRegistroObjetivo = () => {
    limpiarCamposFormulario(nO);
    $(`#R-objetivoInstitucional`).trigger('reset');
}

const cancelarModificacionObjetivo = () => {
    limpiarCamposFormulario(nOM);
    $(`#R-objetivoInstitucional`).trigger('reset');
}

const cancelarRegistroArea = () => {
    limpiarCamposFormulario(nA);
    $(`#R-areaEstrategica`).trigger('reset');
}

const cancelarModificacionArea = () => {
    limpiarCamposFormulario(nAM);
    $(`#R-areaEstrategica`).trigger('reset');
}


//ver las dimensiones administrativas registradas 
const dimensionesAdministrativas = () => {
    $('#listado-dimensiones').dataTable().fnDestroy();
    // Cargar listado de dimensiones Administrativas
    $.ajax(`${ API }/dimensiones-administrativas/listar-dimensiones.php`, {
    type: 'POST',
	dataType: 'json',
    contentType: 'application/json',
    success:function(response) {
        const { data } = response;
        console.log(data);
        $('#listado-dimensiones tbody').html(``);
        
        for (let i=0;i<data.length; i++) {
            $('#listado-dimensiones tbody').append(`
                <tr>
                    <td scope="row">${ i + 1 }</td>
                    <td>${ data[i].dimensionAdministrativa }</td>
                    <td>
                        <button type="button" ${data[i].idEstadoDimension === 1 ? `class="btn btn-success" ` : `class="btn btn-danger" `}
                        onclick=(modificaEstadoDimension(${data[i].idDimension}))>
                            ${data[i].estado }
                        </button>
                    </td>
                    
                    <td>
                        <button type="button" class="btn btn-amber" onclick="obtenerDimensionAdministrativa('${data[i].dimensionAdministrativa}','${data[i].idDimension}')">
                            <img src="../img/menu/editar.svg" alt="modificar dimension"/>
                        </button>
                    </td>
                </tr>
            `)
        }
        $('#listado-dimensiones').DataTable({
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


// Registro de dimensión
const registrarDimension = () => { 
    let isValidNombreDimension = verificarInputText(nD, letrasEspaciosCaracteresRegex);
    if (isValidNombreDimension===true) {
        $('#btn-registrar-dimension').prop('disabled', true);   
        let parametros = { dimensionAdministrativa: nombreDimension.value };
        //console.log(parametros);
        $.ajax(`${ API }/dimensiones-administrativas/registrar-dimension.php`, {
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(parametros),
            success:function(response) {
                $('#modalRegistroDimension').modal('hide');
                const { data } = response;
                console.log(response);
                Swal.fire({
                    icon: 'success',
                    title: 'Accion realizada Exitosamente',
                    text: `${ data.message }`,
                });
                $('#btn-registrar-dimension').prop('disabled', false);
                // Carga y llenado de las dimensiones administrativas
                dimensionesAdministrativas();
            },
            error:function(error) {
                console.log(data);
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

        // Limpieza del formulario
        limpiarCamposFormulario(nD);
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Ops...',
            text: 'El registro de la dimension no se pudo realizar',
            footer: '<b>Por favor verifique el formulario de registro</b>'
        })
    }
}

// Modificación Estado Dimensión
const modificaEstadoDimension = (idDimension) => {
    let parametros = { idDimensionAdministrativa: parseInt(idDimension) };
    $.ajax(`${ API }/dimensiones-administrativas/cambiar-estado-dimension.php`, {
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify(parametros),
        success:function(response) {
            const { data } = response;
            console.log(response);
            Swal.fire({
                icon: 'success',
                title: 'Accion realizada Exitosamente',
                text: `${ data.message }`,
            });
              // Carga y llenado de dimensiones administrativas
            dimensionesAdministrativas();
        },
        error:function(error) {
            console.error(error);
            const { status, data } = error.responseJSON;
            if (status === 401) {
                window.location.href = '../views/401.php';
            }
            Swal.fire({
                icon: 'error',
                title: 'Ops...',
                text: `${ data.message }`,
                footer: '<b>Por favor verifique el formulario de registro</b>'
            });
            
        }
    });
}


// Obtenemos la dimension estrategica a modificar
function obtenerDimensionAdministrativa(dimensionAdministrativa,idDimension) {
    console.log(dimensionAdministrativa);
    console.log(idDimension);
    idDimensionSeleccionada = idDimension;
    $('#modalModificarDimension').modal('show');
    $('#M-nombreDimension').val(dimensionAdministrativa).trigger('change');
}

// Modificar la dimensión administrativa
const modificarDimension = () => {
    let isValidNombreDimension = verificarInputText(nDM, letrasEspaciosCaracteresRegex);
    if (isValidNombreDimension===true) {
        $('#btn-modificar-dimension').prop('disabled', true);
        let parametros = {
            idDimensionAdministrativa: parseInt(idDimensionSeleccionada),
            dimensionAdministrativa: nombreDimensionModificar.value
        };
        console.log(parametros);

        $.ajax(`${ API }/dimensiones-administrativas/modificar-dimension.php`,{
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(parametros),
            success:function(response) {
                const { data } = response;
                console.log(response);
                Swal.fire({
                    icon: 'success',
                    title: 'Accion realizada Exitosamente',
                    text: `${ data.message }`,
                });
                cancelarModificacionDimension();
                $('#btn-modificar-dimension').prop('disabled', false);
                $('#modalModificarDimension').modal('hide');
                dimensionesAdministrativas();
            },
            error:function(error) {
                const { data } = error;
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
            text: 'El registro de la dimension no se pudo realizar',
            footer: '<b>Por favor verifique el formulario de registro</b>'
        });
        const { status, data } = error.responseJSON;
        if (status === 401) {
            window.location.href = '../views/401.php';
        }
    }
}

