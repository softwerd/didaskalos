var x;
x = $(document);
x.ready(inicializarEventos);

function inicializarEventos() {
	var x = $("#aLectivo");
	x.click(buscarInscriptos);
        x = $("#ayn");
        x.change(buscarHistorialDocente);
}

function buscarInscriptos(){
    var v=$("#aLectivo").attr("value");
    url=document.location.hostname;
    $.get("http://www.pequehogar.com.ar/Didaskalos/index.php?option=alumnos&sub=ajaxAlumnosParaInscribir&aLectivo="+v,
        function(data){
            $("#alumnos").empty();
                $("#alumnos").append(data);
            }
    );
}

function buscarHistorialDocente(){  
    var v=$("#ayn").attr("value");
    if (v > 0){
        jQuery("#grilla").jqGrid('setGridParam',{url:'index.php?option=docentes&sub=jsonListarHistorialDocente'});
    }
}

$(function() {
    $("#fechaNac").datepicker({
    showOn: 'both',
//    buttonImage: 'calendar.png',
//    buttonImageOnly: true,
    changeYear: true,
//    numberOfMonths: 2,
    onSelect: function(textoFecha, objDatepicker){
    $("#mensaje").html("<p>Has seleccionado: " + textoFecha + "</p>");
   }
});
});

$(function() {
    $("#fechaInicio").datepicker({
    showOn: 'both',
//    buttonImage: 'calendar.png',
//    buttonImageOnly: true,
    changeYear: true,
//    numberOfMonths: 2,
    onSelect: function(textoFecha, objDatepicker){
    $("#mensaje").html("<p>Has seleccionado: " + textoFecha + "</p>");
   }
});
});

$(function() {
    $("#fechaFin").datepicker({
    showOn: 'both',
//    buttonImage: 'calendar.png',
//    buttonImageOnly: true,
    changeYear: true,
//    numberOfMonths: 2,
    onSelect: function(textoFecha, objDatepicker){
    $("#mensaje").html("<p>Has seleccionado: " + textoFecha + "</p>");
   }
});
});

$(function() {
    $("#fecha_comprobante").datepicker({
    showOn: 'both',
//    buttonImage: 'calendar.png',
//    buttonImageOnly: true,
    changeYear: true,
//    numberOfMonths: 2,
    onSelect: function(textoFecha, objDatepicker){
    $("#mensaje").html("<p>Has seleccionado: " + textoFecha + "</p>");
   }
});
});

