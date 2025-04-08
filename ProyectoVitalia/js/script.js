/**
 * script.js - Funcionalidades generales del sitio VitaliA
 * 
 * Incluye:
 * - Navegación responsive
 * - Manejo de formularios
 * - Interacciones básicas
 * - Modo alto contraste
 * - Validaciones comunes
 */

document.addEventListener('DOMContentLoaded', function() {
    // ==================== NAVEGACIÓN RESPONSIVE ====================
    const menuToggle = document.createElement('button');
    menuToggle.className = 'menu-toggle';
    menuToggle.innerHTML = '<i class="icono-menu"></i>';
    const nav = document.querySelector('header nav');
    
    if (nav) {
        document.querySelector('header').appendChild(menuToggle);
        
        menuToggle.addEventListener('click', function() {
            nav.classList.toggle('activo');
            menuToggle.classList.toggle('activo');
        });
        
        // Cerrar menú al hacer clic en un enlace
        document.querySelectorAll('nav a').forEach(enlace => {
            enlace.addEventListener('click', function() {
                nav.classList.remove('activo');
                menuToggle.classList.remove('activo');
            });
        });
    }
    
    // ==================== MANEJO DE FORMULARIOS ====================
    document.querySelectorAll('form').forEach(form => {
        // Validación básica de formularios
        form.addEventListener('submit', function(e) {
            let valido = true;
            const inputsRequeridos = form.querySelectorAll('[required]');
            
            inputsRequeridos.forEach(input => {
                if (!input.value.trim()) {
                    input.classList.add('invalido');
                    valido = false;
                    
                    // Mostrar mensaje de error
                    if (!input.nextElementSibling || !input.nextElementSibling.classList.contains('mensaje-error')) {
                        const mensajeError = document.createElement('span');
                        mensajeError.className = 'mensaje-error';
                        mensajeError.textContent = 'Este campo es obligatorio';
                        input.insertAdjacentElement('afterend', mensajeError);
                    }
                } else {
                    input.classList.remove('invalido');
                    if (input.nextElementSibling && input.nextElementSibling.classList.contains('mensaje-error')) {
                        input.nextElementSibling.remove();
                    }
                }
            });
            
            if (!valido) {
                e.preventDefault();
                
                // Desplazarse al primer error
                const primerError = form.querySelector('.invalido');
                if (primerError) {
                    primerError.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'center' 
                    });
                }
            }
        });
        
        // Limpiar validación al escribir
        form.querySelectorAll('input, textarea').forEach(input => {
            input.addEventListener('input', function() {
                if (this.value.trim()) {
                    this.classList.remove('invalido');
                    if (this.nextElementSibling && this.nextElementSibling.classList.contains('mensaje-error')) {
                        this.nextElementSibling.remove();
                    }
                }
            });
        });
    });
    
    // ==================== MODO ALTO CONTRASTE ====================
    const botonContraste = document.getElementById('alternar-contraste');
    
    if (botonContraste) {
        botonContraste.addEventListener('click', function() {
            document.body.classList.toggle('alto-contraste');
            localStorage.setItem('altoContraste', document.body.classList.contains('alto-contraste'));
        });
        
        // Aplicar preferencia guardada
        if (localStorage.getItem('altoContraste') === 'true') {
            document.body.classList.add('alto-contraste');
        }
    }
    
    // ==================== MANEJO DE PESTAÑAS ====================
    document.querySelectorAll('.tab-button').forEach(boton => {
        boton.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            
            // Ocultar todos los contenidos de tabs
            document.querySelectorAll('.contenido-tab').forEach(tab => {
                tab.classList.remove('activo');
            });
            
            // Desactivar todos los botones
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('activo');
            });
            
            // Mostrar el contenido seleccionado
            document.getElementById(tabId).classList.add('activo');
            this.classList.add('activo');
        });
    });
    
    // ==================== GALERÍA DE IMÁGENES ====================
    document.querySelectorAll('.galeria-imagenes').forEach(galeria => {
        galeria.addEventListener('click', function(e) {
            if (e.target.tagName === 'IMG') {
                // Crear visor modal
                const visor = document.createElement('div');
                visor.className = 'visor-imagen';
                
                const img = document.createElement('img');
                img.src = e.target.src;
                img.alt = e.target.alt || 'Imagen ampliada';
                
                const cerrar = document.createElement('span');
                cerrar.className = 'cerrar-visor';
                cerrar.innerHTML = '&times;';
                
                visor.appendChild(img);
                visor.appendChild(cerrar);
                document.body.appendChild(visor);
                
                // Cerrar al hacer clic
                visor.addEventListener('click', function() {
                    document.body.removeChild(visor);
                });
                
                // Prevenir cierre al hacer clic en la imagen
                img.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }
        });
    });
    
    // ==================== PREVISUALIZACIÓN DE IMÁGENES ====================
    const inputImagenes = document.querySelector('input[type="file"][accept="image/*"]');
    
    if (inputImagenes) {
        const vistaPrevia = document.getElementById('vista-previa');
        
        inputImagenes.addEventListener('change', function() {
            vistaPrevia.innerHTML = '';
            
            if (this.files && this.files.length > 0) {
                const maxFiles = parseInt(this.getAttribute('data-max-files') || 5);
                const files = Array.from(this.files).slice(0, maxFiles);
                
                files.forEach(file => {
                    if (file.type.match('image.*')) {
                        const reader = new FileReader();
                        
                        reader.onload = function(e) {
                            const contenedorImg = document.createElement('div');
                            contenedorImg.className = 'imagen-previa';
                            
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            
                            const eliminar = document.createElement('span');
                            eliminar.className = 'eliminar-imagen';
                            eliminar.innerHTML = '&times;';
                            eliminar.title = 'Eliminar imagen';
                            
                            eliminar.addEventListener('click', function() {
                                vistaPrevia.removeChild(contenedorImg);
                                actualizarInputArchivos();
                            });
                            
                            contenedorImg.appendChild(img);
                            contenedorImg.appendChild(eliminar);
                            vistaPrevia.appendChild(contenedorImg);
                        };
                        
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
        
        function actualizarInputArchivos() {
            // En una implementación real, aquí manejarías el DataTransfer
            // para actualizar los archivos seleccionados
            console.log('Actualizar lista de archivos seleccionados');
        }
    }
    
    // ==================== SCROLL SUAVE ====================
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // ==================== NOTIFICACIONES ====================
    function mostrarNotificacion(mensaje, tipo = 'exito', tiempo = 5000) {
        const notificacion = document.createElement('div');
        notificacion.className = `notificacion ${tipo}`;
        notificacion.textContent = mensaje;
        
        document.body.appendChild(notificacion);
        
        setTimeout(() => {
            notificacion.classList.add('mostrar');
        }, 100);
        
        setTimeout(() => {
            notificacion.classList.remove('mostrar');
            setTimeout(() => {
                document.body.removeChild(notificacion);
            }, 300);
        }, tiempo);
    }
    
    // Mostrar notificaciones almacenadas (ej. después de redirección)
    if (sessionStorage.getItem('notificacion')) {
        const notificacion = JSON.parse(sessionStorage.getItem('notificacion'));
        mostrarNotificacion(notificacion.mensaje, notificacion.tipo);
        sessionStorage.removeItem('notificacion');
    }
    
    // ==================== IDIOMA ====================
    const selectorIdioma = document.getElementById('selector-idioma');
    
    if (selectorIdioma) {
        selectorIdioma.addEventListener('change', function() {
            // Guardar preferencia de idioma
            localStorage.setItem('idioma', this.value);
            
            // Recargar la página para aplicar cambios
            // En una implementación real, podrías usar AJAX para cambiar el contenido
            window.location.reload();
        });
        
        // Establecer idioma guardado
        const idiomaGuardado = localStorage.getItem('idioma') || 'es';
        selectorIdioma.value = idiomaGuardado;
    }
    
    // ==================== GEOLOCALIZACIÓN ====================
    const botonGeolocalizacion = document.getElementById('obtener-ubicacion');
    
    if (botonGeolocalizacion) {
        botonGeolocalizacion.addEventListener('click', function() {
            if (navigator.geolocation) {
                this.disabled = true;
                this.textContent = 'Obteniendo ubicación...';
                
                navigator.geolocation.getCurrentPosition(
                    position => {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        
                        // Actualizar campos ocultos o mostrar en interfaz
                        document.getElementById('latitud').value = lat;
                        document.getElementById('longitud').value = lng;
                        
                        // Mostrar en interfaz
                        const ubicacionElemento = document.getElementById('mostrar-ubicacion') || 
                                                document.createElement('div');
                        ubicacionElemento.id = 'mostrar-ubicacion';
                        ubicacionElemento.innerHTML = `
                            <p>Ubicación obtenida: Lat ${lat.toFixed(4)}, Lng ${lng.toFixed(4)}</p>
                            <small>Esta información se incluirá en tu alerta de emergencia</small>
                        `;
                        
                        botonGeolocalizacion.insertAdjacentElement('afterend', ubicacionElemento);
                        botonGeolocalizacion.textContent = 'Ubicación obtenida';
                    },
                    error => {
                        console.error('Error al obtener ubicación:', error);
                        mostrarNotificacion(
                            'No se pudo obtener tu ubicación: ' + error.message, 
                            'error'
                        );
                        botonGeolocalizacion.disabled = false;
                        botonGeolocalizacion.textContent = 'Obtener Ubicación';
                    },
                    { enableHighAccuracy: true, timeout: 10000 }
                );
            } else {
                mostrarNotificacion(
                    'Tu navegador no soporta geolocalización', 
                    'error'
                );
            }
        });
    }
});