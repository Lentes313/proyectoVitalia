document.addEventListener('DOMContentLoaded', function() {
    const botonEmergencia = document.getElementById('boton-emergencia');
    const formularioEmergencia = document.getElementById('formulario-emergencia');
    const checkboxContactos = document.querySelectorAll('input[name="contactos[]"]');
    
    if (botonEmergencia) {
        botonEmergencia.addEventListener('click', function(e) {
            if (formularioEmergencia && !confirm('¿Estás seguro de activar la alerta de emergencia? Se notificará a los contactos seleccionados.')) {
                e.preventDefault();
            }
        });
    }
    
    // Validar que al menos un contacto esté seleccionado
    if (formularioEmergencia) {
        formularioEmergencia.addEventListener('submit', function(e) {
            let alMenosUnoSeleccionado = false;
            
            checkboxContactos.forEach(checkbox => {
                if (checkbox.checked) {
                    alMenosUnoSeleccionado = true;
                }
            });
            
            if (!alMenosUnoSeleccionado) {
                e.preventDefault();
                alert('Por favor selecciona al menos un contacto para alertar.');
            }
        });
    }
    
    // Geolocalización para emergencias
    const mapaEmergencia = document.getElementById('mapa-emergencia');
    
    if (mapaEmergencia) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    
                    // En una implementación real, aquí integrarías Google Maps o similar
                    mapaEmergencia.innerHTML = `
                        <div class="mapa-real">
                            <p>Ubicación obtenida: Lat ${lat.toFixed(4)}, Lng ${lng.toFixed(4)}</p>
                            <div class="mapa-placeholder"></div>
                            <input type="hidden" name="latitud" value="${lat}">
                            <input type="hidden" name="longitud" value="${lng}">
                        </div>
                    `;
                },
                function(error) {
                    mapaEmergencia.innerHTML = `
                        <div class="alerta error">
                            No se pudo obtener tu ubicación: ${error.message}
                        </div>
                    `;
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        } else {
            mapaEmergencia.innerHTML = `
                <div class="alerta error">
                    Tu navegador no soporta geolocalización.
                </div>
            `;
        }
    }
    
    // Manejo de la alerta rápida desde otras páginas
    const activarAlertaRapida = document.getElementById('activar-alerta-rapida');
    
    if (activarAlertaRapida) {
        activarAlertaRapida.addEventListener('click', function() {
            fetch('api/emergencia_rapida.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    usuario_id: this.dataset.usuarioId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.exito) {
                    alert('Alerta de emergencia enviada a tus contactos principales.');
                } else {
                    alert('Error al enviar alerta: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al comunicarse con el servidor.');
            });
        });
    }
});