<?php
session_start();

function verificarAutenticacion() {
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: login.php');
        exit();
    }
}

function registrarUsuario($db, $nombre, $apellidos, $email, $contrasena) {
    // Verificar si el correo ya existe
    $query = 'SELECT id FROM usuarios WHERE email = ? LIMIT 1';
    $stmt = $db->prepare($query);
    $stmt->execute([$email]);
    
    if ($stmt->rowCount() > 0) {
        return ['error' => 'El correo electrónico ya está registrado'];
    }
    
    // Hash de la contraseña
    $contrasena_hash = password_hash($contrasena, PASSWORD_BCRYPT);
    
    // Insertar nuevo usuario
    $query = 'INSERT INTO usuarios (nombre, apellidos, email, contrasena, fecha_registro) 
              VALUES (?, ?, ?, ?, NOW())';
    $stmt = $db->prepare($query);
    
    if ($stmt->execute([$nombre, $apellidos, $email, $contrasena_hash])) {
        return ['exito' => 'Usuario registrado correctamente. Por favor inicia sesión.'];
    } else {
        return ['error' => 'Error al registrar el usuario. Por favor intenta nuevamente.'];
    }
}

function iniciarSesion($db, $email, $contrasena) {
    $query = 'SELECT id, nombre, apellidos, email, contrasena FROM usuarios WHERE email = ? LIMIT 1';
    $stmt = $db->prepare($query);
    $stmt->execute([$email]);
    
    if ($stmt->rowCount() === 1) {
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (password_verify($contrasena, $usuario['contrasena'])) {
            // Guardar datos en sesión
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nombre'] = $usuario['nombre'] . ' ' . $usuario['apellidos'];
            $_SESSION['email'] = $usuario['email'];
            
            return true;
        }
    }
    
    return false;
}

function cerrarSesion() {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit();
}
?>