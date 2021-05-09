$(document).ready(function (){
    dimensionesEstrategicas();
});

let idDimensionSeleccionada;
let idObjetivoSeleccionado;
let idAreaEstrategicaSeleccionada;
let idResultadoInstitucionalSeleccionado;

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
                                    // Peticiones de las dimensiones estrategicas
const dimensionesEstrategicas = () => {
    $('#listado-dimensiones').dataTable().fnDestroy();
    // Cargar listado de dimensiones estrategicas
    $.ajax(`${ API }/dimensiones-estrategicas/listar-dimensiones.php`, {
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
                    <td>${ data[i].dimensionEstrategica }</td>
                    <td>
                        <button type="button" ${data[i].idEstadoDimension === 1 ? `class="btn btn-success" ` : `class="btn btn-danger" `}
                        onclick=(modificaEstadoDimension(${data[i].idDimension}))>
                            ${data[i].estado }
                        </button>
                    </td>
                    <td>
                        <button type="button" class="btn btn-primary" onclick=(verObjetivosInstitucionales(${data[i].idDimension}))>
                            Objetivos Institucionales
                        </button>
                    </td>
                    <td>
                        <button type="button" class="btn btn-amber" onclick="obtenerDimensionEstrategica('${data[i].dimensionEstrategica}','${data[i].idDimension}')">
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
        // console.error(error);
        const { status, data } = error.responseJSON;
            if (status === 401) {
                window.location.href = '../views/401.php';
            }
    }});
}


// Registro dimenesion
const registrarDimension = () => { 
    let isValidNombreDimension = verificarInputText(nD, letrasEspaciosCaracteresRegex);
    if (isValidNombreDimension===true) {
        $('#btn-registrar-dimension').prop('disabled', true);   
        let parametros = { dimensionEstrategica: nombreDimension.value };
        //console.log(parametros);

        $.ajax(`${ API }/dimensiones-estrategicas/registrar-dimension.php`, {
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
                // Carga y llenado de las dimensiones estrategicas
                dimensionesEstrategicas();
            },
            error:function(error) {
                $('#btn-registrar-dimension').prop('disabled', false);
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

// Modificacion Estado Dimension
const modificaEstadoDimension = (idDimension) => {
    let parametros = { idDimensionEstrategica: parseInt(idDimension) };
    $.ajax(`${ API }/dimensiones-estrategicas/cambiar-estado-dimension.php`, {
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

            // Carga y llenado de dimensiones estrategicas
            dimensionesEstrategicas();
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
function obtenerDimensionEstrategica(dimensionEstrategica,idDimension) {
    console.log(dimensionEstrategica);
    console.log(idDimension);
    idDimensionSeleccionada = idDimension;
    $('#modalModificarDimension').modal('show');
    $('#M-nombreDimension').val(dimensionEstrategica).trigger('change');
}

// Modificamos la dimension
const modificarDimension = () => {
    let isValidNombreDimension = verificarInputText(nDM, letrasEspaciosCaracteresRegex);
    if (isValidNombreDimension===true) {
        $('#btn-modificar-dimension').prop('disabled', true);
        let parametros = {
            idDimensionEstrategica: parseInt(idDimensionSeleccionada),
            dimensionEstrategica: nombreDimensionModificar.value
        };
        console.log(parametros);

        $.ajax(`${ API }/dimensiones-estrategicas/modificar-dimension.php`,{
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
                dimensionesEstrategicas();
            },
            error:function(error) {
                $('#btn-modificar-dimension').prop('disabled', false);
                const { status, data } = error.responseJSON;
                if (status === 401) {
                    window.location.href = '../views/401.php';
                }
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
        })
    }
}

                        // Peticiones de los objetivos institucionales
let nombreObjetivo = document.querySelector('#R-objetivoInstitucional');
let nO = { valorEtiqueta: nombreObjetivo, id: 'R-objetivoInstitucional', name: 'Objetivo Institucional', min: 1, max: 800, type: 'text' };
let nombreObjetivoModificar = document.querySelector('#M-objetivoInstitucional');
let nOM = { valorEtiqueta: nombreObjetivoModificar, id: 'M-objetivoInstitucional', name: 'Objetivo Institucional', min: 1, max: 800, type: 'text' };

const verObjetivosInstitucionales = (idDimension) => {
    idDimensionSeleccionada = idDimension;
    let parametros = { idDimensionEstrategica: parseInt(idDimension) };
    $('#listado-objetivos').dataTable().fnDestroy();
    // Cargar listado de dimensiones estrategicas
    $.ajax(`${ API }/objetivos-institucionales/listar-objetivos-por-dimension.php`, {
    type: 'POST',
    dataType: 'json',
    contentType: 'application/json',
    data: JSON.stringify(parametros),
    success:function(response) {
        $('#modalObjetivosInstitucionales').modal('show');
        const { data } = response;
        console.log(data);
        $('#listado-objetivos tbody').html(``);
        
        for (let i=0;i<data.length; i++) {
            $('#listado-objetivos tbody').append(`
                <tr>
                    <td scope="row">${ i + 1}</td>
                    <td>${ data[i].ObjetivoInstitucional }</td>
                    <td>
                        <button type="button" ${data[i].idEstadoObjetivoInstitucional === 1 ? `class="btn btn-success" ` : `class="btn btn-danger" `}
                        onclick=(modificarEstadoObjetivo('${data[i].idObjetivoInstitucional}','${data[i].idEstadoObjetivoInstitucional}'))>
                            ${data[i].estado }
                        </button>
                    </td>
                    <td>
                        <button type="button" class="btn btn-primary" onclick="visualizarAreasEstrategicas('${data[i].idObjetivoInstitucional}')">
                            <img src="../img/menu/ver-icon.svg"/>
                            Ver
                        </button>
                    </td>
                    <td>
                        <button type="button" class="btn btn-amber" onclick="obtenerObjetivoInstitucional('${data[i].idObjetivoInstitucional}','${data[i].ObjetivoInstitucional}')">
                            <img src="../img/menu/editar.svg" alt="modificar objetivos"/>
                        </button>
                    </td>
                </tr>
            `)
        }
        $('#listado-objetivos').DataTable({
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
        Swal.fire({
            icon: 'error',
            title: 'Ops...',
            text: `${ data.message }`
        });
    }});
}

const registrarObjetivo = () => {
    let isValidNombreObjetivo = verificarInputText(nO, letrasEspaciosCaracteresRegex);
    if (isValidNombreObjetivo===true) {
        $('#btn-registrar-objetivo').prop('disabled', true);   
        let parametros = {
            idDimensionEstrategica: parseInt(idDimensionSeleccionada),
            objetivoInstitucional: nombreObjetivo.value
        };
        console.log('Longitud de esta vaina => ', (parametros.objetivoInstitucional.length));
        console.log(parametros);
        $.ajax(`${ API }/objetivos-institucionales/registro-objetivo-por-dimension.php`, {
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(parametros),
            success:function (response) {
                const { data } = response;
                console.log(response);
                Swal.fire({
                    icon: 'success',
                    title: 'Accion realizada Exitosamente',
                    text: `${ data.message }`,
                });
                $('#btn-registrar-objetivo').prop('disabled', false); 
                $('#modalRegistroObjetivo').modal('hide');
                verObjetivosInstitucionales(idDimensionSeleccionada);
                cancelarRegistroObjetivo();
            },
            error:function (error) {
                $('#btn-registrar-objetivo').prop('disabled', false); 
                const { status, data } = error.responseJSON;
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
            text: 'El registro del objetivo no se pudo realizar',
            footer: '<b>Por favor verifique el formulario de registro</b>'
        });
    }
}

const modificarEstadoObjetivo = (idObjetivo, idEstadoObjetivo) => {
    let parametros = { idObjetivo: parseInt(idObjetivo), idEstadoObjetivo: parseInt(idEstadoObjetivo) };
    console.log('Data -> ', parametros);
    $.ajax(`${ API }/objetivos-institucionales/modificar-estado-objetivo.php`, {
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify(parametros),
        success:function (response) {
            const { data } = response;
            console.log(response);
            Swal.fire({
                icon: 'success',
                title: 'Accion realizada Exitosamente',
                text: `${ data.message }`,
            });
            verObjetivosInstitucionales(idDimensionSeleccionada);
        },
        error:function (error) {
            const { status, data } = error.responseJSON;
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
}

function obtenerObjetivoInstitucional (idObjetivo, objetivoInstitucional) {
    $('#modalModificarObjetivo').modal('show');
    idObjetivoSeleccionado = idObjetivo;
    $('#M-objetivoInstitucional').val(objetivoInstitucional).trigger('change');
}

const modificarObjetivoInstitucional = () => {
    let isValidNombreObjetivo = verificarInputText(nOM, letrasEspaciosCaracteresRegex);
    if (isValidNombreObjetivo===true) {
        $('#btn-modificar-objetivo').prop('disabled', true);   
        let parametros = {
            idObjetivo: parseInt(idObjetivoSeleccionado),
            objetivoInstiucional: nombreObjetivoModificar.value
        };
        console.log('Data -> ', parametros);
        $.ajax(`${ API }/objetivos-institucionales/modificar-objetivo.php`, {
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(parametros),
            success:function (response) {
                const { data } = response;
                console.log(response);
                Swal.fire({
                    icon: 'success',
                    title: 'Accion realizada Exitosamente',
                    text: `${ data.message }`,
                });
                $('#btn-modificar-objetivo').prop('disabled', false);
                verObjetivosInstitucionales(idDimensionSeleccionada);
                cancelarModificacionObjetivo();
                $('#modalModificarObjetivo').modal('hide');
            },
            error:function (error) {
                $('#btn-modificar-objetivo').prop('disabled', false);
                const { status, data } = error.responseJSON;
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
            text: 'El registro de la dimension no se pudo realizar',
            footer: '<b>Por favor verifique el formulario de registro</b>'
        });
    }
}

                        // Peticiones de las areas estrategicas
let nombreArea = document.querySelector('#R-areaEstrategica');
let nA = { valorEtiqueta: nombreArea, id: 'R-areaEstrategica', name: 'Area Estrategica', min: 1, max: 500, type: 'text' };
let nombreAreaModificar = document.querySelector('#M-areaEstrategica');
let nAM = { valorEtiqueta: nombreAreaModificar, id: 'M-areaEstrategica', name: 'Area Estrategica', min: 1, max: 500, type: 'text' };
                        
const visualizarAreasEstrategicas = (idObjetivo) => {
    $('#listado-areas').dataTable().fnDestroy();
    idObjetivoSeleccionado = idObjetivo;
    let parametros = { idObjetivo: parseInt(idObjetivo) };
    $.ajax(`${ API }/areas-estrategicas/listar-areas-por-objetivo.php`, {
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify(parametros),
        success:function (response) {
            const { data } = response;
            console.log(data);
            $('#modalAreasEstrategicas').modal('show');
            $('#listado-areas tbody').html(``);
        
            for (let i=0;i<data.length; i++) {
            $('#listado-areas tbody').append(`
                <tr>
                    <td scope="row">${ i + 1}</td>
                    <td>${ data[i].areaEstrategica }</td>
                    <td>
                        <button type="button" ${data[i].idEstadoAreaEstrategica === 1 ? `class="btn btn-success" ` : `class="btn btn-danger" `}
                        onclick=(modificarEstadoArea('${data[i].idAreaEstrategica}','${data[i].idEstadoAreaEstrategica}'))>
                            ${data[i].estado }
                        </button>
                    </td>
                    <td>
                        <button type="button" class="btn btn-info btn-rounded" onclick="visualizarResultadosInstitucionales('${data[i].idAreaEstrategica}')">
                            <img src="../img/menu/ver-icon.svg"/>
                            Ver
                        </button>
                    </td>
                    <td>
                        <button type="button" class="btn btn-amber" onclick="obtenerAreaEstrategica('${data[i].idAreaEstrategica}','${data[i].areaEstrategica}')">
                            <img src="../img/menu/editar.svg" alt="modificar objetivos"/>
                        </button>
                    </td>
                </tr>
            `)
        }
        $('#listado-areas').DataTable({
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
            console.error(error);
            Swal.fire({
                icon: 'error',
                title: 'Ops...',
                text: `${ data.message }`
            });
        }
    });
}

const registrarArea = () => {
    let isValidArea = verificarInputText(nA, letrasEspaciosCaracteresRegex);
    if (isValidArea===true) {
        $('#btn-registrar-area').prop('disabled', true);
        let parametros = {
            idObjetivo: parseInt(idObjetivoSeleccionado),
            areaEstrategica: nombreArea.value
        };
        console.log('Data -> ', parametros);
        $.ajax(`${ API }/areas-estrategicas/registrar-area-por-objetivo.php`, {
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(parametros),
            success:function (response) {
                const { data } = response;
                console.log(response);
                Swal.fire({
                    icon: 'success',
                    title: 'Accion realizada Exitosamente',
                    text: `${ data.message }`,
                });
                $('#btn-registrar-area').prop('disabled', false);
                $('#modalRegistroArea').modal('hide');
                visualizarAreasEstrategicas(idObjetivoSeleccionado);
                cancelarRegistroArea();
            },
            error:function (error) {
                $('#btn-registrar-area').prop('disabled', false);
                const { status, data } = error.responseJSON;
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
            text: 'El registro del area no se pudo realizar',
            footer: '<b>Por favor verifique el formulario de registro</b>'
        });
    }
}

const modificarEstadoArea = (idArea, idEstadoArea) => {
        let parametros = {
            idArea: parseInt(idArea),
            idEstadoArea: parseInt(idEstadoArea)
        };
        console.log('Data -> ', parametros);
        $.ajax(`${ API }/areas-estrategicas/cambiar-estado-area.php`, {
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(parametros),
            success:function (response) {
                const { data } = response;
                if (data.status === 401) {
                    window.location = '../../../views/401.php';
                }
                console.log(response);
                Swal.fire({
                    icon: 'success',
                    title: 'Accion realizada Exitosamente',
                    text: `${ data.message }`,
                });
                visualizarAreasEstrategicas(idObjetivoSeleccionado);
            },
            error:function (error) {
                const { status, data } = error.responseJSON;
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
    
}

function obtenerAreaEstrategica (idArea, areaEstrategica) {
    idAreaEstrategicaSeleccionada = idArea;
    $('#modalModificarArea').modal('show');
    $('#M-areaEstrategica').val(areaEstrategica).trigger('change');
}

const modificarAreaEstrategica = () => {
    let isValidNombreArea = verificarInputText(nAM, letrasEspaciosCaracteresRegex);
    if (isValidNombreArea===true) {
        $('#btn-modificar-area').prop('disabled', true);
        let parametros = {
            idArea: parseInt(idAreaEstrategicaSeleccionada),
            areaEstrategica: nombreAreaModificar.value
        };
        console.log('Data -> ', parametros);
        $.ajax(`${ API }/areas-estrategicas/modificar-area.php`, {
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(parametros),
            success:function (response) {
                const { data } = response;
                console.log(response);
                Swal.fire({
                    icon: 'success',
                    title: 'Accion realizada Exitosamente',
                    text: `${ data.message }`,
                });
                $('#btn-modificar-area').prop('disabled', false);
                visualizarAreasEstrategicas(idObjetivoSeleccionado);
                cancelarModificacionArea();
                $('#modalModificarArea').modal('hide');
            },
            error:function (error) {
                $('#btn-modificar-area').prop('disabled', false);
                const { status, data } = error.responseJSON;
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
            text: 'El registro de la dimension no se pudo realizar',
            footer: '<b>Por favor verifique el formulario de registro</b>'
        });
    }
}

const visualizarResultadosInstitucionales = (idAreaEstrategica) => {
    idAreaEstrategicaSeleccionada = idAreaEstrategica;
    $('#listado-resultados').dataTable().fnDestroy();
    let parametros = { idAreaEstrategica: idAreaEstrategicaSeleccionada };
    $.ajax(`${ API }/resultados-institucionales/listar-resultados.php`, {
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify(parametros),
        success:function(response) {
            const { data } = response;
            console.log(data);
            
            $('#modalResultadosInstitucionales').modal('show');
            $('#listado-resultados tbody').html(``);
        
            for (let i=0;i<data.length; i++) {
            $('#listado-resultados tbody').append(`
                <tr>
                    <td scope="row">${ i + 1}</td>
                    <td>${ data[i].resultadoInstitucional }</td>
                    <td>
                        <button type="button" ${data[i].idEstadoResultadoInstitucional === 1 ? `class="btn btn-success" ` : `class="btn btn-danger" `}
                        onclick=(modificarEstadoResultado('${data[i].idResultadoInstitucional}','${data[i].idEstadoResultadoInstitucional}'))>
                            ${data[i].estado }
                        </button>
                    </td>
                    <td>
                        <button type="button" class="btn btn-amber" onclick="modalModificarResultadoSeleccionado('${data[i].idResultadoInstitucional}','${data[i].resultadoInstitucional}')">
                            <img src="../img/menu/editar.svg" alt="modificar resultado"/>
                        </button>
                    </td>
                </tr>
            `)
        }
        $('#listado-resultados').DataTable({
            language: i18nEspaniol,
            //dom: 'Blfrtip',
            //buttons: botonesExportacion,
            retrieve: true
        });
        },
        error:function(error) {
            const { status, data } = error.responseJSON;
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
}

const modalRegistraResultadoInstitucional = () => {
    $('#modalRegistraResultadosInstitucionales').modal('show');
}

const registrarResultado = () => {
    let resultadoInstitucional = document.querySelector('#R-resultadoInstitucional');
    let rI = { valorEtiqueta: resultadoInstitucional, id: 'R-resultadoInstitucional', name: 'Objetivo Institucional', min: 1, max: 700, type: 'text' };
    const isValidResultado = verificarInputText(rI, letrasEspaciosCaracteresRegex);
    if (isValidResultado) {
        let parametros = {
            idAreaEstrategica: parseInt(idAreaEstrategicaSeleccionada),
            resultadoInstitucional: resultadoInstitucional.value
        };
        console.log(parametros);
        $('#btn-registrar-resultado').prop('disabled', true);
        $.ajax(`${ API }/resultados-institucionales/registrar-resultado.php`, {
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(parametros),
            success:function(response) {
                $('#modalRegistraResultadosInstitucionales').modal('hide');
                const { data } = response;
                console.log(data);
                visualizarResultadosInstitucionales(idAreaEstrategicaSeleccionada);
                cancelarRegistroResultado();
                $('#btn-registrar-resultado').prop('disabled', false);
                Swal.fire({
                    icon: 'success',
                    title: 'Accion realizada Exitosamente',
                    text: `${ data.message }`,
                });
            },
            error:function(error) {
                console.log(error);
                $('#btn-registrar-resultado').prop('disabled', false);
                const { status, data } = error.responseJSON;
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
            text: 'El registro del resultado no se puede realizar',
            footer: '<b>Por favor verifique el formulario de registro</b>'
        });
    }
}

cancelarRegistroResultado = () => {
    let resultadoInstitucional = document.querySelector('#R-resultadoInstitucional');
    let rI = { valorEtiqueta: resultadoInstitucional, id: 'R-resultadoInstitucional', name: 'Objetivo Institucional', min: 1, max: 600, type: 'text' };
    limpiarCamposFormulario(rI);
    $(`#R-resultadoInstitucional`).trigger('reset');
}

const modificarEstadoResultado = (idResultadoInstitucional, idEstadoResultadoInstitucional) => {
    let parametros = {
        idResultadoInstitucional: parseInt(idResultadoInstitucional),
        idEstadoResultadoInstitucional:parseInt(idEstadoResultadoInstitucional)
    };
    console.log(parametros);
    $.ajax(`${ API }/resultados-institucionales/modificar-estado-resultado.php`, {
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify(parametros),
        success:function(response) {
            const { data } = response;
            console.log(data);
            visualizarResultadosInstitucionales(idObjetivoSeleccionado);
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
                console.error(error);
                Swal.fire({
                    icon: 'error',
                    title: 'Ops...',
                    text: `${ data.message }`
                });
        }
    })
}

const modalModificarResultadoSeleccionado = (idResultadoInstitucional, resultadoInstitucional) => {
    $('#modalModificaResultadosInstitucionales').modal('show');
    idResultadoInstitucionalSeleccionado = idResultadoInstitucional;
    $('#M-resultadoInstitucional').val(resultadoInstitucional).trigger('change');
}


const modificarResultado = () => {
    let resultadoInstitucionalModificado = document.querySelector('#M-resultadoInstitucional');
    let rIM = { valorEtiqueta: resultadoInstitucionalModificado, id: 'M-resultadoInstitucional', name: 'Objetivo Institucional', min: 1, max: 700, type: 'text' };
    const isValidResultado = verificarInputText(rIM, letrasEspaciosCaracteresRegex);
    if (isValidResultado) {
        let parametros = {
            idResultadoInstitucional: parseInt(idResultadoInstitucionalSeleccionado),
            resultadoInstitucional: resultadoInstitucionalModificado.value
        };
        console.log(parametros);
        $('#btn-modificar-resultado').prop('disabled', true);
        $.ajax(`${ API }/resultados-institucionales/modificar-resultado.php`, {
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(parametros),
            success:function(response) {
                $('#modalModificaResultadosInstitucionales').modal('hide');
                const { data } = response;
                console.log(data);
                visualizarResultadosInstitucionales(idObjetivoSeleccionado);
                cancelarRegistroResultado();
                $('#btn-modificar-resultado').prop('disabled', false);
                Swal.fire({
                    icon: 'success',
                    title: 'Accion realizada Exitosamente',
                    text: `${ data.message }`,
                });
            },
            error:function(error) {
                console.log(error);
                $('#btn-modificar-resultado').prop('disabled', false);
                const { status, data } = error.responseJSON;
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
            text: 'El registro del resultado no se puede realizar',
            footer: '<b>Por favor verifique el formulario de registro</b>'
        });
    }
}

cancelarModificacionResultado = () => {
    let resultadoInstitucionalModificado = document.querySelector('#M-resultadoInstitucional');
    let rIM = { valorEtiqueta: resultadoInstitucionalModificado, id: 'M-resultadoInstitucional', name: 'Objetivo Institucional', min: 1, max: 700, type: 'text' };
    limpiarCamposFormulario(rIM);
    $(`#R-resultadoInstitucional`).trigger('reset');
}
