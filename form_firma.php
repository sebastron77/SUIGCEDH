<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Firma Digital';
require_once('includes/load.php');


$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id_user'];

?>
<?php header('Content-type: text/html; charset=utf-8');

?>
 
<script>
function realizaProceso(){
	$("#token").html("");					
        var parametros = {
                "email" : 'pruebas@gmail.com',
                "password" : '78rR8D]]'
        };
		
		var token="";
		document.getElementById('cargando').style.display = 'block';						
        $.ajax({
                data:  parametros,
                url:   'https://tableroelectronico-qa.michoacan.gob.mx/api/login',
                type:  'post',
				dataType : 'json',
                beforeSend: function () {
                        //$("#resultado").html("Procesando, espere por favor...");
                },
                success:  function (data) {
						var token=data.token;						
						 var serializedForm = $("#formulario").serializeArray();
   
					   // Create an FormData object 
						var dataform = new FormData();
						
						//aqui se cargan todod los inputs para envio a travez de ajax
					   $.each(serializedForm, function (key, input) {
								//alert("Input "+input.name+" /V:"+input.value);
								dataform.append(input.name, encodeURIComponent(input.value));
						});
												
						dataform.append("pdf[]", $('input[name=documento]')[0].files[0]);									
						dataform.append("cer", $('input[name=certificado]')[0].files[0]);	
						dataform.append("key", $('input[name=key]')[0].files[0]);								
						
						//12345678a
						var documento = $('input[name=documento]')[0].value.split("\\");
						var name_documento = documento[documento.length-1];
                        //alert( $('input[name=documento]')[0].value);
                        //alert( name_documento);
                        $("#token").html(data.token);	
						
							$.ajax({
								url : 'https://tableroelectronico-qa.michoacan.gob.mx/api/firmarPDF',							
								type:  'post',
								enctype: 'multipart/form-data',
								data: dataform,
								processData: false,
								contentType: false,
								dataType : 'json',
								headers: {'Authorization': 'Bearer'+data.token},
								success : function(response) {								
								   $("#resultado").html("Generando PDF...");
									var resultado = JSON.stringify(response);
									 var arrayDeCadenas = resultado.split(":");									 
									 var valor_inicial = arrayDeCadenas[arrayDeCadenas.length-1].replace('"','');
									 var cadena_b64 = valor_inicial.substr(0, valor_inicial.length-3);
									 var tam_cadena = cadena_b64.length;
									 if(tam_cadena > 1000){
									$.post('genera_pdffirmado.php', {cad_b64:cadena_b64,name_documento:name_documento}, function(responsse){ 
											  //alert("success");
											  //$("#mypar").html(responsse.amount);
												var documento_pdf = name_documento.split(".");
												var name_documento_final = documento_pdf[0]+"_firmado.pdf";
												$("#resultado").html("Listo Generado "+tam_cadena);													
												window.open("uploads/firmaselectronicas/"+name_documento_final,"_blank");
											
										});					
									 }else{
													$("#resultado").html("Algo ocurrio "+cadena_b64); 
									 }
										
										
								   //$("#resultado").html(cad_b64);
									document.getElementById('cargando').style.display = 'none';														   
								},
								
								error : function(response) {							   			
									 $("#resultado").html('Disculpe, existió un problema.'+JSON.stringify(response));
									 
								}
						});						
						
				},
				error : function(data) {
                        $("#resultado").html('Disculpe, existió un problema');
				}
        });
}
</script>
<?php include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>

<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Datos para Envio de Documento a firma Digital</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="create_firma.php?" id="formulario" name="formulario" enctype="multipart/form-data">
                <div class="row">
								
					
                     <div class="col-md-3">
                        <div class="form-group">
                            <label for="documento">Documento a Firmar</label>
                            <input type="file" accept="application/pdf" class="form-control" name="documento" id="documento" required>
                        </div>
                    </div>
				 
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="certificado">Certificado</label>
                            <input type="file" accept=".cer" class="form-control" name="certificado" id="certificado" required>
                        </div>
                    </div>
					
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="key">Key</label>
                            <input type="file" accept=".key" class="form-control" name="key" id="key" required>
                        </div>
                    </div>
					
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="pass" id="pass" class="form-control" placeholder="Password">
                            <input type="hidden" name="cadenaOrigen" id="cadenaOrigen" value="prueba">
                            <input type="hidden" name="clave_tramite" id="clave_tramite" value="PCEDH">
                        </div>
                    </div>
					
					<div class="col-md-6">
                        <div class="form-group">
                            <label for="observaciones">Token</label>
                            <textarea class="form-control" name="token" id="token" cols="10" rows="5"></textarea>
                        </div>
                    </div>
					
					<div class="col-md-6">
                        <div class="form-group">
                            <label for="observaciones">Resultado</label>
                            <textarea class="form-control" name="resultado" id="resultado" cols="10" rows="5"></textarea>
                        </div>
                    </div>	
					
					
					
				</div>		
				
				<div class="row">
					<div class="col-md-12">
                        <div class="form-group">
                            <img src="medios/10-11-02-622_512.gif" width="100px;" id="cargando" style="display: none;"> 
                        </div>
                    </div>	
				</div>		
               
				
					
                <div class="form-group clearfix">
                    <a href="cursos_diplomados.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="button" name="create_firma" class="btn btn-primary" onclick="realizaProceso();return false;" value="subir">Firmar</button>
					
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>