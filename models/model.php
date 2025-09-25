<?php
class EnlacesPaginas {
    public static function EnlacesPaginasModel($enlace) {
        $viewsPath = __DIR__ . "/../view/";

        switch ($enlace) {
            case "sign-in":
                return $viewsPath . "sign-in.php";
            case "sign-up":
                return $viewsPath . "sign-up.php";
            case "index":
                return $viewsPath . "plantilla.php";
            case "home":
                return $viewsPath . "home.php";
            case "tickets":
                return $viewsPath . "tickets.php";
            case "logout":
                return $viewsPath . "logout.php";
            case "usuarios":
                return $viewsPath . "usuarios.php";
            default:
                // fallback = sign-in
                return $viewsPath . "sign-in.php";
        }
    }
}
