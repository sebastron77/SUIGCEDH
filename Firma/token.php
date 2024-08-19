<html>
 
<head>
 
<title>Ejemplo sencillo de AJAX</title>
 
<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
 
<script>
function realizaProceso(){
        var parametros = {
                "email" : 'pruebas@gmail.com',
                "password" : '78rR8D]]'
        };
        $.ajax({
                data:  parametros,
                url:   'https://tableroelectronico-qa.michoacan.gob.mx/api/login',
                type:  'post',
				dataType : 'json',
                beforeSend: function () {
                        $("#resultado").html("Procesando, espere por favor...");
                },
                success:  function (data) {
                        $("#resultado").html(data.token);					
				},
				error : function(response) {
                        $("#resultado").html('Disculpe, existió un problema');
				}
        });
}
</script>
 
</head>
 
<body>
 
Introduce valor 1
 
<input type="text" name="caja_texto" id="valor1" value="0"/> 
 
 
Introduce valor 2
 
<input type="text" name="caja_texto" id="valor2" value="0"/>
 
Realiza suma
 
<input type="button" href="javascript:;" onclick="realizaProceso();return false;" value="Genera token"/>
 
<br/>
 
Resultado: <span id="resultado">0</span>
 
</body>
 
</html>