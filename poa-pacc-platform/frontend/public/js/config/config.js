// Enpoint o URI general, a partir de esta URI se debera hacer las peticiones
const API = '../../../backend/api';
const APILogin = 'backend/api';

const error1 = 'Tipo de peticion no valida';
const error2 = 'La clave digitada es incorrecta, escriba su clave nuevamente';
const noAutorizado = 'No esta autorizado para realizar esta peticion o su token de acceso ha caducado, debe loguearse';

// Expresiones regulares
const numeroTelefonoRefex = /^(2\d{3})(-)?\d{4}/;
const numerosRegex = /^[+]?([0-9]+(?:[\.][0-9]*)?|\.[0-9]+)$/;
const unahEmailRegex = /[A-Za-z][\w]*@unah(\.edu)?\.hn/;
const letrasEspaciosRegex2 = /^[ñA-Za-z _]*[ñA-Za-z][ñA-Za-z _]*$/;
const letrasEspaciosCaracteresRegex = /^[ñA-Za-z _]*[ñA-Za-z][ñA-Za-z _]/;
const avatarUsuarioRegex = /^.+\.(jpe?g|gif|png)$/i;
const codigoEmpleadoRegex = /^\d{1,5}$/;
const nombresApellidosRegex = /^([a-z ñáéíóú]{1,80})$/i;
const justificacionRegex = /^[\w]/;
//const nombresApellidosRegex = /^[a-zA-ZÀ-ÿ\u00f1\u00d1]+(\s*[a-zA-ZÀ-ÿ\u00f1\u00d1]*)*[a-zA-ZÀ-ÿ\u00f1\u00d1]+$/;
const extraeCamposEmailRegex = /^([^]+)@(\w+).(\w+).?(\w+)$/;
const claveUsuarioRegex = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[!@#$%^&*()_+])[A-Za-z\d][A-Za-z\d!@#$%^&*()_+]{8,16}$/;
const regexCampoMonetario = /^[+]?([0-9]+(?:[\.][0-9]*)?|\.[0-9]+)$/;
const regexNumeroPositivoEntero = /^[0-9]{1,3}$/;
const regexNumeroPositivoEnteroCalculo = /^[+]?[0-9]{1,9}(?:.[0-9]{1,2})?$/;

const codigoObjRegex = /^[0-9]{5}(-[0-9]{2})?$/;


const i18nEspaniol = {
    "sProcessing":     "Procesando...",
    "sLengthMenu":     "Mostrar _MENU_ registros",
    "sZeroRecords":    "No se encontraron resultados",
    "sEmptyTable":     "Ningún dato disponible en esta tabla",
    "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
    "sSearch":         "Buscar:",
    "sInfoThousands":  ",",
    "sLoadingRecords": "Cargando...",
    "oPaginate": {
        "sFirst":    "Primero",
        "sLast":     "Último",
        "sNext":     "Siguiente",
        "sPrevious": "Anterior"
    },
    "oAria": {
        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    },
    "buttons": {
        "copy": "Copiar",
        "colvis": "Visibilidad"
    }
}

const botonesExportacion = [{
    extend: 'excelHtml5',
    text: '<i class="fas fa-file-excel"><i/>',
    titleAttr:'Exportar excel',
    className: 'btn btn-success'
    }, {
    extend: 'pdfHtml5',
    text: '<i class="fas fa-file-pdf"><i/>',
    titleAttr:'Exportar a pdf',
    className: 'btn btn-danger'
    }, {
    extend: 'print',
    text: '<i class="fas fa-print"><i/>',
    titleAttr:'Imprimir tabla',
    className: 'btn btn-info'
}];