<?php
include 'includes/header.php';
require_once 'config/database.php';
require_once 'includes/auth_functions.php';

$db = $database->conectar();
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
    $apellidos = filter_input(INPUT_POST, 'apellidos', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $contrasena = $_POST['contrasena'];
    $confirmar_contrasena = $_POST['confirmar_contrasena'];

    // Validaciones
    if (empty($nombre)) {
        $errores[] = 'El nombre es obligatorio';
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'El correo electrónico no es válido';
    }
    
    if (strlen($contrasena) < 8) {
        $errores[] = 'La contraseña debe tener al menos 8 caracteres';
    }
    
    if ($contrasena !== $confirmar_contrasena) {
        $errores[] = 'Las contraseñas no coinciden';
    }

    if (empty($errores)) {
        $resultado = registrarUsuario($db, $nombre, $apellidos, $email, $contrasena);
        
        if (isset($resultado['exito'])) {
            header('Location: registro_exitoso.php');
            exit();
        } else {
            $errores[] = $resultado['error'];
        }
    }
}
?>

<main class="contenedor-formulario">
    <section class="formulario-autenticacion">
        <h2>Crear una cuenta</h2>
        
        <?php if (!empty($errores)): ?>
            <div class="alertas">
                <?php foreach ($errores as $error): ?>
                    <div class="alerta error"><?php echo $error; ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form action="register.php" method="POST">
            <div class="grupo-formulario">
                <label for="nombre">Nombre(s)</label>
                <input type="text" id="nombre" name="nombre" required 
                       value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>">
            </div>
            
            <div class="grupo-formulario">
                <label for="apellidos">Apellidos</label>
                <input type="text" id="apellidos" name="apellidos" required 
                       value="<?php echo htmlspecialchars($_POST['apellidos'] ?? ''); ?>">
            </div>
            
            <div class="grupo-formulario">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" required 
                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>
            
            <div class="grupo-formulario">
                <label for="contrasena">Contraseña</label>
                <input type="password" id="contrasena" name="contrasena" required>
                <p class="ayuda">Mínimo 8 caracteres</p>
            </div>
            
            <div class="grupo-formulario">
                <label for="confirmar_contrasena">Confirmar Contraseña</label>
                <input type="password" id="confirmar_contrasena" name="confirmar_contrasena" required>
            </div>
            
            <button type="submit" class="boton-primario">Registrarse</button>
            
            <div class="opciones-adicionales">
                <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
            </div>
        </form>
    </section>
</main>

<?php include 'includes/footer.php'; ?>