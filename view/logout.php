<?php
session_start();
session_unset();   // elimina todas las variables de sesión
session_destroy(); // destruye la sesión

// Redirigir al login
header("Location: index.php?axn=sign-in");
exit;
