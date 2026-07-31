<?php
require_once __DIR__ . '/../config/config.php';

$mensaje = '';
$tipoMensaje = '';
$site_name = 'Mideliz';
$title = 'Reservas';

// Inicializar variables para evitar warnings
$nombre = $apellido = $ciudad = $email = $telefono = $fecha_evento = $comentarios = '';
$num_invitados = $tipo_comida_id = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre        = sanitizar($_POST['nombre']);
    $apellido      = sanitizar($_POST['apellido']);
    $ciudad        = sanitizar($_POST['ciudad']);
    $email         = sanitizar($_POST['correo']);
    $telefono      = sanitizar($_POST['telefono']);
    $fecha_evento  = sanitizar($_POST['fecha_evento']);
    $num_invitados = (int) $_POST['num_invitados'];
    $tipo_comida_id = (int) $_POST['tipo_comida'];
    $comentarios   = sanitizar($_POST['comentarios']);

    $errores = [];

    // --- Validaciones ---
    if (empty($nombre)) $errores[] = "El nombre es requerido";
    if (empty($apellido)) $errores[] = "El apellido es requerido";
    if (empty($ciudad)) $errores[] = "La ciudad es requerida";
    if (empty($email) || !validarEmail($email)) $errores[] = "El correo electrónico es inválido";
    if (empty($telefono)) $errores[] = "El teléfono es requerido";

    if (empty($fecha_evento)) {
        $errores[] = "La fecha del evento es requerida";
    } else {
        $fechaEvento = DateTime::createFromFormat('Y-m-d', $fecha_evento);
        $hoy = new DateTime('today');
        if (!$fechaEvento) {
            $errores[] = "Formato de fecha no válido";
        } elseif ($fechaEvento < $hoy) {
            $errores[] = "La fecha del evento debe ser futura";
        }
    }

    if ($num_invitados < 1 || $num_invitados > 500) $errores[] = "Número de invitados no válido";
    if ($tipo_comida_id <= 0) $errores[] = "Debe seleccionar un tipo de comida";

    // --- Procesamiento ---
    if (empty($errores)) {
        $pdo = conectarDB();

        if ($pdo) {
            try {
                // Verificar si el usuario ya existe
                $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
                $stmt->execute([$email]);
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($usuario) {
                    $usuario_id = $usuario['id'];
                } else {
                    // Insertar nuevo usuario
                    $sqlUsuario = "INSERT INTO usuarios (nombre, apellido, ciudad, telefono, email, password)
                                   VALUES (?, ?, ?, ?, ?, NULL)";
                    $stmt = $pdo->prepare($sqlUsuario);
                    $stmt->execute([$nombre, $apellido, $ciudad, $telefono, $email]);
                    $usuario_id = $pdo->lastInsertId();
                }

                // Insertar reserva
                $sqlReserva = "INSERT INTO reservas 
                               (usuario_id, fecha_evento, num_invitados, tipo_comida_id, comentarios, estado_id, fecha_reserva)
                               VALUES (?, ?, ?, ?, ?, 1, ?)";
                $stmt2 = $pdo->prepare($sqlReserva);
                $stmt2->execute([
                    $usuario_id,
                    $fecha_evento,
                    $num_invitados,
                    $tipo_comida_id,
                    $comentarios,
                    date('Y-m-d H:i:s')
                ]);

                $mensaje = "✅ ¡Reserva registrada con éxito!";
                $tipoMensaje = "success";

                // Limpiar valores del formulario
                $nombre = $apellido = $ciudad = $email = $telefono = $fecha_evento = $comentarios = '';
                $num_invitados = $tipo_comida_id = '';
            } catch (PDOException $e) {
                $mensaje = "❌ Error al guardar la reserva. Intenta nuevamente.";
                $tipoMensaje = "error";
                error_log("Error en reserva: " . $e->getMessage());
            }
        } else {
            $mensaje = "❌ Error de conexión a la base de datos.";
            $tipoMensaje = "error";
        }
    } else {
        $mensaje = "Corrige los errores:<br>• " . implode("<br>• ", $errores);
        $tipoMensaje = "error";
    }
}

// --- Obtener tipos de comida ---
$tiposComida = [];
$pdo = conectarDB();
if ($pdo) {
    $stmt = $pdo->query("SELECT id, nombre FROM tipos_comida ORDER BY nombre ASC");
    $tiposComida = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> Reservas - <?php echo htmlspecialchars($site_name); ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
  <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="bg-gradient-to-br from-red-50 via-white to-red-100 font-sans min-h-screen flex flex-col">

  <!-- Navigation -->
  <nav class="fixed w-full bg-white bg-opacity-90 backdrop-blur-md shadow-md z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 sm:py-4">
      <div class="flex justify-between items-center">
        <a href="index.html" class="text-xl sm:text-2xl font-bold text-red-600 hover:text-red-700 transition">
          Mideliz
        </a>

        <!-- Desktop Menu -->
        <div class="hidden md:flex items-center gap-4">
          <ul class="flex space-x-6 lg:space-x-8 text-base lg:text-lg">
            <li><a href="index.php" class="nav-link">Inicio</a></li>
            <li><a href="menu.php" class="nav-link">Menú</a></li>
            <li><a href="about.php" class="nav-link">Sobre Nosotros</a></li>
            <li><a href="contact.php" class="nav-link">Contacto</a></li>
            <li class="relative">
              <button id="userMenuButton" class="focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                  stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-gray-700 hover:text-red-600 transition">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
              </button>
              <div id="userMenu" class="hidden absolute right-0 mt-4 w-48 bg-white border bg-opacity-95 border-gray-200 rounded-xl shadow-lg py-2 z-50">
                <a href="profile.php" class="nav-link block px-4 py-2">Editar perfil</a>
                <a href="logout.php" class="nav-link block px-4 py-2">Cerrar sesión</a>
              </div>
            </li>
          </ul>
        </div>

        <!-- Mobile Menu Button -->
        <button id="mobileMenuButton" class="md:hidden focus:outline-none">
          <svg id="hamburgerIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" 
            stroke-width="2" stroke="currentColor" class="w-7 h-7 text-gray-700">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
          </svg>
          <svg id="closeIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" 
            stroke-width="2" stroke="currentColor" class="w-7 h-7 text-gray-700 hidden">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Mobile Menu -->
      <div id="mobileMenu" class="md:hidden overflow-hidden transition-all duration-300 ease-in-out" style="max-height: 0;">
        <ul class="pt-4 pb-2 space-y-2">
          <li><a href="index.php" class="mobile-link block py-2 px-4 hover:bg-red-50 rounded transition">Inicio</a></li>
          <li><a href="menu.php" class="mobile-link block py-2 px-4 hover:bg-red-50 rounded transition">Menú</a></li>
          <li><a href="about.php" class="mobile-link block py-2 px-4 hover:bg-red-50 rounded transition">Sobre Nosotros</a></li>
          <li><a href="contact.php" class="mobile-link block py-2 px-4 hover:bg-red-50 rounded transition">Contacto</a></li>
          <li><a href="profile.php" class="mobile-link block py-2 px-4 hover:bg-red-50 rounded transition">Editar perfil</a></li>
          <li><a href="logout.php" class="mobile-link block py-2 px-4 text-red-600 hover:bg-red-50 rounded transition">Cerrar sesión</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Formulario -->
  <section class="flex-grow px-4 pt-24 sm:pt-28 sm:px-6 pb-12">
    <div class="reserva-card max-w-4xl mx-auto">
      <div class="bg-white rounded-2xl shadow-2xl p-6 sm:p-8 lg:p-10">
        
        <div class="text-center mb-6 sm:mb-8">
          <div class="inline-block p-3 bg-red-100 rounded-full mb-4">
            <i class="fas fa-calendar-check text-4xl sm:text-5xl text-red-600"></i>
          </div>
          <h2 class="text-2xl sm:text-3xl font-bold text-red-600 mb-2">Reserva tu Evento</h2>
          <p class="text-gray-600 text-sm sm:text-base">
            Completa el formulario y haremos realidad tu evento perfecto
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
              <i class="fas fa-user text-red-600 mr-2"></i>Nombre *
            </label>
            <input type="text" id="nombre" name="nombre"
                  value="<?php echo htmlspecialchars($nombre); ?>"
                  required
                  placeholder="Tu nombre"
                  class="w-full p-3 text-sm sm:text-base border-2 border-gray-300 rounded-lg focus:border-red-600 outline-none transition-all duration-300">
          </div>

          <!-- Apellido -->
          <div class="form-group">
            <label for="apellido" class="block text-sm font-semibold text-gray-700 mb-2">
              <i class="fas fa-user text-red-600 mr-2"></i>Apellido *
            </label>
            <input type="text" id="apellido" name="apellido"
                  value="<?php echo htmlspecialchars($apellido); ?>"
                  required
                  placeholder="Tu apellido"
                  class="w-full p-3 text-sm sm:text-base border-2 border-gray-300 rounded-lg focus:border-red-600 outline-none transition-all duration-300">
          </div>

          <!-- Ciudad y Correo -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="form-group">
              <label for="ciudad" class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-city text-red-600 mr-2"></i>Ciudad *
              </label>
              <input type="text" id="ciudad" name="ciudad"
                    value="<?php echo htmlspecialchars($ciudad); ?>"
                    required
                    placeholder="Tu ciudad"
                    class="w-full p-3 text-sm sm:text-base border-2 border-gray-300 rounded-lg focus:border-red-600 outline-none transition-all duration-300">
            </div>

            <div class="form-group">
              <label for="correo" class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-envelope text-red-600 mr-2"></i>Email *
              </label>
              <input type="email" id="correo" name="correo"
                    value="<?php echo htmlspecialchars($email); ?>"
                    required
                    placeholder="ejemplo@correo.com"
                    class="w-full p-3 text-sm sm:text-base border-2 border-gray-300 rounded-lg focus:border-red-600 outline-none transition-all duration-300">
            </div>
          </div>

          <!-- Teléfono y Fecha -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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

            <div class="form-group">
              <label for="fecha_evento" class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-calendar text-red-600 mr-2"></i>Fecha del Evento *
              </label>
              <input type="date" id="fecha_evento" name="fecha_evento"
                    value="<?php echo htmlspecialchars($fecha_evento); ?>"
                    min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>"
                    required
                    class="w-full p-3 text-sm sm:text-base border-2 border-gray-300 rounded-lg focus:border-red-600 outline-none transition-all duration-300">
            </div>
          </div>

          <!-- Invitados y Menú -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="form-group">
              <label for="num_invitados" class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-users text-red-600 mr-2"></i>Número de Invitados *
              </label>
              <input type="number" id="num_invitados" name="num_invitados"
                    value="<?php echo htmlspecialchars($num_invitados); ?>"
                    min="1" max="500" required
                    placeholder="1-500"
                    class="w-full p-3 text-sm sm:text-base border-2 border-gray-300 rounded-lg focus:border-red-600 outline-none transition-all duration-300">
            </div>

            <div class="form-group">
              <label for="tipo_comida" class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-utensils text-red-600 mr-2"></i>Menú *
              </label>
              <select id="tipo_comida" name="tipo_comida" required
                      class="w-full p-3 text-sm sm:text-base border-2 border-gray-300 rounded-lg focus:border-red-600 outline-none transition-all duration-300">
                  <option value="">Selecciona un menú...</option>
                  <?php foreach ($tiposComida as $tipo): ?>
                      <option value="<?php echo $tipo['id']; ?>" 
                          <?php echo (isset($tipo_comida_id) && $tipo_comida_id == $tipo['id']) ? 'selected' : ''; ?>>
                          <?php echo htmlspecialchars($tipo['nombre']); ?>
                      </option>
                  <?php endforeach; ?>
              </select>
            </div>
          </div>

          <!-- Comentarios -->
          <div class="form-group">
            <label for="comentarios" class="block text-sm font-semibold text-gray-700 mb-2">
              <i class="fas fa-comment-dots text-red-600 mr-2"></i>Comentarios Adicionales
            </label>
            <textarea id="comentarios" name="comentarios" rows="5"
                      placeholder="Cuéntanos más sobre tu evento..."
                      class="w-full p-3 text-sm sm:text-base border-2 border-gray-300 rounded-lg focus:border-red-600 outline-none transition-all duration-300 resize-none"><?php echo htmlspecialchars($comentarios); ?></textarea>
          </div>

          <!-- Botón -->
          <button type="submit" class="submit-btn w-full bg-red-600 hover:bg-red-700 text-white py-3 sm:py-3.5 rounded-lg font-semibold shadow-lg transition-all duration-300 hover:-translate-y-1 active:translate-y-0 text-sm sm:text-base flex items-center justify-center space-x-2">
            <span>Confirmar Reserva</span>
            <i class="fas fa-check-circle"></i>
          </button>
        </form>

      </div>
    </div>
  </section>

  <!-- Features -->
  <section class="max-w-6xl mx-auto px-4 sm:px-6 py-12 sm:py-16 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
    <div class="feature-card bg-white p-6 sm:p-8 rounded-2xl shadow-lg text-center transform hover:scale-105 transition-transform duration-300">
      <i class="fas fa-star text-4xl sm:text-5xl text-red-600 mb-4"></i>
      <h4 class="text-lg sm:text-xl font-semibold mb-2 text-gray-800">Calidad Premium</h4>
      <p class="text-sm sm:text-base text-gray-600">Los mejores ingredientes y chefs profesionales para tu evento especial.</p>
    </div>
    <div class="feature-card bg-white p-6 sm:p-8 rounded-2xl shadow-lg text-center transform hover:scale-105 transition-transform duration-300">
      <i class="fas fa-clock text-4xl sm:text-5xl text-red-600 mb-4"></i>
      <h4 class="text-lg sm:text-xl font-semibold mb-2 text-gray-800">Servicio Puntual</h4>
      <p class="text-sm sm:text-base text-gray-600">Garantizamos la puntualidad y organización perfecta de tu evento.</p>
    </div>
    <div class="feature-card bg-white p-6 sm:p-8 rounded-2xl shadow-lg text-center transform hover:scale-105 transition-transform duration-300 sm:col-span-2 lg:col-span-1">
      <i class="fas fa-heart text-4xl sm:text-5xl text-red-600 mb-4"></i>
      <h4 class="text-lg sm:text-xl font-semibold mb-2 text-gray-800">Atención Personalizada</h4>
      <p class="text-sm sm:text-base text-gray-600">Cada evento es único y adaptamos nuestro servicio a tus necesidades.</p>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-red-700 text-white py-6 sm:py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center">
      <p class="text-sm sm:text-base">© <?php echo date("Y"); ?> <?php echo htmlspecialchars($site_name); ?>. Todos los derechos reservados.</p>
      <div class="mt-3 sm:mt-4 flex justify-center space-x-4 sm:space-x-6 text-xl sm:text-2xl">
        <a href="#" class="hover:text-red-300 transition"><i class="fab fa-facebook-f"></i></a>
        <a href="#" class="hover:text-red-300 transition"><i class="fab fa-instagram"></i></a>
        <a href="#" class="hover:text-red-400 transition"><i class="fab fa-whatsapp"></i></a>
      </div>
    </div>
  </footer>

  <!-- GSAP Animations Script -->
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
      const userMenuButton = document.getElementById('userMenuButton');
      const userMenu = document.getElementById('userMenu');
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

      // Menú de usuario desktop
      if (userMenuButton && userMenu) {
        userMenuButton.addEventListener('click', (e) => {
          e.stopPropagation();
          userMenu.classList.toggle('hidden');
        });

        document.addEventListener('click', (e) => {
          if (!userMenu.classList.contains('hidden') && !userMenu.contains(e.target) && !userMenuButton.contains(e.target)) {
            userMenu.classList.add('hidden');
          }
        });
      }

      // Hero
      gsap.from(".hero-title", {
        opacity: 0,
        y: isMobile ? -30 : -50,
        duration: config.duration,
        ease: config.ease
      });

      gsap.from(".hero-subtitle", {
        opacity: 0,
        y: isMobile ? 30 : 50,
        duration: config.duration,
        delay: 0.3,
        ease: config.ease
      });

      // Animación Reserva Card
      gsap.from(".reserva-card", {
        opacity: 0,
        y: isMobile ? 30 : 50,
        scale: 0.95,
        duration: config.duration,
        delay: 0.3,
        ease: "back.out(1.2)"
      });

      // Animación elementos del formulario
      gsap.from(".form-group", {
        opacity: 0,
        x: isMobile ? 0 : -30,
        duration: config.duration * 0.8,
        stagger: 0.08,
        delay: 0.5,
        ease: config.ease
      });

      // Animación mensaje de alerta si existe
      const mensaje = document.querySelector('.mensaje-alerta');
      if (mensaje) {
        gsap.from(mensaje, {
          opacity: 0,
          y: -20,
          duration: 0.5,
          delay: 0.4,
          ease: "power2.out"
        });
      }

      // Features con ScrollTrigger corregido
      gsap.utils.toArray(".feature-card").forEach((card, i) => {
        gsap.from(card, {
          scrollTrigger: {
            trigger: card,
            start: "top 80%",
            toggleActions: "play reverse play reverse"
          },
          opacity: 0,
          y: isMobile ? 30 : 50,
          duration: config.duration,
          delay: i * 0.15,
          ease: config.ease
        });
      });

      // Footer con ScrollTrigger corregido
      gsap.from("footer", {
        scrollTrigger: {
          trigger: "footer",
          start: "top 90%",
          toggleActions: "play none none none"
        },
        opacity: 0,
        y: isMobile ? 30 : 50,
        duration: config.duration,
        ease: config.ease
      });

     

      // Validación visual del número de invitados
      const numInvitados = document.getElementById('num_invitados');
      if (numInvitados) {
        numInvitados.addEventListener('input', () => {
          const val = parseInt(numInvitados.value);
          if (val < 1 || val > 500) {
            numInvitados.style.borderColor = '#ef4444';
          } else {
            numInvitados.style.borderColor = '#22c55e';
          }
        });
      }

      // Validación visual de la fecha
      const fechaEvento = document.getElementById('fecha_evento');
      if (fechaEvento) {
        fechaEvento.addEventListener('change', () => {
          const fecha = new Date(fechaEvento.value);
          const hoy = new Date();
          hoy.setHours(0, 0, 0, 0);
          
          if (fecha < hoy) {
            fechaEvento.style.borderColor = '#ef4444';
          } else {
            fechaEvento.style.borderColor = '#22c55e';
          }
        });
      }
    });
  </script>
</body>
</html>