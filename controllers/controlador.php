<?php
require_once __DIR__ . "/../models/model.php";

class MvcController {
    public function enlacespaginascontrol() {
        if (isset($_GET["axn"])) {
            $enlace = $_GET["axn"];
        } else {
            // default page = sign-in
            $enlace = "sign-in";
        }

        $respuesta = EnlacesPaginas::EnlacesPaginasModel($enlace);

        if (file_exists($respuesta)) {
            include $respuesta;
        } else {
            echo "Error: View not found → " . htmlspecialchars($respuesta);
        }
    }
}
