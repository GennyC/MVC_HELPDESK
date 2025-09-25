<?php
require_once __DIR__ . "/../controllers/controladorLogin.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$mensajeError = "";

// Procesar login
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["login"])) {
    $resultado = ctrlLogin::ctrlIngresoUsuario();

    if ($resultado === "ok") {
        // Verificamos el rol del usuario
        $rol = $_SESSION['rol'] ?? 'cliente';

// después de login exitoso
if ($resultado === "ok") {
    header("Location: index.php?axn=home");
    exit;
}

    } elseif ($resultado === "error") {
        $mensajeError = "❌ Email o contraseña incorrectos";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HELPDESK - Sign In</title>
    <link rel="stylesheet" href="public/css/separate/pages/login.min.css">
    <link rel="stylesheet" href="public/css/lib/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="public/css/main.css">
</head>
<body>
    <div class="page-center">
        <div class="page-center-in">
            <div class="container-fluid">

                <?php if (!empty($mensajeError)): ?>
                    <div class="alert alert-danger text-center">
                        <?= $mensajeError ?>
                    </div>
                <?php endif; ?>

                <form class="sign-box" method="POST" action="">
                    <div class="sign-avatar">
                        <img src="public/img/avatar-sign.png" alt="">
                    </div>
                    <header class="sign-title">Sign In</header>
                    
                    <div class="form-group">
                        <input type="email" class="form-control" name="email" placeholder="E-Mail" required />
                    </div>
                    
                    <div class="form-group">
                        <input type="password" class="form-control" name="password" placeholder="Password" required />
                    </div>

                    <input type="hidden" name="login" value="1">

                    <button type="submit" class="btn btn-rounded">Sign in</button>

                    <p class="sign-note">
                        New to our website? <a href="index.php?axn=sign-up">Sign up</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
