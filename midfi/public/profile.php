<?php
session_start();
require_once __DIR__ . '/../config/config.php';

// --- Verificar sesión ---
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login_user.php');
    exit;
}

$site_name = "Mideliz";
$title = "Mi Perfil";
$usuario_id = intval($_SESSION['usuario_id']);
$pdo = conectarDB();

// --- CSRF token ---
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

$mensaje = '';
$tipoMensaje = '';

// --- Obtener datos actuales ---
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = :id LIMIT 1");
$stmt->execute([':id' => $usuario_id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    session_destroy();
    header('Location: login_user.php');
    exit;
}

// --- Procesar formulario ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $mensaje = '⚠️ Token de seguridad inválido.';
        $tipoMensaje = 'error';
    } else {
        $nombre = sanitizar($_POST['nombre']);
        $apellido = sanitizar($_POST['apellido']);
        $telefono = sanitizar($_POST['telefono']);
        $pass_actual = $_POST['password_actual'] ?? '';
        $pass_nueva = $_POST['password_nueva'] ?? '';
        $pass_confirm = $_POST['password_confirm'] ?? '';

        if ($nombre === '' || $apellido === '') {
            $mensaje = '⚠️ Nombre y apellido son obligatorios.';
            $tipoMensaje = 'error';
        } else {
            // ✅ Actualización sin campo "ciudad"
            $update = $pdo->prepare("
                UPDATE usuarios 
                SET nombre = :nombre, apellido = :apellido, telefono = :telefono
                WHERE id = :id
            ");
            $update->execute([
                ':nombre' => $nombre,
                ':apellido' => $apellido,
                ':telefono' => $telefono,
                ':id' => $usuario_id
            ]);

            // --- Cambio de contraseña ---
            if ($pass_actual || $pass_nueva || $pass_confirm) {
                if (!password_verify($pass_actual, $usuario['password'])) {
                    $mensaje = '⚠️ La contraseña actual no es correcta.';
                    $tipoMensaje = 'error';
                } elseif ($pass_nueva !== $pass_confirm) {
                    $mensaje = '⚠️ Las contraseñas nuevas no coinciden.';
                    $tipoMensaje = 'error';
                } elseif (strlen($pass_nueva) < 6) {
                    $mensaje = '⚠️ La nueva contraseña debe tener al menos 6 caracteres.';
                    $tipoMensaje = 'error';
                } else {
                    $new_hash = password_hash($pass_nueva, PASSWORD_DEFAULT);
                    $pdo->prepare("UPDATE usuarios SET password = :p WHERE id = :id")
                        ->execute([':p' => $new_hash, ':id' => $usuario_id]);
                    $mensaje = '✅ Contraseña actualizada correctamente.';
                    $tipoMensaje = 'success';
                }
            } else {
                if (!$mensaje) {
                    $mensaje = '✅ Perfil actualizado correctamente.';
                    $tipoMensaje = 'success';
                }
            }

            // --- Actualizar datos del usuario en memoria ---
            $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = :id LIMIT 1");
            $stmt->execute([':id' => $usuario_id]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        }
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
        <li><a href="index.php#inicio" class="nav-link">Inicio</a></li>
        <li><a href="menu.php" class="nav-link">Menú</a></li>
        <li><a href="about.php" class="nav-link">Sobre Nosotros</a></li>
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
        <li><a href="index.php" class="block py-2 text-gray-700 hover:text-red-600 transition mobile-link">Inicio</a></li>
        <li><a href="menu.php" class="block py-2 text-gray-700 hover:text-red-600 transition mobile-link">Menú</a></li>
        <li><a href="about.php" class="block py-2 text-gray-700 hover:text-red-600 transition mobile-link">Sobre Nosotros</a></li>
        <li><a href="contact.php" class="block py-2 text-gray-700 hover:text-red-600 transition mobile-link">Contacto</a></li>
        <li><a href="profile.php" class="block py-2 text-gray-700 hover:text-red-600 transition mobile-link">Editar perfil</a></li>
        <li><a href="logout.php" class="block py-2 text-red-600 hover:text-red-700 transition font-semibold mobile-link">Cerrar sesión</a></li>
      </ul>
    </div>
  </nav>

  <!-- Profile Section -->
  <main class="flex-1 flex items-start justify-center px-4 sm:px-6 pt-24 sm:pt-28 pb-8">
    <div class="w-full max-w-7xl">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Card Izquierda - Info Usuario -->
        <div class="profile-card-left lg:col-span-1 bg-white rounded-2xl shadow-2xl p-6 sm:p-8">
          <div class="flex flex-col items-center">
            <div class="w-24 h-24 sm:w-28 sm:h-28 bg-gradient-to-br from-red-500 to-red-700 rounded-full flex items-center justify-center mb-4 shadow-lg">
              <i class="fas fa-user text-white text-4xl sm:text-5xl"></i>
            </div>
            <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-1">
              <?php echo htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']); ?>
            </h2>
            <p class="text-sm text-gray-500 mb-6">Miembro de <?php echo htmlspecialchars($site_name); ?></p>
          </div>

          <div class="space-y-3 text-sm sm:text-base">
            <div class="info-item bg-gray-50 rounded-lg p-3 sm:p-4 shadow-sm">
              <div class="flex items-center space-x-3">
                <i class="fas fa-user text-red-600 text-lg"></i>
                <div>
                  <p class="text-xs text-gray-500">Nombre</p>
                  <p class="font-semibold text-gray-800"><?php echo htmlspecialchars($usuario['nombre']); ?></p>
                </div>
              </div>
            </div>

            <div class="info-item bg-gray-50 rounded-lg p-3 sm:p-4 shadow-sm">
              <div class="flex items-center space-x-3">
                <i class="fas fa-user text-red-600 text-lg"></i>
                <div>
                  <p class="text-xs text-gray-500">Apellido</p>
                  <p class="font-semibold text-gray-800"><?php echo htmlspecialchars($usuario['apellido']); ?></p>
                </div>
              </div>
            </div>

            <div class="info-item bg-gray-50 rounded-lg p-3 sm:p-4 shadow-sm">
              <div class="flex items-center space-x-3">
                <i class="fas fa-phone text-red-600 text-lg"></i>
                <div>
                  <p class="text-xs text-gray-500">Teléfono</p>
                  <p class="font-semibold text-gray-800"><?php echo htmlspecialchars($usuario['telefono'] ?: 'No registrado'); ?></p>
                </div>
              </div>
            </div>

            <div class="info-item bg-gray-50 rounded-lg p-3 sm:p-4 shadow-sm">
              <div class="flex items-center space-x-3">
                <i class="fas fa-envelope text-red-600 text-lg"></i>
                <div>
                  <p class="text-xs text-gray-500">Correo</p>
                  <p class="font-semibold text-gray-800 break-all"><?php echo htmlspecialchars($usuario['email']); ?></p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Card Derecha - Formulario -->
        <div class="profile-card-right lg:col-span-2 bg-white rounded-2xl shadow-2xl p-6 sm:p-8 lg:p-10">
          <h1 class="text-2xl sm:text-3xl font-bold text-red-600 mb-6">Editar Perfil</h1>

          <?php if ($mensaje): ?>
            <div class="mensaje-alerta <?php echo $tipoMensaje === 'error' ? 'bg-red-100 border-red-400 text-red-600' : 'bg-green-100 text-green-600'; ?> border px-3 sm:px-4 py-2 sm:py-3 rounded-lg mb-6 text-center text-sm sm:text-base">
              <?php echo htmlspecialchars($mensaje); ?>
            </div>
          <?php endif; ?>

          <form method="POST" action="" class="space-y-6">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

            <!-- Sección: Información Personal -->
            <div>
              <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-id-card text-red-600 mr-2"></i>
                Información Personal
              </h3>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-group">
                  <label for="nombre" class="block text-sm font-semibold text-gray-700 mb-2">
                    Nombre *
                  </label>
                  <input type="text" 
                         id="nombre"
                         name="nombre" 
                         value="<?php echo htmlspecialchars($usuario['nombre']); ?>" 
                         required
                         class="w-full p-3 text-sm sm:text-base border-2 border-gray-300 rounded-lg focus:border-red-600 outline-none transition-all duration-300">
                </div>

                <div class="form-group">
                  <label for="apellido" class="block text-sm font-semibold text-gray-700 mb-2">
                    Apellido *
                  </label>
                  <input type="text" 
                         id="apellido"
                         name="apellido" 
                         value="<?php echo htmlspecialchars($usuario['apellido']); ?>" 
                         required
                         class="w-full p-3 text-sm sm:text-base border-2 border-gray-300 rounded-lg focus:border-red-600 outline-none transition-all duration-300">
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div class="form-group">
                  <label for="telefono" class="block text-sm font-semibold text-gray-700 mb-2">
                    Teléfono
                  </label>
                  <input type="tel" 
                         id="telefono"
                         name="telefono" 
                         value="<?php echo htmlspecialchars($usuario['telefono']); ?>" 
                         placeholder="+57 300 123 4567"
                         class="w-full p-3 text-sm sm:text-base border-2 border-gray-300 rounded-lg focus:border-red-600 outline-none transition-all duration-300">
                </div>

                <div class="form-group">
                  <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                    Correo electrónico
                  </label>
                  <input type="email" 
                         id="email"
                         value="<?php echo htmlspecialchars($usuario['email']); ?>" 
                         disabled
                         class="w-full p-3 text-sm sm:text-base border-2 border-gray-200 bg-gray-100 text-gray-500 rounded-lg cursor-not-allowed">
                </div>
              </div>
            </div>

            <hr class="border-gray-300">

            <!-- Sección: Cambiar Contraseña -->
            <div>
              <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-lock text-red-600 mr-2"></i>
                Cambiar Contraseña
              </h3>

              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="form-group">
                  <label for="password_actual" class="block text-sm font-semibold text-gray-700 mb-2">
                    Contraseña actual
                  </label>
                  <input type="password" 
                         id="password_actual"
                         name="password_actual" 
                         placeholder="••••••••"
                         class="w-full p-3 text-sm sm:text-base border-2 border-gray-300 rounded-lg focus:border-red-600 outline-none transition-all duration-300">
                </div>

                <div class="form-group">
                  <label for="password_nueva" class="block text-sm font-semibold text-gray-700 mb-2">
                    Nueva contraseña
                  </label>
                  <input type="password" 
                         id="password_nueva"
                         name="password_nueva" 
                         placeholder="Mínimo 6 caracteres"
                         class="w-full p-3 text-sm sm:text-base border-2 border-gray-300 rounded-lg focus:border-red-600 outline-none transition-all duration-300">
                </div>

                <div class="form-group">
                  <label for="password_confirm" class="block text-sm font-semibold text-gray-700 mb-2">
                    Confirmar nueva
                  </label>
                  <input type="password" 
                         id="password_confirm"
                         name="password_confirm" 
                         placeholder="Repite la contraseña"
                         class="w-full p-3 text-sm sm:text-base border-2 border-gray-300 rounded-lg focus:border-red-600 outline-none transition-all duration-300">
                </div>
              </div>

              <p class="text-xs sm:text-sm text-gray-500 mt-2">
                <i class="fas fa-info-circle text-red-600 mr-1"></i>
                Deja estos campos vacíos si no deseas cambiar tu contraseña
              </p>
            </div>

            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-4">
              <a href="index.php" class="text-red-600 hover:text-red-700 font-semibold transition flex items-center space-x-2">
                <i class="fas fa-arrow-left"></i>
                <span>Volver al Inicio</span>
              </a>
              <button type="submit" 
                      class="submit-btn w-full sm:w-auto bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-semibold shadow-lg transition-all duration-300 hover:-translate-y-1 text-sm sm:text-base flex items-center justify-center space-x-2">
                <i class="fas fa-save"></i>
                <span>Guardar Cambios</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="bg-red-700 text-white py-6 sm:py-8 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center">
      <p class="text-sm sm:text-base">© <?php echo date("Y"); ?> <?php echo htmlspecialchars($site_name); ?>. Todos los derechos reservados.</p>
      <div class="mt-3 sm:mt-4 flex justify-center space-x-4 sm:space-x-6 text-xl sm:text-2xl">
        <a href="#" class="hover:text-red-300 transition"><i class="fab fa-facebook-f"></i></a>
        <a href="#" class="hover:text-red-300 transition"><i class="fab fa-instagram"></i></a>
        <a href="#" class="hover:text-red-400 transition"><i class="fab fa-whatsapp"></i></a>
      </div>
    </div>
  </footer>

  <script>
  window.addEventListener("load", () => {
    gsap.registerPlugin(ScrollTrigger);

    const isMobile = window.innerWidth < 768;

    const animConfig = {
      mobile: { duration: 0.8, ease: "power2.out" },
      desktop: { duration: 1, ease: "power4.out" }
    };

    const config = isMobile ? animConfig.mobile : animConfig.desktop;

    // Navbar
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

      document.querySelectorAll('.mobile-link').forEach(link => {
        link.addEventListener('click', () => {
          mobileMenu.style.maxHeight = '0';
          hamburgerIcon.classList.remove('hidden');
          closeIcon.classList.add('hidden');
          menuOpen = false;
        });
      });

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

    // Animaciones Profile Cards
    gsap.from(".profile-card-left", {
      opacity: 0,
      x: isMobile ? 0 : -50,
      y: isMobile ? 30 : 0,
      duration: config.duration,
      delay: 0.3,
      ease: config.ease
    });

    gsap.from(".profile-card-right", {
      opacity: 0,
      x: isMobile ? 0 : 50,
      y: isMobile ? 30 : 0,
      duration: config.duration,
      delay: 0.4,
      ease: config.ease
    });

    // Animación info items
    gsap.from(".info-item", {
      opacity: 0,
      y: 20,
      duration: config.duration * 0.6,
      stagger: 0.1,
      delay: 0.6,
      ease: config.ease
    });

    // Animación form groups
    gsap.from(".form-group", {
      opacity: 0,
      y: 20,
      duration: config.duration * 0.6,
      stagger: 0.05,
      delay: 0.7,
      ease: config.ease
    });

    // Mensaje alerta
    const mensaje = document.querySelector('.mensaje-alerta');
    if (mensaje) {
      gsap.from(mensaje, {
        opacity: 0,
        y: -20,
        duration: 0.5,
        delay: 0.5,
        ease: "power2.out"
      });
    }

    // Footer
    gsap.from("footer", {
      y: isMobile ? 30 : 50,
      opacity: 0,
      duration: config.duration,
      delay: 1.2,
      ease: config.ease
    });

    // Hover effects (solo desktop)
    if (!isMobile) {
      document.querySelectorAll('input:not([disabled])').forEach(input => {
        input.addEventListener('focus', () => {
          gsap.to(input, { scale: 1.02, duration: 0.3, ease: "power2.out" });
        });
        input.addEventListener('blur', () => {
          gsap.to(input, { scale: 1, duration: 0.3, ease: "power2.out" });
        });
      });
    }

    // Validación contraseñas
    const passNueva = document.getElementById('password_nueva');
    const passConfirm = document.getElementById('password_confirm');

    if (passConfirm && passNueva) {
      passConfirm.addEventListener('input', () => {
        if (passNueva.value && passNueva.value !== passConfirm.value) {
          passConfirm.style.borderColor = '#ef4444';
        } else if (passNueva.value === passConfirm.value && passConfirm.value !== '') {
          passConfirm.style.borderColor = '#22c55e';
        } else {
          passConfirm.style.borderColor = '#d1d5db';
        }
      });
    }
  });
  </script>
</body>
</html>
