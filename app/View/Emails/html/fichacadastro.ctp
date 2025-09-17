<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>E-mail </title>

<style>
	html, body{
		margin: 0;
		padding: 0;

	}
	.container{
		position: relative;
		width: 700px;
		margin: 0 auto;
		font-family: "Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, "sans-serif";
		}
	.decora{
		width: 100%;
		height: 25px;
		display: block;
		background-color: #636E7B;
	}
	.img-topo{
		margin: 10px 0;
	}
	.spacer{
		width: 100%;
		height: 1px;
		margin: 5px auto;
	}
	.logopib{
		width: 234px;
		height: 50px;
		position: relative;
		display: inline;
		margin: 0 auto!important;
	}
</style>
</head>

<body>
<div class="container">
	<div class="decora"></div>
	<img src="https://www.pibcuritiba.org.br/wp-content/uploads/2017/03/logopib.png" class="img-topo" alt="PIB" style="
    float: right;
">
	<h1> Olá, <?php echo $nomeMembro; ?>. </h1>	
    <p>Você deve preencher esta ficha até a entrevista (caso já tenha preenchido, desconsidere):</p>
    <p><?php echo $link; ?></p>
	<p> Qualquer dúvida ou problema com nossa plataforma entre em contato conosco para mais informações pelo seguinte email ou telefone:</p>
	<p> integracao@pibcuritiba.org.br - 3091-4439 (ligações e WhatsApp) </p>
	<p><b>Este é um email automático, favor não responder.</b></p>
	<!--<p style="text-align: center; margin: 5px 0;"><img src="https://www.pibcuritiba.org.br/wp-content/uploads/2017/03/logopib.png" class="logopib" alt="Primeira Igreja Batista de Curitiba"></p>-->
	<hr>
	<div class="decora"></div>
</div>
</body>
</html>
