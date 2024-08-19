/*--------------------------------------------------------------*/
/* Funcion para realizar busqueda de asignaciones con el buscador */
/*--------------------------------------------------------------*/
$(buscar_datos());

function buscar_datos(consulta){
    $.ajax({
        url: './table_asignaciones.php',
        type: 'POST',
        dataType: 'html' ,
        data: {consulta: consulta},
    })
    .done(function(respuesta){
        $("#datos").html(respuesta);
    })
    .fail(function(){
        console.log("error");
    })
}

$(document).on('keyup', '#caja_busqueda', function(){
    var valor = $(this).val();
    if(valor != ""){
        buscar_datos(valor);
    }else{
        buscar_datos();
    }
});

/*--------------------------------------------------------------*/
/* Funcion para realizar busqueda de resguardos con el buscador */
/*--------------------------------------------------------------*/
$(buscar_datosResguardo());

function buscar_datosResguardo(consultaResguardo){
    $.ajax({
        url: './table_resguardos.php',
        type: 'POST',
        dataType: 'html' ,
        data: {consulta: consultaResguardo},
    })
    .done(function(respuestaResguardo){
        $("#datosResguardo").html(respuestaResguardo);
    })
    .fail(function(){
        console.log("error");
    })
}

$(document).on('keyup', '#caja_busquedaResguardo', function(){
    var valorResguardo = $(this).val();
    if(valorResguardo != ""){
        buscar_datosResguardo(valorResguardo);
    }else{
        buscar_datosResguardo();
    }
});


/*--------------------------------------------------------------*/
/* Funcion para realizar busqueda de usuarios con el buscador */
/*--------------------------------------------------------------*/
$(buscar_datosUsers());

function buscar_datosUsers(consultaUsers){
    $.ajax({
        url: './table_users.php',
        type: 'POST',
        dataType: 'html' ,
        data: {consulta: consultaUsers},
    })
    .done(function(respuestaUsers){
        $("#datosUsers").html(respuestaUsers);
    })
    .fail(function(){
        console.log("error");
    })
}

$(document).on('keyup', '#caja_busquedaUsers', function(){
    var valorUsers = $(this).val();
    if(valorUsers != ""){
        buscar_datosUsers(valorUsers);
    }else{
        buscar_datosUsers();
    }
});


/*--------------------------------------------------------------*/
/* Funcion para realizar busqueda de detalles de usuario con el buscador */
/*--------------------------------------------------------------*/
$(buscar_datosDetalles());

function buscar_datosDetalles(consultaDetalles){
    $.ajax({
        url: './table_detalles.php',
        type: 'POST',
        dataType: 'html' ,
        data: {consulta: consultaDetalles},
    })
    .done(function(respuestaDetalles){
        $("#datosDetalles").html(respuestaDetalles);
    })
    .fail(function(){
        console.log("error");
    })
}

$(document).on('keyup', '#caja_busquedaDetalles', function(){
    var valorDetalles = $(this).val();
    if(valorDetalles != ""){
        buscar_datosDetalles(valorDetalles);
    }else{
        buscar_datosDetalles();
    }
});


/*--------------------------------------------------------------*/
/* Funcion para realizar busqueda de "mis asignaciones" con el buscador */
/*--------------------------------------------------------------*/
$(buscar_datosMisAsignaciones());

function buscar_datosMisAsignaciones(consultaAsignaciones){
    $.ajax({
        url: './table_misasignaciones.php',
        type: 'POST',
        dataType: 'html' ,
        data: {consulta: consultaAsignaciones},
    })
    .done(function(respuestaAsignaciones){
        $("#datosMisAsignaciones").html(respuestaAsignaciones);
    })
    .fail(function(){
        console.log("error");
    })
}

$(document).on('keyup', '#caja_busquedaMisAsignaciones', function(){
    var valorAsignaciones = $(this).val();
    if(valorAsignaciones != ""){
        buscar_datosMisAsignaciones(valorAsignaciones);
    }else{
        buscar_datosMisAsignaciones();
    }
});

/*--------------------------------------------------------------*/
/* Funcion para realizar busqueda de componentes con el buscador */
/*--------------------------------------------------------------*/
$(buscar_datosComponentes());

function buscar_datosComponentes(consultaComponentes){
    $.ajax({
        url: './table_products.php',
        type: 'POST',
        dataType: 'html' ,
        data: {consulta: consultaComponentes},
    })
    .done(function(respuestaComponentes){
        $("#datosComponentes").html(respuestaComponentes);
    })
    .fail(function(){
        console.log("error");
    })
}

$(document).on('keyup', '#caja_busquedaComponentes', function(){
    var valorComponentes = $(this).val();
    if(valorComponentes != ""){
        buscar_datosComponentes(valorComponentes);
    }else{
        buscar_datosComponentes();
    }
});