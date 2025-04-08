<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VitaliA - Asistencia en Primeros Auxilios</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="contenedor-principal">
        <section class="hero">
            <h1>Asistencia en Primeros Auxilios en Tiempo Real</h1>
            <p>Accede a información médica confiable cuando más la necesites</p>
            <div class="botones-accion">
                <a href="login.php" class="boton-primario">Iniciar Sesión</a>
                <a href="register.php" class="boton-secundario">Registrarse</a>
                <a href="chat.php" class="boton-emergencia">Emergencia</a>
            </div>
        </section>

        <section class="caracteristicas">
            <div class="tarjeta-caracteristica">
                <img src="assets/chat-icon.png" alt="Chat IA">
                <h3>Chat con IA</h3>
                <p>Asistencia médica básica en tiempo real con inteligencia artificial</p>
            </div>
            <div class="tarjeta-caracteristica">
                <img src="assets/history-icon.png" alt="Historial">
                <h3>Historial Médico</h3>
                <p>Registro de tus consultas y emergencias para seguimiento</p>
            </div>
            <div class="tarjeta-caracteristica">
                <img src="assets/education-icon.png" alt="Educación">
                <h3>Recursos Educativos</h3>
                <p>Guías y videos sobre primeros auxilios</p>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>
    <script src="js/script.js"></script>
</body>
</html>