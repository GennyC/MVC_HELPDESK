<?php
class Conexion {
    public static function conectar() {
        try {
            $url = getenv("JAWSDB_URL");

            if ($url) {
                // Parse JawsDB URL (Heroku)
                $dbparts = parse_url($url);

                $host = $dbparts['host'];
                $user = $dbparts['user'];
                $pass = $dbparts['pass'];
                $dbname = ltrim($dbparts['path'], '/');
                $port = $dbparts['port'] ?? 3306;
            } else {
                // Local XAMPP fallback
                $host = "localhost";
                $user = "root";
                $pass = "12345";
                $dbname = "proyectomvc_helpdesk";
                $port = 3306;
            }

            $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8";
            $dbh = new PDO($dsn, $user, $pass);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $dbh;

        } catch (Exception $e) {
            echo "Error de conexión: " . $e->getMessage();
            die();
        }
    }

    public static function ruta() {
        return "http://localhost/proyectomvc_helpdesk/"; 
        // Cambia a "https://your-heroku-app.herokuapp.com/" en producción
    }
}
