<?php
class Conexion {
    public static function conectar() {
        try {
            // If using ClearDB (Heroku add-on)
            $cleardb_url = getenv("CLEARDB_DATABASE_URL");

            if ($cleardb_url) {
                $parts = parse_url($cleardb_url);

                $host = $parts["host"];
                $user = $parts["user"];
                $pass = $parts["pass"];
                $dbname = ltrim($parts["path"], "/");
            } else {
                // Fallback to manual config vars if no ClearDB is found
                $host = getenv("DB_HOST") ?: "localhost";
                $user = getenv("DB_USER") ?: "root";
                $pass = getenv("DB_PASS") ?: "12345";
                $dbname = getenv("DB_NAME") ?: "proyectomvc_helpdesk";
            }

            $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
            $dbh = new PDO($dsn, $user, $pass);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbh->exec("SET NAMES utf8");
            return $dbh;

        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
            die();
        }
    }

    public static function ruta() {
        // ⚡ Change to your Heroku app URL in production
        return "https://your-heroku-app.herokuapp.com/";
    }
}
