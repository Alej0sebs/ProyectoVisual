<?php
include_once __DIR__ . "/../Models/model.php";
class MvcController {
    public function enlacesPaginasController() {
        $paginas = ["Inicio", "Nosotros", "Servicios", "Contactanos", "Login"];
        if (isset($_GET["action"]) && in_array($_GET["action"], $paginas)) {
            $enlacesController = $_GET["action"];
        } else {
            $enlacesController = "Inicio";
        }
        $respuesta = EnlacesPaguinas::enlacesPaginasModel($enlacesController);
        include $respuesta;
    }
}
?>