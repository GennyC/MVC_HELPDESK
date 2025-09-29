<?php
class Conexion {
    public static function conectar() {
        try {
            // Check if running on Heroku (JAWSDB_URL exists)
            if (getenv("JAWSDB_URL")) {
                $url = parse_url(getenv("JAWSDB_URL"));

                $host = $url["host"];
                $dbname = ltrim($url["path"], '/');
                $user = $url["user"];
                $pass = $url["pass"];

                $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8";
                $dbh = new PDO($dsn, $user, $pass);
            } else {
                // Localhost fallback
                $dbh = new PDO(
                    "mysql:host=localhost;dbname=proyectomvc_helpdesk;charset=utf8",
                    "root",
                    "12345"
                );
            }

            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $dbh;

        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
            die();
        }
    }

    public static function ruta() {
        // Change this if your app’s URL on Heroku is different
        return "https://mvchelpdesk.herokuapp.com/";
    }
}
