<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Estadísticas de Orientaciones';
require_once('includes/load.php');
?>
<?php
$user = current_user();
$nivel = $user['user_level'];
$id_user = $user['id'];
$id_user = $user['id'];

if ($nivel <= 2) {
    page_require_level(2);
}
if ($nivel == 3) {
    page_require_level(3);
}
if ($nivel == 4) {
    redirect('home.php');
}
if ($nivel == 5) {
    page_require_level_exacto(5);
}
if ($nivel == 6) {
    redirect('home.php');
}
if ($nivel == 7) {
    page_require_level_exacto(7);
}

$total_mujeres = count_by_id_mujer('orientacion_canalizacion', 1);
$total_hombres = count_by_id_hombre('orientacion_canalizacion', 1);
$total_lgbtiq = count_by_id_lgbt('orientacion_canalizacion', 1);
$total_lgbt = count_by_id_lgbt2('orientacion_canalizacion', 1);

$total_gv_lgbt = count_by_comLg('orientacion_canalizacion', 1);
$total_gv_lgbt2 = count_by_comLg2('orientacion_canalizacion', 1);
$total_der_mujer = count_by_derMuj('orientacion_canalizacion', 1);
$total_nna = count_by_nna('orientacion_canalizacion', 1);
$total_nna2 = count_by_nna2('orientacion_canalizacion', 1);
$total_disc = count_by_disc('orientacion_canalizacion', 1);
$total_mig = count_by_mig('orientacion_canalizacion', 1);
$total_vih = count_by_vih('orientacion_canalizacion', 1);
$total_gi = count_by_gi('orientacion_canalizacion', 1);
$total_perio = count_by_perio('orientacion_canalizacion', 1);
$total_ddh = count_by_ddh('orientacion_canalizacion', 1);
$total_am = count_by_am('orientacion_canalizacion', 1);
$total_int = count_by_int('orientacion_canalizacion', 1);
$total_otros = count_by_otros('orientacion_canalizacion', 1);
$total_na = count_by_na('orientacion_canalizacion', 1);

$total_asesorv = count_by_asesorv('orientacion_canalizacion', 1);
$total_asistentev = count_by_asistentev('orientacion_canalizacion', 1);
$total_comp = count_by_comp('orientacion_canalizacion', 1);
$total_escrito = count_by_escrito('orientacion_canalizacion', 1);
$total_vt = count_by_vt('orientacion_canalizacion', 1);
$total_ve = count_by_ve('orientacion_canalizacion', 1);
$total_cndh = count_by_cndh('orientacion_canalizacion', 1);

$total_sin_est = count_by_sin_est('orientacion_canalizacion', 1);
$total_primaria = count_by_primaria('orientacion_canalizacion', 1);
$total_secundaria = count_by_secundaria('orientacion_canalizacion', 1);
$total_preparatoria = count_by_preparatoria('orientacion_canalizacion', 1);
$total_licenciatura = count_by_licenciatura('orientacion_canalizacion', 1);
$total_especialidad = count_by_especialidad('orientacion_canalizacion', 1);
$total_maestria = count_by_maestria('orientacion_canalizacion', 1);
$total_doctorado = count_by_doctorado('orientacion_canalizacion', 1);
$total_posdoctorado = count_by_posdoctorado('orientacion_canalizacion', 1);

?>
<?php include_once('layouts/header.php'); ?>


<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<!-- ChartJS -->
<script src="chart.js/Chart.js"></script>
</head>
<body>
<div class="container">
<h1 class="page-header text-left">Gráfico de líneas usando Chart.js con PHP</h1>
<div class="row">
<form class="navbar-form navbar-left" method="POST" action="agregar_ventas.php">
<div class="form-group">
<input name="monto" type="text" class="form-control" placeholder="Monto">
</div>
<div class="form-group">
<input name="venta_fecha" type="date" class="form-control" placeholder="Fecha">
</div>
<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Agregar Venta </button>
</form>

</div><!-- /.row -->
<div class="row">

<div class="col-md-10">
<div class="box box-success">
<div class="box-header with-border">
<?php
$year = date('Y');
?>
<h3 class="box-title">Reporte de ventas (<?php echo $year-1; ?> vs <?php echo $year; ?>)</h3>

</div>
<div class="box-body">
<div class="chart">
<canvas id="lineChart" style="height:250px"></canvas>
</div>
</div>
<!-- /.box-body -->
</div>
</div>
</div>
</div>
<?php include('proceso.php'); ?>
<script>
$(function () {
var lineChartData = {
labels : ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre'],
datasets: [
{
label : 'Año Previo',
fillColor : 'rgba(210, 214, 222, 1)',
strokeColor : 'rgba(210, 214, 222, 1)',
pointColor : 'rgba(210, 214, 222, 1)',
pointStrokeColor : '#c1c7d1',
pointHighlightFill : '#fff',
pointHighlightStroke: 'rgba(220,220,220,1)',
data : [ "<?php echo $pjan; ?>",
"<?php echo $pfeb; ?>",
"<?php echo $pmar; ?>",
"<?php echo $papr; ?>",
"<?php echo $pmay; ?>",
"<?php echo $pjun; ?>",
"<?php echo $pjul; ?>",
"<?php echo $paug; ?>",
"<?php echo $psep; ?>",
"<?php echo $poct; ?>",
"<?php echo $pnov; ?>",
"<?php echo $pdec; ?>" 
]
},
{
label : 'Este año',
fillColor : 'rgba(60,141,188,0.9)',
strokeColor : 'rgba(60,141,188,0.8)',
pointColor : '#3b8bba',
pointStrokeColor : 'rgba(60,141,188,1)',
pointHighlightFill : '#fff',
pointHighlightStroke: 'rgba(60,141,188,1)',
data : [ "<?php echo $tjan; ?>",
"<?php echo $tfeb; ?>",
"<?php echo $tmar; ?>",
"<?php echo $tapr; ?>",
"<?php echo $tmay; ?>",
"<?php echo $tjun; ?>",
"<?php echo $tjul; ?>",
"<?php echo $taug; ?>",
"<?php echo $tsep; ?>",
"<?php echo $toct; ?>",
"<?php echo $tnov; ?>",
"<?php echo $tdec; ?>" 
]
}
]
}

var lineChartCanvas = $('#lineChart').get(0).getContext('2d')
var lineChart = new Chart(lineChartCanvas)
var lineChartOptions = {
//Boolean - If we should show the scale at all
showScale : true,
//Boolean - Whether grid lines are shown across the chart
scaleShowGridLines : false,
//String - Colour of the grid lines
scaleGridLineColor : 'rgba(0,0,0,.05)',
//Number - Width of the grid lines
scaleGridLineWidth : 1,
//Boolean - Whether to show horizontal lines (except X axis)
scaleShowHorizontalLines: true,
//Boolean - Whether to show vertical lines (except Y axis)
scaleShowVerticalLines : true,
//Boolean - Whether the line is curved between points
bezierCurve : true,
//Number - Tension of the bezier curve between points
bezierCurveTension : 0.3,
//Boolean - Whether to show a dot for each point
pointDot : false,
//Number - Radius of each point dot in pixels
pointDotRadius : 4,
//Number - Pixel width of point dot stroke
pointDotStrokeWidth : 1,
//Number - amount extra to add to the radius to cater for hit detection outside the drawn point
pointHitDetectionRadius : 20,
//Boolean - Whether to show a stroke for datasets
datasetStroke : true,
//Number - Pixel width of dataset stroke
datasetStrokeWidth : 2,
//Boolean - Whether to fill the dataset with a color
datasetFill : true,
//String - A legend template
legendTemplate : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].lineColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
//Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
maintainAspectRatio : true,
//Boolean - whether to make the chart responsive to window resizing
responsive : true
}

lineChartOptions.datasetFill = false
lineChart.Line(lineChartData, lineChartOptions)

})
</script>

<?php include_once('layouts/footer.php'); ?>