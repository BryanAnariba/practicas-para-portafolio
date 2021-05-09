$(document).ready(function (){
    obtenerObjetos();
});
const agregarATabla = (dataSet) => {
    $('#ObjetosTodas').dataTable().fnDestroy();
    $('#ObjetosTodas tbody').html(``);
    
    for (let i=0;i<dataSet.length; i++) {
        $('#ObjetosTodas tbody').append(`
            <tr align="center">
                <td scope="row">${ i + 1 }</td>
                <td>${ dataSet[i].codigoObjetoGasto }</td>
                <td>${ dataSet[i].DescripcionCuenta }</td>
                <td>${ dataSet[i].abrev }</td>
                <td>${ dataSet[i].estado }</td>
                <td>
                    <button type="button" class="btn btn-amber" onclick="obtenerObjeto('${dataSet[i].idObjetoGasto}','${dataSet[i].codigoObjetoGasto}','${dataSet[i].DescripcionCuenta}','${dataSet[i].abrev}','${dataSet[i].idEstado}')">
                        <img src="../img/menu/editar.svg" alt="modificar dimension"/>
                    </button>
                </td>
            </tr>
        `)
    }
    $('#ObjetosTodas').DataTable({
        language: i18nEspaniol,
        dom: 'Blfrtip',
        buttons: botonesExportacion,
        retrieve: true
    });
};
const obtenerObjetos = () => {
    const peticion = {
        nada: ""
    };
    $.ajax(`${ API }/ObjetosGasto/ObtenerObjetosGasto.php`, {
        type: 'POST',
        dataType: 'json',
        data: (peticion),
        success:function(response) {
            agregarATabla(response.data);
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
                text: `${ data.message }`
            });
        }
    });
};
const cambiarEstadoModificado = (idEstado) => {
    const peticion = {
        nada: ""
    };
    $.ajax(`${ API }/ObjetosGasto/ObtenerEstado.php`, {
        type: 'POST',
        dataType: 'json',
        data: (peticion),
        success:function(response) {
            document.getElementById("EstadoModif").innerHTML="";
            for(let i = 0; i < response.data.length;i++){
                if(idEstado==response.data[i].idEstado){
                    document.getElementById("EstadoModif").innerHTML+=`<option value="${response.data[i].idEstado}" selected>${response.data[i].estado}</option>`;
                }else{
                    document.getElementById("EstadoModif").innerHTML+=`<option value="${response.data[i].idEstado}">${response.data[i].estado}</option>`;
                }
            }
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
const restablecerCamposM = ()=>{
    document.querySelector(`#labelCodigoObjeto`).classList.remove('text-danger')
    document.querySelector('#CodigoObjeto').classList.remove('text-danger');
    document.querySelector('#CodigoObjeto').classList.remove('is-invalid')
    document.querySelector(`#errorsCodigoObjeto`).classList.add('d-none');
    document.querySelector(`#labelObjetoDeGasto`).classList.remove('text-danger')
    document.querySelector('#ObjetoDeGasto').classList.remove('text-danger');
    document.querySelector('#ObjetoDeGasto').classList.remove('is-invalid')
    document.querySelector(`#errorsObjetoDeGasto`).classList.add('d-none');
    document.querySelector(`#labelAbreviatura2`).classList.remove('text-danger')
    document.querySelector('#num0M').classList.remove('text-danger');
    document.querySelector('#num0M').classList.remove('is-invalid');
    document.querySelector('#num1M').classList.remove('text-danger');
    document.querySelector('#num1M').classList.remove('is-invalid');
    document.querySelector('#num2M').classList.remove('text-danger');
    document.querySelector('#num2M').classList.remove('is-invalid');
    document.querySelector('#num3M').classList.remove('text-danger');
    document.querySelector('#num3M').classList.remove('is-invalid');
    document.querySelector('#num4M').classList.remove('text-danger');
    document.querySelector('#num4M').classList.remove('is-invalid');
    document.querySelector(`#errorsAbreviatura2`).classList.add('d-none');
    document.querySelector(`#labelEstadoModif`).classList.remove('text-danger')
    document.querySelector('#EstadoModif').classList.remove('text-danger');
    document.querySelector('#EstadoModif').classList.remove('is-invalid')
    document.querySelector(`#errorsEstadoModif`).classList.add('d-none');
    $("#ObjetoDeGasto").val("").trigger("change");
    $("#num0M").val('').trigger("change");
    $("#num1M").val('').trigger("change");
    $("#num2M").val('').trigger("change");
    $("#num3M").val('').trigger("change");
    $("#num4M").val('').trigger("change");
    $("#CodigoObjeto").val("").trigger("change");
};
var id = 0;
const obtenerObjeto = (id,codigo,ObjetosGasto,abrev,idEstado) => {
    this.id=id;
    $('#modalModificarObjeto').modal('show');
    document.querySelector(`#labelCodigoObjeto`).classList.remove('text-danger')
    document.querySelector('#CodigoObjeto').classList.remove('text-danger');
    document.querySelector('#CodigoObjeto').classList.remove('is-invalid')
    document.querySelector(`#errorsCodigoObjeto`).classList.add('d-none');
    document.querySelector(`#labelObjetoDeGasto`).classList.remove('text-danger')
    document.querySelector('#ObjetoDeGasto').classList.remove('text-danger');
    document.querySelector('#ObjetoDeGasto').classList.remove('is-invalid')
    document.querySelector(`#errorsObjetoDeGasto`).classList.add('d-none');
    document.querySelector(`#labelAbreviatura2`).classList.remove('text-danger')
    document.querySelector('#Abreviatura2').classList.remove('text-danger');
    document.querySelector('#Abreviatura2').classList.remove('is-invalid')
    document.querySelector(`#errorsAbreviatura2`).classList.add('d-none');
    document.querySelector(`#labelEstadoModif`).classList.remove('text-danger')
    document.querySelector('#EstadoModif').classList.remove('text-danger');
    document.querySelector('#EstadoModif').classList.remove('is-invalid')
    document.querySelector(`#errorsEstadoModif`).classList.add('d-none');
    $("#ObjetoDeGasto").val(ObjetosGasto).trigger("change");
    $("#num0M").val(abrev.charAt(0)).trigger("change");
    $("#num1M").val(abrev.charAt(2)+abrev.charAt(3)).trigger("change");
    $("#num2M").val(abrev.charAt(5)+abrev.charAt(6)).trigger("change");
    $("#num3M").val(abrev.charAt(8)+abrev.charAt(9)).trigger("change");
    $("#num4M").val(abrev.charAt(11)+abrev.charAt(12)).trigger("change");
    $("#CodigoObjeto").val(codigo).trigger("change");
    $("#botonModif").attr("disabled", false);
    cambiarEstadoModificado(idEstado);
};
const actualizarObjeto = () => {

    // Capturando las etiquetas completas de los inputs para despues obtener el valor
    let ObjetoDeGasto = document.querySelector('#ObjetoDeGasto');
    let Num0M = document.querySelector('#num0M');
    let Num1M = document.querySelector('#num1M');
    let Num2M = document.querySelector('#num2M');
    let Num3M = document.querySelector('#num3M');
    let Num4M = document.querySelector('#num4M');
    let CodigoObjeto = document.querySelector('#CodigoObjeto');
    let Estado = document.querySelector('#EstadoModif');
    
    // Tipando los atributos con los valores de la base de datos bueno algunos -> nP = nombrePersona
    let oG = { valorEtiqueta: ObjetoDeGasto, id: 'ObjetoDeGasto', name: 'Objeto de Gasto', min: 1, max: 80, type: 'text' };
    let N0M = { valorEtiqueta: Num0M, id: 'Abreviatura', name: 'Num0', min: 1, max: 2, type: 'text' };
    let N1M = { valorEtiqueta: Num1M, id: 'Abreviatura', name: 'Num1', min: 1, max: 2, type: 'text' };
    let N2M = { valorEtiqueta: Num2M, id: 'Abreviatura', name: 'Num2', min: 1, max: 2, type: 'text' };
    let N3M = { valorEtiqueta: Num3M, id: 'Abreviatura', name: 'Num3', min: 1, max: 2, type: 'text' };
    let N4M = { valorEtiqueta: Num4M, id: 'Abreviatura', name: 'Num4', min: 1, max: 2, type: 'text' };
    let cO = { valorEtiqueta: CodigoObjeto, id: 'CodigoObjeto',name: 'Codigo objeto de gasto', min: 1, max: 15, type: 'text' };
    let Es = { valorEtiqueta: Estado, id: 'EstadoModif',name: 'Estado', type: 'select' };
    

    // Llamando a las funciones para realizar la verificacion de los campos retorna true o false
    let isValidObjeto = verificarInputText(oG,letrasEspaciosCaracteresRegex);
    let isValidN1M = verificarInputNumber(N1M,numerosRegex);
    let isValidN0M = verificarInputNumber(N0M,numerosRegex);
    let isValidN2M = verificarInputNumber(N2M,numerosRegex);
    let isValidN3M = verificarInputNumber(N3M,numerosRegex);
    let isValidN4M = verificarInputNumber(N4M,numerosRegex);
    let isValidCodigo = verificarInputText(cO,codigoObjRegex);
    let isValidEstado = verificarSelect(Es);

    // Si todos los campos que llevan validaciones estan okey o true que realice el ajax o fetch o axios o lo que sea
    if (
        (isValidObjeto === true) &&
        (isValidN0M === true) &&
        (isValidN1M === true) &&
        (isValidN2M === true) &&
        (isValidN3M === true) &&
        (isValidN4M === true) &&
        (isValidEstado === true) &&
        (isValidCodigo === true) 
    ) {
        const dataModifObjeto = {
            idObjetoGasto: this.id,
            ObjetoDeGasto: ObjetoDeGasto.value,
            Abreviatura: `${Num0M.value}-${Num1M.value}-${Num2M.value}-${Num3M.value}-${Num4M.value}`,
            CodigoObjeto: CodigoObjeto.value,
            Estado: Estado.value
        };
        
        $.ajax(`${ API }/ObjetosGasto/ActualizarObjeto.php`, {
            type: 'POST',
            dataType: 'json',
            data: (dataModifObjeto),
            success:function(response) {
                const { data } = response;
                document.querySelector(`#labelCodigoObjeto`).classList.remove('text-danger')
                document.querySelector('#CodigoObjeto').classList.remove('text-danger');
                document.querySelector('#CodigoObjeto').classList.remove('is-invalid')
                document.querySelector(`#errorsCodigoObjeto`).classList.add('d-none');
                document.querySelector(`#labelObjetoDeGasto`).classList.remove('text-danger')
                document.querySelector('#ObjetoDeGasto').classList.remove('text-danger');
                document.querySelector('#ObjetoDeGasto').classList.remove('is-invalid')
                document.querySelector(`#errorsObjetoDeGasto`).classList.add('d-none');
                document.querySelector(`#labelAbreviatura2`).classList.remove('text-danger')
                document.querySelector('#num1M').classList.remove('text-danger');
                document.querySelector('#num1M').classList.remove('is-invalid');
                document.querySelector('#num2M').classList.remove('text-danger');
                document.querySelector('#num2M').classList.remove('is-invalid');
                document.querySelector('#num3M').classList.remove('text-danger');
                document.querySelector('#num3M').classList.remove('is-invalid');
                document.querySelector('#num4M').classList.remove('text-danger');
                document.querySelector('#num4M').classList.remove('is-invalid');
                document.querySelector(`#errorsAbreviatura2`).classList.add('d-none');
                document.querySelector(`#labelEstadoModif`).classList.remove('text-danger')
                document.querySelector('#EstadoModif').classList.remove('text-danger');
                document.querySelector('#EstadoModif').classList.remove('is-invalid')
                document.querySelector(`#errorsEstadoModif`).classList.add('d-none');
                $("#ObjetoDeGasto").val("").trigger("change");
                $("#num1M").val('').trigger("change");
                $("#num2M").val('').trigger("change");
                $("#num3M").val('').trigger("change");
                $("#num4M").val('').trigger("change");
                $("#CodigoObjeto").val("").trigger("change");
                $('#modalModificarObjeto').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Accion realizada Exitosamente',
                    text: `${ data.message }`,
                });
                obtenerObjetos();
            },
            error:function(error) {
                const { status, data } = error.responseJSON;
                if (status === 401) {
                    window.location.href = '../views/401.php';
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Ops...',
                        text: `${ data.message }`,
                        footer: '<b>Por favor verifique el formulario de registro</b>'
                    })
                }
            }
        });
    } else { // caso contrario mostrar alerta y notificar al usuario 
        Swal.fire({
            icon: 'error',
            title: 'Ops...',
            text: 'No se pudo modificar el objeto del gasto',
            footer: '<b>Por favor verifique el formulario de registro</b>'
        })
    }
};
const restablecerCampos = ()=>{
    document.querySelector(`#labelObjetoDeGastoR`).classList.remove('text-danger')
    document.querySelector('#ObjetoDeGastoR').classList.remove('text-danger');
    document.querySelector('#ObjetoDeGastoR').classList.remove('is-invalid')
    document.querySelector(`#errorsObjetoDeGastoR`).classList.add('d-none');
    document.querySelector(`#labelAbreviatura`).classList.remove('text-danger')
    document.querySelector('#num0').classList.remove('text-danger');
    document.querySelector('#num0').classList.remove('is-invalid');
    document.querySelector('#num1').classList.remove('text-danger');
    document.querySelector('#num1').classList.remove('is-invalid');
    document.querySelector('#num2').classList.remove('text-danger');
    document.querySelector('#num2').classList.remove('is-invalid');
    document.querySelector('#num3').classList.remove('text-danger');
    document.querySelector('#num3').classList.remove('is-invalid');
    document.querySelector('#num4').classList.remove('text-danger');
    document.querySelector('#num4').classList.remove('is-invalid');
    document.querySelector(`#errorsAbreviatura`).classList.add('d-none');
    document.querySelector(`#labelCodigoObjetoR`).classList.remove('text-danger')
    document.querySelector('#CodigoObjetoR').classList.remove('text-danger');
    document.querySelector('#CodigoObjetoR').classList.remove('is-invalid')
    document.querySelector(`#errorsCodigoObjetoR`).classList.add('d-none');
    document.querySelector(`#labelEstado`).classList.remove('text-danger')
    document.querySelector('#Estado').classList.remove('text-danger');
    document.querySelector('#Estado').classList.remove('is-invalid')
    document.querySelector(`#errorsEstado`).classList.add('d-none');
    $("#ObjetoDeGastoR").val('').trigger("change");
    $("#num0").val('').trigger("change");
    $("#num1").val('').trigger("change");
    $("#num2").val('').trigger("change");
    $("#num3").val('').trigger("change");
    $("#num4").val('').trigger("change");
    $("#CodigoObjetoR").val('').trigger("change");
    $("#Estado").val('').trigger("change");
};
const DesplegarModalRegistro = () => {
    $('#modalRegistrarObjeto').modal('show');
    cambiarEst();
}
const cambiarEst = () => {
    const peticion = {
        nada: ""
    };
    
    $.ajax(`${ API }/ObjetosGasto/ObtenerEstado.php`, {
        type: 'POST',
        dataType: 'json',
        data: (peticion),
        success:function(response) {
            document.getElementById("Estado").innerHTML="";
            for(let i = 0; i < response.data.length;i++){
                document.getElementById("Estado").innerHTML+=`<option value="${response.data[i].idEstado}">${response.data[i].estado}</option>`;
            };
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
const registrarObjeto = () => {

    // Capturando las etiquetas completas de los inputs para despues obtener el valor
    let ObjetoDeGasto = document.querySelector('#ObjetoDeGastoR');
    let Num0 = document.querySelector('#num0');
    let Num1 = document.querySelector('#num1');
    let Num2 = document.querySelector('#num2');
    let Num3 = document.querySelector('#num3');
    let Num4 = document.querySelector('#num4');
    let CodigoObjeto = document.querySelector('#CodigoObjetoR');
    let Estado = document.querySelector('#Estado');
    
    // Tipando los atributos con los valores de la base de datos bueno algunos -> nP = nombrePersona
    let oG = { valorEtiqueta: ObjetoDeGasto, id: 'ObjetoDeGastoR', name: 'Objeto de Gasto', min: 1, max: 80, type: 'text' };
    let N0 = { valorEtiqueta: Num0, id: 'Abreviatura', name: 'Abreviatura', min: 1, max: 1, type: 'text' };
    let N1 = { valorEtiqueta: Num1, id: 'Abreviatura', name: 'Abreviatura', min: 1, max: 2, type: 'text' };
    let N2 = { valorEtiqueta: Num2, id: 'Abreviatura', name: 'Abreviatura', min: 1, max: 2, type: 'text' };
    let N3 = { valorEtiqueta: Num3, id: 'Abreviatura', name: 'Abreviatura', min: 1, max: 2, type: 'text' };
    let N4 = { valorEtiqueta: Num4, id: 'Abreviatura', name: 'Abreviatura', min: 1, max: 2, type: 'text' };
    let cO = { valorEtiqueta: CodigoObjeto, id: 'CodigoObjetoR',name: 'Codigo objeto de gasto', min: 1, max: 15, type: 'text' };
    let Es = { valorEtiqueta: Estado, id: 'Estado',name: 'Estado', type: 'select' };
    
    // Llamando a las funciones para realizar la verificacion de los campos retorna true o false
    let isValidObjeto = verificarInputText(oG,letrasEspaciosCaracteresRegex);
    let isValidN0 = verificarInputNumber(N0,numerosRegex);
    let isValidN1 = verificarInputNumber(N1,numerosRegex);
    let isValidN2 = verificarInputNumber(N2,numerosRegex);
    let isValidN3 = verificarInputNumber(N3,numerosRegex);
    let isValidN4 = verificarInputNumber(N4,numerosRegex);
    let isValidCodigo = verificarInputText(cO,codigoObjRegex);
    let isValidEstado = verificarSelect(Es);
    
    // Si todos los campos que llevan validaciones estan okey o true que realice el ajax o fetch o axios o lo que sea
    if (
        (isValidObjeto === true) &&
        (isValidN0 === true) &&
        (isValidN1 === true) &&
        (isValidN2 === true) &&
        (isValidN3 === true) &&
        (isValidN4 === true) &&
        (isValidEstado === true) &&
        (isValidCodigo === true) 
    ) {
        const dataNuevoObjeto = {
            idObjetoGasto: this.id,
            ObjetoDeGasto: ObjetoDeGasto.value,
            Abreviatura: `${Num0.value}-${Num1.value}-${Num2.value}-${Num3.value}-${Num4.value}`,
            CodigoObjeto: CodigoObjeto.value,
            Estado: Estado.value
        };
        console.log(dataNuevoObjeto.Abreviatura);
        $.ajax(`${ API }/ObjetosGasto/RegistrarObjeto.php`, {
            type: 'POST',
            dataType: 'json',
            data: (dataNuevoObjeto),
            success:function(response) {
                const { data } = response;
                document.querySelector(`#labelObjetoDeGastoR`).classList.remove('text-danger')
                document.querySelector('#ObjetoDeGastoR').classList.remove('text-danger');
                document.querySelector('#ObjetoDeGastoR').classList.remove('is-invalid')
                document.querySelector(`#errorsObjetoDeGastoR`).classList.add('d-none');
                document.querySelector(`#labelAbreviatura`).classList.remove('text-danger')
                document.querySelector('#num1').classList.remove('text-danger');
                document.querySelector('#num1').classList.remove('is-invalid');
                document.querySelector('#num2').classList.remove('text-danger');
                document.querySelector('#num2').classList.remove('is-invalid');
                document.querySelector('#num3').classList.remove('text-danger');
                document.querySelector('#num3').classList.remove('is-invalid');
                document.querySelector('#num4').classList.remove('text-danger');
                document.querySelector('#num4').classList.remove('is-invalid');
                document.querySelector(`#errorsAbreviatura`).classList.add('d-none');
                document.querySelector(`#labelCodigoObjetoR`).classList.remove('text-danger')
                document.querySelector('#CodigoObjetoR').classList.remove('text-danger');
                document.querySelector('#CodigoObjetoR').classList.remove('is-invalid')
                document.querySelector(`#errorsCodigoObjetoR`).classList.add('d-none');
                document.querySelector(`#labelEstado`).classList.remove('text-danger')
                document.querySelector('#Estado').classList.remove('text-danger');
                document.querySelector('#Estado').classList.remove('is-invalid')
                document.querySelector(`#errorsEstado`).classList.add('d-none');
                $("#ObjetoDeGastoR").val('').trigger("change");
                $("#num1").val('').trigger("change");
                $("#num2").val('').trigger("change");
                $("#num3").val('').trigger("change");
                $("#num4").val('').trigger("change");
                $("#CodigoObjetoR").val('').trigger("change");
                $("#Estado").val('').trigger("change");
                $('#modalRegistrarObjeto').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Accion realizada Exitosamente',
                    text: `${ data.message }`,
                })
                obtenerObjetos();
            },
            error:function(error) {
                const { status, data } = error.responseJSON;
                if (status === 401) {
                    window.location.href = '../views/401.php';
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Ops...',
                        text: `${ data.message }`,
                        footer: '<b>Por favor verifique el formulario de registro</b>'
                    })
                };
            }
        });
    } else { // caso contrario mostrar alerta y notificar al usuario 
        Swal.fire({
            icon: 'error',
            title: 'Ops...',
            text: 'El registro del objeto del gasto no se pudo realizar',
            footer: '<b>Por favor verifique el formulario de registro</b>'
        })
    }
};
function setCharAt(str,index,chr) {
    if(index > str.length-1) return str;
    return str.substring(0,index) + chr + str.substring(index+1);
}
function validaC(e,id){

    tecla = (document.all) ? e.keyCode : e.which;

    //Tecla de retroceso para borrar, siempre la permite
    if (tecla==8){
        return true;
    }
        
    // Patron de entrada, en este caso solo acepta numeros
    patron2 =/[-]/;
    patron =/[0-9]/;
    
    tecla_final = String.fromCharCode(tecla);
    
    if(document.querySelector(`#`+id).value.length>=0 && document.querySelector(`#`+id).value.length<5){
        return patron.test(tecla_final);
    }else if(document.querySelector(`#`+id).value.length==5){
        return patron2.test(tecla_final);
    }else{
        return patron.test(tecla_final);
    }
}
function validaAC(e){
    tecla = (document.all) ? e.keyCode : e.which;

    //Tecla de retroceso para borrar, siempre la permite
    if (tecla==8){
        return true;
    }
        
    // Patron de entrada, en este caso solo acepta numeros
    patron =/[0-9]/;
    
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}
const inputCharacters0 = (event) =>{
    if(document.querySelector('#num0').value.length==1 && event.which == 13){
      document.getElementById('num1').focus();
    }
  }
const inputCharacters = (event) =>{
  if(document.querySelector('#num1').value.length==2 && event.which == 13){
    document.getElementById('num2').focus();
  }
}
const inputCharacters2 = (event) =>{
    if(document.querySelector('#num2').value.length==2 && event.which == 13){
      document.getElementById('num3').focus();
    }
  }
const inputCharacters3 = (event) =>{
  if(document.querySelector('#num3').value.length==2 && event.which == 13){
    document.getElementById('num4').focus();
  }
}
document.getElementById('num0').addEventListener('keydown', inputCharacters0);
document.getElementById('num1').addEventListener('keydown', inputCharacters);
document.getElementById('num2').addEventListener('keydown', inputCharacters2);
document.getElementById('num3').addEventListener('keydown', inputCharacters3);

const inputCharacters0M = (event) =>{
    if(document.querySelector('#num0M').value.length==1 && event.which == 13){
      document.getElementById('num1M').focus();
    }
  }
const inputCharactersM = (event) =>{
    if(document.querySelector('#num1M').value.length==2 && event.which == 13){
      document.getElementById('num2M').focus();
    }
  }
  const inputCharacters2M = (event) =>{
      if(document.querySelector('#num2M').value.length==2 && event.which == 13){
        document.getElementById('num3M').focus();
      }
    }
  const inputCharacters3M = (event) =>{
    if(document.querySelector('#num3M').value.length==2 && event.which == 13){
      document.getElementById('num4M').focus();
    }
  }
  document.getElementById('num0M').addEventListener('keydown', inputCharacters0M);
  document.getElementById('num1M').addEventListener('keydown', inputCharactersM);
  document.getElementById('num2M').addEventListener('keydown', inputCharacters2M);
  document.getElementById('num3M').addEventListener('keydown', inputCharacters3M);
