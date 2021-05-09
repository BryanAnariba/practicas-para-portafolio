$(document).ready(function () {
    $.ajax(`${ API }/usuarios/menu.php`, {
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        success:function(response) {
            const { data } = response;
            console.log(data);
        },
        error:function(error) {
            console.log(error.responseJSON);
            const { status, data } = error.responseJSON;
                if (status === 401) {
                    window.location.href = '../views/401.php';
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Ops...',
                    text: `${ data.message }`,
                    footer: '<b>Debes loguearte nuevamente</b>',
                    confirmButtonText: "Ir al login"
                });
                
            
        }
    });
});