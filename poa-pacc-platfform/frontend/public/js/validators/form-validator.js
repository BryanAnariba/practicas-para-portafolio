// Funcion que pinta en pantalla los inputs de color rojo notificando error
const despliegeErrores = (valorEtiqueta, id, type) => {
    valorEtiqueta.classList.add('is-invalid');
    if (type === 'text' || type === 'number') {
        document.querySelector(`#label${ id }`).classList.add('text-danger');
    }
    valorEtiqueta.classList.add('text-danger');
    document.querySelector(`#errors${ id }`).classList.remove('d-none');
}

const resetearCampos = (valorEtiqueta, id, type) => {
    valorEtiqueta.classList.remove('is-invalid');
    if (type === 'text' || type === 'email') {
        document.querySelector(`#label${ id }`).classList.remove('text-danger');
        valorEtiqueta.value = '';
    } else if (type === 'select') {
        valorEtiqueta.value = '';
    } else if (type === 'number') {
        document.querySelector(`#label${ id }`).classList.remove('text-danger');
        valorEtiqueta.value = '';
    }
    valorEtiqueta.classList.remove('text-danger');
    document.querySelector(`#errors${ id }`).classList.add('d-none');
    
}

// Funcion que remueve en pantalla los inputs de color rojo notificando error
const remueveErrores = (valorEtiqueta, id, type) => {
    valorEtiqueta.classList.remove('is-invalid');
    if (type === 'text' || type === 'number') {
        document.querySelector(`#label${ id }`).classList.remove('text-danger');
    }
    valorEtiqueta.classList.remove('text-danger');
    document.querySelector(`#errors${ id }`).classList.add('d-none');
}

// Funcion para verificar email de la unah
const verificarEmail = (datosCampoEmail) => {
    const { valorEtiqueta, id, name, type } = datosCampoEmail;
    let isEmailValid = false;
    
    if (valorEtiqueta.value === '') {
        despliegeErrores(valorEtiqueta, id, type);
        document.querySelector(`#errors${ id }`).innerHTML = `El campo ${ name } es obligatorio`;
        isValid = false;    
    } else if (!unahEmailRegex.test(valorEtiqueta.value)) { // Si hace match la expresion regular con email notifique
        despliegeErrores(valorEtiqueta, id, type);
        document.querySelector(`#errors${ id }`).innerHTML = `El campo ${ name } no es un correo de la UNAH `;
        isEmailValid = false;
    } else {
        remueveErrores(valorEtiqueta, id, type);
        document.querySelector(`#errors${ id }`).innerHTML = ``;
        isEmailValid = true;
    }
    return isEmailValid;
}

// Funcion para verificar fechas, que no este vacia y que no se pase de la fecha actual
const verificarFecha = (date) => {
    const { valorEtiqueta, id, name } = date;
    let isValidFecha = false;
    const x = new Date().toISOString().split('T')[0];
    const fecha = valorEtiqueta.value;
    if (valorEtiqueta.value === '') {
        document.querySelector(`#errors${ id }`).classList.remove('d-none');
        document.querySelector(`#errors${ id }`).innerHTML = `El campo ${ name } es obligatorio`;
        isValidFecha = false;
    } else if (fecha >= x) {
        document.querySelector(`#errors${ id }`).classList.remove('d-none');
        document.querySelector(`#errors${ id }`).innerHTML = `El campo ${ name } se pasa de la fecha actual`;
        isValidFecha = false;
    } else {
        document.querySelector(`#errors${ id }`).classList.add('d-none');
        document.querySelector(`#errors${ id }`).innerHTML = ``;
        isValidFecha = true;
    }

    return isValidFecha;
}

// Funcion para verificar fechas de solicitud de permisos, que no este vacia y que no sea una fecha menor a la fecha actual
const verificarFechaSolicitud = (date) => {
    const { valorEtiqueta, id, name } = date;
    let isValidFecha = false;
    const x = new Date().toISOString().split('T')[0];
    const fecha = valorEtiqueta.value;

    if (valorEtiqueta.value === '') {
        document.querySelector(`#errors${ id }`).classList.remove('d-none');
        document.querySelector(`#errors${ id }`).innerHTML = `El campo ${ name } es obligatorio`;
        isValidFecha = false;
    } else if (fecha < x) {
        document.querySelector(`#errors${ id }`).classList.remove('d-none');
        document.querySelector(`#errors${ id }`).innerHTML = `El campo ${ name } no puede ser menor a la fecha actual`;
        isValidFecha = false;
    } else {
        document.querySelector(`#errors${ id }`).classList.add('d-none');
        document.querySelector(`#errors${ id }`).innerHTML = ``;
        isValidFecha = true;
    }

    return isValidFecha;
}

const verificarHoraSolicitud = (time) => {
    const { valorEtiqueta, id, name } = time;
    let isValidHora = false;
    //const x = new Date().toISOString().split('T')[0];
    //const fecha = valorEtiqueta.value;

    if (valorEtiqueta.value === '') {
        document.querySelector(`#errors${ id }`).classList.remove('d-none');
        document.querySelector(`#errors${ id }`).innerHTML = `El campo ${ name } es obligatorio`;
        isValidHora = false; 
    } else {
        document.querySelector(`#errors${ id }`).classList.add('d-none');
        document.querySelector(`#errors${ id }`).innerHTML = ``;
        isValidHora = true;
    }

    return isValidHora;
}



// Funcion para verificar textos y cadenas de string y su rspectivo tamanio
const verificarInputText = (inputData, regex) => {
    let isValid = false;
    const { valorEtiqueta, id, name, min, max, type } = inputData;
    if (valorEtiqueta.value.length === 0 || (valorEtiqueta.value === null)) {
        despliegeErrores(valorEtiqueta, id, type);
        document.querySelector(`#errors${ id }`).innerHTML = `El campo ${ name } es obligatorio`;
        isValid = false;    
    } else if ((valorEtiqueta.value.length < min)) {
        despliegeErrores(valorEtiqueta, id, type);
        document.querySelector(`#errors${ id }`).innerHTML = `El campo ${ name } debe tener al menos ${ min } caracteres`;
        isValid = false;
    } else if ((valorEtiqueta.value.length > max)) {
        despliegeErrores(valorEtiqueta, id, type);
        document.querySelector(`#errors${ id }`).innerHTML = `El campo ${ name } debe tener un maximo ${ max } caracteres`;
        isValid = false;
    } else if (regex.test(valorEtiqueta.value) === false) {
        despliegeErrores(valorEtiqueta, id, type);
        document.querySelector(`#errors${ id }`).innerHTML = `El campo ${ name } no es valido`;
        isValid = false;
    } else {
        remueveErrores(valorEtiqueta, id, type);
        document.querySelector(`#errors${ id }`).innerHTML = ``;
        isValid = true;
    }
    return isValid;
} 

// Funcion para verificar numeros
const verificarInputNumber = (inputData, regex) => {
    let isValid = false;
    const { valorEtiqueta, id, name, min, max, type } = inputData;
    if (valorEtiqueta.value.length === 0 || (valorEtiqueta.value === null)) {
        despliegeErrores(valorEtiqueta, id, type);
        document.querySelector(`#errors${ id }`).innerHTML = `El campo ${ name } es obligatorio`;
        isValid = false;    
    } else if ((valorEtiqueta.value.length < min)) {
        console.warn(valorEtiqueta.value.length);
        despliegeErrores(valorEtiqueta, id, type);
        document.querySelector(`#errors${ id }`).innerHTML = `El campo ${ name } debe tener al menos ${ min } caracteres`;
        isValid = false;
    } else if ((valorEtiqueta.value.length > max)) {
        despliegeErrores(valorEtiqueta, id, type);
        document.querySelector(`#errors${ id }`).innerHTML = `El campo ${ name } debe tener un maximo ${ max } caracteres`;
        isValid = false;
    } else if (regex.test(valorEtiqueta.value) === false) {
        despliegeErrores(valorEtiqueta, id, type);
        document.querySelector(`#errors${ id }`).innerHTML = `El campo ${ name } no es valido`;
        isValid = false;
    } else {
        remueveErrores(valorEtiqueta, id, type);
        document.querySelector(`#errors${ id }`).innerHTML = ``;
        isValid = true;
    }
    return isValid;
}


// Funcion para verificar select vacios
const verificarSelect = (selectData) => {
    const { valorEtiqueta, id, name, type } = selectData;
    let isValid = false;
    if ((valorEtiqueta.value.length===0)|| (valorEtiqueta.value === null)) {
        despliegeErrores(valorEtiqueta, id, type);
        document.querySelector(`#errors${ id }`).innerHTML = `El campo ${ name } es obligatorio`;
        isValid = false;
    } else {
        remueveErrores(valorEtiqueta, id, type);
        document.querySelector(`#errors${ id }`).innerHTML = ``;
        isValid = true;
    }
    return isValid;
}

const generaNombreUsuario = (emailUnah) => {
    return extraeCamposEmailRegex.exec(emailUnah);
}

const  verificarImagen = (obj) => {
    var uploadFile = obj.files[0]; // Para extraer el mimetype nombre imagen etc
    const { name } = uploadFile;

    if (!(/\.(jpg|png|gif)$/i).test(name)) {
        Swal.fire({
            icon: 'error',
            title: 'Ops...',
            text: 'El archivo seleccionado no es una imagen',
            footer: '<b>Por favor seleccione una</b>'
        });
        return false;
    }
    else {
        let img = new Image();
        img.onload = function () {
            if (uploadFile.size > 1000000) { // Si el tamanio de la imagen es mayor a 1 mb
                Swal.fire({
                    icon: 'error',
                    title: 'Ops...',
                    text: 'El tamaño de la imagen es muy grande',
                    footer: '<b>Por favor seleccione una imagen de maximo 1MB</b>'
                });
            } else {              
            }
        };
        return uploadFile;
    }                 
}

const limpiarCamposFormulario = (valoresEtiqueta) => {
    const { valorEtiqueta, id, type } = valoresEtiqueta
    resetearCampos(valorEtiqueta, id, type);
}

