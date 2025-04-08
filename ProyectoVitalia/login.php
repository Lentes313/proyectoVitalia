<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - VitaliA</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="contenedor-formulario">
        <section class="formulario-autenticacion">
            <h2>Iniciar Sesión</h2>
            <form action="procesar_login.php" method="POST">
                <div class="grupo-formulario">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="grupo-formulario">
                    <label for="contrasena">Contraseña</label>
                    <input type="password" id="contrasena" name="contrasena" required>
                </div>
                <button type="submit" class="boton-primario">Ingresar</button>
                <div class="opciones-adicionales">
                    <a href="recuperar_contrasena.php">¿Olvidaste tu contraseña?</a>
                    <p>¿No tienes cuenta? <a href="register.php">Regístrate aquí</a></p>
                </div>
            </form>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>
    <script src="js/script.js"></script>
</body>
</html>