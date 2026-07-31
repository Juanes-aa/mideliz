<?php
session_start();
require_once __DIR__ . '/../config/config.php';

$site_name = 'Mideliz';
$title = 'Contacto';
$usuario_logueado = isset($_SESSION['usuario_id']);

$mensaje = '';
$tipoMensaje = '';

// Inicializar variables
$nombre = $email = $telefono = $asunto = $mensaje_form = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = sanitizar($_POST['nombre']);
    $email = sanitizar($_POST['email']);
    $telefono = sanitizar($_POST['telefono']);
    $asunto = sanitizar($_POST['asunto']);
    $mensaje_form = sanitizar($_POST['mensaje']);

    $errores = [];

    // Validaciones
    if (empty($nombre)) $errores[] = "El nombre es requerido";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errores[] = "El correo electrónico es inválido";
    if (empty($telefono)) $errores[] = "El teléfono es requerido";
    if (empty($asunto)) $errores[] = "El asunto es requerido";
    if (empty($mensaje_form)) $errores[] = "El mensaje es requerido";

    // Procesamiento
    if (empty($errores)) {
        $pdo = conectarDB();

        if ($pdo) {
            try {
                $sql = "INSERT INTO mensajes_contacto (nombre, email, telefono, asunto, mensaje, fecha_envio)
                        VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $nombre,
                    $email,
                    $telefono,
                    $asunto,
                    $mensaje_form,
                    date('Y-m-d H:i:s')
                ]);

                $mensaje = "✅ ¡Mensaje enviado con éxito! Te contactaremos pronto.";
                $tipoMensaje = "success";

                // Limpiar formulario
                $nombre = $email = $telefono = $asunto = $mensaje_form = '';
            } catch (PDOException $e) {
                $mensaje = "❌ Error al enviar el mensaje. Intenta nuevamente.";
                $tipoMensaje = "error";
                error_log("Error en contacto: " . $e->getMessage());
            }
        } else {
            $mensaje = "❌ Error de conexión a la base de datos.";
            $tipoMensaje = "error";
        }
    } else {
        $mensaje = "⚠️ Corrige los errores: " . implode(", ", $errores);
        $tipoMensaje = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($title); ?> - <?php echo htmlspecialchars($site_name); ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="assets/css/styles.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
  <style>
    /* Asegurar que las animaciones funcionen en móviles */
    * {
      -webkit-transform: translate3d(0, 0, 0);
      transform: translate3d(0, 0, 0);
    }
  </style>
</head>
<body class="flex flex-col min-h-screen font-poppins bg-gradient-to-br from-red-50 via-white to-red-100">

  <!-- Navigation -->
  <nav class="fixed w-full bg-white bg-opacity-90 backdrop-blur-md shadow-md z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 sm:py-4 flex justify-between items-center">
      <a href="index.php" class="text-xl sm:text-2xl font-bold text-red-600 hover:text-red-700 transition">
        <?php echo htmlspecialchars($site_name); ?>
      </a>

      <!-- Menú Desktop -->
      <ul class="hidden lg:flex items-center space-x-6 xl:space-x-8 text-base xl:text-lg">
        <li><a href="index.php" class="nav-link">Inicio</a></li>
        <li><a href="menu.php" class="nav-link">Menú</a></li>
        <li><a href="about.php" class="nav-link">Sobre Nosotros</a></li>
        
        <?php if ($usuario_logueado): ?>
          <li><a href="contact.php" class="nav-link">Contacto</a></li>
          <li class="relative">
            <button id="userMenuButton" class="focus:outline-none">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor" class="w-7 h-7 xl:w-8 xl:h-8 text-gray-700 hover:text-red-600 transition">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 
                    9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 
                    21a8.966 8.966 0 0 1-5.982-2.275M15 
                    9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
              </svg>
            </button>
            <div id="userMenu"
                class="hidden absolute right-0 mt-4 w-48 bg-white border bg-opacity-90 border-gray-200 rounded-xl shadow-lg py-2 z-50">
              <a href="profile.php" class="nav-link block px-4 py-2">Editar perfil</a>
              <a href="logout.php" class="nav-link block px-4 py-2">Cerrar sesión</a>
            </div>
          </li>
        <?php else: ?>
          <li><a href="login.php" class="nav-link">Iniciar Sesión</a></li>
        <?php endif; ?>
      </ul>

      <!-- Botón Hamburguesa Mobile -->
      <button id="mobileMenuButton" class="lg:hidden focus:outline-none z-50">
        <svg id="hamburgerIcon" class="w-7 h-7 text-gray-700 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
        <svg id="closeIcon" class="w-7 h-7 text-gray-700 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>
    </div>

    <!-- Menú Mobile -->
    <div id="mobileMenu" class="lg:hidden bg-white border-t border-gray-200 max-h-0 overflow-hidden transition-all duration-300">
      <ul class="px-4 py-4 space-y-3">
        <li><a href="index.php#inicio" class="block py-2 text-gray-700 hover:text-red-600 transition mobile-link">Inicio</a></li>
        <li><a href="menu.php" class="block py-2 text-gray-700 hover:text-red-600 transition mobile-link">Menú</a></li>
        <li><a href="about.php" class="block py-2 text-gray-700 hover:text-red-600 transition mobile-link">Sobre Nosotros</a></li>
        
        <?php if ($usuario_logueado): ?>
          <li><a href="contact.php" class="block py-2 text-gray-700 hover:text-red-600 transition mobile-link">Contacto</a></li>
          <li><a href="profile.php" class="block py-2 text-gray-700 hover:text-red-600 transition mobile-link">Editar perfil</a></li>
          <li><a href="logout.php" class="block py-2 text-red-600 hover:text-red-700 transition font-semibold mobile-link">Cerrar sesión</a></li>
        <?php else: ?>
          <li><a href="login.php" class="block py-2 text-gray-700 hover:text-red-600 transition mobile-link">Iniciar Sesión</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </nav>

  <!-- Main Content -->
  <main class="flex-1 px-4 sm:px-6 pt-20 sm:pt-24 pb-8">
    <div class="max-w-7xl mx-auto mt-4 sm:mt-8">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
        
        <!-- Formulario -->
        <div class="form-card">
          <div class="bg-white rounded-2xl shadow-2xl p-6 sm:p-8 lg:p-10">
            
            <div class="text-center mb-6 sm:mb-8">
              <div class="inline-block p-3 bg-red-100 rounded-full mb-4">
                <i class="fas fa-envelope text-4xl sm:text-5xl text-red-600"></i>
              </div>
              <h2 class="text-2xl sm:text-3xl font-bold text-red-600 mb-2">Contáctanos</h2>
              <p class="text-gray-600 text-sm sm:text-base">
                Completa el formulario y nos pondremos en contacto contigo
              </p>
            </div>

            <?php if ($mensaje): ?>
              <div class="mensaje-alerta mb-6 p-3 sm:p-4 rounded-lg text-sm sm:text-base <?php echo $tipoMensaje === 'success' ? 'bg-green-100 text-green-600' : 'bg-red-100 border-red-400 text-red-600'; ?> border">
                <?php echo $mensaje; ?>
              </div>
            <?php endif; ?>

            <form method="POST" action="" class="space-y-4">
              
              <!-- Nombre -->
              <div class="form-group">
                <label for="nombre" class="block text-sm font-semibold text-gray-700 mb-2">
                  <i class="fas fa-user text-red-600 mr-2"></i>Nombre Completo *
                </label>
                <input type="text" id="nombre" name="nombre"
                      value="<?php echo htmlspecialchars($nombre); ?>"
                      required
                      placeholder="Tu nombre completo"
                      class="w-full p-3 text-sm sm:text-base border-2 border-gray-300 rounded-lg focus:border-red-600 outline-none transition-all duration-300">
              </div>

              <!-- Email y Teléfono -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-group">
                  <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-envelope text-red-600 mr-2"></i>Email *
                  </label>
                  <input type="email" id="email" name="email"
                        value="<?php echo htmlspecialchars($email); ?>"
                        required
                        placeholder="ejemplo@correo.com"
                        class="w-full p-3 text-sm sm:text-base border-2 border-gray-300 rounded-lg focus:border-red-600 outline-none transition-all duration-300">
                </div>

                <div class="form-group">
                  <label for="telefono" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-phone text-red-600 mr-2"></i>Teléfono *
                  </label>
                  <input type="tel" id="telefono" name="telefono"
                        value="<?php echo htmlspecialchars($telefono); ?>"
                        required
                        placeholder="+57 300 123 4567"
                        class="w-full p-3 text-sm sm:text-base border-2 border-gray-300 rounded-lg focus:border-red-600 outline-none transition-all duration-300">
                </div>
              </div>

              <!-- Asunto -->
              <div class="form-group">
                <label for="asunto" class="block text-sm font-semibold text-gray-700 mb-2">
                  <i class="fas fa-tag text-red-600 mr-2"></i>Asunto *
                </label>
                <select id="asunto" name="asunto" required
                        class="w-full p-3 text-sm sm:text-base border-2 border-gray-300 rounded-lg focus:border-red-600 outline-none transition-all duration-300">
                    <option value="">Selecciona un asunto...</option>
                    <option value="Consulta General" <?php echo ($asunto == 'Consulta General') ? 'selected' : ''; ?>>Consulta General</option>
                    <option value="Reserva de Evento" <?php echo ($asunto == 'Reserva de Evento') ? 'selected' : ''; ?>>Reserva de Evento</option>
                    <option value="Cotización" <?php echo ($asunto == 'Cotización') ? 'selected' : ''; ?>>Cotización</option>
                    <option value="Sugerencias" <?php echo ($asunto == 'Sugerencias') ? 'selected' : ''; ?>>Sugerencias</option>
                    <option value="Reclamos" <?php echo ($asunto == 'Reclamos') ? 'selected' : ''; ?>>Reclamos</option>
                    <option value="Otro" <?php echo ($asunto == 'Otro') ? 'selected' : ''; ?>>Otro</option>
                </select>
              </div>

              <!-- Mensaje -->
              <div class="form-group">
                <label for="mensaje" class="block text-sm font-semibold text-gray-700 mb-2">
                  <i class="fas fa-comment-dots text-red-600 mr-2"></i>Mensaje *
                </label>
                <textarea id="mensaje" name="mensaje" rows="5"
                          required
                          placeholder="Escribe tu mensaje aquí..."
                          class="w-full p-3 text-sm sm:text-base border-2 border-gray-300 rounded-lg focus:border-red-600 outline-none transition-all duration-300 resize-none"><?php echo htmlspecialchars($mensaje_form); ?></textarea>
              </div>

              <!-- Botón -->
              <button type="submit" class="submit-btn w-full bg-red-600 hover:bg-red-700 text-white py-3 sm:py-3.5 rounded-lg font-semibold shadow-lg transition-all duration-300 hover:-translate-y-1 active:translate-y-0 text-sm sm:text-base flex items-center justify-center space-x-2">
                <span>Enviar Mensaje</span>
                <i class="fas fa-paper-plane"></i>
              </button>
            </form>

          </div>
        </div>

        <!-- Información de Contacto -->
        <div class="contact-info space-y-6">
          
          <!-- Datos de Contacto -->
          <div class="info-card bg-white rounded-2xl shadow-2xl p-6 sm:p-8">
            <h3 class="text-xl sm:text-2xl font-bold text-red-600 mb-6 flex items-center">
              <i class="fas fa-info-circle mr-2"></i>Información de Contacto
            </h3>
            
            <div class="space-y-5">
              <div class="info-item flex items-start space-x-4">
                <div class="flex-shrink-0 w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                  <i class="fas fa-map-marker-alt text-red-600 text-xl"></i>
                </div>
                <div class="flex-1">
                  <h4 class="font-semibold text-gray-800 mb-1 text-sm sm:text-base">Dirección</h4>
                  <p class="text-gray-600 text-sm sm:text-base">Cocina oculta, Medellín, Colombia</p>
                </div>
              </div>

              <div class="info-item flex items-start space-x-4">
                <div class="flex-shrink-0 w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                  <i class="fas fa-phone-alt text-red-600 text-xl"></i>
                </div>
                <div class="flex-1">
                  <h4 class="font-semibold text-gray-800 mb-1 text-sm sm:text-base">Teléfono</h4>
                  <a href="tel:+573217714480" class="text-gray-600 hover:text-red-600 transition text-sm sm:text-base">
                    +57 321 771 4480
                  </a>
                </div>
              </div>

              <div class="info-item flex items-start space-x-4">
                <div class="flex-shrink-0 w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                  <i class="fas fa-envelope text-red-600 text-xl"></i>
                </div>
                <div class="flex-1">
                  <h4 class="font-semibold text-gray-800 mb-1 text-sm sm:text-base">Email</h4>
                  <a href="mailto:contacto@mideliz.com" class="text-gray-600 hover:text-red-600 transition text-sm sm:text-base break-all">
                    contacto@mideliz.com
                  </a>
                </div>
              </div>

              <div class="info-item flex items-start space-x-4">
                <div class="flex-shrink-0 w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                  <i class="fas fa-clock text-red-600 text-xl"></i>
                </div>
                <div class="flex-1">
                  <h4 class="font-semibold text-gray-800 mb-1 text-sm sm:text-base">Horario</h4>
                  <p class="text-gray-600 text-sm sm:text-base">Lunes a Viernes: 9:00 AM - 6:00 PM</p>
                  <p class="text-gray-600 text-sm sm:text-base">Sábados: 10:00 AM - 2:00 PM</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Redes Sociales -->
          <div class="social-card bg-gradient-to-br from-red-600 to-red-700 rounded-2xl shadow-2xl p-6 sm:p-8 text-white">
            <h3 class="text-xl sm:text-2xl font-bold mb-4 flex items-center">
              <i class="fas fa-share-alt mr-2"></i>Síguenos
            </h3>
            <p class="mb-6 text-sm sm:text-base opacity-90">
              Mantente al día con nuestras novedades y eventos especiales
            </p>
            <div class="flex space-x-4">
              <a href="#" class="social-icon w-12 h-12 sm:w-14 sm:h-14 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full flex items-center justify-center transition-all" aria-label="Facebook">
                <i class="fab fa-facebook-f text-xl sm:text-2xl"></i>
              </a>
              <a href="#" class="social-icon w-12 h-12 sm:w-14 sm:h-14 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full flex items-center justify-center transition-all" aria-label="Instagram">
                <i class="fab fa-instagram text-xl sm:text-2xl"></i>
              </a>
              </a>
              <a href="#" class="social-icon w-12 h-12 sm:w-14 sm:h-14 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full flex items-center justify-center transition-all" aria-label="WhatsApp">
                <i class="fab fa-whatsapp text-xl sm:text-2xl"></i>
              </a>
            </div>
          </div>

          <!-- CTA Reserva -->
          <div class="cta-card bg-red-50 border-2 border-red-200 rounded-2xl p-6 sm:p-8 text-center">
            <i class="fas fa-calendar-check text-4xl sm:text-5xl text-red-600 mb-4"></i>
            <h3 class="text-lg sm:text-xl font-bold mb-2 text-gray-800">¿Listo para tu evento?</h3>
            <p class="text-gray-600 mb-5 text-sm sm:text-base">
              Reserva ahora y disfruta de una experiencia gastronómica única
            </p>
            <a href="reservation.php" class="inline-block bg-red-600 text-white px-6 sm:px-8 py-2.5 sm:py-3 rounded-lg font-semibold hover:bg-red-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm sm:text-base">
              <i class="fas fa-utensils mr-2"></i>Reserva tu Evento
            </a>
          </div>

        </div>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="bg-red-700 text-white py-6 sm:py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center">
      <p class="text-sm sm:text-base">© <?php echo date("Y"); ?> <?php echo htmlspecialchars($site_name); ?>. Todos los derechos reservados.</p>
      <div class="mt-3 sm:mt-4 flex justify-center space-x-4 sm:space-x-6 text-xl sm:text-2xl">
        <a href="#" class="hover:text-red-300 transition"><i class="fab fa-facebook-f"></i></a>
        <a href="#" class="hover:text-red-300 transition"><i class="fab fa-instagram"></i></a>
        <a href="#" class="hover:text-red-300 transition"><i class="fab fa-whatsapp"></i></a>
      </div>
    </div>
  </footer>

  <script>
    window.addEventListener("load", () => {
      gsap.registerPlugin(ScrollTrigger);

      // Detectar si es dispositivo móvil
      const isMobile = window.innerWidth < 768;

      // Configuración de animaciones según el dispositivo
      const animConfig = {
        mobile: {
          duration: 0.8,
          ease: "power2.out"
        },
        desktop: {
          duration: 1,
          ease: "power4.out"
        }
      };

      const config = isMobile ? animConfig.mobile : animConfig.desktop;

      // Animación Navbar
      gsap.from("nav", {
        y: -100,
        opacity: 0,
        duration: config.duration,
        ease: config.ease
      });

      // === MENÚ MOBILE ===
      const mobileMenuButton = document.getElementById('mobileMenuButton');
      const mobileMenu = document.getElementById('mobileMenu');
      const hamburgerIcon = document.getElementById('hamburgerIcon');
      const closeIcon = document.getElementById('closeIcon');
      let menuOpen = false;

      if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', (e) => {
          e.stopPropagation();
          menuOpen = !menuOpen;
          
          if (menuOpen) {
            mobileMenu.style.maxHeight = mobileMenu.scrollHeight + 'px';
            hamburgerIcon.classList.add('hidden');
            closeIcon.classList.remove('hidden');
          } else {
            mobileMenu.style.maxHeight = '0';
            hamburgerIcon.classList.remove('hidden');
            closeIcon.classList.add('hidden');
          }
        });

        // Cerrar menú al hacer clic en un enlace
        document.querySelectorAll('.mobile-link').forEach(link => {
          link.addEventListener('click', () => {
            mobileMenu.style.maxHeight = '0';
            hamburgerIcon.classList.remove('hidden');
            closeIcon.classList.add('hidden');
            menuOpen = false;
          });
        });

        // Cerrar menú al hacer clic fuera
        document.addEventListener('click', (e) => {
          if (menuOpen && !mobileMenu.contains(e.target) && !mobileMenuButton.contains(e.target)) {
            mobileMenu.style.maxHeight = '0';
            hamburgerIcon.classList.remove('hidden');
            closeIcon.classList.add('hidden');
            menuOpen = false;
          }
        });
      }

      // Menú usuario
      const userMenuButton = document.getElementById('userMenuButton');
      const userMenu = document.getElementById('userMenu');

      if (userMenuButton && userMenu) {
        userMenuButton.addEventListener('click', (e) => {
          e.stopPropagation();
          userMenu.classList.toggle('hidden');
        });
        document.addEventListener('click', (e) => {
          if (!userMenuButton.contains(e.target) && !userMenu.contains(e.target)) {
            userMenu.classList.add('hidden');
          }
        });
      }

      // === ANIMACIONES GSAP ===
      
      // Formulario con efecto back.out
      gsap.from(".form-card", {
        opacity: 0,
        y: isMobile ? 30 : 50,
        scale: 0.95,
        duration: config.duration,
        delay: 0.3,
        ease: "back.out(1.2)"
      });

      // Información de contacto
      gsap.from(".contact-info > div", {
        opacity: 0,
        y: isMobile ? 30 : 50,
        scale: 0.95,
        duration: config.duration,
        stagger: 0.15,
        delay: 0.4,
        ease: "back.out(1.2)"
      });

      // Items de información
      gsap.from(".info-item", {
        opacity: 0,
        x: isMobile ? 0 : 20,
        duration: 0.6,
        stagger: 0.12,
        delay: 0.6,
        ease: "power2.out"
      });

      // Animación de mensaje de alerta si existe
      const mensajeAlerta = document.querySelector('.mensaje-alerta');
      if (mensajeAlerta) {
        gsap.from(mensajeAlerta, {
          opacity: 0,
          y: -20,
          duration: 0.5,
          delay: 0.4,
          ease: "power2.out"
        });

        // Auto-ocultar después de 5 segundos
        setTimeout(() => {
          gsap.to(mensajeAlerta, {
            opacity: 0,
            y: -20,
            duration: 0.5,
            ease: "power2.in",
            onComplete: () => {
              mensajeAlerta.style.display = 'none';
            }
          });
        }, 5000);
      }

      // Footer
      gsap.from("footer", {
        y: isMobile ? 30 : 50,
        opacity: 0,
        duration: config.duration,
        delay: 0.8,
        ease: config.ease
      });

      // === EFECTOS HOVER (SOLO DESKTOP) ===
      if (!isMobile) {
        // Hover en info-items
        document.querySelectorAll('.info-item').forEach(item => {
          item.addEventListener('mouseenter', () => {
            gsap.to(item, { 
              x: 10, 
              duration: 0.3, 
              ease: "power2.out" 
            });
          });
          item.addEventListener('mouseleave', () => {
            gsap.to(item, { 
              x: 0, 
              duration: 0.3, 
              ease: "power2.out" 
            });
          });
        });

        // Hover en redes sociales
        document.querySelectorAll('.social-icon').forEach(icon => {
          icon.addEventListener('mouseenter', () => {
            gsap.to(icon, { 
              scale: 1.15,
              duration: 0.3, 
              ease: "back.out(1.7)" 
            });
          });
          icon.addEventListener('mouseleave', () => {
            gsap.to(icon, { 
              scale: 1, 
              rotate: 0,
              duration: 0.3, 
              ease: "power2.out" 
            });
          });
        });
      }
    });
  </script>
</body>
</html>