let idcontrolPresupuestoSeleccionado = null;
let idPresupuestoAnualSeleccionadoDepto = null;
const openModalRegistroPresupuesto = () => {
    $('#estadoPresupuestoAnual').html(``);
    $.ajax(`${ API }/estados/listar-estados.php`, {
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        success:function(response) {
            const { data } = response;
            $('#modalRegistrarPresupuestoAnual').modal('show');
            console.log(data);
            for(let i=0;i<data.length; i++) {
                $('#estadoPresupuestoAnual').append(`
                    <option value="${ data[i].idEstado }">${ data[i].estado }</option>
                `);
            }
        },
        error:function(error) {
            console.log(error);
            const { status, data } = error.responseJSON;
            if (status === 401) {
                window.location.href = '../views/401.php';
            }
            Swal.fire({
                icon: 'error',
                title: 'Ops...',
                text: `${ data.message }`,
            });
        }
    });
}

const registrarPresupuesto = () => {
    let presupuestoAnual = document.querySelector('#R-presupuestoAnual');
    let pA = { valorEtiqueta: presupuestoAnual, id: 'R-presupuestoAnual', name: 'Presupuesto Anual', min: 1, max: 15, type: 'number' };
    let fechaPresupuesto = document.querySelector('#R-fechaPresupuestoAnual');
    let fP = { valorEtiqueta: fechaPresupuesto, id: 'R-fechaPresupuestoAnual', name: 'Fecha del presupuesto', type: 'date' };
    let isValidPresupuestoAnual = verificarInputNumber(pA, regexCampoMonetario);
    let isValidFecha = verificarInputText(fP,justificacionRegex);

    if ((isValidPresupuestoAnual === true) && (isValidFecha === true)) {
        $('#btn-registrar-presupuesto').prop('disabled', true);
        let parametros = {
            presupuestoAnual: Number(presupuestoAnual.value),
            estadoPresupuestoAnual: parseInt($('#estadoPresupuestoAnual').val()),
            fechaPresupuestoAnual: fechaPresupuesto.value
        };
        console.log(parametros);
        $.ajax(`${ API }/presupuestos/registrar-presupuesto-anual.php` , {
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(parametros),
            success:function(response) {
                const { data } = response;
                console.log(data);
                Swal.fire({
                    icon: 'success',
                    title: 'Accion realizada Exitosamente',
                    text: `${ data.message }`,
                });
                $('#modalRegistrarPresupuestoAnual').modal('hide');
                cancelarRegistroPresupuesto();
                $('#btn-registrar-presupuesto').prop('disabled', false);
            },
            error:function(error) {
                cancelarRegistroPresupuesto();
                $('#btn-registrar-presupuesto').prop('disabled', false);
                console.log(error);
                const { status, data } = error.responseJSON;
                if (status === 401) {
                    window.location.href = '../views/401.php';
                }
                console.log(data);
                Swal.fire({
                    icon: 'error',
                    title: 'Ops...',
                    text: `${ data.message }`,
                });
            }
        });
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Ops...',
            text: 'El registro del presupuesto no se pudo realizar',
            footer: '<b>Por favor verifique el formulario de registro</b>'
        });
    }
}

const cancelarRegistroPresupuesto = () => {
    let presupuestoAnual = document.querySelector('#R-presupuestoAnual');
    let pA = { valorEtiqueta: presupuestoAnual, id: 'R-presupuestoAnual', name: 'Presupuesto Anual', min: 1, max: 15, type: 'number' };
    let fechaPresupuesto = document.querySelector('#R-fechaPresupuestoAnual');
    let fP = { valorEtiqueta: fechaPresupuesto, id: 'R-fechaPresupuestoAnual', name: 'Fecha del presupuesto', type: 'date' };
    limpiarCamposFormulario(pA);
    $('#R-presupuestoAnual').trigger('reset');
    limpiarCamposFormulario(fP);
    $('#R-fechaPresupuestoAnual').val('');
}
const cancelarModificacionPresupuesto = () => {
    let presupuestoAnual = document.querySelector('#M-presupuestoAnual');
    let pA = { valorEtiqueta: presupuestoAnual, id: 'M-presupuestoAnual', name: 'Presupuesto Anual', min: 1, max: 15, type: 'number' };
    limpiarCamposFormulario(pA);
    $('#M-presupuestoAnual').trigger('reset');
}

const listarPresupuestos = () => {
    $.ajax(`${ API }/presupuestos/listar-presupuestos.php` ,{
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        success:function(response) {
            const { data } = response;
            console.log(data);
            if (data.length === 0) {
                $('#notificacion-presupuesto-anual').html(`
                    <span class="text-primary font-weigth-bolder text-center">No se ha asignado presupuestos para este año<span>
                `);
            } else {
                $('#listado-presupuestos tbody').html(``);
                for (let i=0;i<data.length; i++) {
                $('#listado-presupuestos tbody').append(`
                    <tr class="my-auto">
                        <td class="my-auto">
                            <h5>${ i + 1 }</h5>
                        </td>
                        <td class="my-auto">
                            <h5>${ data[i].idControlPresupuestoActividad }</h5>
                        </td>
                        <td class="my-auto">
                            <h5>${ data[i].presupuestoAnual }</h5>
                        </td>
                        <td class="my-auto">
                            <h5>${ data[i].fechaPresupuesto }</h5>
                        </td>
                        <td class="my-auto">
                        <button type="button" ${ parseInt(data[i].estadoLlenadoActividades) === 1 ? `class="btn btn-success" ` : `class="btn btn-danger" `}
                        onclick="modificarEstadoLlenado('${data[i].idControlPresupuestoActividad}','${data[i].estadoLlenadoActividades}')">
                            ${parseInt(data[i].estadoLlenadoActividades) === 1 ? `Abierto` : `Cerrado `}
                        </button>
                        </td>
                        <td class="my-auto">
                            <button type="button" class="btn btn-info btn-sm" onclick="modificarPresupuesto('${ data[i].idControlPresupuestoActividad }','${ data[i].idEstadoPresupuestoAnual }')">
                                <img src="../img/menu/visualizar-icon.svg" alt="Modificar Presupuesto"/>
                            </button>
                        </td>
                    </tr>
                `)
                }
                $('#listado-presupuestos').DataTable({
                    language: i18nEspaniol,
                    //dom: 'Blfrtip',
                    //buttons: botonesExportacion,
                    retrieve: true
                });
            }
        },
        error:function(error) {
            const { status, data } = error.responseJSON;
            if (status === 401) {
                window.location.href = '../views/401.php';
            }
            console.log(data);
            Swal.fire({
                icon: 'error',
                title: 'Ops...',
                text: `${ data.message }`,
            });
        }
    });
}


let presupuestoAnualModificar = document.querySelector('#M-presupuestoAnual');
let pAM = { valorEtiqueta: presupuestoAnualModificar, id: 'M-presupuestoAnual', name: 'Presupuesto Anual', min: 1, max: 15, type: 'number' };
let idPresupuestoSeleccionado = null;
const modificarPresupuesto = (idControlPresupuestoActividad, idEstadoActividad) => {
    
    $('#M-estadoPresupuestoAnual').html(``);
    idPresupuestoSeleccionado = idControlPresupuestoActividad;
    let parametros ={ idPresupuestoAnual: parseInt(idControlPresupuestoActividad) };
    $.when(
        $.ajax(`${ API }/presupuestos/verifica-presupuesto-modificar.php` , {
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(parametros)
        }),
        $.ajax(`${ API }/estados/listar-estados.php`, {
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json'
    }))
    .done(function(presupuestoResponse,estadosResponse) {
        $('#M-presupuestoAnual').val(presupuestoResponse[0].data.presupuestoAnual).trigger('change');
        for(let i=0;i<estadosResponse[0].data.length; i++) {
            if(estadosResponse[0].data[i].idEstado === parseInt(idEstadoActividad)) {
                $('#M-estadoPresupuestoAnual').append(`
                <option value="${ estadosResponse[0].data[i].idEstado }" selected>${ estadosResponse[0].data[i].estado }</option>
            `);
            } else {
                $('#M-estadoPresupuestoAnual').append(`
                <option value="${ estadosResponse[0].data[i].idEstado }">${ estadosResponse[0].data[i].estado }</option>
                `);
            }
        }
        $('#modalModificarPresupuestoAnual').modal('show');
    })
    .fail(function(error) {
        const { status, data } = error.responseJSON;
        if (status === 401) {
            window.location.href = '../views/401.php';
        }
        console.log(data);
        Swal.fire({
            icon: 'error',
            title: 'Ops...',
            text: `${ data.message }`,
            footer: '<b>Por favor recargue la pagina</b>'
        });
    });
}

const modificaPresupuesto = () => {
    let isValidPresupuestoAnual = verificarInputNumber(pAM, regexCampoMonetario);
    if (isValidPresupuestoAnual === true) {
        let parametros = {
            idControlPresupuestoActividad: parseInt(idPresupuestoSeleccionado),
            presupuestoAnual: Number(document.querySelector('#M-presupuestoAnual').value),
            estadoPresupuestoAnual: parseInt($('#M-estadoPresupuestoAnual').val())
        };
        console.log(parametros);
        $.ajax(`${ API }/presupuestos/modificar-presupuesto-anual.php` , {
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(parametros),
            success:function(response) {
                const { data } = response;
                $('#btn-modif-presupuesto').prop('disabled', false);
                $('#modalModificarPresupuestoAnual').modal('hide');
                $('#modalVisualizarPresupuesto').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Accion realizada Exitosamente',
                    text: `${ data.message }`,
                });
            },
            error:function(error) {
                $('#btn-modif-presupuesto').prop('disabled', false);
                $('#modalModificarPresupuestoAnual').modal('hide');
                $('#modalVisualizarPresupuesto').modal('hide');
                console.log(error);
                const { status, data } = error.responseJSON;
                if (status === 401) {
                    window.location.href = '../views/401.php';
                }
                console.log(data);
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
            text: 'El registro del presupuesto no se pudo realizar',
            footer: '<b>Por favor verifique el formulario de registro</b>'
        });
    }
}

const asignarPresupuestoDepto = () => {
    $('#modalRegistrarPresupuesto').modal('show');
    $.when(
        $.ajax(`${ API }/presupuestos-departamentos/listar-informacion-presupuesto-anual.php`, {
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json'
        }),
        $.ajax(`${ API }/presupuestos-departamentos/listar-presupuestos-deptos.php`, {
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json'
        }),
        $.ajax(`${ API }/departamentos/listar-departamentos.php`, {
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify({ idEstadoDepartamento: 1 })
        }))
        .done(function(informacionPresupuestoResponse, presupuestoDepartamentosResponse, departamentosFacultadResponse) {
            $('#listado-presupuestos-departamentos').dataTable().fnDestroy();
            let infoPresupuestos = informacionPresupuestoResponse[0].data;
            let listadoDepartamentos = departamentosFacultadResponse[0].data;
            let presupuestoTotal = (infoPresupuestos.presupuestoAnual === null ? 0 : infoPresupuestos.presupuestoAnual);
            let presupuestoUtilizado = (infoPresupuestos.montoTotalPorDepartamentos === null ? 0 : infoPresupuestos.montoTotalPorDepartamentos);
            idcontrolPresupuestoSeleccionado = infoPresupuestos.idControlPresupuestoActividad;

            $('#departamento-facultad').html(``);
            $('#presupuestoAnualTotal').val(presupuestoTotal).trigger('change');
            $('#presupuestoAnualDisponible').val(presupuestoUtilizado).trigger('change');
            for(let i=0; i<listadoDepartamentos.length; i++) {
                $('#departamento-facultad').append(`
                    <option value="${ listadoDepartamentos[i].idDepartamento }">${ listadoDepartamentos[i].nombreDepartamento }</option>
                `)
            }

            console.log(presupuestoDepartamentosResponse[0].data);

                let presupuestoDepartamentos = presupuestoDepartamentosResponse[0].data;
                $('#listado-presupuestos-departamentos tbody').html(``);
                for (let i=0;i<presupuestoDepartamentos.length; i++) {
                $('#listado-presupuestos-departamentos tbody').append(`
                    <tr class="my-auto">
                        <td class="my-auto">
                            <h5>${ i + 1 }</h5>
                        </td>
                        <td class="my-auto">
                            <h5>${ presupuestoDepartamentos[i].nombreDepartamento }</h5>
                        </td>
                        <td class="my-auto">
                            <h5>${ presupuestoDepartamentos[i].abrev }</h5>
                        </td>
                        <td class="my-auto">
                            <h5>${ presupuestoDepartamentos[i].montoPresupuesto }</h5>
                        </td>
                        <td class="my-auto">
                            <h5>${ presupuestoDepartamentos[i].fechaPresupuesto }</h5>
                        </td>
                        <td class="my-auto">
                            <button 
                                type="button" 
                                class="btn btn-info btn-sm" 
                                onclick="modificarPresupuestoDepartamento('${ presupuestoDepartamentos[i].idControlPresupuestoActividad }', '${ presupuestoDepartamentos[i].idDepartamento }', '${ presupuestoDepartamentos[i].nombreDepartamento }' ,'${ presupuestoDepartamentos[i].montoPresupuesto }')">
                                <img src="../img/menu/visualizar-icon.svg" alt="Modificar Presupuesto"/>
                            </button>
                        </td>
                    </tr>
                `)
                }
                $('#listado-presupuestos-departamentos').DataTable({
                    language: i18nEspaniol,
                    //dom: 'Blfrtip',
                    //buttons: botonesExportacion,
                    retrieve: true
                });
        })
        .fail(function(error) {
            console.log(error);
            const { status, data } = error.responseJSON;
            if (status === 401) {
                window.location.href = '../views/401.php';
            }
            console.log(data);
            Swal.fire({
                icon: 'error',
                title: 'Ops...',
                text: `${ data.message }`,
                footer: '<b>Por favor recargue la pagina</b>'
            });
        });
}

const registrarPresupuestoDepartamento = () => {
    let presupuestoDepartamento = document.querySelector('#R-presupuestoDepartamento');
    let pD = { valorEtiqueta: presupuestoDepartamento, id: 'R-presupuestoDepartamento', name: 'Presupuesto Departamento', min: 1, max: 15, type: 'number' };

    let isValidPresupuestoDepartamento = verificarInputNumber(pD, regexCampoMonetario);

    if (isValidPresupuestoDepartamento === true) { 
        $('#btn-registra-presupuesto-depto').prop('disabled', true);
        let parametros = {
            idControlPresupuestoActividad: parseInt(idcontrolPresupuestoSeleccionado),
            idDepartamento: parseInt($('#departamento-facultad').val()),
            montoPresupuesto: Number(presupuestoDepartamento.value)
        };
        console.log(parametros);
        $.ajax(`${ API }/presupuestos-departamentos/registrar-presupuesto-depto.php`, {
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(parametros),
            success:function(response) {
                asignarPresupuestoDepto();
                $('#btn-registra-presupuesto-depto').prop('disabled', false);
                cancelarPresupuestoDepto(); 
                const { data } = response;
                Swal.fire({
                    icon: 'success',
                    title: 'Accion realizada Exitosamente',
                    text: `${ data.message }`,
                });
            },
            error:function(error) {
                $('#btn-registra-presupuesto-depto').prop('disabled', false);
                console.log(error);
                cancelarPresupuestoDepto();
                const { status, data } = error.responseJSON;
                if (status === 401) {
                    window.location.href = '../views/401.php';
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Ops...',
                    text: `${ data.message }`,
                    footer: '<b>Por favor recargue la pagina</b>'
                });
            }
        })
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Ops...',
            text: 'El registro del presupuesto de departamento no se pudo realizar',
            footer: '<b>Por favor verifique el formulario de registro</b>'
        });
    }
}

const cancelarPresupuestoDepto = () => {
    let presupuestoDepartamento = document.querySelector('#R-presupuestoDepartamento');
    let pD = { valorEtiqueta: presupuestoDepartamento, id: 'R-presupuestoDepartamento', name: 'Presupuesto Anual', min: 1, max: 15, type: 'number' };
    limpiarCamposFormulario(pD);
    $('#R-presupuestoDepartamento').trigger('reset');
}

const modificarPresupuestoDepartamento = (idPresupuestoAnual, idDepartamento, nombreDepartamento, montoPresupuesto) => {
    $('#M-Depto').html(``);
    idPresupuestoAnualSeleccionadoDepto = idPresupuestoAnual;
    $('#modalModificarPresupuestoDepto').modal('show');
    $('#M-presupuestoDepto').val(montoPresupuesto).trigger('change');
    $('#M-Depto').html(`<option value="${ idDepartamento }">${ nombreDepartamento }</option>`);
}

const mPresupuestoDepartamento = () => {
    let presupuestoDepartamento = document.querySelector('#M-presupuestoDepto');
    let pD = { valorEtiqueta: presupuestoDepartamento, id: 'M-presupuestoDepto', name: 'Presupuesto Departamento', min: 1, max: 15, type: 'number' };

    let isValidPresupuestoDepartamento = verificarInputNumber(pD, regexCampoMonetario);

    if (isValidPresupuestoDepartamento) {
        let parametros = {
            idDepartamento: parseInt($('#M-Depto').val()),
            montoPresupuesto: Number($('#M-presupuestoDepto').val()),
            idControlPresupuestoActividad: parseInt(idPresupuestoAnualSeleccionadoDepto)
        };
        console.log(parametros);
        $('#btn-modif-presupuesto-dep').prop('disabled', true);
        $.ajax(`${ API }/presupuestos-departamentos/modificar-presupuesto-depto.php`, {
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(parametros),
            success:function(response) {
                
                $('#modalModificarPresupuestoDepto').modal('hide');
                asignarPresupuestoDepto();
                $('#btn-modif-presupuesto-dep').prop('disabled', false);
                cancelarModificacionPresupuestoDepartamentos(); 
                const { data } = response;
                Swal.fire({
                    icon: 'success',
                    title: 'Accion realizada Exitosamente',
                    text: `${ data.message }`,
                });
            },
            error:function(error) {
                $('#btn-modif-presupuesto-dep').prop('disabled', false);
                console.log(error);
                cancelarModificacionPresupuestoDepartamentos();
                const { status, data } = error.responseJSON;
                if (status === 401) {
                    window.location.href = '../views/401.php';
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Ops...',
                    text: `${ data.message }`,
                    footer: '<b>Por favor recargue la pagina</b>'
                });
            }
        });
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Ops...',
            text: 'El registro del presupuesto de departamento no se pudo realizar',
            footer: '<b>Por favor verifique el formulario de registro</b>'
        });
    }
}

const cancelarModificacionPresupuestoDepartamentos = () => {
    let presupuestoDepartamento = document.querySelector('#M-presupuestoDepto');
    let pD = { valorEtiqueta: presupuestoDepartamento, id: 'M-presupuestoDepto', name: 'Presupuesto Departamento', min: 1, max: 15, type: 'number' };
    limpiarCamposFormulario(pD);
    $('#M-presupuestoDepto').trigger('reset');
}

const modificarEstadoLlenado = (idPresupuesto, idEstadoLlenado) => {
    let parametros = {
        idControlPresupuestoActividad: parseInt(idPresupuesto),
        estadoLlenadoActividades: parseInt(idEstadoLlenado)
    };

    $.ajax(`${ API }/presupuestos/modificar-estado-llenado-presupuesto.php`, {
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify(parametros),
        success:function(response) {
            const { data } = response;
            listarPresupuestos();
            Swal.fire({
                icon: 'success',
                title: 'Accion realizada Exitosamente',
                text: `${ data.message }`,
            });
        },
        error:function(error) {
            console.log(error);
            const { status, data } = error.responseJSON;
            if (status === 401) {
                window.location.href = '../views/401.php';
            }
            Swal.fire({
                icon: 'error',
                title: 'Ops...',
                text: `${ data.message }`,
                footer: '<b>Por favor recargue la pagina</b>'
            });
        }
    });
    console.log(parametros);
}