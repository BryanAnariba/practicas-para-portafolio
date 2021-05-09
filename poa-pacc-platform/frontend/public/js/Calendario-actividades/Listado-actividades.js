var arregloActividades = [];

var idDepartamento=null;
var Mes=null;

$(document).ready(function (){
    if(Usuario['abrevTipoUsuario']=='J_D'||Usuario['abrevTipoUsuario']=='C_C'){
        idDepartamento=Usuario['idDepartamento'];
    }else if(Usuario['abrevTipoUsuario']=='D_F'||Usuario['abrevTipoUsuario']=='SE_AD'||Usuario['abrevTipoUsuario']=='U_E'){
        cambiarDepa();
        $('#modalSeleccionDepart').on('shown.bs.modal', function () {
            cambiarDepa();
        })
    }

    LlenarDimensionesEstrategicas();
    LlenarAnios();

    var element = document.querySelector("#Departamento");

    element.addEventListener('focus', function () {
        this.size=5;
    });
    element.addEventListener('change', function () {
        this.size=1;
        this.blur();
    });
    element.addEventListener('blur', function () {
        this.size=1;
    });

    $('#modalesActividad').on('hidden.bs.modal', function () {
        $('#modalVerMas').modal('show');
        $('body').addClass('modal-open');
    })
    $('#modalRegistros').on('hidden.bs.modal', function () {
        $('#modalesActividad').modal('show');
        $('body').addClass('modal-open');
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
                var element = document.querySelector("#DimensionAdministrativa");
                element.addEventListener('focus', function () {
                    this.size=5;
                });
                element.addEventListener('change', function () {
                    this.size=1;
                    this.blur();
                });
                element.addEventListener('blur', function () {
                    this.size=1;
                });
                LlenarMeses();
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
    })
    $('#modalRegistosNoT').on('hidden.bs.modal', function () {
        $('#modalVerMas').modal('show');
        $('body').addClass('modal-open');
    })
    $('#modalVerMasAct').on('shown.bs.modal', function () {
        $('body').addClass('modal-open');
    })
    $('#modalVerMasAct').on('hidden.bs.modal', function () {
        $('body').addClass('modal-open');
    })
});

const LlenarDimensionesEstrategicas = () =>{
    $.ajax(`${ API }/calendario-actividades/obtener-dimensiones-estrategicas.php`, {
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        success:function(response) {
            const { data } = response;
            $('#DimensionesListado').dataTable().fnDestroy();
            $('#DimensionesListado tbody').html('');
            for (let i=0;i<data.length; i++) {
                $('#DimensionesListado tbody').append(`
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
            $('#DimensionesListado').DataTable({
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
};

const verActividades = (idDimension,dimensionEstrategica) =>{
    $('#modalActividadesDimensionEstrategica').modal('show');
    $("#ActividadesDimensionEstrategicaLabel").html(`${dimensionEstrategica}`);
    if(idDepartamento==null){
        AgregarArreglo(idDimension);
    }else{
        MostrarPorDepa(idDimension);
    }
};

const LlenarAnios = () =>{
    $.ajax(`${ API }/calendario-actividades/obtener-anio-presupuesto.php`, {
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        success:function(response) {
            const { data } = response;
            var today = new Date();
            var year = today.getFullYear();

            for(let i = 0; i < data.length;i++){
                if(data[i]['year(fechaPresupuestoAnual)']==year){
                    document.getElementById("PorAnio").innerHTML+=`<option value="${data[i]['year(fechaPresupuestoAnual)']}" selected>${data[i]['year(fechaPresupuestoAnual)']}</option>`;
                }else{
                    document.getElementById("PorAnio").innerHTML+=`<option value="${data[i]['year(fechaPresupuestoAnual)']}">${data[i]['year(fechaPresupuestoAnual)']}</option>`;
                }
            }
            var element = document.querySelector("#PorAnio");

            element.addEventListener('focus', function () {
                this.size=5;
            });
            element.addEventListener('change', function () {
                this.size=1;
                this.blur();
            });
            element.addEventListener('blur', function () {
                this.size=1;
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
const LlenarMeses = () =>{
    var Meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
    
    document.getElementById("PorMes").innerHTML=`<option value="" selected>Mostrar todos</option>`;

    for(let i = 0; i < Meses.length;i++){
        document.getElementById("PorMes").innerHTML+=`<option value="${Meses[i]}">${Meses[i]}</option>`;
    }

    var element = document.querySelector("#PorMes");

    element.addEventListener('focus', function () {
        this.size=5;
    });
    element.addEventListener('change', function () {
        this.size=1;
        this.blur();
    });
    element.addEventListener('blur', function () {
        this.size=1;
    });
}
const GuardarAnio = () => {
    Swal.fire({
        icon: 'success',
        title: `Mostrando dimensiones respecto al año: ${document.querySelector("#PorAnio").value}`
    });
}
const GuardarIdDepaDepa = () => {
    idDepartamento=document.querySelector("#Departamento").value;
    departamento=$('select[name="Departamento"] option:selected').text();
    $("#modalSeleccionDepart").modal('toggle');
    Swal.fire({
        icon: 'success',
        title: `Mostrando dimensiones respecto al departartamento ${departamento}`
    });
}
const EliminarIdDepaDepa = () => {
    idDepartamento=null;
    Swal.fire({
        icon: 'success',
        title: `Mostrando dimensiones respecto a todos los departartamentos`
    });
}
const MostrarPorDepa = (idDimension) => {
    var peticion = {
        Depa: Usuario['idDepartamento'],
        idDimension:idDimension,
        Anio:document.querySelector("#PorAnio").value
    };
    switch(Usuario['abrevTipoUsuario']) {
        case 'C_C':
            $.ajax(`${ API }/calendario-actividades/obtenerActividadesPorDepa.php`, {
                type: 'POST',
                dataType: 'json',
                data: (peticion),
                success:function(response) {
                    const { status, data } = response;
                    arregloActividades=data;
                    TablaInicio();
                
                },
                error:function(error) {
                    console.warn(error);
                    const { status, data } = error.responseJSON;
                    if (status === 401) {
                        window.location.href = '../views/401.php';
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Ops...',
                        text: `${ data.message }`
                    });
                }
            });
            break;
        case 'J_D':
                $.ajax(`${ API }/calendario-actividades/obtenerActividadesPorDepa.php`, {
                    type: 'POST',
                    dataType: 'json',
                    data: (peticion),
                    success:function(response) {
                        const { status, data } = response;
                        arregloActividades=data;
                        TablaInicio();
                    
                    },
                    error:function(error) {
                        console.warn(error);
                        const { status, data } = error.responseJSON;
                        if (status === 401) {
                            window.location.href = '../views/401.php';
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Ops...',
                            text: `${ data.message }`
                        });
                    }
                });
                break;
        case 'U_E':
            if(document.querySelector("#Departamento").value!=''){
                idDepartamento=document.querySelector("#Departamento").value;
                const peticion = {
                    Depa: document.querySelector("#Departamento").value,
                    idDimension:idDimension,
                    Anio:document.querySelector("#PorAnio").value
                };
                $.ajax(`${ API }/calendario-actividades/obtenerActividadesPorDepa.php`, {
                    type: 'POST',
                    dataType: 'json',
                    data: (peticion),
                    success:function(response) {
                        const { status, data } = response;
                        arregloActividades=data;
                        TablaInicio();
                    },
                    error:function(error) {
                        console.warn(error);
                        const { status, data } = error.responseJSON;
                        if (status === 401) {
                            window.location.href = '../views/401.php';
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Ops...',
                            text: `${ data.message }`
                        });
                    }
                });
            }else{
                AgregarArreglo();
            }
          break;
        case 'D_F':
            if(document.querySelector("#Departamento").value!=''){
                idDepartamento=document.querySelector("#Departamento").value;
                const peticion = {
                    Depa: document.querySelector("#Departamento").value,
                    idDimension:idDimension,
                    Anio:document.querySelector("#PorAnio").value
                };
                $.ajax(`${ API }/calendario-actividades/obtenerActividadesPorDepa.php`, {
                    type: 'POST',
                    dataType: 'json',
                    data: (peticion),
                    success:function(response) {
                        const { status, data } = response;
                        arregloActividades=data;
                        TablaInicio();
                    },
                    error:function(error) {
                        console.warn(error);
                        const { status, data } = error.responseJSON;
                        if (status === 401) {
                            window.location.href = '../views/401.php';
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Ops...',
                            text: `${ data.message }`
                        });
                    }
                });
            }else{
                AgregarArreglo();
            }
          break;
        case 'SE_AD':
            if(document.querySelector("#Departamento").value!=''){
                idDepartamento=document.querySelector("#Departamento").value;
                const peticion = {
                    Depa: document.querySelector("#Departamento").value,
                    idDimension:idDimension,
                    Anio:document.querySelector("#PorAnio").value
                };
                $.ajax(`${ API }/calendario-actividades/obtenerActividadesPorDepa.php`, {
                    type: 'POST',
                    dataType: 'json',
                    data: (peticion),
                    success:function(response) {
                        const { status, data } = response;
                        arregloActividades=data;
                        TablaInicio();
                    },
                    error:function(error) {
                        console.warn(error);
                        const { status, data } = error.responseJSON;
                        if (status === 401) {
                            window.location.href = '../views/401.php';
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Ops...',
                            text: `${ data.message }`
                        });
                    }
                });
            }else{
                AgregarArreglo();
            }
          break;
        default:
            console.error("opcion no disponible");
          break;
    }
    LlenarMeses();
};

const cambiarDepa = () => {
    $.ajax(`${ API }/Carreras/obtenerDepartamentos.php`, {
        type: 'POST',
        dataType: 'json',
        success:function(response) {
            if(idDepartamento!=null){
                document.getElementById("Departamento").innerHTML="<option value='' disabled>Mostar todos</option>";
                for(let i = 0; i < response.data.length;i++){
                    if(idDepartamento==response.data[i].idDepartamento){
                        document.getElementById("Departamento").innerHTML+=`<option value="${response.data[i].idDepartamento}" selected>${response.data[i].nombreDepartamento}</option>`;
                    }else{
                        document.getElementById("Departamento").innerHTML+=`<option value="${response.data[i].idDepartamento}">${response.data[i].nombreDepartamento}</option>`;
                    }
                }
            }else{
                document.getElementById("Departamento").innerHTML="<option value='' disabled selected>Mostar todos</option>";
                for(let i = 0; i < response.data.length;i++){
                    document.getElementById("Departamento").innerHTML+=`<option value="${response.data[i].idDepartamento}">${response.data[i].nombreDepartamento}</option>`;
                }
            }
        },
        error:function(error) {
            const { status, data } = error.responseJSON;
            if (status === 401) {
                window.location.href = '../views/401.php';
            }
            Swal.fire({
                icon: 'error',
                title: 'Ops...',
                text: `${ data.message }`
            });
        }
    });
};

const AgregarArreglo = (idDimension) => {
    const Dimension = {
        idDimension: idDimension,
        Anio:document.querySelector("#PorAnio").value
    };
    switch(Usuario['abrevTipoUsuario']) {
        case 'U_E':
            $.ajax(`${ API }/calendario-actividades/obtener-actividades.php`, {
                type: 'POST',
                dataType: 'json',
                data: (Dimension),
                success:function(response) {
                    const { status, data } = response;
                    arregloActividades=data;
                    TablaInicio();

                },
                error:function(error) {
                    console.warn(error);
                    const { status, data } = error.responseJSON;
                    if (status === 401) {
                        window.location.href = '../views/401.php';
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Ops...',
                        text: `${ data.message }`
                    });
                }
            });
          break;
        case 'D_F':
            $.ajax(`${ API }/calendario-actividades/obtener-actividades.php`, {
                type: 'POST',
                dataType: 'json',
                data: (Dimension),
                success:function(response) {
                    const { status, data } = response;
                    arregloActividades=data;
                    TablaInicio();

                },
                error:function(error) {
                    console.warn(error);
                    const { status, data } = error.responseJSON;
                    if (status === 401) {
                        window.location.href = '../views/401.php';
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Ops...',
                        text: `${ data.message }`
                    });
                }
            });
          break;
        case 'SE_AD':
            $.ajax(`${ API }/calendario-actividades/obtener-actividades.php`, {
                type: 'POST',
                dataType: 'json',
                data: (Dimension),
                success:function(response) {
                    const { status, data } = response;
                    arregloActividades=data;
                    TablaInicio();

                },
                error:function(error) {
                    console.warn(error);
                    const { status, data } = error.responseJSON;
                    if (status === 401) {
                        window.location.href = '../views/401.php';
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Ops...',
                        text: `${ data.message }`
                    });
                }
            });
          break;
        default:
            console.error("opcion no disponible");
            break;
      }
    idDepartamento=null;
    Mes=null;
    cambiarDepa();
    LlenarMeses();
};
const TablaInicio = () => {    
    switch(Usuario['abrevTipoUsuario']) {
        case 'C_C':
            $('#ActividadesListado').dataTable().fnDestroy();
            $('#ActividadesListado tbody').html(``);
            for (let i=0;i<arregloActividades.length; i++) {
                $('#ActividadesListado tbody').append(`
                    <tr align="center">
                        <td scope="row">${ i + 1 }</td>
                        <td>${ arregloActividades[i].correlativoActividad }</td>
                        <td>${ arregloActividades[i].nombreDepartamento }</td>
                        <td align="justify">${ arregloActividades[i].actividad }</td>
                        <td align="justify">${ arregloActividades[i].TipoActividad }</td>
                        <td>${ arregloActividades[i].NumeroDeActividadesDefinidas }</td>
                        <td>
                            <button type="button" class="btn btn-amber" onclick="VerMas(${arregloActividades[i].idActividad})">
                                <img src="../img/menu/ver-icon.svg" alt="modificar dimension"/>
                            </button>
                        </td>
                    </tr>
                `)
            };
          break;
        case 'J_D':
            $('#ActividadesListado').dataTable().fnDestroy();
            $('#ActividadesListado tbody').html(``);
            for (let i=0;i<arregloActividades.length; i++) {
                $('#ActividadesListado tbody').append(`
                    <tr align="center">
                        <td scope="row">${ i + 1 }</td>
                        <td>${ arregloActividades[i].correlativoActividad }</td>
                        <td>${ arregloActividades[i].nombreDepartamento }</td>
                        <td align="justify">${ arregloActividades[i].actividad }</td>
                        <td align="justify">${ arregloActividades[i].TipoActividad }</td>
                        <td>${ arregloActividades[i].NumeroDeActividadesDefinidas }</td>
                        <td>
                            <button type="button" class="btn btn-amber" onclick="VerMas(${arregloActividades[i].idActividad})">
                                <img src="../img/menu/ver-icon.svg" alt="modificar dimension"/>
                            </button>
                        </td>
                    </tr>
                `)
            };
          break;
        case 'SE_AD':
            $('#ActividadesListado thead').html(`
                <tr align="center">
                    <th scope="col">#</th>
                    <th scope="col">Correlativo</th>
                    <th scope="col">Departamento</th>
                    <th scope="col">Tema Actividad</th>
                    <th scope="col">Tipo Actividad</th>
                    <th scope="col"># Actividades definidas</th>
                    <th scope="col">ver mas</th>
                </tr>`
            );
            $('#ActividadesListado').dataTable().fnDestroy();
            $('#ActividadesListado tbody').html(``);

            for (let i=0;i<arregloActividades.length; i++) {
                $('#ActividadesListado tbody').append(`
                    <tr align="center">
                        <td scope="row">${ i + 1 }</td>
                        <td>${ arregloActividades[i].correlativoActividad }</td>
                        <td>${ arregloActividades[i].nombreDepartamento }</td>
                        <td align="justify">${ arregloActividades[i].actividad }</td>
                        <td align="justify">${ arregloActividades[i].TipoActividad }</td>
                        <td>${ arregloActividades[i].NumeroDeActividadesDefinidas }</td>
                        <td>
                            <button type="button" class="btn btn-amber" onclick="VerMas(${arregloActividades[i].idActividad})">
                                <img src="../img/menu/ver-icon.svg" alt="modificar dimension"/>
                            </button>
                        </td>
                    </tr>
                `)
            };
          break;
        case 'D_F':
            $('#ActividadesListado thead').html(`
                <tr align="center">
                    <th scope="col">#</th>
                    <th scope="col">Correlativo</th>
                    <th scope="col">Departamento</th>
                    <th scope="col">Tema Actividad</th>
                    <th scope="col">Tipo Actividad</th>
                    <th scope="col"># Actividades definidas</th>
                    <th scope="col">ver mas</th>
                </tr>`
            );
            $('#ActividadesListado').dataTable().fnDestroy();
            $('#ActividadesListado tbody').html(``);

            for (let i=0;i<arregloActividades.length; i++) {
                $('#ActividadesListado tbody').append(`
                    <tr align="center">
                        <td scope="row">${ i + 1 }</td>
                        <td>${ arregloActividades[i].correlativoActividad }</td>
                        <td>${ arregloActividades[i].nombreDepartamento }</td>
                        <td align="justify">${ arregloActividades[i].actividad }</td>
                        <td align="justify">${ arregloActividades[i].TipoActividad }</td>
                        <td>${ arregloActividades[i].NumeroDeActividadesDefinidas }</td>
                        <td>
                            <button type="button" class="btn btn-amber" onclick="VerMas(${arregloActividades[i].idActividad})">
                                <img src="../img/menu/ver-icon.svg" alt="modificar dimension"/>
                            </button>
                        </td>
                    </tr>
                `)
            };
          break;
        case 'U_E':
            $('#ActividadesListado thead').html(`
                <tr align="center">
                    <th scope="col">#</th>
                    <th scope="col">Correlativo</th>
                    <th scope="col">Departamento</th>
                    <th scope="col">Tema Actividad</th>
                    <th scope="col">Tipo Actividad</th>
                    <th scope="col"># Actividades definidas</th>
                    <th scope="col">ver mas</th>
                </tr>`
            );
            $('#ActividadesListado').dataTable().fnDestroy();
            $('#ActividadesListado tbody').html(``);

            for (let i=0;i<arregloActividades.length; i++) {
                $('#ActividadesListado tbody').append(`
                    <tr align="center">
                        <td scope="row">${ i + 1 }</td>
                        <td>${ arregloActividades[i].correlativoActividad }</td>
                        <td>${ arregloActividades[i].nombreDepartamento }</td>
                        <td align="justify">${ arregloActividades[i].actividad }</td>
                        <td align="justify">${ arregloActividades[i].TipoActividad }</td>
                        <td>${ arregloActividades[i].NumeroDeActividadesDefinidas }</td>
                        <td>
                            <button type="button" class="btn btn-amber" onclick="VerMas(${arregloActividades[i].idActividad})">
                                <img src="../img/menu/ver-icon.svg" alt="modificar dimension"/>
                            </button>
                        </td>
                    </tr>
                `)
            };
          break;
        default:
            console.error("opcion no disponible");
            break;
      }
      $('#ActividadesListado').DataTable({
        language: i18nEspaniol,
        retrieve: true
    });
};
const VerMas = (idActividad) =>{
    $('#modalVerMas').modal('show');

    const peticion = {
        idActividad: idActividad
    };
    $.ajax(`${ API }/calendario-actividades/obtener-actividades-por-Id.php`, {
        type: 'POST',
        dataType: 'json',
        data: (peticion),
        success:function(response) {
            const { status, data } = response;
            $('#correlativo').val(data[0].correlativoActividad);
            $('#Dep').val(data[0].nombreDepartamento);
            $('#DimEst').val(data[0].dimensionEstrategica);
            $('#ObjInst').val(data[0].objetivoInstitucional);
            $('#AreaEst').val(data[0].areaEstrategica);
            $('#TemaAct').val(data[0].actividad);
            $('#TipoAct').val(data[0].TipoActividad);
            $('#NumAct').val(data[0].NumeroDeActividadesDefinidas);
            $("#ResUn").attr("onclick",`llenar('resultado',${data[0].idActividad},'${data[0].actividad}')`);
            $("#justificacion").attr("onclick",`llenar('justificacion',${data[0].idActividad},'${data[0].actividad}')`);
            $("#presupuesto").attr("onclick",`llenar('presupuesto',${data[0].idActividad},'${data[0].actividad}')`);
            $("#actividades").attr("onclick",`llenar('actividadesAntes',${data[0].idActividad},'${data[0].actividad}')`);
            $("#actividadesAdminDescrip").attr("onclick",`llenar('actividades',${data[0].idActividad},'${data[0].actividad}')`);
        },
        error:function(error) {
            console.warn(error);
            const { status, data } = error.responseJSON;
            if (status === 401) {
                window.location.href = '../views/401.php';
            }
            Swal.fire({
                icon: 'error',
                title: 'Ops...',
                text: `${ data.message }`
            });
        }
    });
};

// Llenado modales de visualizacion

const llenar = (vista,idActividad,nombreActividad) =>{

    const peticion = {
        idActividad: idActividad
    };

    setTimeout(() => {
        $.when(
            $.ajax(`${ API }/calendario-actividades/getInfoActPorId.php`, {
                type: 'POST',
                dataType: 'json',
                data: (peticion),
            }),
            $.ajax(`${ API }/calendario-actividades/obtener-actividades-planif.php`, {
                type: 'POST',
                dataType: 'json',
                data: (peticion),
            }))
            .done(function(InfoAct, Actividad) {
                switch(vista) {
                    case 'resultado':
                        $('#modalRegistosNoT').modal('show');
                        $('.espacioLL').html(`  <div class="form-group d-flex row">
                                                    <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                                        <h5 class="form-control">Resultados Institucionales:</h5>
                                                    </div>
                                                    <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                                        <textarea 
                                                            type="text" 
                                                            id="ResultadosInstitucionales" 
                                                            class="form-control" 
                                                            align="justify"
                                                            readonly
                                                        ></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group d-flex row">
                                                    <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                                        <h5 class="form-control">Resultados de la unidad:</h5>
                                                    </div>
                                                    <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                                        <textarea 
                                                            type="text" 
                                                            id="ResultadosU" 
                                                            class="form-control" 
                                                            align="justify"
                                                            readonly
                                                        ></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group d-flex row">
                                                    <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                                        <h5 class="form-control">Indicador de resultados:</h5>
                                                    </div>
                                                    <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                                        <textarea 
                                                            type="text" 
                                                            id="Indicador" 
                                                            class="form-control" 
                                                            align="justify"
                                                            readonly
                                                        ></textarea>
                                                    </div>
                                                </div>`);
                        $("#RegistosNoTLabel").html('Resultados de la unidad');
                        $("#ResultadosInstitucionales").html(InfoAct[0].data[0].resultadoInstitucional);
                        $("#ResultadosU").html(InfoAct[0].data[0].resultadosUnidad);
                        $("#Indicador").html(InfoAct[0].data[0].indicadoresResultado);
                    break;
                    case 'justificacion':
                        $('#modalRegistosNoT').modal('show');
                        $('.espacioLL').html(`  <div class="form-group d-flex row">
                                                    <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                                        <h5 class="form-control">Justificacion:</h5>
                                                    </div>
                                                    <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                                        <textarea 
                                                            type="text" 
                                                            id="Justificacion" 
                                                            class="form-control" 
                                                            value="2" 
                                                            align="justify"
                                                            readonly
                                                        ></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group d-flex row">
                                                    <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                                        <h5 class="form-control">Medios de verificacion:</h5>
                                                    </div>
                                                    <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                                        <textarea 
                                                            type="text" 
                                                            id="MediosdeV" 
                                                            class="form-control" 
                                                            value="2" 
                                                            align="justify"
                                                            readonly
                                                        ></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group d-flex row">
                                                    <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                                        <h5 class="form-control">Poblacion Objetivo:</h5>
                                                    </div>
                                                    <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                                        <textarea 
                                                            type="text" 
                                                            id="PoblacionObjetivo" 
                                                            class="form-control" 
                                                            value="2" 
                                                            align="justify"
                                                            readonly
                                                        ></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group d-flex row">
                                                    <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                                        <h5 class="form-control">Responsable:</h5>
                                                    </div>
                                                    <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                                        <textarea 
                                                            type="text" 
                                                            id="Responsable" 
                                                            class="form-control" 
                                                            value="2" 
                                                            align="justify"
                                                            readonly
                                                        ></textarea>
                                                    </div>
                                                </div>`);
                        $("#RegistosNoTLabel").html('Justificacion de la actividad');
                        $("#Justificacion").html(InfoAct[0].data[0].justificacionActividad);
                        $("#MediosdeV").html(InfoAct[0].data[0].medioVerificacionActividad);
                        $("#PoblacionObjetivo").html(InfoAct[0].data[0].poblacionObjetivoActividad);
                        $("#Responsable").html(InfoAct[0].data[0].responsableActividad);
                    break;
                    case 'presupuesto':
                        $('#modalRegistosNoT').modal('show');
                        $('.espacioLL').html(`<div class="row text-center">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group d-flex row">
                                <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                    <h5 class="form-control">Monto total planificado:</h5>
                                </div>
                                <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                    <input type="number" id="MontoT" class="form-control" readonly disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row text-center">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group d-flex row">
                                <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                    <h5 class="form-control">Primer Trimestre:</h5>
                                </div>
                                <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                    <input type="text" id="MontoT1" class="form-control" readonly disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group d-flex row">
                                <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                    <h5 class="form-control">Segundo Trimestre:</h5>
                                </div>
                                <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                    <input type="text" id="MontoT2" class="form-control" readonly disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group d-flex row">
                                <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                    <h5 class="form-control">Tercer Trimestre:</h5>
                                </div>
                                <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                    <input type="text" id="MontoT3" class="form-control" readonly disabled>
                                </div>
                            </div>
                        </div>
            
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group d-flex row">
                                <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                    <h5 class="form-control">Cuarto Trimestre:</h5>
                                </div>
                                <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                    <input type="text" id="MontoT4" class="form-control" readonly disabled>
                                </div>
                            </div>
                        </div>
                    </div>`);
                    $("#RegistosNoTLabel").html('Presupuesto de la actividad');
                    $("#MontoT").val(InfoAct[0].data[0].CostoTotal).trigger("change");
                    $("#MontoT1").val(InfoAct[0].data[0].Trimestre1).trigger("change");
                    $("#MontoT2").val(InfoAct[0].data[0].Trimestre2).trigger("change");
                    $("#MontoT3").val(InfoAct[0].data[0].Trimestre3).trigger("change");
                    $("#MontoT4").val(InfoAct[0].data[0].Trimestre4).trigger("change");
                    break;
                    case 'actividades':
                        $('#modalRegistros').modal('show');
                        $('#RegistrosTodos').dataTable().fnDestroy();
                        $('#RegistrosTodos tbody').html(``);
                        $('#myModalLabel').html("Listado de actividades correspondientes");
                        $('#RegistrosTodos thead').html(`<tr align="center">
                            <th scope="col">#</th>
                            <th scope="col">Actividad</th>
                            <th scope="col">Costo Total</th>
                            <th scope="col">Mes requerido</th>
                            <th scope="col">Ver Mas</th>
                        </tr>`);
                        if(document.querySelector("#PorMes").value!='' && document.querySelector("#DimensionAdministrativa").value!=''){
                            for (let i=0;i<Actividad[0].data.length; i++) {
                                if(Actividad[0].data[i].mesRequerido==document.querySelector("#PorMes").value && Actividad[0].data[i].idDimensionAdministrativa==document.querySelector("#DimensionAdministrativa").value){
                                    $('#RegistrosTodos tbody').append(`
                                        <tr align="center">
                                            <td scope="row">${ i + 1 }</td>
                                            <td>${ Actividad[0].data[i].nombreActividad }</td>
                                            <td>${ Actividad[0].data[i].costoTotal }</td>    
                                            <td>${ Actividad[0].data[i].mesRequerido }</td>
                                            <td>
                                                <button type="button" class="btn btn-amber" onclick="VerMasAct(${ Actividad[0].data[i].idDescripcionAdministrativa })">
                                                    <img src="../img/menu/ver-icon.svg" alt="modificar dimension"/>
                                                </button>
                                            </td>     
                                        </tr>
                                    `)
                                }
                            }
                        }else if(document.querySelector("#PorMes").value!=''){
                            for (let i=0;i<Actividad[0].data.length; i++) {
                                if(Actividad[0].data[i].mesRequerido==document.querySelector("#PorMes").value){
                                    $('#RegistrosTodos tbody').append(`
                                        <tr align="center">
                                            <td scope="row">${ i + 1 }</td>
                                            <td>${ Actividad[0].data[i].nombreActividad }</td>
                                            <td>${ Actividad[0].data[i].costoTotal }</td>    
                                            <td>${ Actividad[0].data[i].mesRequerido }</td>
                                            <td>
                                                <button type="button" class="btn btn-amber" onclick="VerMasAct(${ Actividad[0].data[i].idDescripcionAdministrativa })">
                                                    <img src="../img/menu/ver-icon.svg" alt="modificar dimension"/>
                                                </button>
                                            </td>     
                                        </tr>
                                    `)
                                }
                            }
                        }else if(document.querySelector("#DimensionAdministrativa").value!=''){
                            for (let i=0;i<Actividad[0].data.length; i++) {
                                if(Actividad[0].data[i].idDimensionAdministrativa==document.querySelector("#DimensionAdministrativa").value){
                                    $('#RegistrosTodos tbody').append(`
                                        <tr align="center">
                                            <td scope="row">${ i + 1 }</td>
                                            <td>${ Actividad[0].data[i].nombreActividad }</td>
                                            <td>${ Actividad[0].data[i].costoTotal }</td>    
                                            <td>${ Actividad[0].data[i].mesRequerido }</td>
                                            <td>
                                                <button type="button" class="btn btn-amber" onclick="VerMasAct(${ Actividad[0].data[i].idDescripcionAdministrativa })">
                                                    <img src="../img/menu/ver-icon.svg" alt="modificar dimension"/>
                                                </button>
                                            </td>     
                                        </tr>
                                    `)
                                }
                            }
                        }else{
                            for (let i=0;i<Actividad[0].data.length; i++) {
                                $('#RegistrosTodos tbody').append(`
                                    <tr align="center">
                                        <td scope="row">${ i + 1 }</td>
                                        <td>${ Actividad[0].data[i].nombreActividad }</td>
                                        <td>${ Actividad[0].data[i].costoTotal }</td>    
                                        <td>${ Actividad[0].data[i].mesRequerido }</td>
                                        <td>
                                            <button type="button" class="btn btn-amber" onclick="VerMasAct(${ Actividad[0].data[i].idDescripcionAdministrativa })">
                                                <img src="../img/menu/ver-icon.svg" alt="modificar dimension"/>
                                            </button>
                                        </td>     
                                    </tr>
                                `)
                            }
                        }
                        $('#RegistosLabel').html('Actividades definidas '+ nombreActividad);
                    break;
                    case 'actividadesAntes':
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
                                var element = document.querySelector("#DimensionAdministrativa");

                                element.addEventListener('focus', function () {
                                    this.size=5;
                                });
                                element.addEventListener('change', function () {
                                    this.size=1;
                                    this.blur();
                                });
                                element.addEventListener('blur', function () {
                                    this.size=1;
                                });
                                LlenarMeses();
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
                    break;
                    default:
                        console.error("opcion no disponible");
                        break;
                };
                $('#RegistrosTodos').DataTable({
                    language: i18nEspaniol,
                    "lengthMenu": [[3, 6, 9, -1], [3, 6, 9, "All"]],
                    retrieve: true
                });
            })
            .fail(function(error) {
                console.warn(error);
                const { status, data } = error.responseJSON;
                if (status === 401) {
                    window.location.href = '../views/401.php';
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Ops...',
                    text: `${ data.message }`
                });
            });
    });

};

const VerMasAct = (idActividad) => {
    const peticion = {
        idActividad: parseInt(idActividad)
    };

    $.ajax(`${ API }/calendario-actividades/ObtenerActividadDimensionAdmin.php`,{
        type: 'POST',
        dataType: 'json',
        data: (peticion),
        success:function(response) {
            const { data } = response;
            
            switch (data[0].idDimensionAdministrativa) {
                case 1:
                    $('#modalVerMasAct').modal('show');

                        $("#verMasDimensionAdministrativa").html(`<div class="form-group d-flex row">
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <h5 class="form-control">Actividad:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <textarea 
                                    type="text" 
                                    id="actividad" 
                                    class="form-control"  
                                    align="justify"
                                    readonly
                                >${ data[0].nombreActividad }</textarea>
                            </div>
                        </div>
                        <div class="form-group d-flex row">
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <h5 class="form-control">Cantidad cantidadPersonas:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <input 
                                    type="text" 
                                    id="cantidad" 
                                    class="form-control"  
                                    value="${ JSON.parse(data[0].Descripcion).cantidadPersonas }"
                                    align="justify"
                                    readonly
                                >
                            </div>
                        </div>
                        <div class="form-group d-flex row">
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <h5 class="form-control">Cantidad:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <input 
                                    type="text" 
                                    id="cantidad" 
                                    class="form-control"  
                                    value="${ data[0].Cantidad }"
                                    align="justify"
                                    readonly
                                >
                            </div>
                        </div>
                        <div class="form-group d-flex row">
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <h5 class="form-control">Costo:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <input 
                                    type="text" 
                                    id="costo" 
                                    class="form-control" 
                                    value="${ data[0].Costo }" 
                                    align="justify"
                                    readonly
                                >
                            </div>
                        </div>
                        <div class="form-group d-flex row">
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <h5 class="form-control">Costo Total:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <input 
                                    type="text" 
                                    id="CostoTotal" 
                                    class="form-control"  
                                    value="${ data[0].CostoTotal }"
                                    align="justify"
                                    readonly
                                >
                            </div>
                        </div>
                        <div class="form-group d-flex row">
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <h5 class="form-control">Tipo de presupuesto:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <input
                                    type="text" 
                                    id="TipoDePresupuesto" 
                                    class="form-control"  
                                    value="${ data[0].tipoPresupuesto }"
                                    align="justify"
                                    readonly
                                >
                            </div>
                        </div>
                        <div class="form-group d-flex row">
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <h5 class="form-control">Objeto del gasto:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <input 
                                    type="text" 
                                    id="AbreviaturaActividadVerMas" 
                                    class="form-control"  
                                    value="${ data[0].ObjetoGasto }"
                                    align="justify"
                                    readonly
                                >
                            </div>
                        </div>
                        <div class="form-group d-flex row">
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <h5 class="form-control">Descripción de cuenta:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <textarea 
                                    type="text" 
                                    id="DescripciónDeCuenta" 
                                    class="form-control"  
                                    align="justify"
                                    readonly
                                >${ data[0].DescripcionCuenta }</textarea>
                            </div>
                        </div>
                        <div class="form-group d-flex row">
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <h5 class="form-control">Dimension Estrategica:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <textarea 
                                    type="text" 
                                    id="DimensionEstrategica" 
                                    class="form-control"  
                                    align="justify"
                                    readonly
                                >${ data[0].dimensionEstrategica }</textarea>
                            </div>
                        </div>
                        <div class="form-group d-flex row">
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <h5 class="form-control">Mes requerido:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <input 
                                    type="text" 
                                    id="MesRequerido" 
                                    class="form-control"  
                                    value="${ data[0].mesRequerido }"
                                    align="justify"
                                    readonly
                                >
                            </div>
                        </div>`);
                        $("#VerMasActLabel").html(`Informacion correspondiente a la actividad: ${ data[0].nombreActividad }`);
                break;
                case 2:
                    $('#modalVerMasAct').modal('show');

                    $("#verMasDimensionAdministrativa").html(`<div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Actividad:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <textarea 
                                type="text" 
                                id="actividad" 
                                class="form-control"  
                                align="justify"
                                readonly
                            >${ data[0].nombreActividad }</textarea>
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Cantidad:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="cantidad" 
                                class="form-control"  
                                value="${ data[0].Cantidad }"
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Meses:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="meses" 
                                class="form-control" 
                                value="${ JSON.parse(data[0].Descripcion).meses }" 
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Costo:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="costo" 
                                class="form-control" 
                                value="${ data[0].Costo }" 
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Costo Total:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="CostoTotal" 
                                class="form-control"  
                                value="${ data[0].CostoTotal }"
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Tipo de presupuesto:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input
                                type="text" 
                                id="TipoDePresupuesto" 
                                class="form-control"  
                                value="${ data[0].tipoPresupuesto }"
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Objeto del gasto:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="AbreviaturaActividadVerMas" 
                                class="form-control"  
                                value="${ data[0].ObjetoGasto }"
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Descripción de cuenta:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <textarea 
                                type="text" 
                                id="DescripciónDeCuenta" 
                                class="form-control"  
                                align="justify"
                                readonly
                            >${ data[0].DescripcionCuenta }</textarea>
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Dimension Estrategica:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <textarea 
                                type="text" 
                                id="DimensionEstrategica" 
                                class="form-control"  
                                align="justify"
                                readonly
                            >${ data[0].dimensionEstrategica }</textarea>
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Mes requerido:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="MesRequerido" 
                                class="form-control"  
                                value="${ data[0].mesRequerido }"
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>`);
                    $("#VerMasActLabel").html(`Informacion correspondiente a la actividad: ${ data[0].nombreActividad }`);

                break;
                case 3:
                    $('#modalVerMasAct').modal('show');

                    $("#verMasDimensionAdministrativa").html(`<div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Actividad:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <textarea 
                                type="text" 
                                id="actividad" 
                                class="form-control"  
                                align="justify"
                                readonly
                            >${ data[0].nombreActividad }</textarea>
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Cantidad:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="cantidad" 
                                class="form-control"  
                                value="${ data[0].Cantidad }"
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Costo:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="costo" 
                                class="form-control" 
                                value="${ data[0].Costo }" 
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Costo Total:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="CostoTotal" 
                                class="form-control"  
                                value="${ data[0].CostoTotal }"
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Tipo de presupuesto:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input
                                type="text" 
                                id="TipoDePresupuesto" 
                                class="form-control"  
                                value="${ data[0].tipoPresupuesto }"
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Objeto del gasto:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="AbreviaturaActividadVerMas" 
                                class="form-control"  
                                value="${ data[0].ObjetoGasto }"
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Descripción de cuenta:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <textarea 
                                type="text" 
                                id="DescripciónDeCuenta" 
                                class="form-control"  
                                align="justify"
                                readonly
                            >${ data[0].DescripcionCuenta }</textarea>
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Dimension Estrategica:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <textarea 
                                type="text" 
                                id="DimensionEstrategica" 
                                class="form-control"  
                                align="justify"
                                readonly
                            >${ data[0].dimensionEstrategica }</textarea>
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Mes requerido:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="MesRequerido" 
                                class="form-control"  
                                value="${ data[0].mesRequerido }"
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>`);
                    $("#VerMasActLabel").html(`Informacion correspondiente a la actividad: ${ data[0].nombreActividad }`);
                break;
                case 4:
                    $('#modalVerMasAct').modal('show');

                    $("#verMasDimensionAdministrativa").html(`<div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Actividad:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <textarea 
                                type="text" 
                                id="actividad" 
                                class="form-control"  
                                align="justify"
                                readonly
                            >${ data[0].nombreActividad }</textarea>
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Cantidad:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="cantidad" 
                                class="form-control"  
                                value="${ data[0].Cantidad }"
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Costo:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="costo" 
                                class="form-control" 
                                value="${ data[0].Costo }" 
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Costo Total:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="CostoTotal" 
                                class="form-control"  
                                value="${ data[0].CostoTotal }"
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Tipo de presupuesto:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input
                                type="text" 
                                id="TipoDePresupuesto" 
                                class="form-control"  
                                value="${ data[0].tipoPresupuesto }"
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Objeto del gasto:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="AbreviaturaActividadVerMas" 
                                class="form-control"  
                                value="${ data[0].ObjetoGasto }"
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Descripción de cuenta:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <textarea 
                                type="text" 
                                id="DescripciónDeCuenta" 
                                class="form-control"  
                                align="justify"
                                readonly
                            >${ data[0].DescripcionCuenta }</textarea>
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Dimension Estrategica:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <textarea 
                                type="text" 
                                id="DimensionEstrategica" 
                                class="form-control"  
                                align="justify"
                                readonly
                            >${ data[0].dimensionEstrategica }</textarea>
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Mes requerido:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="MesRequerido" 
                                class="form-control"  
                                value="${ data[0].mesRequerido }"
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Tipo de equipo tecnologico:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="tipoEquipoTecnologico" 
                                class="form-control"  
                                value="${ JSON.parse(data[0].Descripcion).tipoEquipoTecnologico }"
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>`);
                break;
                case 5:
                    $('#modalVerMasAct').modal('show');

                    $("#verMasDimensionAdministrativa").html(`<div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Actividad:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <textarea 
                                type="text" 
                                id="actividad" 
                                class="form-control"  
                                align="justify"
                                readonly
                            >${ data[0].nombreActividad }</textarea>
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Cantidad:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="cantidad" 
                                class="form-control"  
                                value="${ data[0].Cantidad }"
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Costo:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="costo" 
                                class="form-control" 
                                value="${ data[0].Costo }" 
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Costo Total:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="CostoTotal" 
                                class="form-control"  
                                value="${ data[0].CostoTotal }"
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Tipo de presupuesto:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input
                                type="text" 
                                id="TipoDePresupuesto" 
                                class="form-control"  
                                value="${ data[0].tipoPresupuesto }"
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Objeto del gasto:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="AbreviaturaActividadVerMas" 
                                class="form-control"  
                                value="${ data[0].ObjetoGasto }"
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Descripción de cuenta:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <textarea 
                                type="text" 
                                id="DescripciónDeCuenta" 
                                class="form-control"  
                                align="justify"
                                readonly
                            >${ data[0].DescripcionCuenta }</textarea>
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Dimension Estrategica:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <textarea 
                                type="text" 
                                id="DimensionEstrategica" 
                                class="form-control"  
                                align="justify"
                                readonly
                            >${ data[0].dimensionEstrategica }</textarea>
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Mes requerido:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="MesRequerido" 
                                class="form-control"  
                                value="${ data[0].mesRequerido }"
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>`);
                    $("#VerMasActLabel").html(`Informacion correspondiente a la actividad: ${ data[0].nombreActividad }`);
                break;
                case 6:
                    $('#modalVerMasAct').modal('show');

                    $("#verMasDimensionAdministrativa").html(`<div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Actividad:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <textarea 
                                type="text" 
                                id="actividad" 
                                class="form-control"  
                                align="justify"
                                readonly
                            >${ data[0].nombreActividad }</textarea>
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Cantidad:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="cantidad" 
                                class="form-control"  
                                value="${ data[0].Cantidad }"
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Costo:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="costo" 
                                class="form-control" 
                                value="${ data[0].Costo }" 
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Costo Total:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="CostoTotal" 
                                class="form-control"  
                                value="${ data[0].CostoTotal }"
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Tipo de presupuesto:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input
                                type="text" 
                                id="TipoDePresupuesto" 
                                class="form-control"  
                                value="${ data[0].tipoPresupuesto }"
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Objeto del gasto:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="AbreviaturaActividadVerMas" 
                                class="form-control"  
                                value="${ data[0].ObjetoGasto }"
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Descripción de cuenta:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <textarea 
                                type="text" 
                                id="DescripciónDeCuenta" 
                                class="form-control"  
                                align="justify"
                                readonly
                            >${ data[0].DescripcionCuenta }</textarea>
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Dimension Estrategica:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <textarea 
                                type="text" 
                                id="DimensionEstrategica" 
                                class="form-control"  
                                align="justify"
                                readonly
                            >${ data[0].dimensionEstrategica }</textarea>
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Mes requerido:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="MesRequerido" 
                                class="form-control"  
                                value="${ data[0].mesRequerido }"
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Area Beca:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="areaBeca" 
                                class="form-control"  
                                value="${ JSON.parse(data[0].Descripcion).areaBeca }"
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>`);
                break;
                case 7:
                    $('#modalVerMasAct').modal('show');

                    $("#verMasDimensionAdministrativa").html(`<div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Actividad:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <textarea 
                                type="text" 
                                id="actividad" 
                                class="form-control"  
                                align="justify"
                                readonly
                            >${ data[0].nombreActividad }</textarea>
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Cantidad:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="cantidad" 
                                class="form-control"  
                                value="${ data[0].Cantidad }"
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Costo:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="costo" 
                                class="form-control" 
                                value="${ data[0].Costo }" 
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Costo Total:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="CostoTotal" 
                                class="form-control"  
                                value="${ data[0].CostoTotal }"
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Tipo de presupuesto:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input
                                type="text" 
                                id="TipoDePresupuesto" 
                                class="form-control"  
                                value="${ data[0].tipoPresupuesto }"
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Objeto del gasto:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="AbreviaturaActividadVerMas" 
                                class="form-control"  
                                value="${ data[0].ObjetoGasto }"
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Descripción de cuenta:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <textarea 
                                type="text" 
                                id="DescripciónDeCuenta" 
                                class="form-control"  
                                align="justify"
                                readonly
                            >${ data[0].DescripcionCuenta }</textarea>
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Dimension Estrategica:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <textarea 
                                type="text" 
                                id="DimensionEstrategica" 
                                class="form-control"  
                                align="justify"
                                readonly
                            >${ data[0].dimensionEstrategica }</textarea>
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Mes requerido:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="MesRequerido" 
                                class="form-control"  
                                value="${ data[0].mesRequerido }"
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Proyecto:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="proyecto" 
                                class="form-control"  
                                value="${ JSON.parse(data[0].Descripcion).proyecto }"
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>`);
                break;
                case 8:
                    $('#modalVerMasAct').modal('show');

                    $("#verMasDimensionAdministrativa").html(`<div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Actividad:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <textarea 
                                type="text" 
                                id="actividad" 
                                class="form-control"  
                                align="justify"
                                readonly
                            >${ data[0].nombreActividad }</textarea>
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Cantidad:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="cantidad" 
                                class="form-control"  
                                value="${ data[0].Cantidad }"
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Costo:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="costo" 
                                class="form-control" 
                                value="${ data[0].Costo }" 
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Costo Total:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="CostoTotal" 
                                class="form-control"  
                                value="${ data[0].CostoTotal }"
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Tipo de presupuesto:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input
                                type="text" 
                                id="TipoDePresupuesto" 
                                class="form-control"  
                                value="${ data[0].tipoPresupuesto }"
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Objeto del gasto:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="AbreviaturaActividadVerMas" 
                                class="form-control"  
                                value="${ data[0].ObjetoGasto }"
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Descripción de cuenta:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <textarea 
                                type="text" 
                                id="DescripciónDeCuenta" 
                                class="form-control"  
                                align="justify"
                                readonly
                            >${ data[0].DescripcionCuenta }</textarea>
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Dimension Estrategica:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <textarea 
                                type="text" 
                                id="DimensionEstrategica" 
                                class="form-control"  
                                align="justify"
                                readonly
                            >${ data[0].dimensionEstrategica }</textarea>
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Mes requerido:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="MesRequerido" 
                                class="form-control"  
                                value="${ data[0].mesRequerido }"
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Descripcion item:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="descripcionItem" 
                                class="form-control"  
                                value="${ JSON.parse(data[0].Descripcion).descripcionItem }"
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Cantidad item:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="cantidadItem" 
                                class="form-control"  
                                value="${ JSON.parse(data[0].Descripcion).cantidadItem }"
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Precio item:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="precioItem" 
                                class="form-control"  
                                value="${ JSON.parse(data[0].Descripcion).precioItem }"
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Valor uno:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="valorUno" 
                                class="form-control"  
                                value="${ JSON.parse(data[0].Descripcion).valorUno }"
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    <div class="form-group d-flex row">
                        <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <h5 class="form-control">Valor dos:</h5>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <input 
                                type="text" 
                                id="valorDos" 
                                class="form-control"  
                                value="${ JSON.parse(data[0].Descripcion).valorDos }"
                                align="justify"
                                readonly
                            >
                        </div>
                    </div>
                    `);
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