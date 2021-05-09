let idRangoLlenadoDimensionSeleccionado = null;
const listarLlenado = () => {
    $('#listado-llenado').dataTable().fnDestroy();
    $('#modalVisualizarDistribucionLlenado').modal('show');
    $.ajax(`${ API }/llenado-actividad-dimension/listar-control-llenado-actividades.php`, {
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        success:function(response) {
            const { data } = response;
            console.log(data);

            $('#listado-llenado tbody').html(``);
        
            for (let i=0;i<data.length; i++) {
            $('#listado-llenado tbody').append(`
                <tr>
                    <td scope="row">${ i + 1 }</td>
                    <td>${ data[i].idLlenadoDimension }</td>
                    <td>
                        ${ data[i].tipoUsuario }
                    </td>
                    <td>
                        ${ data[i].valorLlenadoDimensionInicial }
                    </td>
                    <td>
                        ${ data[i].valorLlenadoDimensionFinal }
                    </td>
                    <td>
                        <button type="button" class="btn btn-amber" onclick="modificarRango('${data[i].idLlenadoDimension}','${data[i].valorLlenadoDimensionInicial}','${data[i].valorLlenadoDimensionFinal}', '${ data[i].tipoUsuario }')">
                            <img src="../img/menu/editar.svg" alt="modificar rango"/>
                        </button>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger" onclick="eliminarRango('${data[i].idLlenadoDimension}')">
                            <img src="../img/menu/delete.svg" alt="eliminar rango"/>
                        </button>
                    </td>
                </tr>
            `)
            }
            $('#listado-llenado').DataTable({
                language: i18nEspaniol,
                retrieve: true
            });
        },
        error:function(error) {
            console.log(error.responseText);
            const { status, data } = error.responseJSON;
                if (status === 401) {
                    window.location.href = '../views/401.php';
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ops...',
                        text: `${ data.message }`,
                        footer: '<b>Por favor recarge la pagina o comuniquese con el super administrador</b>'
                    });
                }
        }
    });
}

const abrirModalRegistroControlLLenado = () => {
    $('#modalRegistraLlenadoActividad').modal('show');
    $.ajax(`${ API }/usuarios/listar-tipos-usuarios.php`, {
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        success:function(response) {
            const { data } = response;
            console.log(data);
            $('#R-idTipoCargo').html(`<option value='' selected>Seleccione el tipo de usuario</option>`);
            for(let i=0;i<data.length;i++) {
                $('#R-idTipoCargo').append(`
                    <option value=${ data[i].idTipoUsuario } >${ data[i].tipoUsuario }</option>
                `);             
            }
        },
        error:function(error) {
            console.log(error.responseText);
            const { status, data } = error.responseJSON;
                if (status === 401) {
                    window.location.href = '../views/401.php';
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ops...',
                        text: `${ data.message }`,
                        footer: '<b>Por favor recarge la pagina o comuniquese con el super administrador</b>'
                    });
            }
        }
    })
}

const registroValorInicial = document.querySelector('#R-valorMinimo');
const registroValorFinal = document.querySelector('#R-valorMaximo');
const registroidTipoUsuario = document.querySelector('#R-idTipoCargo');
let valIniR = { valorEtiqueta: registroValorInicial, id: 'R-valorMinimo', name: 'Valor inicial de llenado', min: 1, max: 3, type: 'text' };
let valFiR = { valorEtiqueta: registroValorFinal, id: 'R-valorMaximo', name: 'Valor final de llenado', min: 1, max: 3, type: 'text' };
let tipoUsuario = { valorEtiqueta: registroidTipoUsuario, id: 'R-idTipoCargo', name: 'Tipo de usuario', type: 'select' };

const registraNuevoRangoLlenadoDimension = () => {
    let isValidValorInicial = verificarInputText(valIniR, regexNumeroPositivoEntero);
    let isValidValorFinal = verificarInputText(valFiR, regexNumeroPositivoEntero);
    let isValidTipoUsuario =  verificarSelect(tipoUsuario);
    if ((isValidValorFinal === true) && (isValidValorInicial === true) && (isValidTipoUsuario === true)) {
        let parametros = {
            valorLlenadoDimensionInicial: parseInt(registroValorInicial.value),
            valorLlenadoDimensionFinal: parseInt(registroValorFinal.value),
            idTipoUsuario: parseInt(registroidTipoUsuario.value)
        };
        console.log(parametros);
        $('#btn-registrar-llenado').prop('disabled', true);
        $.ajax(`${ API }/llenado-actividad-dimension/registro-llenado-actividades.php`, {
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(parametros),
            success:function(response) {
                const { data } = response;
                $('#btn-registrar-llenado').prop('disabled', false);
                $('#modalRegistraLlenadoActividad').modal('hide');
                limpiarCamposRegistro(0);
                Swal.fire({
                    icon: 'success',
                    title: 'Accion realizada Exitosamente',
                    text: `${ data.message }`,
                });
            },
            error:function(error) {
                $('#btn-registrar-llenado').prop('disabled', false);
                $('#modalRegistraLlenadoActividad').modal('hide');
                limpiarCamposRegistro(0);
                console.log(error.responseText);
                const { status, data } = error.responseJSON;
                    if (status === 401) {
                        window.location.href = '../views/401.php';
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Ops...',
                            text: `${ data.message }`,
                            footer: '<b>Por favor recarge la pagina o comuniquese con el super administrador</b>'
                    });
                }
            }
        })
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Ops...',
            text: 'El registro del rango de llenados no se pudo realizar',
            footer: '<b>Por favor verifique el formulario de registro</b>'
        });
    }
}

const limpiarCamposRegistro = () => {
    limpiarCamposFormulario(valIniR);
    $(`#R-valorMinimo`).trigger('reset');
    limpiarCamposFormulario(valFiR);
    $(`#R-valorMaximo`).trigger('reset');
    limpiarCamposFormulario(tipoUsuario);
}


const limpiarCamposModificacion = () => {
    
}

const modificarRango = (idLlenadoDimension, valorInicial, valorFinal, tipoUsuario) => {
    $('#modalModificaLlenadoActividad').modal('show');
    idRangoLlenadoDimensionSeleccionado = idLlenadoDimension;
    $('#M-idTipoCargo').html(`<option value="${ idLlenadoDimension }">${ tipoUsuario }</option>`)
    $('#M-valorMinimo').val(valorInicial).trigger('change');
    $('#M-valorMaximo').val(valorFinal).trigger('change');
}   

const eliminarRango = (idLlenadoDimension) => {
    let parametros = {
        idLlenadoDimension: parseInt(idLlenadoDimension)
    };
    $.ajax(`${ API }/llenado-actividad-dimension/eliminar-llenado-dimension.php`, {
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify(parametros),
        success:function(response) {
            const { data } = response;
            listarLlenado();
            Swal.fire({
                icon: 'success',
                title: 'Accion realizada Exitosamente',
                text: `${ data.message }`,
            });
        },
        error:function(error) {
            console.log(error.responseText);
            const { status, data } = error.responseJSON;
                if (status === 401) {
                    window.location.href = '../views/401.php';
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ops...',
                        text: `${ data.message }`,
                        footer: '<b>Por favor recarge la pagina o comuniquese con el super administrador</b>'
                });
            }
    }});
}

const modificarValoresLlenado = () => {
    const modificacionValorInicial = document.querySelector('#M-valorMinimo');
    const modificacionValorFinal = document.querySelector('#M-valorMaximo');
    let valIniM = { valorEtiqueta: modificacionValorInicial, id: 'M-valorMinimo', name: 'Valor inicial de llenado', min: 1, max: 3, type: 'text' };
    let valFiM = { valorEtiqueta: modificacionValorFinal, id: 'M-valorMaximo', name: 'Valor final de llenado', min: 1, max: 3, type: 'text' };

    let isValidValorInicial = verificarInputText(valIniM, regexNumeroPositivoEntero);
    let isValidValorFinal = verificarInputText(valFiM, regexNumeroPositivoEntero);
    if ((isValidValorFinal === true) && (isValidValorInicial === true)) {
        let parametros = {
            idLlenadoDimension: parseInt(idRangoLlenadoDimensionSeleccionado),
            valorLlenadoDimensionInicial: parseInt(modificacionValorInicial.value),
            valorLlenadoDimensionFinal: parseInt(modificacionValorFinal.value)
        };
        console.log(parametros);
        $('#btn-modificar-llenado').prop('disabled', true);
        $.ajax(`${ API }/llenado-actividad-dimension/modificar-llenado-dimension.php`, {
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(parametros),
            success:function(response) {
                $('#modalModificaLlenadoActividad').modal('hide');
                $('#btn-modificar-llenado').prop('disabled',false);
                idRangoLlenadoDimensionSeleccionado = null;
                limpiarCamposModificar();
                const { data } = response;
                listarLlenado();
                Swal.fire({
                    icon: 'success',
                    title: 'Accion realizada Exitosamente',
                    text: `${ data.message }`,
                });
            },
            error:function(error) {
                $('#modalModificaLlenadoActividad').modal('hide');
                $('#btn-modificar-llenado').prop('disabled',false);
                idRangoLlenadoDimensionSeleccionado = null;
                limpiarCamposModificar();
                console.log(error.responseText);
                const { status, data } = error.responseJSON;
                    if (status === 401) {
                        window.location.href = '../views/401.php';
                    } else {
                        Swal.fire({
                        icon: 'error',
                        title: 'Ops...',
                        text: `${ data.message }`,
                        footer: '<b>Por favor recarge la pagina o comuniquese con el super administrador</b>'
                    });
                }
            }
        });
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Ops...',
            text: 'La modificacion del rango de llenados no se pudo realizar',
            footer: '<b>Por favor verifique el formulario de registro</b>'
        });
    }

}

const limpiarCamposModificar = () => {
    const modificacionValorInicial = document.querySelector('#M-valorMinimo');
    const modificacionValorFinal = document.querySelector('#M-valorMaximo');
    let valIniM = { valorEtiqueta: modificacionValorInicial, id: 'M-valorMinimo', name: 'Valor inicial de llenado', min: 1, max: 3, type: 'text' };
    let valFiM = { valorEtiqueta: modificacionValorFinal, id: 'M-valorMaximo', name: 'Valor final de llenado', min: 1, max: 3, type: 'text' };

    $(`#M-valorMinimo`).trigger('reset');
    limpiarCamposFormulario(valIniM);
    $(`#M-valorMaximo`).trigger('reset');
    limpiarCamposFormulario(valFiM);
}