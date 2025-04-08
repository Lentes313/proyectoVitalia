<?php 
$tituloPagina = "Acerca de VitaliA";
include 'includes/header.php'; 
?>

<main class="contenedor-principal">
    <section class="seccion-informativa">
        <h1>Acerca de VitaliA</h1>
        
        <div class="contenido-about">
            <div class="mision">
                <h2>Nuestra Misión</h2>
                <p>VitaliA nace con el objetivo de proporcionar asistencia médica básica inmediata a través de tecnología accesible, reduciendo la incertidumbre en situaciones de emergencia y promoviendo una cultura de prevención.</p>
            </div>
            
            <div class="equipo">
                <h2>El Equipo</h2>
                <p>Somos estudiantes de Ingeniería en Sistemas Computacionales del Tecnológico Superior de Jalisco, comprometidos con desarrollar soluciones tecnológicas que impacten positivamente en la sociedad.</p>
                
                <div class="miembros-equipo">
                    <div class="miembro">
                        <img src="assets/team1.jpg" alt="Santiago De la Cruz">
                        <h3>Santiago De la Cruz</h3>
                        <p>Desarrollador Backend</p>
                    </div>
                    <div class="miembro">
                        <img src="assets/team2.jpg" alt="Emmanuel Castillo">
                        <h3>Emmanuel Castillo</h3>
                        <p>Desarrollador Frontend</p>
                    </div>
                </div>
            </div>
            
            <div class="tecnologias">
                <h2>Tecnologías Utilizadas</h2>
                <div class="lista-tecnologias">
                    <div class="tecnologia">
                        <img src="assets/html5.png" alt="HTML5">
                        <span>HTML5</span>
                    </div>
                    <div class="tecnologia">
                        <img src="assets/css3.png" alt="CSS3">
                        <span>CSS3</span>
                    </div>
                    <div class="tecnologia">
                        <img src="assets/js.png" alt="JavaScript">
                        <span>JavaScript</span>
                    </div>
                    <div class="tecnologia">
                        <img src="assets/php.png" alt="PHP">
                        <span>PHP</span>
                    </div>
                    <div class="tecnologia">
                        <img src="assets/mysql.png" alt="MySQL">
                        <span>MySQL</span>
                    </div>
                    <div class="tecnologia">
                        <img src="assets/openai.png" alt="OpenAI">
                        <span>OpenAI API</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>