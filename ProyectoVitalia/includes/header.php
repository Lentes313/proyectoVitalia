<?php
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($tituloPagina) ? $tituloPagina : 'VitaliA - Asistencia en Primeros Auxilios'; ?></title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="assets/favicon.ico" type="image/x-icon">
</head>
<body>
    <header>
        <div class="logo">
            <a href="index.php">VitaliA</a>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="about.php">Acerca de</a></li>
                <li><a href="contact.php">Contacto</a></li>
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <li><a href="dashboard.php">Mi Cuenta</a></li>
                    <li><a href="logout.php">Cerrar Sesión</a></li>
                <?php else: ?>
                    <li><a href="login.php">Iniciar Sesión</a></li>
                    <li><a href="register.php">Registrarse</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>