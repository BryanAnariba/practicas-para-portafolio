var obj = new Array();
var objeto= new Array();
$(document).ready(function(){
    if(Usuario['abrevTipoUsuario']=='J_D'||Usuario['abrevTipoUsuario']=='C_C'){
        idDepartamento=Usuario['idDepartamento'];
    }else if(Usuario['abrevTipoUsuario']=='D_F'||Usuario['abrevTipoUsuario']=='SE_AD'||Usuario['abrevTipoUsuario']=='U_E'){
        cambiarDepa();
        $('#modalSeleccionDepart').on('shown.bs.modal', function () {
            cambiarDepa();
        })
    }
});

const cambiarDepa = () => {
    const peticion = {
        nada: ""
    };
    $.ajax(`${ API }/Carreras/obtenerDepartamentos.php`, {
        type: 'POST',
        dataType: 'json',
        data: (peticion),
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
const GuardarIdDepaDepa = () => {
    idDepartamento=document.querySelector("#Departamento").value;
    departamento=$('select[name="Departamento"] option:selected').text();
    $("#modalSeleccionDepart").modal('toggle');
    Swal.fire({
        icon: 'success',
        title: `Mostrando dimensiones respecto al departartamento ${departamento}`
    });
    $('#calendar').fullCalendar('destroy');
    calendario();
}
const EliminarIdDepaDepa = () => {
    idDepartamento=null;
    Swal.fire({
        icon: 'success',
        title: `Mostrando dimensiones respecto a todos los departartamentos`
    });
    $('#calendar').fullCalendar('destroy');
    calendario();
}

const Adjuntar = (idDimensionAdministrativa,anio,idDescripcionAdministrativa,nombreActividad,mes) => {
    const fecha = ElegirMes(mes,anio);
    var actividad={};
    switch(idDimensionAdministrativa) {
        case 1:
            actividad = {
                id : idDescripcionAdministrativa,
                title  : `${nombreActividad}`,
                start  : `${fecha.start}`,
                end  : `${fecha.end}`,
                color: 'rgba(0, 128, 0, 0.37)',
                textColor: 'white'
            };
            return actividad;
        break;
        case 2:
            actividad = {
                id : idDescripcionAdministrativa,
                title  : `${nombreActividad}`,
                start  : `${fecha.start}`,
                end  : `${fecha.end}`,
                color: 'rgba(255, 0, 0, 0.37)',
                textColor: 'white'
            };
            return actividad;
        break;
        case 3:
            actividad = {
                id : idDescripcionAdministrativa,
                title  : `${nombreActividad}`,
                start  : `${fecha.start}`,
                end  : `${fecha.end}`,
                color: '#1a237e',
                textColor: 'white'
            };
            return actividad;
        break;
        case 4:
            actividad = {
                id : idDescripcionAdministrativa,
                title  : `${nombreActividad}`,
                start  : `${fecha.start}`,
                end  : `${fecha.end}`,
                color: 'rgba(255, 255, 0, 0.37)',
                textColor: 'white'
            };
            return actividad;
        break;
        case 5:
            actividad = {
                id : idDescripcionAdministrativa,
                title  : `${nombreActividad}`,
                start  : `${fecha.start}`,
                end  : `${fecha.end}`,
                color: 'rgba(128, 0, 128, 0.37)',
                textColor: 'white'
            };
            return actividad;
        break;
        case 6:
            actividad = {
                id : idDescripcionAdministrativa,
                title  : `${nombreActividad}`,
                start  : `${fecha.start}`,
                end  : `${fecha.end}`,
                color: 'rgba(255, 166, 0, 0.37)',
                textColor: 'white'
            };
            return actividad;
        break;
        case 7:
            actividad = {
                id : idDescripcionAdministrativa,
                title  : `${nombreActividad}`,
                start  : `${fecha.start}`,
                end  : `${fecha.end}`,
                color: 'black',
                textColor: 'white'
            };
            return actividad;
        break;
        case 8:
            actividad = {
                id : idDescripcionAdministrativa,
                title  : `${nombreActividad}`,
                start  : `${fecha.start}`,
                end  : `${fecha.end}`,
                color: 'white',
                textColor: 'black'
            };
            return actividad;
        break;
        default:
            actividad = {
                id : idDescripcionAdministrativa,
                title  : `${nombreActividad}`,
                start  : `${fecha.start}`,
                end  : `${fecha.end}`,
                color: '#1a237e',
                textColor: 'white'
            };
            return actividad;
        break;
    }
};
const ElegirMes = (Mes,anio) => {
    var start=null;
    var end=null;
    switch(Mes) {
        case 'Enero':
            start = `${anio}-01-01`;
            end = `${anio}-02-01`;
            return {start,end};
        break;
        case 'Febrero':
            start = `${anio}-02-01`;
            end = `${anio}-03-01`;
            return {start,end};
        break;
        case 'Marzo':
            start = `${anio}-03-01`;
            end = `${anio}-04-01`;
            return {start,end};
        break;
        case 'Abril':
            start = `${anio}-04-01`;
            end = `${anio}-05-01`;
            return {start,end};
        break;
        case 'Mayo':
            start = `${anio}-05-01`;
            end = `${anio}-06-01`;
            return {start,end};
        break;
        case 'Junio':
            start = `${anio}-06-01`;
            end = `${anio}-07-01`;
            return {start,end};
        break;
        case 'Julio':
            start = `${anio}-07-01`;
            end = `${anio}-08-01`;
            return {start,end};
        break;
        case 'Agosto':
            start = `${anio}-08-01`;
            end = `${anio}-09-01`;
            return {start,end};
        break;
        case 'Septiembre':
            start = `${anio}-09-01`;
            end = `${anio}-10-01`;
            return {start,end};
        break;
        case 'Octubre':
            start = `${anio}-10-01`;
            end = `${anio}-11-01`;
            return {start,end};
        break;
        case 'Noviembre':
            start = `${anio}-11-01`;
            end = `${anio}-12-01`;
            return {start,end};
        break;
        case 'Diciembre':
            start = `${anio}-12-01`;
            end = `${anio+1}-01-01`;
            return {start,end};
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
};
// const cambioAct = (actividad) => {
//     $('#calend').css('display','block');
// };

const VerMasAct = (idActividad) =>{
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
                        $("#ActividadCalendario").html(`
                        <div class="form-group d-flex row">
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <h5 class="form-control">Departamento:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <input 
                                    type="text" 
                                    id="nombreDepartamento" 
                                    class="form-control"  
                                    value="${ data[0].nombreDepartamento }"
                                    align="justify"
                                    readonly
                                >
                            </div>
                        </div>
                        <div class="form-group d-flex row">
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
                                <h5 class="form-control">Responsable Actividad:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <input 
                                    type="text" 
                                    id="responsableActividad" 
                                    class="form-control"  
                                    value="${ data[0].responsableActividad }"
                                    align="justify"
                                    readonly
                                >
                            </div>
                        </div>
                        <div class="form-group d-flex row">
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <h5 class="form-control">Justificacion de la actividad:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <textarea 
                                    type="text" 
                                    id="justificacionActividad" 
                                    class="form-control"  
                                    align="justify"
                                    readonly
                                >${ data[0].justificacionActividad }</textarea>
                            </div>
                        </div>
                        <div class="form-group d-flex row">
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <h5 class="form-control">Medio de verificacion de la actividad:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <textarea 
                                    type="text" 
                                    id="medioVerificacionActividad" 
                                    class="form-control"  
                                    align="justify"
                                    readonly
                                >${ data[0].medioVerificacionActividad }</textarea>
                            </div>
                        </div>`);
                        $("#VerMasActLabel").html(`Informacion correspondiente a la actividad: ${ data[0].nombreActividad }`);
                break;
                case 2:
                    $("#ActividadCalendario").html(`
                    <div class="form-group d-flex row">
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <h5 class="form-control">Departamento:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <input 
                                    type="text" 
                                    id="nombreDepartamento" 
                                    class="form-control"  
                                    value="${ data[0].nombreDepartamento }"
                                    align="justify"
                                    readonly
                                >
                            </div>
                        </div>
                    <div class="form-group d-flex row">
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
                                <h5 class="form-control">Responsable Actividad:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <input 
                                    type="text" 
                                    id="responsableActividad" 
                                    class="form-control"  
                                    value="${ data[0].responsableActividad }"
                                    align="justify"
                                    readonly
                                >
                            </div>
                        </div>
                        <div class="form-group d-flex row">
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <h5 class="form-control">Justificacion de la actividad:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <textarea 
                                    type="text" 
                                    id="justificacionActividad" 
                                    class="form-control"  
                                    align="justify"
                                    readonly
                                >${ data[0].justificacionActividad }</textarea>
                            </div>
                        </div>
                        <div class="form-group d-flex row">
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <h5 class="form-control">Medio de verificacion de la actividad:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <textarea 
                                    type="text" 
                                    id="medioVerificacionActividad" 
                                    class="form-control"  
                                    align="justify"
                                    readonly
                                >${ data[0].medioVerificacionActividad }</textarea>
                            </div>
                        </div>`);
                    $("#VerMasActLabel").html(`Informacion correspondiente a la actividad: ${ data[0].nombreActividad }`);

                break;
                case 3:
                    $("#ActividadCalendario").html(`
                    <div class="form-group d-flex row">
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <h5 class="form-control">Departamento:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <input 
                                    type="text" 
                                    id="nombreDepartamento" 
                                    class="form-control"  
                                    value="${ data[0].nombreDepartamento }"
                                    align="justify"
                                    readonly
                                >
                            </div>
                        </div>
                    <div class="form-group d-flex row">
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
                                <h5 class="form-control">Responsable Actividad:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <input 
                                    type="text" 
                                    id="responsableActividad" 
                                    class="form-control"  
                                    value="${ data[0].responsableActividad }"
                                    align="justify"
                                    readonly
                                >
                            </div>
                        </div>
                        <div class="form-group d-flex row">
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <h5 class="form-control">Justificacion de la actividad:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <textarea 
                                    type="text" 
                                    id="justificacionActividad" 
                                    class="form-control"  
                                    align="justify"
                                    readonly
                                >${ data[0].justificacionActividad }</textarea>
                            </div>
                        </div>
                        <div class="form-group d-flex row">
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <h5 class="form-control">Medio de verificacion de la actividad:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <textarea 
                                    type="text" 
                                    id="medioVerificacionActividad" 
                                    class="form-control"  
                                    align="justify"
                                    readonly
                                >${ data[0].medioVerificacionActividad }</textarea>
                            </div>
                        </div>`);
                    $("#VerMasActLabel").html(`Informacion correspondiente a la actividad: ${ data[0].nombreActividad }`);
                break;
                case 4:
                    $("#ActividadCalendario").html(`
                    <div class="form-group d-flex row">
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <h5 class="form-control">Departamento:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <input 
                                    type="text" 
                                    id="nombreDepartamento" 
                                    class="form-control"  
                                    value="${ data[0].nombreDepartamento }"
                                    align="justify"
                                    readonly
                                >
                            </div>
                        </div>
                    <div class="form-group d-flex row">
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
                                <h5 class="form-control">Responsable Actividad:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <input 
                                    type="text" 
                                    id="responsableActividad" 
                                    class="form-control"  
                                    value="${ data[0].responsableActividad }"
                                    align="justify"
                                    readonly
                                >
                            </div>
                        </div>
                        <div class="form-group d-flex row">
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <h5 class="form-control">Justificacion de la actividad:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <textarea 
                                    type="text" 
                                    id="justificacionActividad" 
                                    class="form-control"  
                                    align="justify"
                                    readonly
                                >${ data[0].justificacionActividad }</textarea>
                            </div>
                        </div>
                        <div class="form-group d-flex row">
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <h5 class="form-control">Medio de verificacion de la actividad:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <textarea 
                                    type="text" 
                                    id="medioVerificacionActividad" 
                                    class="form-control"  
                                    align="justify"
                                    readonly
                                >${ data[0].medioVerificacionActividad }</textarea>
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
                    $("#ActividadCalendario").html(`
                    <div class="form-group d-flex row">
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <h5 class="form-control">Departamento:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <input 
                                    type="text" 
                                    id="nombreDepartamento" 
                                    class="form-control"  
                                    value="${ data[0].nombreDepartamento }"
                                    align="justify"
                                    readonly
                                >
                            </div>
                        </div>
                    <div class="form-group d-flex row">
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
                                <h5 class="form-control">Responsable Actividad:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <input 
                                    type="text" 
                                    id="responsableActividad" 
                                    class="form-control"  
                                    value="${ data[0].responsableActividad }"
                                    align="justify"
                                    readonly
                                >
                            </div>
                        </div>
                        <div class="form-group d-flex row">
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <h5 class="form-control">Justificacion de la actividad:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <textarea 
                                    type="text" 
                                    id="justificacionActividad" 
                                    class="form-control"  
                                    align="justify"
                                    readonly
                                >${ data[0].justificacionActividad }</textarea>
                            </div>
                        </div>
                        <div class="form-group d-flex row">
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <h5 class="form-control">Medio de verificacion de la actividad:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <textarea 
                                    type="text" 
                                    id="medioVerificacionActividad" 
                                    class="form-control"  
                                    align="justify"
                                    readonly
                                >${ data[0].medioVerificacionActividad }</textarea>
                            </div>
                        </div>`);
                    $("#VerMasActLabel").html(`Informacion correspondiente a la actividad: ${ data[0].nombreActividad }`);
                break;
                case 6:
                    $("#ActividadCalendario").html(`
                    <div class="form-group d-flex row">
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <h5 class="form-control">Departamento:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <input 
                                    type="text" 
                                    id="nombreDepartamento" 
                                    class="form-control"  
                                    value="${ data[0].nombreDepartamento }"
                                    align="justify"
                                    readonly
                                >
                            </div>
                        </div>
                    <div class="form-group d-flex row">
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
                                <h5 class="form-control">Responsable Actividad:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <input 
                                    type="text" 
                                    id="responsableActividad" 
                                    class="form-control"  
                                    value="${ data[0].responsableActividad }"
                                    align="justify"
                                    readonly
                                >
                            </div>
                        </div>
                        <div class="form-group d-flex row">
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <h5 class="form-control">Justificacion de la actividad:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <textarea 
                                    type="text" 
                                    id="justificacionActividad" 
                                    class="form-control"  
                                    align="justify"
                                    readonly
                                >${ data[0].justificacionActividad }</textarea>
                            </div>
                        </div>
                        <div class="form-group d-flex row">
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <h5 class="form-control">Medio de verificacion de la actividad:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <textarea 
                                    type="text" 
                                    id="medioVerificacionActividad" 
                                    class="form-control"  
                                    align="justify"
                                    readonly
                                >${ data[0].medioVerificacionActividad }</textarea>
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
                    $("#ActividadCalendario").html(`
                    <div class="form-group d-flex row">
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <h5 class="form-control">Departamento:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <input 
                                    type="text" 
                                    id="nombreDepartamento" 
                                    class="form-control"  
                                    value="${ data[0].nombreDepartamento }"
                                    align="justify"
                                    readonly
                                >
                            </div>
                        </div>
                    <div class="form-group d-flex row">
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
                                <h5 class="form-control">Responsable Actividad:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <input 
                                    type="text" 
                                    id="responsableActividad" 
                                    class="form-control"  
                                    value="${ data[0].responsableActividad }"
                                    align="justify"
                                    readonly
                                >
                            </div>
                        </div>
                        <div class="form-group d-flex row">
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <h5 class="form-control">Justificacion de la actividad:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <textarea 
                                    type="text" 
                                    id="justificacionActividad" 
                                    class="form-control"  
                                    align="justify"
                                    readonly
                                >${ data[0].justificacionActividad }</textarea>
                            </div>
                        </div>
                        <div class="form-group d-flex row">
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <h5 class="form-control">Medio de verificacion de la actividad:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <textarea 
                                    type="text" 
                                    id="medioVerificacionActividad" 
                                    class="form-control"  
                                    align="justify"
                                    readonly
                                >${ data[0].medioVerificacionActividad }</textarea>
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
                    $("#ActividadCalendario").html(`
                    <div class="form-group d-flex row">
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <h5 class="form-control">Departamento:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <input 
                                    type="text" 
                                    id="nombreDepartamento" 
                                    class="form-control"  
                                    value="${ data[0].nombreDepartamento }"
                                    align="justify"
                                    readonly
                                >
                            </div>
                        </div>
                    <div class="form-group d-flex row">
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
                                <h5 class="form-control">Responsable Actividad:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <input 
                                    type="text" 
                                    id="responsableActividad" 
                                    class="form-control"  
                                    value="${ data[0].responsableActividad }"
                                    align="justify"
                                    readonly
                                >
                            </div>
                        </div>
                        <div class="form-group d-flex row">
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <h5 class="form-control">Justificacion de la actividad:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <textarea 
                                    type="text" 
                                    id="justificacionActividad" 
                                    class="form-control"  
                                    align="justify"
                                    readonly
                                >${ data[0].justificacionActividad }</textarea>
                            </div>
                        </div>
                        <div class="form-group d-flex row">
                            <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <h5 class="form-control">Medio de verificacion de la actividad:</h5>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <textarea 
                                    type="text" 
                                    id="medioVerificacionActividad" 
                                    class="form-control"  
                                    align="justify"
                                    readonly
                                >${ data[0].medioVerificacionActividad }</textarea>
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