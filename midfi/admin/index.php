<?php
/**
 * Dashboard principal del panel de administración
 */

session_start();
require_once __DIR__ . '/../config/config.php';

// Verificar sesión
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Nombre del admin
$adminNombre = $_SESSION['admin_nombre'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Administración</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Poppins', sans-serif; }
  </style>
</head>
<body class="bg-gradient-to-br from-red-50 via-white to-red-100 min-h-screen">

  <!-- ======= NAV ======= -->
  <nav class="bg-red-600 shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 h-16 flex justify-between items-center">
      <div class="flex items-center text-white font-semibold text-lg">
        <i class="mr-2"></i>
        Bienvenido, <?= htmlspecialchars($adminNombre) ?>
      </div>
      <div class="flex space-x-6 text-sm">
        <a href="logout.php" class="text-white hover:text-red-200 transition">Cerrar Sesión</a>
      </div>
    </div>
  </nav>

  <!-- ======= CONTENIDO ======= -->
  <div class="max-w-6xl mx-auto px-6 py-12">
    <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">
      Panel de Administración
    </h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

      <!-- Carta Usuarios -->
      <div class="bg-white shadow-lg rounded-2xl p-8 text-center hover:shadow-2xl transition">
        <i class="fas fa-users text-red-600 text-5xl mb-4"></i>
        <h2 class="text-xl font-semibold text-gray-800 mb-2">Administrar Usuarios</h2>
        <p class="text-gray-600 mb-4">Gestiona los administradores o clientes del sistema.</p>
        <a href="users.php" class="inline-block bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-full transition">
          Ir a Usuarios
        </a>
      </div>

      <!-- Carta Reservas -->
      <div class="bg-white shadow-lg rounded-2xl p-8 text-center hover:shadow-2xl transition">
        <i class="fas fa-calendar-alt text-red-600 text-5xl mb-4"></i>
        <h2 class="text-xl font-semibold text-gray-800 mb-2">Administrar Reservas</h2>
        <p class="text-gray-600 mb-4">Visualiza y administra los eventos y reservas.</p>
        <a href="dashboard.php" class="inline-block bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-full transition">
          Ir a Reservas
        </a>
      </div>

      <!-- Carta Mensajes -->
      <div class="bg-white shadow-lg rounded-2xl p-8 text-center hover:shadow-2xl transition">
        <i class="fas fa-envelope text-red-600 text-5xl mb-4"></i>
        <h2 class="text-xl font-semibold text-gray-800 mb-2">Mensajes Recibidos</h2>
        <p class="text-gray-600 mb-4">Consulta y responde los mensajes enviados por los usuarios.</p>
        <a href="contacts.php" class="inline-block bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-full transition">
          Ver Mensajes
        </a>
      </div>

    </div>
  </div>

</body>
</html>
