<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>E-mail Contribua</title>

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
		background-color: #299031;
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
	<h1> Olá, <?php echo $nome; ?></h1>
	<p> A partir de agora você terá acesso a Central de Membros/Frequentadores da Primeira Igreja Batista de Curitiba </p>
	<p> Seu Login é: <?php echo $login; ?> ou seu CPF e sua senha temporária é: <?php echo $senha; ?> </p>
	<p> Na primeira vez em que você fizer o login, será solicitado a alteração da mesma. </p>
    <p> Na Central, você poderá alterar seus dados pessoais, obter seu extrato de dizimos e ofertas e também acessar ao relatório das assembléias. </p>
	<p> Para acessar a Central de Membros <a href="https://central.pibcuritiba.org.br" target="_blank">clique aqui</a> </p>
	<p> Atenciosamente </p>
    <p> Ministério de Integração</p>
    <p> Primeira Igreja Batista de Curitiba</p>
    <p> <a href="https://pibcuritiba.org.br/" target="_blank">PIB Curitiba</a> </p>
	<div class="spacer"></div>
	<div class="decora"></div>
</div>
</body>
</html>
