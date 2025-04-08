<?php
header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../includes/auth_functions.php';

session_start();
verificarAutenticacion();

$db = (new Database())->conectar();
$usuario_id = $_SESSION['usuario_id'];

$input = json_decode(file_get_contents('php://input'), true);
$mensaje = filter_var($input['mensaje'] ?? '', FILTER_SANITIZE_STRING);

if (empty($mensaje)) {
    echo json_encode(['error' => 'El mensaje no puede estar vacío']);
    exit;
}

// 1. Guardar el mensaje del usuario en el historial
$queryInsert = 'INSERT INTO historial_chat (usuario_id, mensaje, respuesta, fecha_chat) 
                VALUES (?, ?, "", NOW())';
$stmtInsert = $db->prepare($queryInsert);
$stmtInsert->execute([$usuario_id, $mensaje]);
$chat_id = $db->lastInsertId();

// 2. Procesar con IA (simulado - en producción usarías la API de OpenAI)
$respuestaIA = procesarConIA($mensaje);

// 3. Actualizar con la respuesta
$queryUpdate = 'UPDATE historial_chat SET respuesta = ? WHERE id = ?';
$stmtUpdate = $db->prepare($queryUpdate);
$stmtUpdate->execute([$respuestaIA, $chat_id]);

// 4. Devolver respuesta
echo json_encode([
    'respuesta' => $respuestaIA,
    'chat_id' => $chat_id
]);

function procesarConIA($mensaje) {
    // Simulación simple - en producción usarías la API real
    
    $palabrasClave = [
        'dolor cabeza' => [
            'respuesta' => 'Para dolores de cabeza, te recomiendo: 1. Descansar en un lugar tranquilo. 2. Tomar agua. 3. Aplicar compresas frías. Si persiste, consulta a un médico.',
            'emergencia' => false
        ],
        'quemadura' => [
            'respuesta' => 'Para quemaduras: 1. Enfría con agua corriente (no muy fría) por 10-15 min. 2. No uses hielo. 3. Cubre con gasa estéril. Si es grave, busca atención médica.',
            'emergencia' => true
        ],
        // Más respuestas predefinidas...
    ];
    
    foreach ($palabrasClave as $clave => $respuesta) {
        if (stripos($mensaje, $clave) !== false) {
            return $respuesta['respuesta'];
        }
    }
    
    // Respuesta por defecto
    return "Gracias por tu mensaje. Estoy procesando tu consulta sobre '$mensaje'. Para asistencia más precisa, por favor describe tu situación con más detalles.";
}
?>