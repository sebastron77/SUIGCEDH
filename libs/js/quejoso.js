// Función autocompletar
function autocompletar() {
	var minimo_letras = 0; // minimo letras visibles en el autocompletar
	var palabra = $('#id_cat_quejoso').val();
	//Contamos el valor del input mediante una condicional
	if (palabra.length >= minimo_letras) {
		$.ajax({
			url: 'mostrar_quejoso.php',
			type: 'POST',
			data: {palabra:palabra},
			success:function(data){
				$('#lista_id').show();
				$('#lista_id').html(data);
			}
		});
	} else {
		//ocultamos la lista
		$('#lista_id').hide();
	}
}

// Funcion Mostrar valores
function set_item(id,opciones) {
	// Cambiar el valor del formulario input
	$('#id_cat_quejoso').val(opciones);
	$('#quejoso').val(id);

	// ocultar lista de proposiciones
	$('#lista_id').hide();
}