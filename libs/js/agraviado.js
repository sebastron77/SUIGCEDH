// Función autocompletar
function autocompletar2() {
	var minimo_letras = 0; // minimo letras visibles en el autocompletar
	var palabra2 = $('#id_cat_agrav').val();
	
	//Contamos el valor del input mediante una condicional
	if (palabra2.length >= minimo_letras) {
		$.ajax({
			url: 'mostrar_agraviado.php',
			type: 'POST',
			data: {palabra2:palabra2},
			success:function(data){
				$('#lista_id2').show();
				$('#lista_id2').html(data);				
			}
		});
	} else {
		//ocultamos la lista		
		$('#lista_id2').hide();
	}
}

// Funcion Mostrar valores
function set_item2(id,opciones) {	
	// Cambiar el valor del formulario input
	$('#id_cat_agrav').val(opciones);
	$('#agraviado').val(id);
	
	// ocultar lista de proposiciones
	$('#lista_id2').hide();
}