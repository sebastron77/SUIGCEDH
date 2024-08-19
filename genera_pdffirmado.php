<?php 
$b64 = $_POST['cad_b64'] ; 
$name_documento = $_POST['name_documento'] ; 
$documento = explode(".",$name_documento);

$name_documento_final = $documento[0]."_firmado.pdf";

# Decode the Base64 string, making sure that it contains only valid characters
$bin = base64_decode($b64, true);

if (strpos($bin, '%PDF') !== 0) {
  throw new Exception('Missing the PDF file signature');
}

# Write the PDF contents to a local file
file_put_contents('uploads/firmaselectronicas/'.$name_documento_final, $bin);



?>