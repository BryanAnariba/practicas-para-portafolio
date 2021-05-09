$( document ).ready(function() {
    // $('#listado-dimensiones').DataTable();
    // $('#listado-dimensiones tbody').html(``);
    // $('#listado-dimensiones').DataTable({
    //     language: i18nEspaniol,
    //     dom: 'Blfrtip',
    //     buttons: botonesExportacion,
    //     retrieve: true
    // });
    $.when(
        $.ajax(`${ API }/pacc/listado-presupuestos-departamento.php`, {
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json'
        }),
        $.ajax(`${ API }/pacc/listar-comparativa-presupuestos.php`, {
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json'
        })).done(function(dataDepartamentos, dataComparativaPresupuestos) {
            let response = dataDepartamentos[0];
            const { data } = response;
            $('#presupuestoAnual').val(dataComparativaPresupuestos[0].data.presupuestoAnual).trigger('change');
            $('#presupuestoUtilizado').val(dataComparativaPresupuestos[0].data.montoUtilizado).trigger('change');
            $('#fechaPresupuesto').val(dataComparativaPresupuestos[0].data.fechaPresupuesto).trigger('change');
            let presupuestoDisponible = Number(dataComparativaPresupuestos[0].data.presupuestoAnual) - Number(dataComparativaPresupuestos[0].data.montoUtilizado);
            $('#presupuestoDisponible').val(Number.parseFloat(presupuestoDisponible).toFixed(2)).trigger('change');
            let departamentos = data.map(depto => depto.nombreDepartamento);
            let presupuestos = data.map(presupuesto => presupuesto.montoPresupuesto);
            var graficaDepartamentos = document.querySelector('#grafica-presupuestos-departamentos').getContext("2d");
            var graficoTipoDona = new Chart(graficaDepartamentos, {
                type: 'doughnut',
                data: {
                    labels: departamentos,
                    datasets:[{
                        label: presupuestos,
                        data: presupuestos,
                        backgroundColor: ["#F93154","#1A237E","#4A148C","#00B74A","#FFA900","#1266F1"]
                    }],
                },
                options: {
                    responsive: true,
                    legend: {
                        position: 'bottom'
                    },
                }
            });
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
});
var grafico;
const abrirModalReporteGeneral = () => {
    $('#FechaPresupuesto').html(``);
    $.ajax(`${ API }/pacc/genera-lista-anios-presupuestos.php`,{
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        success:function (response) {
            const { data } = response;
            $('#FechaPresupuesto').html(`<option value="">Seleccione el año en que desea generar el PACC</option>`);
            for(let i=0; i<data.length; i++) {
                $('#FechaPresupuesto').append(`<option value="${data[i].idControlPresupuestoActividad}">${ data[i].anio }</option>`);
            }
            $('#FechaPresupuesto').select2({ width: '100%' });
            $('#tipoOrdenamiento').select2({ width: '100%' });
            $('#modalGeneraPaccGeneral').modal('show');
        },
        error:function(error){
            console.log(error.responseText)
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

const generarReporteGeneralPACC = () => {
    if ($('#FechaPresupuesto').val() === "") {
        Swal.fire({
            icon: 'error',
            title: 'Ops...',
            text: "Seleccione una fecha para generar el PACC correspondiente",
            footer: '<b>Por favor verifique el formulario de registro</b>'
        });
    } else if ($('#tipoOrdenamiento').val() === "") {
        Swal.fire({
            icon: 'error',
            title: 'Ops...',
            text: "Seleccione un tipo de ordenamiento para generar el excel del pacc",
            footer: '<b>Por favor verifique el formulario de registro</b>'
        });
    } else {
        let parametros = { 
            fechaPresupuestoActividad: parseInt($('#FechaPresupuesto').val()), 
            tipoOrdenamiento: parseInt($('#tipoOrdenamiento').val())
        };
        $.ajax(`${ API }/pacc/genera-pacc-general.php`,{
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(parametros),
            success:function (response) {
                $('#tipoOrdenamiento').val("");
                $('#modalGeneraPaccGeneral').modal('hide');
                const { data } = response;
                var $a = $("<a>");
                $a.attr("href",response.file);
                $("body").append($a);
                $a.attr("download","Reporte-PACC-Facultad.xlsx");
                $a[0].click();
                $a.remove();
                Swal.fire({
                    icon: 'success',
                    title: 'Accion realizada Exitosamente',
                    text: `${ data.message }`,
                });                
            },
            error:function(error){
                console.log(error.responseText)
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
}

const abrirModalReporteDepartamento = () => {
    let parametros  = { idEstadoDepartamento: parseInt(1) };
    $.when(
        $.ajax(`${ API }/pacc/genera-lista-anios-presupuestos.php`,{
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json'
        }),
        $.ajax(`${ API }/departamentos/listar-departamentos.php`, {
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(parametros)
        })).done(function(dataAniosPresupuesto, dataDepartamentos) {
            const departamentos = dataDepartamentos[0].data;
            const aniosPresupuestos = dataAniosPresupuesto[0].data;

            $('#FechaPresupuestoDepartamento').html(`<option value="">Seleccione el año en que desea generar el PACC</option>`);
            for(let i=0; i<aniosPresupuestos.length; i++) {
                $('#FechaPresupuestoDepartamento').append(`<option value="${ aniosPresupuestos[i].idControlPresupuestoActividad }">${ aniosPresupuestos[i].anio }</option>`);
            }
            $('#FechaPresupuestoDepartamento').select2({ width: '100%' });

            $('#departamento').html(`<option value="">Seleccione el departamento en que desea generar el PACC</option>`);
            for(let i=0; i<departamentos.length; i++) {
                $('#departamento').append(`<option value="${ departamentos[i].idDepartamento }">${ departamentos[i].nombreDepartamento }</option>`);
            }
            $('#departamento').select2({ width: '100%' });
            $('#tipoOrdenamientoDepto').select2({ width: '100%' });
            $('#modalGeneraPaccDepartamento').modal('show');
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

const generarReporteDepartamentoPACC = () => {
    if ($('#FechaPresupuestoDepartamento').val() === "") {
        Swal.fire({
            icon: 'error',
            title: 'Ops...',
            text: "Seleccione una fecha para generar el PACC correspondiente",
            footer: '<b>Por favor verifique el formulario de registro</b>'
        });
    } else {
        if ($('#departamento').val() === "") { 
            Swal.fire({
                icon: 'error',
                title: 'Ops...',
                text: "Seleccione un departamento para generar el PACC correspondiente",
                footer: '<b>Por favor verifique el formulario de registro</b>'
            });
        } else if ($('#tipoOrdenamientoDepto').val() === "") {
            Swal.fire({
                icon: 'error',
                title: 'Ops...',
                text: "Seleccione el tipo de ordenamiento para generar el PACC por departamento correspondiente",
                footer: '<b>Por favor verifique el formulario de registro</b>'
            });
        } else {
            let parametros = { 
                fechaPresupuestoActividad: parseInt($('#FechaPresupuestoDepartamento').val()), 
                idDepartamento: parseInt($('#departamento').val()),
                tipoOrdenamiento: parseInt($("#tipoOrdenamientoDepto").val())
            };
            $.ajax(`${ API }/pacc/genera-pacc-por-departamento.php`,{
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify(parametros),
                success:function (response) {
                    $("#tipoOrdenamientoDepto").val("");
                    $('#modalGeneraPaccDepartamento').modal('hide');
                    const { data } = response;
                    var $a = $("<a>");
                    $a.attr("href",response.file);
                    $("body").append($a);
                    $a.attr("download",`Reporte-PACC-${ response.departamento }.xlsx`);
                    $a[0].click();
                    $a.remove();
                    Swal.fire({
                        icon: 'success',
                        title: 'Accion realizada Exitosamente',
                        text: `${ data.message }`,
                    });
                },
                error:function(error){
                    console.log(error.responseText)
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
    }
}

const abrirModalGraficos = () => {
    let parametros  = { idEstadoDepartamento: parseInt(1) };
    $.when(
        $.ajax(`${ API }/pacc/genera-lista-anios-presupuestos.php`,{
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json'
        }),
        $.ajax(`${ API }/departamentos/listar-departamentos.php`, {
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(parametros)
        })).done(function(dataAniosPresupuesto, dataDepartamentos) {
            const departamentos = dataDepartamentos[0].data;
            const aniosPresupuestos = dataAniosPresupuesto[0].data;

            $('#FechaPresupuestoDepartamentoGrafica').html(`<option value="">Seleccione el año en que desea generar el PACC</option>`);
            for(let i=0; i<aniosPresupuestos.length; i++) {
                $('#FechaPresupuestoDepartamentoGrafica').append(`<option value="${ aniosPresupuestos[i].idControlPresupuestoActividad }">${ aniosPresupuestos[i].anio }</option>`);
            }
            $('#FechaPresupuestoDepartamentoGrafica').select2({ width: '100%' });

            $('#departamentoGrafica').html(`<option value="">Seleccione el departamento en que desea generar el PACC</option>`);
            for(let i=0; i<departamentos.length; i++) {
                $('#departamentoGrafica').append(`<option value="${ departamentos[i].idDepartamento }">${ departamentos[i].nombreDepartamento }</option>`);
            }
            $('#departamentoGrafica').select2({ width: '100%' });
            
            $('#tipoGrafico').select2({ width: '100%' });
            $('#modalGraficos').modal('show');
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


function getRandomColor() {
    var letters = '0123456789ABCDEF';
    var color = '#';
    for (var i = 0; i < 6; i++) {
      color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}

const generarReportes = () => {
    
    //$('#listado-dimensiones tbody').html(``);
    $('#listado-dimensiones').dataTable().fnDestroy();
    // $('#listado-dimensiones tbody').dataTable().fnDestroy();
    if ($('#FechaPresupuestoDepartamentoGrafica').val() === "") {
        Swal.fire({
            icon: 'error',
            title: 'Ops...',
            text: "Seleccione una fecha para generar la grafica de gastos por dimension correspondiente",
            footer: '<b>Por favor verifique el formulario de registro</b>'
        });
    } else {
        if ($('#departamentoGrafica').val() === "") { 
            Swal.fire({
                icon: 'error',
                title: 'Ops...',
                text: "Seleccione un departamento para generar la grafica de gastos por dimension correspondiente",
                footer: '<b>Por favor verifique el formulario de registro</b>'
            });
        } else {
            let parametros = { fechaPresupuestoActividad: parseInt($('#FechaPresupuestoDepartamentoGrafica').val()), idDepartamento: parseInt($('#departamentoGrafica').val()) };
            
            $.ajax(`${ API }/pacc/genera-data-dimensiones-estrategicas-departamentos.php`,{
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify(parametros),
                success:function (response) {
                    const { data } = response;
                    console.log(data);
        
                    $('#listado-dimensiones tbody').html(``);
                    for (let i=0;i<data.length; i++) {
                        
    
                        $('#listado-dimensiones tbody').append(`
                            <tr>
                                <td scope="row" class="text-center">${ data[i].idDimension }</td>
                                <td scope="row" style="background-color: #7CB342;" class="text-white text-center">${ data[i].dimensionEstrategica }</td>
                                <td scope="row" style="background-color: #D50000;" class="text-white text-center">${ data[i].sumatoriaCostosPorDimension }</td>
                                <td scope="row" class="text-center">${ data[i].nombreDepartamento }</td>
                                <td scope="row" class="text-center">${ data[i].anioActividades }</td>
                            </tr>
                        `)
                    }

                    $('#listado-dimensiones').DataTable({
                        language: i18nEspaniol,
                        dom: 'Blfrtip',
                        buttons: botonesExportacion,
                        retrieve: true
                    });
                },
                error:function(error){
                    console.log(error.responseText)
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
    }
}

const abrirModalReportesEspecificos = () => {
    $('#opcion-departamento').addClass('d-none');
    $('#contenedorObjetosGasto').addClass('d-none');
    $('#Opciones').addClass('d-none');
    $.ajax(`${ API }/pacc/genera-lista-anios-presupuestos.php`, {
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        success:function (response) {
            const { data } = response;
            $('#Fecha').html(`<option value="">Seleccione el año</option>`);
            for(let i=0; i<data.length; i++) {
                $('#Fecha').append(`<option value="${ data[i].idControlPresupuestoActividad }">${ data[i].anio }</option>`);
            }
            $('#Fecha').select2({ width: '100%' });
            $('#reporteEspecifico').modal('show');
        },
        error:function(error){
            console.log(error.responseText)
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
        }
    });
} 

const generarObjetos = () => {
    let parametros = {
        idPresupuesto: parseInt($('#Fecha').val())
    };
    $.ajax(`${ API }/ObjetosGasto/listar-objetos-gasto-activos.php`, {
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        data:JSON.stringify(parametros),
        success:function (response) {
            $('#contenedorObjetosGasto').addClass('d-none');
            const { data } = response;
            $('#Objetos').html(`<option value="">Seleccione el objeto de gasto</option>`);
            for(let i=0; i<data.length; i++) {
                $('#Objetos').append(`<option value="${ data[i].idObjetoGasto }">${ data[i].abrev } - ${ data[i].DescripcionCuenta }</option>`);
            }
            
            $('#contenedorObjetosGasto').removeClass('d-none');
            //$('#opcion-departamento').removeClass('d-none');
            $('#opcion-generacion').removeClass('d-none');
            $('#Objetos').select2({ width: '100%' });
            $('#Opciones').select2({ width: '100%' });
            
        },
        error:function(error){
            console.log(error.responseText)
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
        }
    });
}

const opcionGenerarDeptos = () => {
    if (parseInt($('#Opciones').val()) === 1) {
        $('#opcion-departamento').addClass('d-none');
    } else if (parseInt($('#Opciones').val()) === 2) {
        let parametros  = { idEstadoDepartamento: parseInt(1) };
        $.ajax(`${ API }/departamentos/listar-departamentos.php`, {
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(parametros),
            success:function (response) {
                const { data } = response;
                $('#Depto').html(`<option value="">Seleccione el departamento</option>`);
                for(let i=0; i<data.length; i++) {
                    $('#Depto').append(`<option value="${ data[i].idDepartamento }">${ data[i].nombreDepartamento }</option>`);
                }
                $('#Depto').select2({ width: '100%' });
                $('#opcion-departamento').removeClass('d-none');
            },
            error:function(error){
                console.log(error.responseText)
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
            }
        });
    } else {
        $('#opcion-departamento').addClass('d-none');
        Swal.fire({
            icon: 'error',
            title: 'Ops...',
            text: `La opcion seleccionada no es valida`,
            footer: '<b>Por favor elija una opcion correcta</b>'
        });
    }
}

const cancelarOperacion = () => {
    $('#opcion-departamento').addClass('d-none');
    $('#contenedorObjetosGasto').addClass('d-none');
    $('#opcion-departamento').addClass('d-none');
    $('#opcion-generacion').addClass('d-none');
    $('#Opciones').addClass('d-none');
    $('#Depto').val("");
    $('#Objetos').val("");
    $('#Opciones').val("");
    $('#Fecha').val("");
}


const mostrarResultadosFiltroObjetoGasto = () => {
    if ($('#Fecha').val() === "") {
        Swal.fire({
            icon: 'error',
            title: 'Ops...',
            text: "Seleccione una año valido para realizar la busqueda del costo por objeto de gasto",
            footer: '<b>Por favor verifique el formulario de registro</b>'
        });
    } else if ($('#Objetos').val() === "") {
        Swal.fire({
            icon: 'error',
            title: 'Ops...',
            text: "Seleccione un objeto de gasto para realizar la busqueda del costo por objeto de gasto",
            footer: '<b>Por favor verifique el formulario de registro</b>'
        });
    } else if ($('#Opciones').val() === "") {
        Swal.fire({
            icon: 'error',
            title: 'Ops...',
            text: "Seleccione una opcion respecto al filtro del objeto de gasto para realizar la busqueda del costo por objeto de gasto",
            footer: '<b>Por favor verifique el formulario de registro</b>'
        });
    } else {
        let parametros;
        if (parseInt($('#Opciones').val()) === 1) {
            parametros = {
                idPresupuesto: parseInt($('#Fecha').val()),
                idObjetoGasto: parseInt($('#Objetos').val()),
            };
            $.ajax(`${ API }/pacc/listar-costo-por-objeto-gasto.php`, {
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify(parametros),
                success:function (response) {
                    const { data } = response;
                    $('#lista-reporte-por-objeto').dataTable().fnDestroy();
                    if (data != null) {
                        $('#lista-reporte-por-objeto tbody').html(``);
                        for (let i=0;i<data.length; i++) {
                            $('#lista-reporte-por-objeto tbody').append(`
                                <tr>
                                    <td scope="row" class="text-center">${ data[i].codigoObjetoGasto }</td>
                                    <td scope="row" class="text-center">${ data[i].descripcionCuenta }</td>
                                    <td scope="row" class="text-center">${ data[i].sumCostoActPorCodObjGasto }</td>
                                </tr>
                            `)
                        }
                    } else {
                        $('#lista-reporte-por-objeto tbody').html(``);
                    }
                    $('#lista-reporte-por-objeto').DataTable({
                        language: i18nEspaniol,
                        dom: 'Blfrtip',
                        buttons: botonesExportacion,
                        retrieve: true
                    });
                    console.log(data);
                    cancelarOperacion();
                    abrirModalReportesEspecificos();
                },
                error:function(error){
                    console.log(error.responseText)
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
                }
            });
        } else if (parseInt($('#Opciones').val()) === 2) {
            if ($('#Depto').val() === "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Ops...',
                    text: "Seleccione un departamento para generar el costo del objeto de gasto",
                    footer: '<b>Por favor verifique el formulario de registro</b>'
                });
            } else {
                parametros = {
                    idPresupuesto: parseInt($('#Fecha').val()),
                    idObjetoGasto: parseInt($('#Objetos').val()),
                    idDepartamento: parseInt($('#Depto').val())
                };
                $.ajax(`${ API }/pacc/listar-costo-objeto-por-depto.php`, {
                    type: 'POST',
                    dataType: 'json',
                    contentType: 'application/json',
                    data: JSON.stringify(parametros),
                    success:function (response) {
                        const { data } = response;
                        $('#lista-reporte-por-objeto').dataTable().fnDestroy();
                        if (data != null) {
                            $('#lista-reporte-por-objeto tbody').html(``);
                            for (let i=0;i<data.length; i++) {
                                $('#lista-reporte-por-objeto tbody').append(`
                                    <tr>
                                        <td scope="row" class="text-center">${ data[i].codigoObjetoGasto }</td>
                                        <td scope="row" class="text-center">${ data[i].descripcionCuenta }</td>
                                        <td scope="row" class="text-center">${ data[i].sumCostoActPorCodObjGasto }</td>
                                    </tr>
                                `)
                            }
                        } else {
                            $('#lista-reporte-por-objeto tbody').html(``);
                        }
                        $('#lista-reporte-por-objeto').DataTable({
                            language: i18nEspaniol,
                            dom: 'Blfrtip',
                            buttons: botonesExportacion,
                            retrieve: true
                        });

                        cancelarOperacion();
                        abrirModalReportesEspecificos();
                    },
                    error:function(error){
                        console.log(error.responseText)
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
                    }
                });
            }
            
        } else {
            parametros = {
                idPresupuesto: parseInt($('#Fecha').val()),
                idObjetoGasto: parseInt($('#Objetos').val()),
            };
        }
    }
}

const abrirModalReportesEspecificosDepto = () => {
    $('#opcion-departamentos').addClass('d-none');
    $('#contenedorCorrelativos').addClass('d-none');
    $.ajax(`${ API }/pacc/genera-lista-anios-presupuestos.php`, {
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        success:function (response) {
            const { data } = response;
            $('#Fechas').html(`<option value="">Seleccione el año</option>`);
            for(let i=0; i<data.length; i++) {
                $('#Fechas').append(`<option value="${ data[i].idControlPresupuestoActividad }">${ data[i].anio }</option>`);
            }
            $('#Fechas').select2({ width: '100%' });
            $('#reporteEspecificoCorrelativo').modal('show');
        },
        error:function(error){
            console.log(error.responseText)
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
        }
    });
} 

const generaCorrelativosActividad = () => {
    if ($('#Fechas').val() != "") {
        let parametros = {
            idPresupuesto: parseInt($('#Fechas').val())
        };
        $.ajax(`${ API }/pacc/generar-correlativos-por-anio.php`, {
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(parametros),
            success:function (response) {
                const { data } = response;
                $('#Correlativos').html(`<option value="">Seleccione el correlativo de la actividad para ver el costo</option>`);
                for(let i=0; i<data.length; i++) {
                    $('#Correlativos').append(`<option value="${ data[i].idActividad }">${ data[i].correlativoActividad }</option>`);
                }
                $('#Correlativos').select2({ width: '100%' });
                $('#contenedorCorrelativos').removeClass('d-none');
            },
            error:function(error){
                console.log(error.responseText)
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
            }
        });
    } else {
        $('#Correlativos').html(`<option value="">Seleccione el correlativo de la actividad para ver el costo</option>`);
        $('#contenedorCorrelativos').addClass('d-none');
    }
    
}

const mostrarResultadosFiltroDepto = () => {
    if ($('#Fechas').val() === "") {
        Swal.fire({
            icon: 'error',
            title: 'Ops...',
            text: "Seleccione una fecha para poder avanzar en el calculo del costo por correlativo",
            footer: '<b>Por favor verifique el formulario de registro</b>'
        });
    } else if ($('#Correlativos').val() === "") {
        Swal.fire({
            icon: 'error',
            title: 'Ops...',
            text: "Seleccione un correlativo para poder avanzar en el calculo del costo por correlativo",
            footer: '<b>Por favor verifique el formulario de registro</b>'
        });
    } else {
        let parametros = {
            idActividad: parseInt($('#Correlativos').val())
        };
        $.ajax(`${ API }/pacc/generar-costo-correlativo.php`, {
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(parametros),
            success:function (response) {
                const { data } = response;
                console.log(data);
                $('#lista-reporte-por-correlativo').dataTable().fnDestroy();
                    if (data != null) {
                    $('#lista-reporte-por-correlativo tbody').html(``);
                    for (let i=0;i<data.length; i++) {
                        $('#lista-reporte-por-correlativo tbody').append(`
                            <tr>
                                <td scope="row" class="text-center">${ data[i].correlativoActividad }</td>
                                <td scope="row" class="text-center">${ data[i].nombreDepartamento }</td>
                                <td scope="row" class="text-center">${ data[i].costoTotal }</td>
                            </tr>
                        `)
                    }
                } else {
                    $('#lista-reporte-por-correlativo tbody').html(``);
                }
                $('#lista-reporte-por-correlativo').DataTable({
                    language: i18nEspaniol,
                    dom: 'Blfrtip',
                    buttons: botonesExportacion,
                    retrieve: true
                });
                cancelarOperacionCorrelativo();
            },
            error:function(error){
                console.log(error.responseText)
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
            }
        });
    }
}

const cancelarOperacionCorrelativo = () => {
    $('#contenedorCorrelativos').addClass('d-none');
    $('#Fechas').val("");
    $('#Correlativos').val("");
}
