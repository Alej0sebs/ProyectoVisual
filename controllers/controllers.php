<?php
class MvcController {
    public function enlacesPaginaController() {
        $paginas = ["Inicio", "Nosotros", "Servicios", "Contactanos", "Login"];
        if (isset($_GET["action"]) && in_array($_GET["action"], $paginas)) {
            $pagina = $_GET["action"];
            $ruta = "Views/" . $pagina . ".php";
        } else {
            $ruta = "Views/Inicio.php";
        }
        include $ruta;
    }
}
?>