
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- <META HTTP-EQUIV="Refresh" CONTENT="0; URL=index.php"> -->

	<title>Organigrama CEDH Michoacán</title>

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<!-- Optional theme -->
	<link rel="stylesheet" href="main.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Questrial&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<style type="text/css">
  body {
  
}
img{
	width: 80px;
	max-width: 80%;
}

.organigrama * {
  margin: 0px;
  padding: 0px;
}

.organigrama ul {
	padding-top: 20px;
  position: relative;
}

.organigrama li {
	float: left;
  text-align: center;
	list-style-type: none;
	padding: 20px 5px 0px 5px;
  position: relative;
}

.organigrama li::before, .organigrama li::after {
	content: '';
	position: absolute;
  top: 0px;
  right: 50%;
	border-top: 1px solid #f80;
	width: 50%;
  height: 20px;
}

.organigrama li::after{
	right: auto;
  left: 50%;
	border-left: 1px solid #f80;
}

.organigrama li:only-child::before, .organigrama li:only-child::after {
	display: none;
}

.organigrama li:only-child {
  padding-top: 0;
}

.organigrama li:first-child::before, .organigrama li:last-child::after{
	border: 0 none;
}

.organigrama li:last-child::before{
	border-right: 1px solid #f80;
	-webkit-border-radius: 0 5px 0 0;
	-moz-border-radius: 0 5px 0 0;
	border-radius: 0 5px 0 0;
}

.organigrama li:first-child::after{
	border-radius: 5px 0 0 0;
	-webkit-border-radius: 5px 0 0 0;
	-moz-border-radius: 5px 0 0 0;
}

.organigrama ul ul::before {
	content: '';
	position: absolute;
  top: 0;
  left: 50%;
	border-left: 1px solid #f80;
	width: 0;
  height: 20px;
}

.organigrama li a {
	padding: 1em 0.75em;
	text-decoration: none;
	color: #333;
  background-color: rgba(255,255,255,0.5);
	font-family: arial, verdana, tahoma;
	font-size: 0.85em;
	display: inline-block;
	border-radius: 5px;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
  -webkit-transition: all 500ms;
  -moz-transition: all 500ms;
  transition: all 500ms;
}

.organigrama li a:hover {
	font-size: 0.95em;
	color: #ddd;
	display: inline-block;
}

.organigrama > ul > li > a {
  font-size: 1em;
  font-weight: bold;
}

.organigrama > ul > li > ul > li > a {
  width: 8em;
}

.tileA {
   height: 120px;
  width: 120px;
  margin: 10px;
  display: inline-block;
  text-decoration: none;
  color: #000;
  border-radius: 5px;
  user-select: none;
  transition: all 0.2s ease-in-out;
  background-color: #fff;
  text-align: center;
  display: flex;
   align-items: center;
   justify-content: center;
}

.tileA-tittle {
    margin: 0;
    width: 100%;
    padding: 0;
    height: 30px;
    line-height: 40px;
    box-sizing: border-box;
    text-transform: uppercase;
    border-bottom: 1px solid #d8d8d8;
    transition: all 0.2s ease-in-out;
    font-size: 13px;
    font-weight: 500;
}

.tileA-icon {
    width: 100%;
    height: 160px;
    box-sizing: border-box;
   
  margin: 0 auto;
  border-radius: 50%;
  overflow: hidden;
}

</style>
</head>
 <body>
<div class="organigrama">

    <ul>		
		<li>
			<a href="#" class="tileA">
				<div class="tileA-tittle">Presidente</div>
				<div class="tileA-icon">
						<img src="https://cedhmichoacan.org/images/Areas/presidente_foto-modified.png" alt=" mostrar"   />
				</div>
			</a>
			<ul>
					<ul>
			
			
				<li>
					<a href="#" class="tileA">
						<div class="tileA-tittle">Secretaría Técnica</div>
						<div class="tileA-icon">
							<img src="https://cedhmichoacan.org/images/Fotos_Directivos/Diapositiva62-modified.png" alt=" mostrar"   /> 
						</div>
					</a>
				</li>
				<li>
					<a href="#" class="tileA">
						<div class="tileA-tittle">Agendas</div>
						<div class="tileA-icon">
								<img src="https://cedhmichoacan.org/images/Fotos_Directivos/Diapositiva74-modified.png" alt=" mostrar"   /> 
						</div>
					</a>
				</li>
				<li>
							<a href="#" class="tileA">
								<div class="tileA-tittle">Comunicación Social</div>
								<div class="tileA-icon">
										<img src="https://cedhmichoacan.org/images/Fotos_Directivos/Diapositiva66-modified.png" alt=" mostrar"   /> 
								</div>
							</a>
						</li>
				<li>
					<a href="#" class="tileA">
						<div class="tileA-tittle">Visitadurias</div>
						<div class="tileA-icon">
								<img src="https://cedhmichoacan.org/images/Fotos_Directivos/Diapositiva72-modified.png" alt=" mostrar"   /> 
						</div>
					</a>
					
					<ul>
						<li>
							<a href="#" class="tileA">
								<div class="tileA-tittle">Visitadurias</div>
								<div class="tileA-icon">
										<img src="https://cedhmichoacan.org/images/Fotos_Directivos/Diapositiva71-modified.png"alt=" mostrar"   /> 
								</div>
							</a>
						</li>
						<li>
							<a href="#" class="tileA">
								<div class="tileA-tittle">Visitadurias</div>
								<div class="tileA-icon">
										<img src="https://cedhmichoacan.org/images/Fotos_Directivos/Diapositiva65-modified.png"alt=" mostrar"   /> 
								</div>
							</a>
						</li>
						<li>
							<a href="#" class="tileA">
								<div class="tileA-tittle">Visitadurias</div>
								<div class="tileA-icon">
										<img src="https://cedhmichoacan.org/images/Fotos_Directivos/Diapositiva60-modified.png"alt=" mostrar"   /> 
								</div>
							</a>
						</li>
						<li>
							<a href="#" class="tileA">
								<div class="tileA-tittle">Visitadurias</div>
								<div class="tileA-icon">
										<img src="https://cedhmichoacan.org/images/Fotos_Directivos/Diapositiva63-modified.png"alt=" mostrar"   /> 
								</div>
							</a>
						</li>
						<li>
							<a href="#" class="tileA">
								<div class="tileA-tittle">Visitadurias</div>
								<div class="tileA-icon">
										<img src="https://cedhmichoacan.org/images/Fotos_Directivos/Diapositiva67-modified.png"alt=" mostrar"   /> 
								</div>
							</a>
						</li>
						<li>
							<a href="#" class="tileA">
								<div class="tileA-tittle">Visitadurias</div>
								<div class="tileA-icon">
										<img src="https://cedhmichoacan.org/images/Fotos_Directivos/Diapositiva74-modified.png"alt=" mostrar"   /> 
								</div>
							</a>
						</li>
						<li>
							<a href="#" class="tileA">
								<div class="tileA-tittle">Visitadurias</div>
								<div class="tileA-icon">
										<img src="https://cedhmichoacan.org/images/Fotos_Directivos/Diapositiva73-modified.png"alt=" mostrar"   /> 
								</div>
							</a>
						</li>
						
						
					</ul>
					
				</li>
				
			</ul>
			</ul>		
		</li>
		
		<li>
			<a href="#" class="tileA">
				<div class="tileA-tittle">Consejero</div>
				<div class="tileA-icon">
						<img src="https://cedhmichoacan.org/images/Fotos_Directivos/Diapositiva57-modified.png"alt=" mostrar"   />
				</div>
			</a>
		</li>
		
		<li>
			<a href="#" class="tileA">
				<div class="tileA-tittle">Consejero</div>
				<div class="tileA-icon">
						<img src="https://cedhmichoacan.org/images/Fotos_Directivos/Diapositiva56-modified.png" alt=" mostrar"   />
				</div>
			</a>
		</li>
		<li>
			<a href="#" class="tileA">
				<div class="tileA-tittle">Consejero</div>
				<div class="tileA-icon">
						<img src="https://cedhmichoacan.org/images/Fotos_Directivos/Diapositiva58-modified_1.png"alt=" mostrar"   />
				</div>
			</a>
		</li>
		
		<li>
			<a href="#" class="tileA">
				<div class="tileA-tittle">Consejero</div>
				<div class="tileA-icon">
						<img src="https://cedhmichoacan.org/images/Fotos_Directivos/Diapositiva59-modified.png" alt=" mostrar"   />
				</div>
			</a>
		</li>
		
    </ul>
</div>
</body>