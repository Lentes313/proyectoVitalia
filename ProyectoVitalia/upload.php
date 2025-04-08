<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Imágenes - VitaliA</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="contenedor-subida">
        <h2>Subir Imágenes para Historial Médico</h2>
        
        <form id="formulario-subida" action="procesar_subida.php" method="POST" enctype="multipart/form-data">
            <div class="grupo-formulario">
                <label for="descripcion">Descripción de la emergencia</label>
                <textarea id="descripcion" name="descripcion" required placeholder="Describe la situación médica relacionada con la imagen"></textarea>
            </div>
            
            <div class="grupo-formulario">
                <label for="imagenes">Seleccionar imágenes</label>
                <input type="file" id="imagenes" name="imagenes[]" multiple accept="image/*">
                <p class="ayuda">Puedes seleccionar múltiples imágenes (máximo 5)</p>
            </div>
            
            <div class="vista-previa" id="vista-previa">
                <!-- Vista previa de imágenes se mostrará aquí -->
            </div>
            
            <button type="submit" class="boton-primario">Guardar en Historial</button>
        </form>
        
        <section class="historial-imagenes">
            <h3>Tus imágenes anteriores</h3>
            <div class="galeria-imagenes">
                <?php include 'includes/cargar_imagenes_usuario.php'; ?>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>
    <script src="js/script.js"></script>
</body>
</html>