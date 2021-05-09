var estado = 1;
let mesesRequeridos = [
    { id: 'Enero', mes: 'Enero' },
    { id: 'Febrero', mes: 'Febrero' },
    { id: 'Marzo', mes: 'Marzo' },
    { id: 'Abril', mes: 'Abril' },
    { id: 'Mayo', mes: 'Mayo' },
    { id: 'Junio', mes: 'Junio' },
    { id: 'Julio', mes: 'Julio' },
    { id: 'Agosto', mes: 'Agosto' },
    { id: 'Septiembre', mes: 'Septiembre' },
    { id: 'Octubre', mes: 'Octubre' },
    { id: 'Noviembre', mes: 'Noviembre' },
    { id: 'Diciembre', mes: 'Diciembre' }
];
let idDimensionSeleccionada = null;
let idDimensionAdminSeleccionada = null;
let idObjetivoSeleccionado = null;
let idAreaEstrategicaSeleccionada = null;
let idActividadSeleccionada = null;
let costoTotalActividadSeleccionada = null;
let idDescripcionItemSeleccionada = null;
let idActividadParaModificar = null;
let idResultadoInstitucionalModificar = null;
let resultadoInstitucionalModificar = null;
let correlativoModificar = null;
let esModificacion = false;
let esInsercion = false;
let idCostoActividadPorTrimestreSeleccionada = null;
let dimnesionParaModales = null;
$(document).ready(function(){
    // Manejo del estado de la dimension.
    if(estado==0){
        $("#mda").css("opacity", "0.6");
        $("#mda div div button").prop('disabled', true);
        $("#aga").css("opacity", "0.6");
        $("#aga div div button").prop('disabled', true);
    }else{
        $("#mda").css("opacity", "1");
        $("#mda div div button").prop('disabled', false);
        $("#aga").css("opacity", "1");
        $("#aga div div button").prop('disabled', false);
    }
    $("#ventana2").css("display","none");
    $("#ventana3").css("display","none");
    $(".foot-modif").css("display","none");
    $("#ventana1A").css("display","none");
    $(".foot-agr").css("display","none");


    $("body").click(function(){
        $('#modalFormLlenadoDimension').on('hidden.bs.modal', function () {
            if(($("#modalLlenadoActividades").data('bs.modal') || {})._isShown != true){
                $('#modalLlenadoDimension').modal('show');
                $('body').addClass('modal-open');
            }else{
                $('body').addClass('modal-open');
            }
        })
        $('#modalLlenadoActividades').on('hidden.bs.modal', function () {
            //$('#modalFormLlenadoDimension').modal('show');
            $('body').addClass('modal-open');
        })
    });
});
const avanzarA = () =>{
    $("#ventana1").css("display","none");
    $("#ventana2").css("display","block");
    llenar("DimensionesTablaModificar3");
}
const avanzarB = () =>{
    $("#ventana1").css("display","none");
    $("#ventana2").css("display","none");
    $("#ventana3").css("display","block");
    $(".foot-modif").css("display","block");
}
const avanzarAgreg = ()=>{
    $("#ventanaA").css("display","none");
    $("#ventana1A").css("display","block");
    $(".foot-agr").css("display","block");
};
const llenar = (tabla) => {
    idActividadParaModificar = null;
    idResultadoInstitucionalModificar = null;
    resultadoInstitucionalModificar = null;
    correlativoModificar = null;
    esModificacion = false;
    esInsercion = true;
    $('#save').removeClass('d-none');
    $('#edit').addClass('d-none');
    $('.registrar-actividad-cancelar').removeClass('d-none');
    $('.modificar-actividad-cancelar').addClass('d-none');
    //$('#modalLlenadoDimension').modal('show');
    pos=1;
    $('#modalLlenadoActividades').modal('hide');
    for(let i=1;i<5;i++){
        $("#progressbar li").eq(i).removeClass("active");
    };
    $("#segundo").css({'display': 'none','position': 'relative'});
    $("#tercero").css({'display': 'none','position': 'relative'});
    $("#cuarto").css({'display': 'none','position': 'relative'});
    $("#quinto").css({'display': 'none','position': 'relative'});
    $("#primero").css({'opacity': 1});
    $("#primero").show();
    $("#msform")[0].reset();
    $("#foote-modal").css({'display': 'block','position': ''});
    $("#foote-modal").show();
    resetW();
    $('#'+tabla).dataTable().fnDestroy();
    $('#'+tabla+' tbody').html(``);
    switch(tabla) {
        case 'DimensionesTabla':
            $.ajax(`${ API }/actividades/lista-actividades-por-dimension.php`, {
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                success:function(response) {
                    const { data } = response;
                    
                    console.log(data);
                    for (let i=0;i<data.length; i++) {
                        if(parseInt(data[i].cantidadActividadesPorDimension) === 0){
                            $('#DimensionesTabla tbody').append(`
                                <tr align="center">
                                    <td>
                                        <div class="rojo" row>
                                            <i class="fas fa-exclamation"></i><div style="display:none"></div>
                                        </div>    
                                    </td>
                                    <td>${ data[i].idDimension }</td>
                                    <td>${ data[i].dimensionEstrategica }</td>
                                    <td>
                                        <button type="button" class="btn btn-amber cambioModal" onclick="avanzar('${ data[i].idDimension }','${ data[i].dimensionEstrategica }')">
                                            <img src="../img/menu/editar.svg" alt="modificar dimension"/>
                                        </button>
                                    </td>
                                </tr>
                            `);
                        } else if(data[i].cantidadActividadesPorDimension > 0){
                            $('#DimensionesTabla tbody').append(`
                                <tr align="center">
                                    <td>
                                        <div class="verde row">
                                            <i class="fas fa-exclamation"></i><div style="display:none"></div>
                                        </div>
                                    </td>
                                    <td>${ data[i].idDimension }</td>
                                    <td>${ data[i].dimensionEstrategica }</td>
                                    <td>
                                        <button type="button" class="btn btn-amber cambioModal" onclick="avanzar('${ data[i].idDimension }','${ data[i].dimensionEstrategica }')">
                                            <img src="../img/menu/editar.svg" alt="modificar dimension"/>
                                        </button>
                                    </td>
                                </tr>
                            `);
                        }
                    };
                    $('#'+tabla).DataTable({
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
        break;
        case 'DimensionesTablaModificar':
            $('#Actividades-Por-Dimension').dataTable().fnDestroy();
            $("#ventana1").css("display","block");
            $("#ventana2").css("display","none");
            $("#ventana3").css("display","none");
            $(".foot-modif").css("display","none");
            $.ajax(`${ API }/actividades/lista-actividades-por-dimension.php`, {
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                success:function(response) {
                    const { data } = response;
                    console.log(data);
                    $('#Actividades-Por-Dimension tbody').html(``);
                    for (let i=0;i<data.length; i++) {
                        $('#Actividades-Por-Dimension tbody').append(`
                            <tr align="center">
                                <td>${ i + 1 }</td>
                                <td>${ data[i].dimensionEstrategica }</td>
                                <td>
                                    <button type="button" class="btn btn-amber cambioModal" onclick="verActividades('${ data[i].idDimension }','${ data[i].dimensionEstrategica }')">
                                        <img src="../img/menu/ver-icon.svg" alt="ver actividades dimension"/>
                                    </button>
                                </td>
                            </tr>
                        `);
                    };
                    $('#Actividades-Por-Dimension').DataTable({
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
        break;
        case 'DimensionesTablaModificar3':
            for (let i=0;i<this.arreglo.length; i++) {
                if(this.arreglo[i].estado=="pendiente"){
                    
                }else if(this.arreglo[i].estado=="llena"){
                    $('#DimensionesTablaModificar3 tbody').append(`
                        <tr align="center">
                            <td>
                                <div class="verde row">
                                    <i class="fas fa-exclamation"></i><div style="display:none">${this.arreglo[i].estado}</div>
                                </div>
                            </td>
                            <td>${ this.arreglo[i].dimension }</td>
                            <td>
                                <button type="button" class="btn btn-amber" onclick="avanzarB()">
                                    <img src="../img/menu/editar.svg" alt="modificar dimension"/>
                                </button>
                            </td>
                        </tr>
                    `);
                }
            };
        break;
        case 'DimensionesTablaAgregar':
            for (let i=0;i<this.arreglo.length; i++) {
                if(this.arreglo[i].estado=="pendiente"){
                    
                }else if(this.arreglo[i].estado=="llena"){
                    $('#DimensionesTablaAgregar tbody').append(`
                        <tr align="center">
                            <td>
                                <div class="verde row">
                                    <i class="fas fa-exclamation"></i><div style="display:none">${this.arreglo[i].estado}</div>
                                </div>
                            </td>
                            <td>${ this.arreglo[i].dimension }</td>
                            <td>
                                <button type="button" class="btn btn-amber" onclick="avanzarAgreg()">
                                    <img src="../img/menu/editar.svg" alt="modificar dimension"/>
                                </button>
                            </td>
                        </tr>
                    `);
                }
            };
        break;
        default:
            Swal.fire({
                icon: 'error',
                title: 'Ops...',
                text: 'No se puede avanzar, la opcion no es valida',
                footer: '<b>Vuela a realizar todos los pasos</b>'
            })
    };
};

const avanzar = (idDimension,dimensionEstrategica) =>{ 
    $('#ObjInstitucional').append(`<option value="" selected>Seleccione Objetivo Institucional</option>`);
    $('#AreaEstrategica').append(`<option value="" selected>Seleccione Area Estrategica</option>`);
    $('#selecciona-registro-area').addClass('d-none');
    $('#ObjInstitucional').html(``);
    console.log(dimensionEstrategica);
    $('#DimEstrategica').html(`<option value="${ idDimension }">${ dimensionEstrategica }</option>`);
    let parametros = { 
        idDimension: parseInt(idDimension)
    };
    $.when(
        $.ajax(`${ API }/objetivos-institucionales/listar-objetivos-activos-por-dimension.php`, {
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(parametros)
        }),
        $.ajax(`${ API }/actividades/genera-comparativo-presupuestos.php`, {
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json'
        }),
        $.ajax(`${ API }/actividades/ver-estado-presupuesto-anual.php`, {
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
        })) 
        .done(function(objetivoResponse, comparativaPresupuestosResponse, estadoPresupuestoAnual) {
            let estadoPresupuestoAnualActividades = estadoPresupuestoAnual[0].data; 
            if (estadoPresupuestoAnualActividades.message === true) {
                Swal.fire({
                    icon: 'error',
                    title: 'Ops...',
                    text: 'La fecha para el llenado de actividades ya cerro, no puede ingresar mas actividades al sistema hasta que la secretaria administrativa habilite el presupuesto',
                    footer: '<b style="text-align: center;">Para mas informacion comuniquese con la secretaria administrativa</b>'
                })
            } else {
                let listadoObjetivos = objetivoResponse[0].data;
                let comparativaPresupuestoTotalUsuado = comparativaPresupuestosResponse[0].data;
                $('#ObjInstitucional').append(`<option value="" selected>Seleccione Objetivo Institucional</option>`);
                for(let i=0; i<listadoObjetivos.length; i++) {
                    $('#ObjInstitucional').append(`
                        <option value="${ listadoObjetivos[i].idObjetivoInstitucional }">${ listadoObjetivos[i].objetivoInstitucional }</option>
                    `);
                }
                console.log(comparativaPresupuestoTotalUsuado)
                $('#PresupuestoUtilizado').val(Number(comparativaPresupuestoTotalUsuado.presupuestoConsumidoPorDepto))
                $('#PresupuestoDisponible').val(Number(comparativaPresupuestoTotalUsuado.presupuestoTotalDepartamento))
                $('#modalLlenadoDimension').modal('hide');
                $('#modalFormLlenadoDimension').modal('show');
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
                footer: '<b>Recargue la pagina nuevamente</b>'
            });
        });
};

const avanzarModificacion = (idDimension,dimensionEstrategica, idObjetivo, objetivo, idArea, area) =>{ 
    
    $('#AreaEstrategica').html(``);
    $('#ObjInstitucional').html(``);
    $('#selecciona-registro-area').removeClass('d-none');
    
    $('#DimEstrategica').html(`<option value="${ idDimension }">${ dimensionEstrategica }</option>`);
    let parametros = { 
        idDimension: parseInt(idDimension)
    };
    let parametro = { 
        idObjetivo: parseInt(idObjetivo)
    };
    $.when(
        $.ajax(`${ API }/objetivos-institucionales/listar-objetivos-activos-por-dimension.php`, {
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(parametros)
        }),
        $.ajax(`${ API }/actividades/genera-comparativo-presupuestos.php`, {
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json'
        }),
        $.ajax(`${ API }/areas-estrategicas/listar-areas-por-objetivo-activas.php`, {
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(parametro)
        })) 
        .done(function(objetivoResponse, comparativaPresupuestosResponse, areasResponse) {
            
            let listadoObjetivos = objetivoResponse[0].data;
            let comparativaPresupuestoTotalUsuado = comparativaPresupuestosResponse[0].data;
            let listadoAreas = areasResponse[0].data;
            console.log(listadoObjetivos);
            console.log(idObjetivo);
            for(let i=0; i<listadoObjetivos.length; i++) {
                if (listadoObjetivos[i].idObjetivoInstitucional === parseInt(idObjetivo)) {
                    console.log('entro aqui')
                    $('#ObjInstitucional').append(`
                        <option value="${ listadoObjetivos[i].idObjetivoInstitucional }" selected>${ listadoObjetivos[i].objetivoInstitucional }</option>`);
                } else {
                    $('#ObjInstitucional').append(`
                        <option value="${ listadoObjetivos[i].idObjetivoInstitucional }">${ listadoObjetivos[i].objetivoInstitucional }</option>
                    `);
                }
            }
            for(let i=0; i<listadoAreas.length; i++) {
                if (listadoAreas[i].idAreaEstrategica === parseInt(idArea)) {
                    $('#AreaEstrategica').append(`
                        <option value="${ listadoAreas[i].idAreaEstrategica }" selected>${ listadoAreas[i].areaEstrategica }</option>
                    `);
                } else {
                    $('#AreaEstrategica').append(`
                        <option value="${ listadoAreas[i].idAreaEstrategica }">${ listadoAreas[i].areaEstrategica }</option>
                    `);
                }
            }
            $('#PresupuestoUtilizado').val(Number(comparativaPresupuestoTotalUsuado.presupuestoConsumidoPorDepto))
            $('#PresupuestoDisponible').val(Number(comparativaPresupuestoTotalUsuado.presupuestoTotalDepartamento))
            $('#modalLlenadoDimension').modal('hide');
            $('#modalFormLlenadoDimension').modal('show');
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
                footer: '<b>Recargue la pagina nuevamente</b>'
            })
        });
};

const cargarAreasEstrategicasActivas = () => {
    $('#AreaEstrategica').html(``);
    let parametros = {
        idObjetivo: parseInt($('#ObjInstitucional').val())
    };
    $.ajax(`${ API }/areas-estrategicas/listar-areas-por-objetivo-activas.php`, {
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify(parametros),
        success:function(response) {
            $('#selecciona-registro-area').removeClass('d-none');
            const { data } = response;
            console.log(data);
            $('#AreaEstrategica').append(`<option value="" selected>Seleccione Area Estrategica</option>`);
            for(let i=0; i<data.length; i++) {
                $('#AreaEstrategica').append(`
                    <option value="${ data[i].idAreaEstrategica }">${ data[i].areaEstrategica }</option>
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
    });
}

const verActividades = (idDimension, dimension) => {
    dimnesionParaModales = dimension;
    let parametros = { idDimension: parseInt(idDimension) };
    
    $.ajax(`${ API }/actividades/listar-actividades-dimension.php`, {
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify(parametros),
        success:function(response) {
            const { data } = response;
            console.log(data);
            $('#listado-actividades').dataTable().fnDestroy();
            $('#listado-actividades tbody').html(``);
            for(let i=0;i<data.length; i++) {
                $('#listado-actividades tbody').append(`
                    <tr align="center">
                        <td>
                            ${ i + 1 }
                        </td>
                        <td>
                            ${ data[i].objetivoInstitucional }
                        </td>
                        <td>
                            ${ data[i].areaEstrategica }
                        </td>
                        <td>
                            ${ data[i].resultadoInstitucional }
                        </td>
                        <td>
                            ${ data[i].resultadosUnidad }
                        </td>
                        <td>
                            ${ data[i].indicadoresResultado }
                        </td>
                        <td>
                            ${ data[i].correlativoActividad }
                        </td>
                        <td>
                            ${ data[i].actividad }
                        </td>
                        <td>
                            ${ data[i].porcentajeTrimestre1 }
                        </td>
                        <td>
                            ${ data[i].Trimestre1 }
                        </td>
                        <td>
                            ${ data[i].porcentajeTrimestre2 }
                        </td>
                        <td>
                            ${ data[i].Trimestre2 }
                        </td>
                        <td>
                            ${ data[i].porcentajeTrimestre3 }
                        </td>
                        <td>
                            ${ data[i].Trimestre3 }
                        </td>
                        <td>
                            ${ data[i].porcentajeTrimestre4 }
                        </td>
                        <td>
                            ${ data[i].Trimestre4 }
                        </td>
                        <td>
                            ${ data[i].sumatoriaPorcentaje }
                        </td>
                        <td>
                            ${ data[i].CostoTotal }
                        </td>
                        <td>
                            ${ data[i].justificacionActividad }
                        </td>
                        <td>
                            ${ data[i].medioVerificacionActividad }
                        </td>
                        <td>
                            ${ data[i].poblacionObjetivoActividad }
                        </td>
                        <td>
                            ${ data[i].responsableActividad }
                        </td>
                        <td>
                            <button type="button" class="btn btn-amber" onclick="agregarDesglose('${ data[i].idActividad }', '${ data[i].CostoTotal }','${ data[i].idDimension }')">
                                <img src="../img/menu/agregar-archivo.svg" alt="desglose dimension"/>
                            </button>
                        </td>
                        <td>
                            <button type="button" class="btn btn-amber" onclick="modificarActividad('${ data[i].idActividad }')">
                                <img src="../img/menu/editar.svg" alt="modificar actividad"/>
                            </button>
                        </td>
                    </tr>
                `)
            }
            $('#listado-actividades').DataTable({
                language: i18nEspaniol,
                retrieve: true
            });
            $('#modalCargaActividades').modal('show');
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

const agregarDesglose = (idActividad, costoTotal, idDimension) => {
    idActividadSeleccionada = idActividad;
    costoTotalActividadSeleccionada = costoTotal;
    idDimensionSeleccionada = idDimension;
    $('#CostoT').val(costoTotalActividadSeleccionada).trigger('change');
    $('#modalesActividad').modal('show');
    $('#DimensionAdministrativa').html(`<option value="" selected>Seleccione una dimension administrativa</option>`);
    $.ajax(`${ API }/dimensiones-administrativas/listar-dimensiones-activas.php`,{
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        success:function(response) {
            const { data } = response;
            for(let i=0;i<data.length;i++) {
                $('#DimensionAdministrativa').append(`
                    <option value="${ data[i].idDimension }">
                        ${ data[i].dimensionAdministrativa }
                    </option>`);
            }
        },
        error:function(error){
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

const abrirModalRegistroDimensionAdmin = () => {
    $('#modalRegistroDimensionAdmin').modal('show');

}

const registraItemAdministrativo = () => {
    $('#modalRegistroDimensionAdmin').modal('show');
    
    $('#modificarItems').addClass('d-none');
    $('#insertarItems').removeClass('d-none');
}

const generaTablasAcordeDimension = (object) => {
    idDimensionAdminSeleccionada = parseInt(object.value);
    let parametro = { idActividad: parseInt(idActividadSeleccionada), idDimensionAdmin: parseInt(object.value) };
    console.log(idDimensionAdminSeleccionada)
    $.when(
        $.ajax(`${ API }/ObjetosGasto/listar-objetos-gasto-activos.php`,{
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
        }),
        $.ajax(`${ API }/tipo-presupuestos/listar-tipo-presupuestos-actuales.php`,{
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
        }),
        $.ajax(`${ API }/descripcion-administrativa/listar-descripciones-administrativas.php`,{
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(parametro)
        }))
        .done(function(objetosGasto, tipoPresupuestos, descripcionesAdministrativas) {
            $('#ObjGasto').html(`<option value="" selected>Seleccione Objeto Gasto</option>`);
            $('#TipoPresupuesto').html(`<option value="" selected>Seleccione Tipo Presupuesto</option>`);
            let objetosGastos = objetosGasto[0].data;
            let presupuestos = tipoPresupuestos[0].data;
            const { data } = descripcionesAdministrativas[0];
            for (let i=0;i<objetosGastos.length;i++) {
                $('#ObjGasto').append(`
                <option value="${ objetosGastos[i].idObjetoGasto }">${ objetosGastos[i].abrev } - ${ objetosGastos[i].DescripcionCuenta }</option>
                `);
            }
            $('#ObjGasto').select2();

            for(let i=0;i<presupuestos.length;i++) {
                $('#TipoPresupuesto').append(`
                    <option value="${ presupuestos[i].idTipoPresupuesto }">${ presupuestos[i].tipoPresupuesto }</option>
                `)
            }

            $('#MesRequerido').html(`<option value="" selected>Seleccionar mes requerido</option>`);
            for(let i=0;i<mesesRequeridos.length;i++) {
                $('#MesRequerido').append(`
                    <option value="${ mesesRequeridos[i].id }">${ mesesRequeridos[i].mes }</option>
                `)
            }
            
            switch (parseInt(idDimensionAdminSeleccionada)) {
                case 10:
                    $('#modalDimensionesAdmin1').modal('show');
                    $('#dimension-1-campo').removeClass('d-none');
                    $('#dimension-2-campo').addClass('d-none');
                    $('#dimension-4-campo').addClass('d-none');
                    $('#dimension-6-campo').addClass('d-none');
                    $('#dimension-7-campo').addClass('d-none');
                    $('#dimension-8-campo').addClass('d-none');
                    $('#dimension-administrativa-1').dataTable().fnDestroy();
                    $('#dimension-administrativa-1 tbody').html(``);
                    for(let i=0;i<data.length; i++) {
                        let descripcion = JSON.parse(data[i].Descripcion);
                        $('#dimension-administrativa-1 tbody').append(`
                            <tr align="center">
                                <td>
                                    ${ i + 1 }
                                </td>
                                <td>
                                    ${ data[i].nombreActividad }
                                </td>
                                <td>
                                    ${ descripcion.cantidadPersonas }
                                </td>
                                <td>
                                    ${ data[i].Cantidad }
                                </td>
                                <td>
                                    ${ data[i].Costo }
                                </td>
                                <td>
                                    ${ data[i].costoTotal }
                                </td>
                                <td>
                                    ${ data[i].tipoPresupuesto }
                                </td>
                                <td>
                                    ${ data[i].abrev }
                                </td>
                                <td>
                                    ${ data[i].descripcionCuenta }
                                </td>
                                <td>
                                    ${ data[i].dimensionEstrategica }
                                </td>
                                <td>
                                    ${ data[i].mesRequerido }
                                </td>
                                <td>
                                    <button type="button" class="btn btn-amber" onclick="modificarItem('${ data[i].idDescripcionAdministrativa }')">
                                        <img src="../img/menu/editar.svg" alt="modificar dimension"/>
                                    </button>
                                </td>
                            </tr>
                        `)
                    }
                    $('#dimension-administrativa-1').DataTable({
                        language: i18nEspaniol,
                        retrieve: true
                    });
                break;
                case 11:
                
                    $('#modalDimensionesAdmin2').modal('show');
                    $('#dimension-administrativa-2').dataTable().fnDestroy();
                    $('#dimension-administrativa-2 tbody').html(``);
                    $('#dimension-2-campo').removeClass('d-none');
                    $('#dimension-1-campo').addClass('d-none');
                    $('#dimension-4-campo').addClass('d-none');
                    $('#dimension-6-campo').addClass('d-none');
                    $('#dimension-7-campo').addClass('d-none');
                    $('#dimension-8-campo').addClass('d-none');
                    for(let i=0;i<data.length; i++) {
                        let descripcion = JSON.parse(data[i].Descripcion);
                        $('#dimension-administrativa-2 tbody').append(`
                            <tr align="center">
                            <td>
                                ${ i + 1 }
                            </td>
                            <td>
                                ${ data[i].nombreActividad }
                            </td>
                            <td>
                                ${ data[i].Cantidad }
                            </td>
                            <td>
                                ${ descripcion.meses}
                            </td>
                            <td>
                                ${ data[i].Costo }
                            </td>
                            <td>
                                ${ data[i].costoTotal }
                            </td>
                            <td>
                                ${ data[i].tipoPresupuesto }
                            </td>
                            <td>
                                ${ data[i].abrev }
                            </td>
                            <td>
                                ${ data[i].descripcionCuenta }
                            </td>
                            <td>
                                ${ data[i].dimensionEstrategica }
                            </td>
                            <td>
                                ${ data[i].mesRequerido }
                            </td>
                            <td>
                                <button type="button" class="btn btn-amber" onclick="modificarItem('${ data[i].idDescripcionAdministrativa }')">
                                    <img src="../img/menu/editar.svg" alt="modificar dimension"/>
                                </button>
                            </td>
                        </tr>
                        `)
                    }
                    $('#dimension-administrativa-2').DataTable({
                        language: i18nEspaniol,
                        retrieve: true
                    });
                break;
                case 12:
                    $('#modalDimensionesAdmin3').modal('show');
                    $('#dimension-1-campo').addClass('d-none');
                    $('#dimension-2-campo').addClass('d-none');
                    $('#dimension-4-campo').addClass('d-none');
                    $('#dimension-6-campo').addClass('d-none');
                    $('#dimension-7-campo').addClass('d-none');
                    $('#dimension-8-campo').addClass('d-none');
                    $('#dimension-administrativa-3').dataTable().fnDestroy();
                    $('#dimension-administrativa-3 tbody').html(``);
                    for(let i=0;i<data.length; i++) {
                        $('#dimension-administrativa-3 tbody').append(`
                        <tr align="center">
                            <td>
                                ${ i + 1 }
                            </td>
                            <td>
                                ${ data[i].nombreActividad }
                            </td>
                            <td>
                                ${ data[i].Cantidad }
                            </td>
                            <td>
                                ${ data[i].Costo }
                            </td>
                            <td>
                                ${ data[i].costoTotal }
                            </td>
                            <td>
                                ${ data[i].tipoPresupuesto }
                            </td>
                            <td>
                                ${ data[i].abrev }
                            </td>
                            <td>
                                ${ data[i].descripcionCuenta }
                            </td>
                            <td>
                                ${ data[i].dimensionEstrategica }
                            </td>
                            <td>
                                ${ data[i].mesRequerido }
                            </td>
                            <td>
                                <button type="button" class="btn btn-amber" onclick="modificarItem('${ data[i]. idDescripcionAdministrativa }')">
                                    <img src="../img/menu/editar.svg" alt="modificar dimension"/>
                                </button>
                            </td>
                        </tr>
                        `)
                    }
                    $('#dimension-administrativa-3').DataTable({
                        language: i18nEspaniol,
                        retrieve: true
                    });
                break;
                case 13:
                    $('#modalDimensionesAdmin4').modal('show');
                    $('#dimension-1-campo').addClass('d-none');
                    $('#dimension-2-campo').addClass('d-none');
                    $('#dimension-4-campo').removeClass('d-none');
                    $('#dimension-6-campo').addClass('d-none');
                    $('#dimension-7-campo').addClass('d-none');
                    $('#dimension-8-campo').addClass('d-none');
                    $('#dimension-administrativa-4').dataTable().fnDestroy();
                    $('#dimension-administrativa-4 tbody').html(``);
                    for(let i=0;i<data.length; i++) {
                        let descripcion = JSON.parse(data[i].Descripcion);
                        $('#dimension-administrativa-4 tbody').append(`
                        <tr align="center">
                        <td>
                            ${ i + 1 }
                        </td>
                        <td>
                            ${ data[i].nombreActividad }    
                        </td>
                        <td>
                            ${ data[i].Cantidad }
                        </td>
                        <td>
                            ${ data[i].Costo }
                        </td>
                        <td>
                            ${ data[i].costoTotal }
                        </td>
                        <td>
                            ${ data[i].tipoPresupuesto }
                        </td>
                        <td>
                            ${ data[i].abrev }
                        </td>
                        <td>
                            ${ data[i].descripcionCuenta }
                        </td>
                        <td>
                            ${ data[i].dimensionEstrategica }
                        </td>
                        <td>
                            ${ data[i].mesRequerido }
                        </td>
                        <td>
                            ${ descripcion.tipoEquipoTecnologico }
                        </td>
                        <td>
                            <button type="button" class="btn btn-amber" onclick="modificarItem('${ data[i].idDescripcionAdministrativa }')">
                                <img src="../img/menu/editar.svg" alt="modificar dimension"/>
                            </button>
                        </td>
                    </tr>
                        `)
                    }
                    $('#dimension-administrativa-4').DataTable({
                        language: i18nEspaniol,
                        retrieve: true
                    });
                break;
                case 14:
                    $('#modalDimensionesAdmin5').modal('show');
                    $('#dimension-1-campo').addClass('d-none');
                    $('#dimension-2-campo').addClass('d-none');
                    $('#dimension-4-campo').addClass('d-none');
                    $('#dimension-6-campo').addClass('d-none');
                    $('#dimension-7-campo').addClass('d-none');
                    $('#dimension-8-campo').addClass('d-none');
                    $('#dimension-administrativa-5').dataTable().fnDestroy();
                    $('#dimension-administrativa-5 tbody').html(``);
                    for(let i=0;i<data.length; i++) {
                        $('#dimension-administrativa-5 tbody').append(`
                        <tr align="center">
                            <td>
                                ${ i + 1 }
                            </td>
                            <td>
                                ${ data[i].nombreActividad }
                            </td>
                            <td>
                                ${ data[i].Cantidad }
                            </td>
                            <td>
                                ${ data[i].Costo }
                            </td>
                            <td>
                                ${ data[i].costoTotal }
                            </td>
                            <td>
                                ${ data[i].tipoPresupuesto }
                            </td>
                            <td>
                                ${ data[i].abrev }
                            </td>
                            <td>
                                ${ data[i].descripcionCuenta }
                            </td>
                            <td>
                                ${ data[i].dimensionEstrategica }
                            </td>
                            <td>
                                ${ data[i].mesRequerido }
                            </td>
                            <td>
                                <button type="button" class="btn btn-amber" onclick="modificarItem('${ data[i].idDescripcionAdministrativa }')">
                                    <img src="../img/menu/editar.svg" alt="modificar dimension"/>
                                </button>
                            </td>
                        </tr>
                        `)
                    }
                    $('#dimension-administrativa-5').DataTable({
                        language: i18nEspaniol,
                        retrieve: true
                    });
                break;
                case 15:
                    $('#modalDimensionesAdmin6').modal('show');
                    $('#dimension-1-campo').addClass('d-none');
                    $('#dimension-2-campo').addClass('d-none');
                    $('#dimension-4-campo').addClass('d-none');
                    $('#dimension-6-campo').removeClass('d-none');
                    $('#dimension-7-campo').addClass('d-none');
                    $('#dimension-8-campo').addClass('d-none');
                    $('#dimension-administrativa-6').dataTable().fnDestroy();
                    $('#dimension-administrativa-6 tbody').html(``);
                    for(let i=0;i<data.length; i++) {
                        let descripcion = JSON.parse(data[i].Descripcion);
                        $('#dimension-administrativa-6 tbody').append(`
                        <tr align="center">
                        <td>
                            ${ i + 1 }
                        </td>
                        <td>
                            ${ data[i].nombreActividad }
                        </td>
                        <td>
                            ${ data[i].Cantidad }
                        </td>
                        <td>
                            ${ data[i].Costo }
                        </td>
                        <td>
                            ${ data[i].costoTotal }
                        </td>
                        <td>
                            ${ data[i].tipoPresupuesto }
                        </td>
                        <td>
                            ${ data[i].abrev }
                        </td>
                        <td>
                            ${ data[i].descripcionCuenta }
                        </td>
                        <td>
                            ${ data[i].dimensionEstrategica }
                        </td>
                        <td>
                            ${ data[i].mesRequerido }
                        </td>
                        <td>
                            ${ descripcion.areaBeca }
                        </td>
                        <td>
                            <button type="button" class="btn btn-amber" onclick="modificarItem('${ data[i].idDescripcionAdministrativa }')">
                                <img src="../img/menu/editar.svg" alt="modificar dimension"/>
                            </button>
                        </td>
                    </tr>
                        `)
                    }
                    $('#dimension-administrativa-6').DataTable({
                        language: i18nEspaniol,
                        retrieve: true
                    });
                break;
                case 16:
                    $('#modalDimensionesAdmin7').modal('show');
                    $('#dimension-1-campo').addClass('d-none');
                    $('#dimension-2-campo').addClass('d-none');
                    $('#dimension-4-campo').addClass('d-none');
                    $('#dimension-6-campo').addClass('d-none');
                    $('#dimension-7-campo').removeClass('d-none');
                    $('#dimension-8-campo').addClass('d-none');
                    $('#dimension-administrativa-7').dataTable().fnDestroy();
                    $('#dimension-administrativa-7 tbody').html(``);
                    for(let i=0;i<data.length; i++) {
                        let descripcion = JSON.parse(data[i].Descripcion);
                        $('#dimension-administrativa-7 tbody').append(`
                        <tr align="center">
                        <td>
                            ${ i + 1 }
                        </td>
                        <td>
                            ${ data[i].nombreActividad }
                        </td>
                        <td>
                            ${ data[i].Cantidad }
                        </td>
                        <td>
                            ${ data[i].Costo }
                        </td>
                        <td>
                            ${ data[i].costoTotal }
                        </td>
                        <td>
                            ${ data[i].tipoPresupuesto }
                        </td>
                        <td>
                            ${ data[i].abrev }
                        </td>
                        <td>
                            ${ data[i].descripcionCuenta }
                        </td>
                        <td>
                            ${ data[i].dimensionEstrategica }
                        </td>
                        <td>
                            ${ data[i].mesRequerido }
                        </td>
                        <td>
                            ${ descripcion.proyecto }
                        </td>
                        <td>
                            <button type="button" class="btn btn-amber" onclick="modificarItem('${ data[i].idDescripcionAdministrativa }')">
                                <img src="../img/menu/editar.svg" alt="modificar dimension"/>
                            </button>
                        </td>
                    </tr>
                        `)
                    }
                    $('#dimension-administrativa-7').DataTable({
                        language: i18nEspaniol,
                        retrieve: true
                    });
                break;
                case 17:
                    $('#modalDimensionesAdmin8').modal('show');
                    $('#dimension-1-campo').addClass('d-none');
                    $('#dimension-2-campo').addClass('d-none');
                    $('#dimension-4-campo').addClass('d-none');
                    $('#dimension-6-campo').addClass('d-none');
                    $('#dimension-8-campo').removeClass('d-none');
                    $('#dimension-administrativa-8 tbody').html(``);
                    for(let i=0;i<data.length; i++) {
                        let descripcion = JSON.parse(data[i].Descripcion);
                        $('#dimension-administrativa-8 tbody').append(`
                        <tr align="center">
                        <td>
                            ${ i + 1 }
                        </td>
                        <td>
                            ${ data[i].nombreActividad }
                        </td>
                        <td>
                            ${ data[i].Cantidad }
                        </td>
                        <td>
                            ${ data[i].Costo }
                        </td>
                        <td>
                            ${ data[i].costoTotal }
                        </td>
                        <td>
                            ${ data[i].tipoPresupuesto }
                        </td>
                        <td>
                            ${ data[i].abrev }
                        </td>
                        <td>
                            ${ data[i].descripcionCuenta }
                        </td>
                        <td>
                            ${ data[i].dimensionEstrategica }
                        </td>
                        <td>
                            ${ data[i].mesRequerido }
                        </td>
                        <td>
                            ${ descripcion.descripcionItem }
                        </td>
                        <td>
                            ${ descripcion.cantidadItem }
                        </td>
                        <td>
                            ${ descripcion.precioItem }
                        </td>
                        <td>
                            ${ descripcion.valorUno }
                        </td>
                        <td>
                            ${ descripcion.valorDos }
                        </td>
                        <td>
                            <button type="button" class="btn btn-amber" onclick="modificarItem('${ data[i].idDescripcionAdministrativa }')">
                                <img src="../img/menu/editar.svg" alt="modificar dimension"/>
                            </button>
                        </td>
                        </tr>
                        `)
                    }
                    $('#dimension-administrativa-8').DataTable({
                        language: i18nEspaniol,
                        retrieve: true
                    });
                break;
                default:
                    Swal.fire({
                        icon: 'error',
                        title: 'Ops...',
                        text: 'No se puede avanzar seleccione una dimension',
                        footer: '<b>La opcion seleccionada no es valida</b>'
                    })
                break;
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
} 

const modificarItem = (idDescripcionAdministrativa) => {
    let parametro = { idDescripcionAdministrativa: parseInt(idDescripcionAdministrativa) };
    $.when(
        $.ajax(`${ API }/ObjetosGasto/listar-objetos-gasto-activos.php`,{
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
        }),
        $.ajax(`${ API }/tipo-presupuestos/listar-tipo-presupuestos-actuales.php`,{
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
        }),
        $.ajax(`${ API }/descripcion-administrativa/genera-datos-descripcion.php`,{
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(parametro)
    }))
    .done(function(objetosGasto, tipoPresupuestos, descripcionAdministrativa) {
        let objetosGastos = objetosGasto[0].data;
        let presupuestos = tipoPresupuestos[0].data;
        let descripcionAdmin = descripcionAdministrativa[0].data;
        console.log(objetosGastos);
        console.log(presupuestos);
        console.log(descripcionAdmin);

        // Rellenando los campos de la descripcion
        // Inicio
        idActividadSeleccionada = descripcionAdmin.idActividad;
        idDimensionAdminSeleccionada = descripcionAdmin.idDimensionAdministrativa;
        idDescripcionItemSeleccionada = descripcionAdmin.idDescripcionAdministrativa;
        $('#UnidadMedida').val(descripcionAdmin.unidadDeMedida).trigger('change');
        $('#modificarItems').removeClass('d-none');
        $('#insertarItems').addClass('d-none');

        $('#NombreActividad').val(descripcionAdmin.nombreActividad).trigger('change');
        $('#Cantidad').val(descripcionAdmin.Cantidad).trigger('change');
        $('#Costo').val(descripcionAdmin.Costo).trigger('change');
        $('#MesRequerido').append(`<option value="${ descripcionAdmin.mesRequerido }" selected>${ descripcionAdmin.mesRequerido }</option>`);
        $('#ObjGasto').html(`<option value="${ descripcionAdmin.idObjetoGasto }" selected>${ descripcionAdmin.abrev } - ${ descripcionAdmin.descripcionCuenta }</option>`)
        $('#TipoPresupuesto').html(`<option value="${ descripcionAdmin.idTipoPresupuesto }" selected>${ descripcionAdmin.tipoPresupuesto }</option>`)
        switch (parseInt(descripcionAdmin.idDimensionAdministrativa)) {
            case 10:
                $('#dimension-1-campo').removeClass('d-none');
                let cantidad = JSON.parse(descripcionAdmin.descripcion);
                $('#CantidadPersonas').val(cantidad.cantidadPersonas).trigger('change');
                $('#dimension-2-campo').addClass('d-none');
                $('#dimension-4-campo').addClass('d-none');
                $('#dimension-6-campo').addClass('d-none');
                $('#dimension-7-campo').addClass('d-none');
                $('#dimension-8-campo').addClass('d-none');
            break;
            case 11:
                $('#dimension-2-campo').removeClass('d-none');
                let mes = JSON.parse(descripcionAdmin.descripcion);
                $('#Meses').val(mes.meses).trigger('change');
                $('#dimension-1-campo').addClass('d-none');
                $('#dimension-4-campo').addClass('d-none');
                $('#dimension-6-campo').addClass('d-none');
                $('#dimension-7-campo').addClass('d-none');
                $('#dimension-8-campo').addClass('d-none');
            break;
            case 12:
                $('#dimension-1-campo').addClass('d-none');
                $('#dimension-2-campo').addClass('d-none');
                $('#dimension-4-campo').addClass('d-none');
                $('#dimension-6-campo').addClass('d-none');
                $('#dimension-7-campo').addClass('d-none');
                $('#dimension-8-campo').addClass('d-none');
            break;
            case 13:
                $('#dimension-1-campo').addClass('d-none');
                let equipoTecnologico = JSON.parse(descripcionAdmin.descripcion);
                $('#TipoEquipoTecnologico').val(equipoTecnologico.tipoEquipoTecnologico).trigger('change');
                $('#dimension-2-campo').addClass('d-none');
                $('#dimension-4-campo').removeClass('d-none');
                $('#dimension-6-campo').addClass('d-none');
                $('#dimension-7-campo').addClass('d-none');
                $('#dimension-8-campo').addClass('d-none');
            break;
            case 14:
                $('#dimension-1-campo').addClass('d-none');
                $('#dimension-2-campo').addClass('d-none');
                $('#dimension-4-campo').addClass('d-none');
                $('#dimension-6-campo').addClass('d-none');
                $('#dimension-7-campo').addClass('d-none');
                $('#dimension-8-campo').addClass('d-none');
            break;
            case 15:
                $('#dimension-1-campo').addClass('d-none');
                $('#AreaBeca').val(descripcionAdmin.descripcion.areaBeca).trigger('change');
                $('#dimension-2-campo').addClass('d-none');
                $('#dimension-4-campo').addClass('d-none');
                $('#dimension-6-campo').removeClass('d-none');
                $('#dimension-7-campo').addClass('d-none');
                $('#dimension-8-campo').addClass('d-none');
            break;
            case 16:
                $('#dimension-1-campo').addClass('d-none');
                let proyectos = JSON.parse(descripcionAdmin.descripcion);
                $('#Proyecto').append(`<option value="${ proyectos.proyecto }" selected>${ proyectos.proyecto }</option>`)
                $('#dimension-2-campo').addClass('d-none');
                $('#dimension-4-campo').addClass('d-none');
                $('#dimension-6-campo').addClass('d-none');
                $('#dimension-7-campo').removeClass('d-none');
                $('#dimension-8-campo').addClass('d-none');
            break;
            case 17:
                $('#dimension-1-campo').addClass('d-none');
                $('#dimension-2-campo').addClass('d-none');
                $('#dimension-4-campo').addClass('d-none');
                $('#dimension-6-campo').addClass('d-none');
                $('#dimension-7-campo').addClass('d-none');
                $('#dimension-8-campo').removeClass('d-none');
                let descripciones = JSON.parse(descripcionAdmin.descripcion);
                $('#DescripcionDimOcho').val(descripciones.descripcionItem).trigger('change');
                $('#CantidadDimOcho').val(descripciones.cantidadItem).trigger('change');
                $('#PrecioDimOcho').val(descripciones.precioItem).trigger('change');
                $('#Setenta').val(descripciones.valorUno).trigger('change');
                $('#Treinta').val(descripciones.valorDos).trigger('change');
            break;
            default:
                Swal.fire({
                    icon: 'error',
                    title: 'Ops...',
                    text: 'No se puede avanzar seleccione una dimension',
                    footer: '<b>La opcion seleccionada no es valida</b>'
                })
            break;
        }
        $('#modalRegistroDimensionAdmin').modal('show');

        // Fin



        for (let i=0;i<objetosGastos.length;i++) {
            $('#ObjGasto').append(`
            <option value="${ objetosGastos[i].idObjetoGasto }">${ objetosGastos[i].abrev } - ${ objetosGastos[i].DescripcionCuenta }</option>
            `);
        }
        $('#ObjGasto').select2();

        for(let i=0;i<presupuestos.length;i++) {
            $('#TipoPresupuesto').append(`
                <option value="${ presupuestos[i].idTipoPresupuesto }">${ presupuestos[i].tipoPresupuesto }</option>
            `)
        }

            $('#MesRequerido').html(``);
            for(let i=0;i<mesesRequeridos.length;i++) {
                if (mesesRequeridos[i].id === descripcionAdmin.mesRequerido) {
                    $('#MesRequerido').append(`<option value="${ mesesRequeridos[i].id }" selected>${ mesesRequeridos[i].mes }</option>`);
                } else {    
                    $('#MesRequerido').append(`
                        <option value="${ mesesRequeridos[i].id }">${ mesesRequeridos[i].mes }</option>
                    `)
                }
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
}

const modificarAct = () => {
    // Campos que tienen en comun
    let Cantidad = document.querySelector('#Cantidad');
    let Costo = document.querySelector('#Costo');
    let CostoT = document.querySelector('#CostoT');
    let TipoPresupuesto = document.querySelector('#TipoPresupuesto');
    let ObjGasto = document.querySelector('#ObjGasto');
    let Mes = document.querySelector('#MesRequerido');

    // Campos que no tienen en comun
    let NombreActividad = document.querySelector('#NombreActividad');
    let CantidadPersonas = document.querySelector('#CantidadPersonas');
    let meses = document.querySelector('#Meses');
    let tipoEquipoTecnologico = document.querySelector('#TipoEquipoTecnologico');
    let areaBeca = document.querySelector('#AreaBeca');
    let proyectos = document.querySelector('#Proyecto');
    let descripcionDimOcho = document.querySelector('#DescripcionDimOcho');
    let cantodadDimOcho = document.querySelector('#CantidadDimOcho');
    let precioDimOcho = document.querySelector('#PrecioDimOcho');
    let setenta = document.querySelector('#Setenta');
    let treinta = document.querySelector('#Treinta');
    
    let NomACT = { valorEtiqueta: NombreActividad, id: 'NombreActividad', name: 'Actividad', min: 1, max: 900, type: 'text' };
    let Ca = { valorEtiqueta: Cantidad, id: 'Cantidad', name: 'Cantidad', min: 1, max: 10, type: 'number' };
    let Co = { valorEtiqueta: Costo, id: 'Costo', name: 'Costo' ,min: 1, max: 13,type: 'number' };
    let CoT = { valorEtiqueta: CostoT, id: 'CostoT', name: 'Costo Total' ,min: 1, max: 13,type: 'number' };
    let Tp = { valorEtiqueta: TipoPresupuesto, id: 'TipoPresupuesto', name: 'TipoPresupuesto' ,type: 'select' };
    let oG = { valorEtiqueta: ObjGasto, id: 'ObjGasto', name: 'ObjGasto' ,type: 'select' };
    let M = { valorEtiqueta: Mes, id: 'MesRequerido', name: 'Mes Rquerido' ,type: 'select' };
    let CaP = { valorEtiqueta: CantidadPersonas, id: 'CantidadPersonas', name: 'Cantidad Personas', min: 1, max: 10, type: 'number' };
    let mes = { valorEtiqueta: meses, id: 'Meses', name: 'Meses', min: 1, max: 4, type: 'number' };
    let tET = { valorEtiqueta: tipoEquipoTecnologico, id: 'TipoEquipoTecnologico', name: 'Tipo Equipo Tecnologico', min: 1, max: 200, type: 'number' };
    let aB = { valorEtiqueta: areaBeca, id: 'AreaBeca', name: 'Area Beca', min: 1, max: 200, type: 'number' };
    let project = { valorEtiqueta: proyectos, id: 'Proyecto', name: 'Proyecto' ,type: 'select' };

    let descripcionDim8 = { valorEtiqueta: descripcionDimOcho, id: 'DescripcionDimOcho', name: 'Descripcion', min: 1, max: 150, type: 'text' };
    let cantidadDimOcho = { valorEtiqueta: cantodadDimOcho, id: 'CantidadDimOcho', name: 'Cantidad', min: 1, max: 10, type: 'number' };
    let precioDOcho = { valorEtiqueta: precioDimOcho, id: 'PrecioDimOcho', name: 'Precio', min: 1, max: 10, type: 'number' };
    let valorSetenta = { valorEtiqueta: setenta, id: 'Setenta', name: 'Valor de 0 a 70', min: 1, max: 2, type: 'number' };
    let valorTreinta = { valorEtiqueta: treinta, id: 'Treinta', name: 'Valor de 0 a 30', min: 1, max: 2, type: 'number' };

    let isValidNombreActividad = verificarInputText(NomACT, justificacionRegex);
    let isValidCantidad = verificarInputNumber(Ca,numerosRegex);
    let isValidCosto = verificarInputNumber(Co,numerosRegex);
    let isValidCostoT = verificarInputNumber(CoT,numerosRegex);
    let isValidTipoPresupuesto = verificarSelect(Tp);
    let isValidObjGasto = verificarSelect(oG);
    let isValidMes = verificarSelect(M);
    let parametros;
    switch(parseInt($('#DimensionAdministrativa').val())) {
        case  10:
            let isValidCantidadPersonas = verificarInputNumber(CaP,numerosRegex);
            if (
                (isValidNombreActividad === true) &&
                (isValidCantidad === true) &&
                (isValidCosto === true) &&
                (isValidCostoT === true) &&
                (isValidTipoPresupuesto === true) &&
                (isValidObjGasto === true) &&
                (isValidMes === true) && 
                (isValidCantidadPersonas === true) 
            ) { 
                parametros = {
                    idDescripcionAdministrativa: parseInt(idDescripcionItemSeleccionada),
                    idActividad: parseInt(idActividadSeleccionada),
                    idObjetoGasto: parseInt(ObjGasto.value),
                    idTipoPresupuesto: parseInt(TipoPresupuesto.value),
                    idDimension: parseInt(idDimensionAdminSeleccionada),
                    nombreActividad: NombreActividad.value,
                    cantidad: Cantidad.value,
                    unidadDeMedida: document.querySelector('#UnidadMedida').value,
                    costo:  Costo.value,
                    costoTotal: CostoT.value,
                    mesRequerido: Mes.value,
                    descripcion: { cantidadPersonas: CantidadPersonas.value }
                }
                console.log(parametros);
                $.ajax(`${ API }/descripcion-administrativa/modifica-descripcion-administrativa.php`,{ 
                    type: 'POST',
                    dataType: 'json',
                    contentType: 'application/json',
                    data: JSON.stringify(parametros),
                    success:function(response) {
                        const { data } = response;
                        console.log(data); 
                        $('#modalRegistroDimensionAdmin').modal('hide');
                        Swal.fire({
                        icon: 'success',
                        title: 'Accion realizada Exitosamente',
                        text: `${ data.message }`
                        });
                        generaTablasAcordeDimension(document.querySelector('#DimensionAdministrativa'));
                        vaciarAct();
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
                                footer: '<b>Verifique los datos del formulario de registro</b>'
                            });
                        }
                    }
                });
            } else { // caso contrario mostrar alerta y notificar al usuario 
                Swal.fire({
                    icon: 'error',
                    title: 'Ops...',
                    text: 'El registro del item en la actividad seleccionada no se pudo realizar',
                    footer: '<b>Por favor verifique el formulario de registro</b>'
                })
            }
        break;
        case  11:
            let isValidMeses = verificarInputNumber(mes,numerosRegex);
            if (
                (isValidNombreActividad === true) &&
                (isValidCantidad === true) &&
                (isValidCosto === true) &&
                (isValidCostoT === true) &&
                (isValidTipoPresupuesto === true) &&
                (isValidObjGasto === true) &&
                (isValidMes === true) && 
                (isValidMeses === true) 
            ) { 
                parametros = {
                    idDescripcionAdministrativa: parseInt(idDescripcionItemSeleccionada),
                    idActividad: parseInt(idActividadSeleccionada),
                    idObjetoGasto: parseInt(ObjGasto.value),
                    idTipoPresupuesto: parseInt(TipoPresupuesto.value),
                    idDimension: parseInt(idDimensionAdminSeleccionada),
                    nombreActividad: NombreActividad.value,
                    cantidad: Cantidad.value,
                    unidadDeMedida: document.querySelector('#UnidadMedida').value,
                    costo:  Costo.value,
                    costoTotal: CostoT.value,
                    mesRequerido: Mes.value,
                    descripcion: { meses: meses.value }
                }
                console.log(parametros);
                $.ajax(`${ API }/descripcion-administrativa/modifica-descripcion-administrativa.php`,{ 
                    type: 'POST',
                    dataType: 'json',
                    contentType: 'application/json',
                    data: JSON.stringify(parametros),
                    success:function(response) {
                        const { data } = response;
                        $('#modalRegistroDimensionAdmin').modal('hide');
                        console.log(data); 
                        Swal.fire({
                        icon: 'success',
                        title: 'Accion realizada Exitosamente',
                        text: `${ data.message }`
                        });
                        generaTablasAcordeDimension(document.querySelector('#DimensionAdministrativa'));
                        vaciarAct();
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
                                footer: '<b>Verifique los datos del formulario de registro</b>'
                            });
                        }
                    }
                });
            } else { // caso contrario mostrar alerta y notificar al usuario 
                Swal.fire({
                    icon: 'error',
                    title: 'Ops...',
                    text: 'El registro del item en la actividad seleccionada no se pudo realizar',
                    footer: '<b>Por favor verifique el formulario de registro</b>'
                })
            }
        break;
        case  12:
            if (
                (isValidNombreActividad === true) &&
                (isValidCantidad === true) &&
                (isValidCosto === true) &&
                (isValidCostoT === true) &&
                (isValidTipoPresupuesto === true) &&
                (isValidObjGasto === true) &&
                (isValidMes === true)
            ) { 
                parametros = {
                    idDescripcionAdministrativa: parseInt(idDescripcionItemSeleccionada),
                    idActividad: parseInt(idActividadSeleccionada),
                    idObjetoGasto: parseInt(ObjGasto.value),
                    idTipoPresupuesto: parseInt(TipoPresupuesto.value),
                    idDimension: parseInt(idDimensionAdminSeleccionada),
                    nombreActividad: NombreActividad.value,
                    cantidad: Cantidad.value,
                    unidadDeMedida: document.querySelector('#UnidadMedida').value,
                    costo:  Number(Costo.value),
                    costoTotal: CostoT.value,
                    mesRequerido: Mes.value,
                    descripcion: {}
                }
                console.log(parametros);
                $.ajax(`${ API }/descripcion-administrativa/modifica-descripcion-administrativa.php`,{ 
                    type: 'POST',
                    dataType: 'json',
                    contentType: 'application/json',
                    data: JSON.stringify(parametros),
                    success:function(response) {
                        const { data } = response;
                        $('#modalRegistroDimensionAdmin').modal('hide');
                        console.log(data); 
                        Swal.fire({
                        icon: 'success',
                        title: 'Accion realizada Exitosamente',
                        text: `${ data.message }`
                        });
                        generaTablasAcordeDimension(document.querySelector('#DimensionAdministrativa'));
                        vaciarAct();
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
                                footer: '<b>Verifique los datos del formulario de registro</b>'
                            });
                        }
                    }
                });
            } else { // caso contrario mostrar alerta y notificar al usuario 
                Swal.fire({
                    icon: 'error',
                    title: 'Ops...',
                    text: 'El registro del item en la actividad seleccionada no se pudo realizar',
                    footer: '<b>Por favor verifique el formulario de registro</b>'
                })
            }
        break;
        case  13:
            let isValidTipoEquipoTecnologico = verificarInputText(tET, letrasEspaciosCaracteresRegex);
            if (
                (isValidNombreActividad === true) &&
                (isValidCantidad === true) &&
                (isValidCosto === true) &&
                (isValidCostoT === true) &&
                (isValidTipoPresupuesto === true) &&
                (isValidObjGasto === true) &&
                (isValidMes === true) &&
                (isValidTipoEquipoTecnologico === true)
            ) { 
                parametros = {
                    idDescripcionAdministrativa: parseInt(idDescripcionItemSeleccionada),
                    idActividad: parseInt(idActividadSeleccionada),
                    idObjetoGasto: parseInt(ObjGasto.value),
                    idTipoPresupuesto: parseInt(TipoPresupuesto.value),
                    idDimension: parseInt(idDimensionAdminSeleccionada),
                    nombreActividad: NombreActividad.value,
                    cantidad: Cantidad.value,
                    unidadDeMedida: document.querySelector('#UnidadMedida').value,
                    costo:  Costo.value,
                    costoTotal: CostoT.value,
                    mesRequerido: Mes.value,
                    descripcion: { tipoEquipoTecnologico: tipoEquipoTecnologico.value }
                }
                console.log(parametros);
                $.ajax(`${ API }/descripcion-administrativa/modifica-descripcion-administrativa.php`,{ 
                    type: 'POST',
                    dataType: 'json',
                    contentType: 'application/json',
                    data: JSON.stringify(parametros),
                    success:function(response) {
                        const { data } = response;
                        console.log(data); 
                        $('#modalRegistroDimensionAdmin').modal('hide');
                        Swal.fire({
                        icon: 'success',
                        title: 'Accion realizada Exitosamente',
                        text: `${ data.message }`
                        });
                        generaTablasAcordeDimension(document.querySelector('#DimensionAdministrativa'));
                        vaciarAct();
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
                                footer: '<b>Verifique los datos del formulario de registro</b>'
                            });
                        }
                    }
                });
            } else { // caso contrario mostrar alerta y notificar al usuario 
                Swal.fire({
                    icon: 'error',
                    title: 'Ops...',
                    text: 'El registro del item en la actividad seleccionada no se pudo realizar',
                    footer: '<b>Por favor verifique el formulario de registro</b>'
                })
            }
        break;
        case  14:
            if (
                (isValidNombreActividad === true) &&
                (isValidCantidad === true) &&
                (isValidCosto === true) &&
                (isValidCostoT === true) &&
                (isValidTipoPresupuesto === true) &&
                (isValidObjGasto === true) &&
                (isValidMes === true)
            ) { 
                parametros = {
                    idDescripcionAdministrativa: parseInt(idDescripcionItemSeleccionada),
                    idActividad: parseInt(idActividadSeleccionada),
                    idObjetoGasto: parseInt(ObjGasto.value),
                    idTipoPresupuesto: parseInt(TipoPresupuesto.value),
                    idDimension: parseInt(idDimensionAdminSeleccionada),
                    nombreActividad: NombreActividad.value,
                    cantidad: Cantidad.value,
                    unidadDeMedida: document.querySelector('#UnidadMedida').value,
                    costo:  Costo.value,
                    costoTotal: CostoT.value,
                    mesRequerido: Mes.value,
                    descripcion: {}
                }
                console.log(parametros);
                $.ajax(`${ API }/descripcion-administrativa/modifica-descripcion-administrativa.php`,{ 
                    type: 'POST',
                    dataType: 'json',
                    contentType: 'application/json',
                    data: JSON.stringify(parametros),
                    success:function(response) {
                        const { data } = response;
                        $('#modalRegistroDimensionAdmin').modal('hide');
                        console.log(data); 
                        Swal.fire({
                        icon: 'success',
                        title: 'Accion realizada Exitosamente',
                        text: `${ data.message }`
                        });
                        generaTablasAcordeDimension(document.querySelector('#DimensionAdministrativa'));
                        vaciarAct();
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
                                footer: '<b>Verifique los datos del formulario de registro</b>'
                            });
                        }
                    }
                });
            } else { // caso contrario mostrar alerta y notificar al usuario 
                Swal.fire({
                    icon: 'error',
                    title: 'Ops...',
                    text: 'El registro del item en la actividad seleccionada no se pudo realizar',
                    footer: '<b>Por favor verifique el formulario de registro</b>'
                })
            }
        break;
        case  15:
            let isValidaAreaBeca = verificarInputText(aB, letrasEspaciosCaracteresRegex);
            if (
                (isValidNombreActividad === true) &&
                (isValidCantidad === true) &&
                (isValidCosto === true) &&
                (isValidCostoT === true) &&
                (isValidTipoPresupuesto === true) &&
                (isValidObjGasto === true) &&
                (isValidMes === true) &&
                (isValidaAreaBeca === true)
            ) { 
                parametros = {
                    idDescripcionAdministrativa: parseInt(idDescripcionItemSeleccionada),
                    idActividad: parseInt(idActividadSeleccionada),
                    idObjetoGasto: parseInt(ObjGasto.value),
                    idTipoPresupuesto: parseInt(TipoPresupuesto.value),
                    idDimension: parseInt(idDimensionAdminSeleccionada),
                    nombreActividad: NombreActividad.value,
                    cantidad: Cantidad.value,
                    unidadDeMedida: document.querySelector('#UnidadMedida').value,
                    costo:  Costo.value,
                    costoTotal: CostoT.value,
                    mesRequerido: Mes.value,
                    descripcion: { areaBeca: areaBeca.value }
                }
                console.log(parametros);
                $.ajax(`${ API }/descripcion-administrativa/modifica-descripcion-administrativa.php`,{ 
                    type: 'POST',
                    dataType: 'json',
                    contentType: 'application/json',
                    data: JSON.stringify(parametros),
                    success:function(response) {
                        const { data } = response;
                        console.log(data); 
                        $('#modalRegistroDimensionAdmin').modal('hide');
                        Swal.fire({
                        icon: 'success',
                        title: 'Accion realizada Exitosamente',
                        text: `${ data.message }`
                        });
                        generaTablasAcordeDimension(document.querySelector('#DimensionAdministrativa'));
                        vaciarAct();
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
                                footer: '<b>Verifique los datos del formulario de registro</b>'
                            });
                        }
                    }
                });
            } else { // caso contrario mostrar alerta y notificar al usuario 
                Swal.fire({
                    icon: 'error',
                    title: 'Ops...',
                    text: 'El registro del item en la actividad seleccionada no se pudo realizar',
                    footer: '<b>Por favor verifique el formulario de registro</b>'
                })
            }
        break;
        case  16:
            let isValidProyecto = verificarSelect(project);
            parametros = {
                idDescripcionAdministrativa: parseInt(idDescripcionItemSeleccionada),
                idActividad: parseInt(idActividadSeleccionada),
                idObjetoGasto: parseInt(ObjGasto.value),
                idTipoPresupuesto: parseInt(TipoPresupuesto.value),
                idDimension: parseInt(idDimensionAdminSeleccionada),
                nombreActividad: NombreActividad.value,
                cantidad: Cantidad.value,
                unidadDeMedida: document.querySelector('#UnidadMedida').value,
                costo:  Costo.value,
                costoTotal: CostoT.value,
                mesRequerido: Mes.value,
                descripcion: { proyecto: proyectos.value }
            }
            console.log(parametros);
            if (
                (isValidNombreActividad === true) &&
                (isValidCantidad === true) &&
                (isValidCosto === true) &&
                (isValidCostoT === true) &&
                (isValidTipoPresupuesto === true) &&
                (isValidObjGasto === true) &&
                (isValidMes === true) &&
                (isValidProyecto === true)
            ) { 
                $.ajax(`${ API }/descripcion-administrativa/modifica-descripcion-administrativa.php`,{ 
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
                        text: `${ data.message }`
                        });
                        $('#modalRegistroDimensionAdmin').modal('hide');
                        generaTablasAcordeDimension(document.querySelector('#DimensionAdministrativa'));
                        vaciarAct();
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
                                footer: '<b>Verifique los datos del formulario de registro</b>'
                            });
                        }
                    }
                });
            } else { // caso contrario mostrar alerta y notificar al usuario 
                Swal.fire({
                    icon: 'error',
                    title: 'Ops...',
                    text: 'El registro del item en la actividad seleccionada no se pudo realizar',
                    footer: '<b>Por favor verifique el formulario de registro</b>'
                })
            }
        break;
        case  17:
            let isDescripcionDimOcho = verificarInputText(descripcionDim8,letrasEspaciosCaracteresRegex);
            let isValidCantidadDimOcho = verificarInputNumber(cantidadDimOcho,numerosRegex);
            let isValidPrecioDimOcho = verificarInputNumber(precioDOcho,numerosRegex);
            let isValidSetenta = verificarInputNumber(valorSetenta,numerosRegex);
            let isValidTreinta = verificarInputNumber(valorTreinta,numerosRegex);
            if (
                (isValidNombreActividad === true) &&
                (isValidCantidad === true) &&
                (isValidCosto === true) &&
                (isValidCostoT === true) &&
                (isValidTipoPresupuesto === true) &&
                (isValidObjGasto === true) &&
                (isValidMes === true) &&
                (isDescripcionDimOcho === true) &&
                (isValidCantidadDimOcho === true) &&
                (isValidPrecioDimOcho === true) &&
                (isValidSetenta === true) &&
                (isValidTreinta === true)
            ) { 
                parametros = {
                    idDescripcionAdministrativa: parseInt(idDescripcionItemSeleccionada),
                    idActividad: parseInt(idActividadSeleccionada),
                    idObjetoGasto: parseInt(ObjGasto.value),
                    idTipoPresupuesto: parseInt(TipoPresupuesto.value),
                    idDimension: parseInt(idDimensionAdminSeleccionada),
                    nombreActividad: NombreActividad.value,
                    cantidad: Cantidad.value,
                    unidadDeMedida: document.querySelector('#UnidadMedida').value,
                    costo:  Costo.value,
                    costoTotal: CostoT.value,
                    mesRequerido: Mes.value,
                    descripcion: { 
                        descripcionItem: descripcionDimOcho.value,
                        cantidadItem: cantodadDimOcho.value,
                        precioItem: precioDimOcho.value,
                        valorUno: setenta.value,
                        valorDos: treinta.value
                    }
                }
                console.log(parametros);
                $.ajax(`${ API }/descripcion-administrativa/modifica-descripcion-administrativa.php`,{ 
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
                        text: `${ data.message }`
                        });
                        $('#modalRegistroDimensionAdmin').modal('hide');
                        generaTablasAcordeDimension(document.querySelector('#DimensionAdministrativa'));
                        vaciarAct();
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
                                footer: '<b>Verifique los datos del formulario de registro</b>'
                            });
                        }
                    }
                });
            } else { // caso contrario mostrar alerta y notificar al usuario 
                Swal.fire({
                    icon: 'error',
                    title: 'Ops...',
                    text: 'El registro del item en la actividad seleccionada no se pudo realizar',
                    footer: '<b>Por favor verifique el formulario de registro</b>'
                })
            }
        break;
        default:
        break;
    
    }
}

const modificarActividad = (idActividad) => {
    let parametros = { idActividad: parseInt(idActividad) };
    esModificacion = true;
    esInsercion = false;
    $('#save').addClass('d-none');
    $('#edit').removeClass('d-none');
    $.ajax(`${ API }/actividades/verifica-estado-presupuesto.php`,{ 
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        success:function(response) {
            $.ajax(`${ API }/actividades/genera-data-actividad.php`,{
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify(parametros),
                success:function(response) {
                    const { data } = response;
                    console.log(data);
                    idCostoActividadPorTrimestreSeleccionada = data.idCostActPorTri;
                    idActividadParaModificar = idActividad;
                    idResultadoInstitucionalModificar = data.idResultadoInstitucional;
                    resultadoInstitucionalModificar = data.resultadoInstitucional;
                    correlativoModificar = data.correlativoActividad;
                    $('#Indicador').val(data.indicadoresResultado).trigger('change');
                    $('#Actividads').val(data.actividad).trigger('change');
                    $('#PresupuestoActividad').val(data.CostoTotal).trigger('change');
                    $('#PorcentPTrimestre').val(data.porcentajeTrimestre1*100).trigger('change');
                    $('#PorcentSTrimestre').val(data.porcentajeTrimestre2*100).trigger('change');
                    $('#PorcentTTrimestre').val(data.porcentajeTrimestre3*100).trigger('change');
                    $('#PorcentCTrimestre').val(data.porcentajeTrimestre4*100).trigger('change');
                    $('#ResultadosDeUnidad').val(data.resultadosUnidad).trigger('change');
                    $('#Justificacions').val(data.justificacionActividad).trigger('change');
                    $('#Medio').val(data.medioVerificacionActividad).trigger('change');
                    $('#Poblacion').val(data.poblacionObjetivoActividad).trigger('change');
                    $('#Responsable').val(data.responsableActividad).trigger('change');
                    avanzarModificacion(data.idDimension, data.dimensionEstrategica, data.idObjetivoInstitucional, data.objetivoInstitucional, data.idAreaEstrategica, data.areaEstrategica);
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
                            footer: '<b>No puedes modificar actividades</b>'
                        });
                    }
                }
            });
            const { data } = response;
            console.log(data);
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
                    footer: '<b>No puedes modificar actividades</b>'
                });
            }
        }
    });
}

const modificarDataActividad = () => {
    let Justificacion = document.querySelector('#Justificacions');
    let Medio = document.querySelector('#Medio');
    let Poblacion = document.querySelector('#Poblacion');
    let Responsable = document.querySelector('#Responsable');

    let jC = { valorEtiqueta: Justificacion, id: 'Justificacions', name: 'Justificacion', min: 1, max: 255, type: 'text' };
    let mD = { valorEtiqueta: Medio, id: 'Medio', name: 'Medio', min: 1, max: 255, type: 'text' };
    let Pb = { valorEtiqueta: Poblacion, id: 'Poblacion', name: 'Poblacion', min: 1, max: 255, type: 'text' };
    let Rp = { valorEtiqueta: Responsable, id: 'Responsable', name: 'Responsable', min: 1, max: 255, type: 'text' };


    let isValidJustificacion = verificarInputText(jC,justificacionRegex);
    let isValidMedio = verificarInputText(mD,letrasEspaciosCaracteresRegex);
    let isValidPoblacion = verificarInputText(Pb,letrasEspaciosCaracteresRegex);
    let isValidResponsable = verificarInputText(Rp,letrasEspaciosCaracteresRegex);

    if (
        (isValidJustificacion === true) &&
        (isValidMedio === true) &&
        (isValidPoblacion === true) &&
        (isValidResponsable === true)
    ) {
        let parametros = {
            idActividad: parseInt(idActividadParaModificar),
            idCostoActTrimestre: parseInt(idCostoActividadPorTrimestreSeleccionada),
            idDimension: parseInt($('#DimEstrategica').val()), 
            idObjetivoInstitucional: parseInt($('#ObjInstitucional').val()),
            idResultadoInstitucional: parseInt($('#ResultadosInstitucional').val()),
            idAreaEstrategica: parseInt($('#AreaEstrategica').val()),
            resultadosUnidad: $('#ResultadosDeUnidad').val(),
            indicadoresResultado: $('#Indicador').val(),
            actividad: $('#Actividads').val(),
            correlativoActividad: correlativoModificar,
            porcentajeTrimestre1: Number($('#PorcentPTrimestre').val()/100),
            porcentajeTrimestre2: Number($('#PorcentSTrimestre').val()/100),
            porcentajeTrimestre3: Number($('#PorcentTTrimestre').val()/100),
            porcentajeTrimestre4: Number($('#PorcentCTrimestre').val()/100),
            justificacionActividad: $('#Justificacions').val(),
            medioVerificacionActividad: $('#Medio').val(),
            poblacionObjetivoActividad: $('#Poblacion').val(),
            responsableActividad: $('#Responsable').val(),
            costoTotal: $('#PresupuestoActividad').val(),
        };
        
        
        console.log(parametros);
        $.ajax(`${ API }/actividades/modificar-actividad.php`, {
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(parametros),
            success:function(response) {
                $('#modalCargaActividades').modal('show');
                verActividades($('#DimEstrategica').val(), dimnesionParaModales);
                $('#modalFormLlenadoDimension').modal('hide');
                $('#modalLlenadoActividades').modal('hide');
                llenar('DimensionesTablaModificar');
                llenar('DimensionesTabla');
                $("#modalModificarDimension").modal('show');
                const { data } = response;
                console.log(data);
                pos=1;
                current_fs = $(this).parent();
                for(let i=1;i<5;i++){
                    $("#progressbar li").eq(i).removeClass("active");
                };
                current_fs.animate({opacity: 0}, {
                step: function(now) {
                    opacity = 1 - now;
                    
                    current_fs.css({
                        'display': 'none',
                        'position': 'relative'
                    });
                        $("#primero").css({'opacity': opacity});
                    },
                        duration: 600
                });
                $("#msform")[0].reset();
                $("form").trigger("change");
                resetW();
                
                Swal.fire({
                    icon: 'success',
                    title: 'Accion realizada Exitosamente',
                    text: `${ data.message }`,
                });
            },
            error:function(error) {
                console.log(error);
                console.log(error.responseText);
                const { status, data } = error.responseJSON;
                if (status === 401) {
                    window.location.href = '../views/401.php';
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ops...',
                        text: `${ data.message }`,
                        footer: '<b>Verifique los datos del formulario</b>'
                    });
                }
            }
        });
    } else { // caso contrario mostrar alerta y notificar al usuario 
        Swal.fire({
            icon: 'error',
            title: 'Ops...',
            text: 'No se puede avanzar',
            footer: '<b>Por favor verifique el formulario de registro</b>'
        });
    }
}

const limpiaFormActividad = () => {
    $('#modalLlenadoDimension').modal('show');
    pos=1;
    $('#modalLlenadoActividades').modal('hide');
    for(let i=1;i<5;i++){
        $("#progressbar li").eq(i).removeClass("active");
    };
    $("#segundo").css({'display': 'none','position': 'relative'});
    $("#tercero").css({'display': 'none','position': 'relative'});
    $("#cuarto").css({'display': 'none','position': 'relative'});
    $("#quinto").css({'display': 'none','position': 'relative'});
    $("#primero").css({'opacity': 1});
    $("#primero").show();
    $("#msform")[0].reset();
    $("#foote-modal").css({'display': 'block','position': ''});
    $("#foote-modal").show();
    resetW();
}