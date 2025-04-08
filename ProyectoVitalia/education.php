<?php 
$tituloPagina = "Recursos Educativos - VitaliA";
include 'includes/header.php'; 
require_once 'config/database.php';

$db = $database->conectar();

// Obtener categorías de información médica
$queryCategorias = 'SELECT DISTINCT categoria FROM informacion_medica ORDER BY categoria';
$stmtCategorias = $db->query($queryCategorias);
$categorias = $stmtCategorias->fetchAll(PDO::FETCH_COLUMN);

// Obtener videos educativos
$queryVideos = 'SELECT v.id, v.url, v.descripcion, i.titulo 
                FROM videos v
                JOIN informacion_medica i ON v.info_id = i.id
                ORDER BY i.titulo';
$stmtVideos = $db->query($queryVideos);
$videos = $stmtVideos->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="contenedor-educacion">
    <section class="seccion-educativa">
        <h1>Recursos Educativos de Primeros Auxilios</h1>
        
        <div class="filtros">
            <h2>Buscar por categoría</h2>
            <div class="categorias">
                <?php foreach ($categorias as $categoria): ?>
                    <button class="boton-categoria" data-categoria="<?php echo htmlspecialchars($categoria); ?>">
                        <?php echo htmlspecialchars($categoria); ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="recursos">
            <div class="guias">
                <h2>Guías de Primeros Auxilios</h2>
                <div class="lista-guias" id="lista-guias">
                    <!-- Las guías se cargarán dinámicamente por JavaScript -->
                    <p>Selecciona una categoría para ver las guías disponibles.</p>
                </div>
            </div>
            
            <div class="videos-educativos">
                <h2>Videos Instructivos</h2>
                <div class="lista-videos">
                    <?php foreach ($videos as $video): ?>
                        <div class="video">
                            <h3><?php echo htmlspecialchars($video['titulo']); ?></h3>
                            <div class="video-contenedor">
                                <iframe src="<?php echo htmlspecialchars($video['url']); ?>" 
                                        frameborder="0" 
                                        allowfullscreen></iframe>
                            </div>
                            <p><?php echo htmlspecialchars($video['descripcion']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <div class="descargas">
            <h2>Material para Descargar</h2>
            <div class="lista-descargas">
                <div class="descarga">
                    <i class="icono-pdf"></i>
                    <h3>Manual de Primeros Auxilios Básicos</h3>
                    <p>Guía completa con procedimientos para emergencias comunes</p>
                    <a href="assets/manual.pdf" download class="boton-descarga">Descargar PDF</a>
                </div>
                <div class="descarga">
                    <i class="icono-pdf"></i>
                    <h3>Poster de Emergencias</h3>
                    <p>Resumen visual de pasos a seguir en diferentes emergencias</p>
                    <a href="assets/poster.pdf" download class="boton-descarga">Descargar PDF</a>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const botonesCategoria = document.querySelectorAll('.boton-categoria');
    
    botonesCategoria.forEach(boton => {
        boton.addEventListener('click', function() {
            const categoria = this.getAttribute('data-categoria');
            cargarGuiasPorCategoria(categoria);
            
            // Resaltar categoría seleccionada
            botonesCategoria.forEach(btn => btn.classList.remove('activo'));
            this.classList.add('activo');
        });
    });
    
    function cargarGuiasPorCategoria(categoria) {
        fetch(`api/guias.php?categoria=${encodeURIComponent(categoria)}`)
            .then(response => response.json())
            .then(data => {
                const listaGuias = document.getElementById('lista-guias');
                
                if (data.length > 0) {
                    listaGuias.innerHTML = data.map(guia => `
                        <div class="guia">
                            <h3>${guia.titulo}</h3>
                            <p>${guia.descripcion}</p>
                            <div class="acciones-guia">
                                <button class="boton-ver" data-id="${guia.id}">Ver Detalles</button>
                                <a href="assets/guias/${guia.id}.pdf" download class="boton-descarga">Descargar</a>
                            </div>
                        </div>
                    `).join('');
                } else {
                    listaGuias.innerHTML = `<p>No hay guías disponibles para esta categoría.</p>`;
                }
            })
            .catch(error => {
                console.error('Error al cargar guías:', error);
                document.getElementById('lista-guias').innerHTML = 
                    `<p class="error">Error al cargar las guías. Por favor intenta nuevamente.</p>`;
            });
    }
});
</script>

<?php include 'includes/footer.php'; ?>