<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat de Emergencia - VitaliA</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/chat.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="contenedor-chat">
        <section class="seleccion-sintomas">
            <h2>¿Qué síntoma estás experimentando?</h2>
            <div class="lista-sintomas">
                <button class="boton-sintoma" data-sintoma="dolor_cabeza">Dolor de cabeza</button>
                <button class="boton-sintoma" data-sintoma="dolor_estomacal">Dolor estomacal</button>
                <button class="boton-sintoma" data-sintoma="dolor_garganta">Dolor de garganta</button>
                <button class="boton-sintoma" data-sintoma="quemadura">Quemadura</button>
                <button class="boton-sintoma" data-sintoma="herida">Herida</button>
                <button class="boton-sintoma" data-sintoma="fractura">Fractura</button>
            </div>
        </section>

        <section class="interfaz-chat">
            <div class="cabecera-chat">
                <h3>Asistente Virtual de Primeros Auxilios</h3>
                <div class="controles-chat">
                    <button id="activar-voz"><i class="icono-voz"></i></button>
                    <button id="alternar-contraste"><i class="icono-contraste"></i></button>
                </div>
            </div>
            
            <div class="mensajes-chat" id="mensajes-chat">
                <!-- Mensajes se cargarán aquí dinámicamente -->
                <div class="mensaje asistente">
                    <p>Hola, soy VitaliA. ¿En qué puedo ayudarte hoy?</p>
                </div>
            </div>
            
            <div class="entrada-chat">
                <textarea id="mensaje-usuario" placeholder="Escribe tu consulta aquí..."></textarea>
                <div class="acciones-chat">
                    <button id="adjuntar-imagen"><i class="icono-imagen"></i></button>
                    <button id="enviar-mensaje">Enviar</button>
                </div>
            </div>
        </section>

        <aside class="informacion-emergencia">
            <h3>¿Es una emergencia grave?</h3>
            <p>Si necesitas ayuda inmediata, activa nuestra alerta de emergencia:</p>
            <button id="boton-emergencia" class="boton-emergencia">Activar Alerta</button>
            
            <div class="consejos-rapidos">
                <h4>Consejos Rápidos</h4>
                <div id="consejos-contenido">
                    <!-- Consejos se cargarán según el síntoma seleccionado -->
                </div>
            </div>
        </aside>
    </main>

    <?php include 'includes/footer.php'; ?>
    <script src="js/chat.js"></script>
</body>
</html>