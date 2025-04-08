/**
 * chat.js - Funcionalidades del chatbot de VitaliA
 * 
 * Incluye:
 * - Interacción con el chatbot
 * - Manejo de mensajes
 * - Integración con API de IA
 * - Síntomas predefinidos
 * - Modo de voz
 */

class ChatVitaliA {
    constructor() {
        this.mensajesChat = document.getElementById('mensajes-chat');
        this.mensajeUsuario = document.getElementById('mensaje-usuario');
        this.botonEnviar = document.getElementById('enviar-mensaje');
        this.botonEmergencia = document.getElementById('boton-emergencia');
        this.botonesSintoma = document.querySelectorAll('.boton-sintoma');
        this.consejosContenido = document.getElementById('consejos-contenido');
        this.botonVoz = document.getElementById('activar-voz');
        this.botonImagen = document.getElementById('adjuntar-imagen');
        this.recognizer = null;
        this.escuchando = false;
        
        this.inicializar();
    }
    
    inicializar() {
        // Mostrar mensaje inicial
        this.agregarMensaje('asistente', 'Hola, soy VitaliA. ¿En qué puedo ayudarte hoy?');
        
        // Configurar eventos
        this.configurarEventos();
        
        // Cargar consejos iniciales
        this.cargarConsejos('general');
    }
    
    configurarEventos() {
        // Enviar mensaje al presionar Enter o el botón
        this.mensajeUsuario.addEventListener('keypress', e => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                this.enviarMensaje();
            }
        });
        
        this.botonEnviar.addEventListener('click', () => this.enviarMensaje());
        
        // Manejar selección de síntomas
        this.botonesSintoma.forEach(boton => {
            boton.addEventListener('click', () => {
                const sintoma = boton.getAttribute('data-sintoma');
                this.manejarSintomaSeleccionado(boton.textContent, sintoma);
            });
        });
        
        // Configurar botón de emergencia
        if (this.botonEmergencia) {
            this.botonEmergencia.addEventListener('click', () => this.manejarEmergencia());
        }
        
        // Configurar reconocimiento de voz (si está disponible)
        if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
            this.configurarReconocimientoVoz();
        } else {
            this.botonVoz.style.display = 'none';
        }
        
        // Configurar subida de imágenes
        if (this.botonImagen) {
            this.botonImagen.addEventListener('click', () => this.manejarSubidaImagen());
        }
    }
    
    manejarSintomaSeleccionado(nombreSintoma, idSintoma) {
        // Mostrar mensaje del usuario
        this.agregarMensaje('usuario', `Estoy experimentando ${nombreSintoma.toLowerCase()}`);
        
        // Mostrar respuesta del asistente
        setTimeout(() => {
            this.agregarMensaje('asistente', `Entiendo que tienes ${nombreSintoma.toLowerCase()}. Voy a brindarte información sobre cómo manejar esta situación.`);
            
            // Cargar consejos específicos
            this.cargarConsejos(idSintoma);
            
            // Obtener información más detallada de la API
            this.obtenerInformacionSintoma(idSintoma);
        }, 1000);
    }
    
    cargarConsejos(idSintoma) {
        const consejos = {
            'general': [
                'En caso de emergencia grave, activa la alerta de emergencia.',
                'Mantén la calma y evalúa la situación antes de actuar.',
                'Asegúrate de que el área es segura antes de ayudar.'
            ],
            'dolor_cabeza': [
                'Descansa en un lugar tranquilo y oscuro',
                'Aplica compresas frías en la frente',
                'Toma suficiente agua para mantenerte hidratado',
                'Evita luces brillantes y ruidos fuertes'
            ],
            'dolor_estomacal': [
                'Toma infusiones de manzanilla o jengibre',
                'Aplica calor suave en el abdomen',
                'Evita alimentos pesados o picantes',
                'Descansa en posición fetal para aliviar molestias'
            ],
            'quemadura': [
                'Enfría la zona con agua corriente (no muy fría) por 10-15 minutos',
                'No uses hielo directamente sobre la quemadura',
                'No apliques cremas o remedios caseros',
                'Cubre con gasa estéril sin apretar'
            ],
            'herida': [
                'Lava la herida con agua limpia y jabón suave',
                'Aplica presión suave para detener el sangrado',
                'Cubre con vendaje estéril',
                'Busca atención médica si la herida es profunda o no deja de sangrar'
            ]
        };
        
        if (this.consejosContenido) {
            const consejosMostrar = consejos[idSintoma] || consejos['general'];
            this.consejosContenido.innerHTML = consejosMostrar.map(consejo => 
                `<div class="consejo-item">${consejo}</div>`
            ).join('');
        }
    }
    
    enviarMensaje() {
        const texto = this.mensajeUsuario.value.trim();
        if (texto) {
            this.agregarMensaje('usuario', texto);
            this.mensajeUsuario.value = '';
            
            // Mostrar indicador de que el asistente está escribiendo
            this.mostrarEscribiendo();
            
            // Simular respuesta del asistente
            setTimeout(() => {
                this.obtenerRespuestaIA(texto);
            }, 1000);
        }
    }
    
    mostrarEscribiendo() {
        // Eliminar indicador anterior si existe
        const indicadorAnterior = this.mensajesChat.querySelector('.escribiendo');
        if (indicadorAnterior) {
            indicadorAnterior.remove();
        }
        
        // Crear nuevo indicador
        const indicador = document.createElement('div');
        indicador.className = 'mensaje asistente escribiendo';
        indicador.innerHTML = '<div class="puntos-escribiendo"><span></span><span></span><span></span></div>';
        this.mensajesChat.appendChild(indicador);
        this.mensajesChat.scrollTop = this.mensajesChat.scrollHeight;
    }
    
    obtenerRespuestaIA(mensaje) {
        // Eliminar indicador de "escribiendo"
        const indicador = this.mensajesChat.querySelector('.escribiendo');
        if (indicador) {
            indicador.remove();
        }
        
        // En una implementación real, aquí harías una llamada a tu API
        // fetch('api/chat.php', { ... })
        
        // Simulación de respuesta basada en palabras clave
        const respuesta = this.generarRespuestaSimulada(mensaje);
        
        // Mostrar respuesta
        this.agregarMensaje('asistente', respuesta.texto);
        
        // Si es una emergencia, sugerir activar alerta
        if (respuesta.emergencia && this.botonEmergencia) {
            setTimeout(() => {
                this.agregarMensaje('asistente', 'Parece que estás en una situación de emergencia. ¿Deseas activar la alerta de emergencia para notificar a tus contactos?');
            }, 1000);
        }
    }
    
    generarRespuestaSimulada(mensaje) {
        const palabrasClave = {
            'dolor cabeza': {
                texto: 'Para dolores de cabeza, te recomiendo: 1. Descansar en un lugar tranquilo. 2. Tomar agua. 3. Aplicar compresas frías. Si persiste, consulta a un médico.',
                emergencia: false
            },
            'dolor estómago': {
                texto: 'Para dolor estomacal: 1. Toma infusiones de manzanilla. 2. Aplica calor suave en el abdomen. 3. Evita alimentos pesados. Si hay dolor intenso o fiebre, busca atención médica.',
                emergencia: false
            },
            'quemadura': {
                texto: 'Para quemaduras: 1. Enfría con agua corriente (no muy fría) por 10-15 min. 2. No uses hielo. 3. Cubre con gasa estéril. Si es grave, busca atención médica inmediata.',
                emergencia: true
            },
            'sangrado': {
                texto: 'Para controlar sangrado: 1. Aplica presión directa con gasa o tela limpia. 2. Eleva la herida por encima del corazón si es posible. 3. Si no se detiene después de 10 minutos, busca ayuda médica.',
                emergencia: true
            },
            'emergencia': {
                texto: 'Si estás en una emergencia médica grave, activa la alerta de emergencia para notificar a tus contactos y servicios médicos cercanos.',
                emergencia: true
            }
        };
        
        // Buscar coincidencias con palabras clave
        mensaje = mensaje.toLowerCase();
        for (const [clave, respuesta] of Object.entries(palabrasClave)) {
            if (mensaje.includes(clave.toLowerCase())) {
                return respuesta;
            }
        }
        
        // Respuesta por defecto
        return {
            texto: `He recibido tu mensaje sobre "${mensaje}". Estoy diseñado para ayudarte con información sobre primeros auxilios. Por favor, describe tu situación con más detalles para que pueda ofrecerte la mejor asistencia.`,
            emergencia: false
        };
    }
    
    manejarEmergencia() {
        if (confirm('¿Estás seguro de activar la alerta de emergencia? Se notificará a tus contactos y servicios médicos cercanos.')) {
            // En una implementación real, aquí harías una llamada a la API
            // fetch('api/emergencia.php', { ... })
            
            // Simular envío de alerta
            this.agregarMensaje('asistente', 'Alerta de emergencia activada. Se ha notificado a tus contactos y servicios médicos cercanos. Por favor, mantén la calma y sigue las instrucciones.');
            
            if (this.botonEmergencia) {
                this.botonEmergencia.disabled = true;
                this.botonEmergencia.textContent = 'Alerta Enviada';
            }
        }
    }
    
    configurarReconocimientoVoz() {
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        this.recognizer = new SpeechRecognition();
        this.recognizer.lang = 'es-ES';
        this.recognizer.interimResults = false;
        this.recognizer.maxAlternatives = 1;
        
        this.botonVoz.addEventListener('click', () => {
            if (this.escuchando) {
                this.recognizer.stop();
                this.escuchando = false;
                this.botonVoz.classList.remove('activo');
            } else {
                this.recognizer.start();
                this.escuchando = true;
                this.botonVoz.classList.add('activo');
                this.mensajeUsuario.placeholder = 'Escuchando...';
            }
        });
        
        this.recognizer.onresult = event => {
            const transcript = event.results[0][0].transcript;
            this.mensajeUsuario.value = transcript;
            this.mensajeUsuario.placeholder = 'Escribe tu consulta aquí...';
            this.botonVoz.classList.remove('activo');
            this.escuchando = false;
            
            // Opcional: enviar automáticamente
            setTimeout(() => this.enviarMensaje(), 500);
        };
        
        this.recognizer.onerror = event => {
            console.error('Error en reconocimiento de voz:', event.error);
            this.mensajeUsuario.placeholder = 'Escribe tu consulta aquí...';
            this.botonVoz.classList.remove('activo');
            this.escuchando = false;
            
            let mensajeError = 'Error en el reconocimiento de voz.';
            if (event.error === 'no-speech') {
                mensajeError = 'No se detectó voz. Intenta nuevamente.';
            } else if (event.error === 'audio-capture') {
                mensajeError = 'No se pudo acceder al micrófono.';
            }
            
            this.agregarMensaje('asistente', mensajeError);
        };
        
        this.recognizer.onend = () => {
            if (this.escuchando) {
                this.recognizer.start(); // Continuar escuchando
            } else {
                this.mensajeUsuario.placeholder = 'Escribe tu consulta aquí...';
            }
        };
    }
    
    manejarSubidaImagen() {
        // Crear input de archivo dinámicamente
        const inputFile = document.createElement('input');
        inputFile.type = 'file';
        inputFile.accept = 'image/*';
        inputFile.multiple = true;
        
        inputFile.addEventListener('change', () => {
            if (inputFile.files && inputFile.files.length > 0) {
                // Mostrar previsualización (simulada)
                this.agregarMensaje('usuario', 'He subido una imagen relacionada con mi consulta.');
                
                // En una implementación real, aquí subirías la imagen al servidor
                // y mostrarías una previsualización
                
                setTimeout(() => {
                    this.agregarMensaje('asistente', 'He recibido la imagen. Estoy analizándola para darte una mejor recomendación.');
                    
                    // Simular análisis de imagen
                    setTimeout(() => {
                        this.agregarMensaje('asistente', 'Basado en la imagen, te recomiendo: [recomendación generada por IA]. Por favor describe cualquier síntoma adicional para mayor precisión.');
                    }, 2000);
                }, 1000);
            }
        });
        
        inputFile.click();
    }
    
    agregarMensaje(tipo, texto) {
        // Eliminar indicador de "escribiendo" si existe
        const indicador = this.mensajesChat.querySelector('.escribiendo');
        if (indicador) {
            indicador.remove();
        }
        
        const elementoMensaje = document.createElement('div');
        elementoMensaje.classList.add('mensaje', tipo);
        
        // Formatear URLs como enlaces
        const textoConEnlaces = texto.replace(
            /(https?:\/\/[^\s]+)/g, 
            '<a href="$1" target="_blank" rel="noopener noreferrer">$1</a>'
        );
        
        elementoMensaje.innerHTML = `<p>${textoConEnlaces}</p>`;
        this.mensajesChat.appendChild(elementoMensaje);
        
        // Desplazarse al final del chat
        this.mensajesChat.scrollTop = this.mensajesChat.scrollHeight;
        
        // Resaltar términos médicos importantes
        this.resaltarTerminosMedicos(elementoMensaje);
    }
    
    resaltarTerminosMedicos(elemento) {
        const terminos = [
            'RCP', 'reanimación', 'hemorragia', 'quemadura', 
            'fractura', 'convulsión', 'ahogamiento', 'envenenamiento',
            'shock', 'asfixia', 'deshidratación', 'infarto'
        ];
        
        const parrafo = elemento.querySelector('p');
        let texto = parrafo.innerHTML;
        
        terminos.forEach(termino => {
            const regex = new RegExp(`(${termino})`, 'gi');
            texto = texto.replace(regex, '<span class="termino-medico">$1</span>');
        });
        
        parrafo.innerHTML = texto;
    }
    
    obtenerInformacionSintoma(idSintoma) {
        // Simulación de obtención de información detallada
        const informacion = {
            'dolor_cabeza': {
                titulo: 'Dolor de cabeza',
                descripcion: 'Los dolores de cabeza pueden tener diversas causas como estrés, deshidratación, tensión muscular o condiciones médicas subyacentes.',
                acciones: [
                    'Descansar en un lugar tranquilo y oscuro',
                    'Hidratarse adecuadamente',
                    'Aplicar compresas frías en la frente',
                    'Masajear suavemente las sienes'
                ],
                cuandoBuscarAyuda: 'Si el dolor es intenso, persistente o acompañado de fiebre, visión borrosa o confusión.'
            },
            'quemadura': {
                titulo: 'Quemaduras',
                descripcion: 'Las quemaduras se clasifican por grados según su severidad. Las de primer grado afectan solo la capa externa de la piel.',
                acciones: [
                    'Enfriar con agua corriente (15-25°C) por 10-15 minutos',
                    'No usar hielo, mantequilla u otros remedios caseros',
                    'Cubrir con gasa estéril sin apretar',
                    'Tomar analgésicos suaves si hay dolor'
                ],
                cuandoBuscarAyuda: 'Para quemaduras de segundo grado (ampollas) o tercer grado (piel carbonizada o blanca), o si cubren un área grande.'
            }
        };
        
        const info = informacion[idSintoma];
        if (info) {
            setTimeout(() => {
                let mensaje = `<strong>${info.titulo}</strong><br>${info.descripcion}<br><br>`;
                mensaje += `<strong>Acciones recomendadas:</strong><br>- ${info.acciones.join('<br>- ')}<br><br>`;
                mensaje += `<strong>Busca ayuda médica:</strong> ${info.cuandoBuscarAyuda}`;
                
                this.agregarMensaje('asistente', mensaje);
            }, 1500);
        }
    }
}

// Inicializar el chat cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    new ChatVitaliA();
});