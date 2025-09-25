<?php
class Conexion {
    public static function conectar() {
        try {
            $dbh = new PDO(
                "mysql:host=localhost;dbname=proyectomvc_helpdesk",
                "root",
                "12345"
            );
            $dbh->exec("SET NAMES utf8");
            return $dbh;
        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
            die();
        }
    }

    public static function ruta() {
        return "http://localhost/proyectomvc_helpdesk/";
    }
}
