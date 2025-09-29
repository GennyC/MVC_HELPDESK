<?php
class Conexion {
    public static function conectar() {
        try {
            // Get the JawsDB connection URL from Heroku config vars
            $url = getenv("JAWSDB_URL");

            if (!$url) {
                throw new Exception("JAWSDB_URL not set in environment.");
            }

            // Parse the URL
            $dbparts = parse_url($url);

            $host = $dbparts['host'];
            $user = $dbparts['user'];
            $pass = $dbparts['pass'];
            $dbname = ltrim($dbparts['path'], '/');
            $port = $dbparts['port'] ?? 3306;

            // Create PDO connection
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
        // Change this to your Heroku app URL when deployed
        return "https://your-heroku-app-name.herokuapp.com/";
    }
}
