<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->

<?php
// error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL);
ini_set('display_errors', 1);
$page_title = 'QR del Personal';
require_once('../includes/load.php');

// $idP =  (int)$_GET['id'];

$user = current_user();
$nivel_user = $user['user_level'];

if ($nivel_user == 1) {
    page_require_level_exacto(1);
}
if ($nivel_user == 2) {
    page_require_level_exacto(2);
}
if ($nivel_user == 14) {
    page_require_level_exacto(14);
}
if ($nivel_user > 2 && $nivel_user < 14) :
    redirect('home.php');
endif;
if ($nivel_user > 14) :
    redirect('home.php');
endif;
?>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/suigcedh/layouts/header.php';?>

<?php   
    
    echo "";
    
    //set it to writable location, a place for temp generated PNG files
    $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;
    
    //html PNG location prefix
    $PNG_WEB_DIR = 'temp/';

    include "phpqrcode.php";
    
    //ofcourse we need rights to create temp dir
    if (!file_exists($PNG_TEMP_DIR))
        mkdir($PNG_TEMP_DIR);
    
    
    $filename = $PNG_TEMP_DIR.'test.png';
    
    //processing form input
    //remember to sanitize user input in real-life solution !!!
    $errorCorrectionLevel = 'L';
    if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L','M','Q','H')))
        $errorCorrectionLevel = $_REQUEST['level'];    

    $matrixPointSize = 4;
    if (isset($_REQUEST['size']))
        $matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);


    if (isset($_REQUEST['data'])) { 
    
        //it's very important!
        if (trim($_REQUEST['data']) == '')
            die('data cannot be empty! <a href="?">back</a>');
            
        // user data
		$data = "https://177.229.209.29/RH/empleado.php?id=".$_REQUEST['data'];
        $filename = $PNG_TEMP_DIR.'test'.md5($_REQUEST['data'].'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
        QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
        
    } else {    
    
        //default data
        //echo 'You can provide data in GET parameter: <a href="?data=like_that">like that</a><hr/>';    
        QRcode::png('PHP QR Code :)', $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
        
    }

    echo '<form action="index.php" method="post">
		<div class="dates-empleado">
		<table style="margin: 0 auto; font-size: 28px; color: black; text-align: center; border-spacing: 20px; border-collapse: separate;">
						<tr> <td> <h1>QR de Empleado</h1><hr/></td> </tr>
						<tr> <td style="text-align: center;"> <img src="'.$PNG_WEB_DIR.basename($filename).'" /></td> </tr>
						<tr> <td> No. Empleado:&nbsp;<input name="data" value="'.(isset($_REQUEST['data'])?htmlspecialchars($_REQUEST['data']):'0').'" />&nbsp; </td> </tr>
						<tr> <td> <input type="hidden" name="level" value="H"></td> </tr>
						<tr> <td> <input type="hidden" name="size" value="10"></td> </tr>
						<tr> <td> <input type="submit" value="Generar QR">  </td> </tr>										
					</table>
		</div>
		</form><hr/>';  

    ?>
<?php include_once('../layouts/footer.php'); ?>