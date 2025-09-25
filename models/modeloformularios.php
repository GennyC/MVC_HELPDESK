<?php
require_once "conexion.php"; 

class modeloformularios {
static public function mdlRegistroUsuario($tabla, $datos) {
    try {
        $stmt = Conexion::conectar()->prepare(
            "INSERT INTO $tabla (nombre, email, password, rol) 
             VALUES (:nombre, :email, :password, :rol)"
        );

        $stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
        $stmt->bindParam(":email", $datos["email"], PDO::PARAM_STR);
        $stmt->bindParam(":password", $datos["password"], PDO::PARAM_STR);
        $stmt->bindParam(":rol", $datos["rol"], PDO::PARAM_STR);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
    } catch (PDOException $e) {
        return "error: " . $e->getMessage();
    }
}


    static public function mdlIngresoUsuario($tabla, $datos) {
        
        $stmt = Conexion::conectar()->prepare(
            "SELECT * FROM $tabla WHERE email = :email LIMIT 1"
        );
        $stmt->bindParam(":email", $datos["email"], PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

