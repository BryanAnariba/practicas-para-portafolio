$(document).ready(function() {
    calendario();
});

const calendario = () =>{
    var date = new Date();
    var yyyy = date.getFullYear().toString();
    var mm = (date.getMonth()+1).toString().length == 1 ? "0"+(date.getMonth()+1).toString() : (date.getMonth()+1).toString();
    var dd  = (date.getDate()).toString().length == 1 ? "0"+(date.getDate()).toString() : (date.getDate()).toString();
    
    $('#calendar').fullCalendar({
        header: {
            language: 'es',
            left: 'prev,next today',
            center: 'title',
            right: 'month,basicWeek,basicDay',
        },
        defaultDate: yyyy+"-"+mm+"-"+dd,
        eventLimit: true, // allow "more" link when too many events
        selectable: true,
        selectHelper: true,
        // select: function(start, end,event) {
        //     $('#ModalAdd #start').val(moment(start).format('YYYY-MM-DD HH:mm:ss'));
        //     $('#ModalAdd #end').val(moment(end).format('YYYY-MM-DD HH:mm:ss'));
        //     $('#ModalAdd').modal('show');
        // },
        eventRender: function(event, element) {
            element.bind('click', function() {
                console.log("Se selecciono solo un evento:",event);
                VerMasAct(event.id);
                $('#ModalVerActividadCalendario').modal('show');
            });
        },
        dayClick: function(date, allDay, jsEvent, view) {
            if(!allDay) {
                // strip time information
                date = new Date(date.getFullYear(), date.getMonth(), date.getDay());
            }
            document.getElementById("actividadFecha").innerHTML="<option value='' selected disabled>Seleccione la actividad:</option>";
            $('#calendar').fullCalendar('clientEvents', function(event) {
                if(event.start <= date && event.end >= date) {
                    console.log("Se selecciono todo el dia:",event.length,event);
                    document.getElementById("actividadFecha").innerHTML+=`<option value="${event.id}">${event.title}</option>`;
                    return true;
                }
                return false;
            });
        },
        eventSources: [{

            events: function(start, end, timezone, callback) {
                $.ajax({
                    url     : `${ API }/calendario-actividades/obtener-todas-actividades-dimension-admin.php`,
                    type    : 'post',
                    dataType: 'json',
                    data    : {
                        //  requires UNIX timestamps
                        start     : start.unix(),
                        end       : end.unix(),
                        component : 'Rak',
                        controller: 'Read',
                        task      : 'getCalendarEvents'
                    },
                    success : function(doc) {
                        var events = [];
                        var {data} = doc;
                        for(let i=0;i<data.length;i++){
                            if(idDepartamento==null){
                                let actividad = Adjuntar(data[i].idDimensionAdministrativa,data[i].anio,data[i].idDescripcionAdministrativa,data[i].nombreActividad,data[i].mesRequerido);
                                    events.push({
                                        id    : actividad.id,
                                        title    : actividad.title,
                                        start    : actividad.start, // will be parsed
                                        end      : actividad.end, // will be parsed
                                        color: actividad.color,
                                        textColor: actividad.textColor
                                });
                            }else{
                                if(idDepartamento==data[i].idDepartamento){
                                    let actividad = Adjuntar(data[i].idDimensionAdministrativa,data[i].anio,data[i].idDescripcionAdministrativa,data[i].nombreActividad,data[i].mesRequerido);
                                        events.push({
                                            id    : actividad.id,
                                            title    : actividad.title,
                                            start    : actividad.start, // will be parsed
                                            end      : actividad.end, // will be parsed
                                            color: actividad.color,
                                            textColor: actividad.textColor
                                    });
                                }
                            }
                        }
                        callback(events);
                    }

                });
            }
        }]
    });
}