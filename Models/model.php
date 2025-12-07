<?php
class EnlacesPaguinas {
    public static function enlacesPaginasModel($enlacesModel) {
        if ($enlacesModel == "Nosotros" || $enlacesModel == "Contactanos" || $enlacesModel == "Servicios" || $enlacesModel == "Login" || $enlacesModel == "Serviciosbootstrap") {
            $module = "Views/" . $enlacesModel . ".php";
        } else {
            $module = "Views/Inicio.php";
        }
        return $module;
    }
}
?>

<?php if (isset($_SESSION['usuario'])): ?>
    <p>Bienvenido <?php echo htmlspecialchars($_SESSION['usuario']); ?> (<?php echo $_SESSION['rol']; ?>)</p>
    <form method="post" action="Models/logout.php" style="display:inline;">
        <button type="submit" name="logout">Cerrar sesión</button>
    </form>
<?php endif; ?>