<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Usuario - VitaliA</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="contenedor-dashboard">
        <aside class="menu-usuario">
            <div class="perfil-usuario">
                <img src="assets/user-default.png" alt="Foto de perfil">
                <h3><?php echo $_SESSION['nombre']; ?></h3>
                <p><?php echo $_SESSION['email']; ?></p>
            </div>
            <nav>
                <ul>
                    <li><a href="dashboard.php" class="activo"><i class="icono-dashboard"></i> Inicio</a></li>
                    <li><a href="chat.php"><i class="icono-chat"></i> Chat de Emergencia</a></li>
                    <li><a href="history.php"><i class="icono-historial"></i> Historial</a></li>
                    <li><a href="upload.php"><i class="icono-subir"></i> Subir Imágenes</a></li>
                    <li><a href="education.php"><i class="icono-educacion"></i> Recursos Educativos</a></li>
                    <li><a href="emergency.php"><i class="icono-emergencia"></i> Contactos de Emergencia</a></li>
                    <li><a href="logout.php"><i class="icono-salir"></i> Cerrar Sesión</a></li>
                </ul>
            </nav>
        </aside>

        <section class="contenido-principal">
            <h2>Bienvenido, <?php echo $_SESSION['nombre']; ?></h2>
            
            <div class="tarjetas-resumen">
                <div class="tarjeta-resumen">
                    <h3>Consultas Recientes</h3>
                    <?php include 'includes/ultimas_consultas.php'; ?>
                </div>
                <div class="tarjeta-resumen">
                    <h3>Emergencias Reportadas</h3>
                    <?php include 'includes/ultimas_emergencias.php'; ?>
                </div>
            </div>

            <div class="acciones-rapidas">
                <a href="chat.php" class="boton-accion">
                    <i class="icono-chat"></i>
                    <span>Nueva Consulta</span>
                </a>
                <a href="emergency.php" class="boton-accion">
                    <i class="icono-emergencia"></i>
                    <span>Alertar Emergencia</span>
                </a>
                <a href="education.php" class="boton-accion">
                    <i class="icono-educacion"></i>
                    <span>Aprender Primeros Auxilios</span>
                </a>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>
    <script src="js/script.js"></script>
</body>
</html>