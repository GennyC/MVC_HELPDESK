<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title>HELPDESK — Registro</title>

	<link href="public/img/favicon.png" rel="icon" type="image/png">
	<link rel="stylesheet" href="public/css/separate/pages/login.min.css">
	<link rel="stylesheet" href="public/css/lib/font-awesome/font-awesome.min.css">
	<link rel="stylesheet" href="public/css/lib/bootstrap/bootstrap.min.css">
	<link rel="stylesheet" href="public/css/main.css">
</head>
<body>

<div class="page-center">
	<div class="page-center-in">
		<div class="container-fluid">

<form class="sign-box" method="POST" action="index.php?axn=sign-up">
	<div class="sign-avatar no-photo">&plus;</div>
	<header class="sign-title">Crear Cuenta</header>

	<div class="form-group">
		<input type="text" class="form-control" name="nombre" placeholder="Nombre" required />
	</div>
	<div class="form-group">
		<input type="email" class="form-control" name="email" placeholder="E-Mail" required />
	</div>
	<div class="form-group">
		<input type="password" class="form-control" name="password" placeholder="Password" required />
	</div>
	<div class="form-group">
		<input type="password" class="form-control" name="password2" placeholder="Repite password" required />
	</div>

	<!-- Campo oculto: rol siempre cliente -->
	<input type="hidden" name="rol" value="cliente">
	<input type="hidden" name="registro" value="1">

	<button type="submit" class="btn btn-rounded btn-success sign-up">Registrar</button>

	<p class="sign-note">
		¿Ya tienes cuenta? <a href="index.php?axn=sign-in">Inicia sesión</a>
	</p>
</form>

<?php
require_once __DIR__ . "/../controllers/controladorLogin.php";

// Procesar registro
$registro = ctrlLogin::ctrlRegistroUsuario();

if ($registro == "ok") {
	echo "<div class='alert alert-success mt-3'>✅ Usuario registrado con éxito</div>";
} elseif ($registro == "error") {
	echo "<div class='alert alert-danger mt-3'>❌ Error al registrar el usuario</div>";
}
?>

		</div>
	</div>
</div><!--.page-center-->

<script src="public/js/lib/jquery/jquery.min.js"></script>
<script src="public/js/lib/tether/tether.min.js"></script>
<script src="public/js/lib/bootstrap/bootstrap.min.js"></script>
<script src="public/js/plugins.js"></script>
<script src="public/js/lib/match-height/jquery.matchHeight.min.js"></script>
<script>
	$(function() {
		$('.page-center').matchHeight({ target: $('html') });
		$(window).resize(function(){
			setTimeout(function(){
				$('.page-center').matchHeight({ remove: true });
				$('.page-center').matchHeight({ target: $('html') });
			},100);
		});
	});
</script>
<script src="public/js/app.js"></script>
</body>
</html>
