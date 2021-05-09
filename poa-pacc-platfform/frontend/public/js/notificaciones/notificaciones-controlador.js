//Peticiones para generar las notificaciones en el sistema, segun cada tipo de usuario y
//segun ciertas acciones realizadas en el sistema.

//Petición para mostrar notificaciones a usuario de tipo Secretaria Academica
const notificacionesSecAcademica = () => {

    $.ajax(`${ API }/control-notificaciones/generarNotificacionesSecAcademica.php`, {
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        success:function(response) {
            //console.log(data);
            let cantidadSolicitudes = response.data[0].cantidadSolicitudes;
            if (cantidadSolicitudes != 0){
                //html(`<span id="contador" class="badge badge-danger badge-counter">${cantidadSolicitudes}</span>`);
                $('#contador').html(`${cantidadSolicitudes}`);
                //$('#dropdown-notificaciones').html(`<a class="dropdown-item" href="#">Tiene solicitudes de permisos pendientes</a>`);
                $('#dropdown-notificaciones').html(`
                    <a class="dropdown-item" href="../views/control-recibir-permisos.php">
                    <img src="../img/control-notificaciones/icono-soli.svg" alt="solicitudes"> Tiene Permisos pendientes</a>
                `);
            }else {
                $('#contador').html(`${cantidadSolicitudes}`);
               // $('#contador').css({'display':'none'});
                $('#dropdown-notificaciones').html(`
                    <a class="dropdown-item" href="#">
                    <img src="../img/control-notificaciones/alerta.svg" alt="solicitudes">&nbsp No hay Notificaciones</a>
                `);
            }
            
        },
        error:function(error) {
            console.warn(error);
            const { status, data } = error.responseJSON;
            if (status === 401) {
                window.location.href = '../views/401.php';
            }
            
        }
    });
};


//Petición para mostrar notificaciones a los usuarios de tipo jefeDepartamento
const notificacionesJefes = () => {

    $.ajax(`${ API }/control-notificaciones/generarNotificacionesJefes.php`, {
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        success:function(response) {
            //console.log(data);
            let cantidadSolicitudes = response.data[0].cantidadSolicitudes;
            let cantidadActividadesPendientes = parseInt(notificacionesActividadesJefes());
            //console.log(cantidadActividadesPendientes);
            if ((cantidadSolicitudes != 0) && (cantidadActividadesPendientes != 0)){
                //html(`<span id="contador" class="badge badge-danger badge-counter">${cantidadSolicitudes}</span>`);
                $('#contador').html(`${cantidadSolicitudes + cantidadActividadesPendientes}`);
                //$('#dropdown-notificaciones').html(`<a class="dropdown-item" href="#">Tiene solicitudes de permisos pendientes</a>`);
                $('#dropdown-notificaciones').html(`
                    <a class="dropdown-item" href="../views/control-recibir-permisos.php">
                    <img src="../img/control-notificaciones/icono-soli.svg" alt="solicitudes"> Tiene Permisos pendientes</a>
                    <a class="dropdown-item" href="../views/control-actividades-JefeCoordinador.php">
                    <img src="../img/menu/ver-icon.svg" alt="solicitudes">&nbsp Hay actividades pendientes </br>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp   de Ejecutar</a>
                `);
                //

            }else if(cantidadSolicitudes != 0){
                $('#contador').html(`${cantidadSolicitudes}`);
                $('#dropdown-notificaciones').html(`
                    <a class="dropdown-item" href="../views/control-recibir-permisos.php">
                    <img src="../img/control-notificaciones/icono-soli.svg" alt="solicitudes"> Tiene Permisos pendientes</a>
                `);
            }else if(cantidadActividadesPendientes != 0){
                $('#contador').html(`${cantidadActividadesPendientes}`);
                
                $('#dropdown-notificaciones').html(`
                    <a class="dropdown-item" href="../views/control-actividades-JefeCoordinador.php">
                    <img src="../img/menu/ver-icon.svg" alt="solicitudes">&nbsp Hay actividades pendientes </br>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp   de Ejecutar</a>
                `);
            }else {
                $('#contador').html(`${cantidadSolicitudes}`);
               // $('#contador').css({'display':'none'});
                //$('#dropdown-notificaciones').html(`<a class="dropdown-item" href="#">No hay Notificaciones</a>`);
                $('#dropdown-notificaciones').html(`
                    <a class="dropdown-item" href="#">
                    <img src="../img/control-notificaciones/alerta.svg" alt="solicitudes">&nbsp No hay Notificaciones</a>
                `);
                //notificacionesActividadesJefes(cantidadSolicitudes);
            }
            
        },
        error:function(error) {
            console.warn(error);
            const { status, data } = error.responseJSON;
            if (status === 401) {
                window.location.href = '../views/401.php';
            }
            
        }
    });
};


//Petición para mostrar notificaciones al usuario de tipo decano
const notificacionesDecano = () => {

    $.ajax(`${ API }/control-notificaciones/generarNotificacionesDecano.php`, {
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        success:function(response) {
            //console.log(data);
            let cantidadSolicitudes = response.data[0].cantidadSolicitudes;
            if (cantidadSolicitudes != 0){
                //html(`<span id="contador" class="badge badge-danger badge-counter">${cantidadSolicitudes}</span>`);
                $('#contador').html(`${cantidadSolicitudes}`);
                //$('#dropdown-notificaciones').html(`<a class="dropdown-item" href="#">Tiene solicitudes de permisos pendientes</a>`);
                $('#dropdown-notificaciones').html(`
                    <a class="dropdown-item" href="../views/control-recibir-permisos.php">
                    <img src="../img/control-notificaciones/icono-soli.svg" alt="solicitudes"> Tiene Permisos pendientes</a>
                `);
            }else {
                $('#contador').html(`${cantidadSolicitudes}`);
               // $('#contador').css({'display':'none'});
                $('#dropdown-notificaciones').html(`
                    <a class="dropdown-item" href="#">
                    <img src="../img/control-notificaciones/alerta.svg" alt="solicitudes">&nbsp No hay Notificaciones</a>
                `);

            }
            
        },
        error:function(error) {
            console.warn(error);
            const { status, data } = error.responseJSON;
            if (status === 401) {
                window.location.href = '../views/401.php';
            }
            
        }
    });
};


//Petición para mostrar notificaciones al usuario de tipo estratega
const notificacionesEstratega = () => {

    $.ajax(`${ API }/control-notificaciones/generarNotificacionesEstratega.php`, {
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        success:function(response) {
            //console.log(data);
            let cantidadInformes = response.data[0].cantidadInformes;
            if (cantidadInformes != 0){
                //html(`<span id="contador" class="badge badge-danger badge-counter">${cantidadSolicitudes}</span>`);
                $('#contador').html(`${cantidadInformes}`);
                //$('#dropdown-notificaciones').html(`<a class="dropdown-item" href="#">Tiene solicitudes de permisos pendientes</a>`);
                $('#dropdown-notificaciones').html(`
                    <a class="dropdown-item" href="../views/control-recibir-informes.php">
                    <img src="../img/control-notificaciones/icono-soli.svg" alt="solicitudes"> Tiene Informes pendientes</a>
                `);
            }else {
                $('#contador').html(`${cantidadInformes}`);
               // $('#contador').css({'display':'none'});
                $('#dropdown-notificaciones').html(`
                    <a class="dropdown-item" href="#">
                    <img src="../img/control-notificaciones/alerta.svg" alt="solicitudes">&nbsp No hay Notificaciones</a>
                `);                
            }
            
        },
        error:function(error) {
            console.warn(error);
            const { status, data } = error.responseJSON;
            if (status === 401) {
                window.location.href = '../views/401.php';
            }
            
        }
    });
};


//Petición para mostrar notificaciones a los usuarios de tipo coordinadores
const notificacionesCoordinadores = () => {

    $.ajax(`${ API }/control-notificaciones/generarNotificacionesCoordinadores.php`, {
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        success:function(response) {
            //console.log(data);
            let cantidadActividadesPendientes = response.data[0].cantidadActividadesPendientes;
            if (cantidadActividadesPendientes != 0){
                //html(`<span id="contador" class="badge badge-danger badge-counter">${cantidadSolicitudes}</span>`);
                $('#contador').html(`${cantidadActividadesPendientes}`);
                //$('#dropdown-notificaciones').html(`<a class="dropdown-item" href="#">Tiene solicitudes de permisos pendientes</a>`);
                $('#dropdown-notificaciones').html(`
                    <a class="dropdown-item" href="../views/control-actividades-JefeCoordinador.php">
                    <img src="../img/menu/ver-icon.svg" alt="solicitudes">&nbsp Hay actividades pendientes </br>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp   de Ejecutar</a>
                `);
            }else {
                $('#contador').html(`${cantidadActividadesPendientes}`);
               // $('#contador').css({'display':'none'});
                $('#dropdown-notificaciones').html(`
                    <a class="dropdown-item" href="#">
                    <img src="../img/control-notificaciones/alerta.svg" alt="solicitudes">&nbsp No hay Notificaciones</a>
                `);                
            }
            
        },
        error:function(error) {
            console.warn(error);
            const { status, data } = error.responseJSON;
            if (status === 401) {
                window.location.href = '../views/401.php';
            }
            
        }
    });
};



//Petición para mostrar notificaciones al usuario de tipo superAdministrador
const notificacionesSecAdministrativa = () => {

    $.ajax(`${ API }/control-notificaciones/generarNotificacionesEstratega.php`, {
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        success:function(response) {
            
            $('#contador').html(`${0}`);
            $('#dropdown-notificaciones').html(`
                <a class="dropdown-item" href="#">
                <img src="../img/control-notificaciones/alerta.svg" alt="solicitudes">&nbsp No hay Notificaciones</a>
            `);            
            
        },
        error:function(error) {
            console.warn(error);
            const { status, data } = error.responseJSON;
            if (status === 401) {
                window.location.href = '../views/401.php';
            }
            
        }
    });
};


//Petición para mostrar notificaciones al usuario de tipo superAdministrador
const notificacionesSuperAdmin = () => {

    $.ajax(`${ API }/control-notificaciones/generarNotificacionesEstratega.php`, {
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        success:function(response) {
            
            $('#contador').html(`${0}`);
            $('#dropdown-notificaciones').html(`
                <a class="dropdown-item" href="#">
                <img src="../img/control-notificaciones/alerta.svg" alt="solicitudes">&nbsp No hay Notificaciones</a>
            `);            
            
        },
        error:function(error) {
            console.warn(error);
            const { status, data } = error.responseJSON;
            if (status === 401) {
                window.location.href = '../views/401.php';
            }
            
        }
    });
};



//Petición para mostrar notificaciones a los usuarios de tipo jefeDepartamento
const notificacionesActividadesJefes = () => {
    let cantidadActividadesPendientes;
    $.ajax(`${ API }/control-notificaciones/generarNotificacionesActividadesJefes.php`, {
        async: false,
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        success:function(response) {
            
            cantidadActividadesPendientes = response.data[0].cantidadActividadesPendientes;
            //.log(cantidadActividadesPendientes);
            
        },
        error:function(error) {
            console.warn(error);
            const { status, data } = error.responseJSON;
            if (status === 401) {
                window.location.href = '../views/401.php';
            }
            
        }
        
    });
    return cantidadActividadesPendientes; 
};