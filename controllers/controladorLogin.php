<?php
require_once __DIR__ . "/../models/modeloformularios.php";

class ctrlLogin {
    // User Registration
    static public function ctrlRegistroUsuario(){
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["registro"])){
            // Debug: Log form submission
            error_log("Form submitted to ctrlRegistroUsuario");
            
            // Validate input
            if(empty($_POST["nombre"]) || empty($_POST["email"]) || empty($_POST["password"])) {
                $_SESSION['registration_error'] = "Todos los campos son obligatorios";
                return "error";
            }

            // Encrypt password
            $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
            
            if (!$password) {
                $_SESSION['registration_error'] = "Error al procesar la contraseña";
                return "error";
            }
            
            $datos = array(
                "nombre" => $_POST["nombre"],
                "email" => $_POST["email"],
                "password" => $password,
                    "rol" => "cliente" // 👈 siempre se asigna como cliente por defecto

            );
            
            // Debug: Log data before sending to model
            error_log("Data prepared for model: " . print_r($datos, true));
            
            $respuesta = modeloformularios::mdlRegistroUsuario("usuarios", $datos);
            
            // Debug: Log model response
            error_log("Model response: " . $respuesta);
            
            if($respuesta == "ok"){
                $_SESSION['registration_success'] = true;
                return "ok";
            } else {
                $_SESSION['registration_error'] = "Error al registrar. " . $respuesta;
                return "error";
            }
        }
        return "no-action";
    }
    
    // User Login (unchanged, but ensure session_start() is at the top of login.php)

    static public function ctrlIngresoUsuario(){
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["login"])) {
            $datos = ["email" => $_POST["email"]];
            $respuesta = modeloformularios::mdlIngresoUsuario("usuarios", $datos);

            if ($respuesta && password_verify($_POST["password"], $respuesta["password"])) {
                $_SESSION['user_id'] = $respuesta['id'];
                $_SESSION['user_email'] = $respuesta['email'];
                $_SESSION['nombre'] = $respuesta['nombre'];
                $_SESSION['rol'] = $respuesta['rol'];
                return "ok";
            } else {
                $_SESSION['login_error'] = "Email o contraseña incorrectos";
                return "error";
            }
        }
        return "no-action";
    }
public static function ctrlLogout() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Limpiar todas las variables de sesión
    $_SESSION = [];

    // Destruir sesión y cookies
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    session_destroy();

    // Redirigir al inicio
    header("Location: index.php");
    exit;
}
}
?>