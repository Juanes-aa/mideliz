<?php
require_once __DIR__ . '/../config/config.php';

// Obtener el nombre del administrador desde la sesión
$adminNombre = $_SESSION['admin_nombre'] ?? 'Administrador';

// Parámetros
$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$message = '';

// Procesar formularios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!check_csrf($_POST['csrf'] ?? '')) {
    die('Token CSRF inválido.');
  }

  // Crear contacto
  if ($_POST['do'] === 'create') {
    $nombre   = trim($_POST['nombre']);
    $email    = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $asunto   = trim($_POST['asunto']);
    $mensaje  = trim($_POST['mensaje']);
    $estado   = $_POST['estado'] ?? 'pendiente';

    if ($nombre && $email && $asunto && $mensaje) {
      $stmt = $pdo->prepare("
        INSERT INTO mensajes_contacto (nombre, email, telefono, asunto, mensaje, estado, fecha_envio)
        VALUES (?, ?, ?, ?, ?, ?, NOW())
      ");
      try {
        $stmt->execute([$nombre, $email, $telefono, $asunto, $mensaje, $estado]);
        $message = "✅ Mensaje registrado correctamente.";
        $action = 'list';
      } catch (PDOException $e) {
        $message = "⚠️ Error al guardar mensaje: " . e($e->getMessage());
      }
    } else {
      $message = "⚠️ Todos los campos obligatorios deben completarse.";
    }
  }

  // Actualizar contacto
  if ($_POST['do'] === 'update' && !empty($_POST['id'])) {
    $cid = (int)$_POST['id'];
    $nombre   = trim($_POST['nombre']);
    $email    = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $asunto   = trim($_POST['asunto']);
    $mensaje  = trim($_POST['mensaje']);
    $estado   = $_POST['estado'] ?? 'pendiente';

    if ($nombre && $email && $asunto && $mensaje) {
      $stmt = $pdo->prepare("
        UPDATE mensajes_contacto
        SET nombre=?, email=?, telefono=?, asunto=?, mensaje=?, estado=?
        WHERE id=?
      ");
      $stmt->execute([$nombre, $email, $telefono, $asunto, $mensaje, $estado, $cid]);
      $message = "✅ Mensaje actualizado correctamente.";
      $action = 'list';
    } else {
      $message = "⚠️ Campos obligatorios faltantes.";
    }
  }

  // Eliminar contacto
  if ($_POST['do'] === 'delete' && !empty($_POST['id'])) {
    $cid = (int)$_POST['id'];
    $pdo->prepare("DELETE FROM mensajes_contacto WHERE id = ?")->execute([$cid]);
    $message = "🗑️ Mensaje eliminado.";
    $action = 'list';
  }
}

// Datos si se edita
$editingContacto = null;
if ($action === 'edit' && $id) {
  $stmt = $pdo->prepare("SELECT * FROM mensajes_contacto WHERE id=?");
  $stmt->execute([$id]);
  $editingContacto = $stmt->fetch();
  if (!$editingContacto) {
    $message = "Mensaje no encontrado.";
    $action = 'list';
  }
}

// Listar contactos
$contactos = [];
if ($action === 'list') {
  $contactos = $pdo->query("SELECT * FROM mensajes_contacto ORDER BY id DESC")->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin | Contactos</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://kit.fontawesome.com/a2e0e6c6c8.js" crossorigin="anonymous"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Poppins', sans-serif; }
  </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-red-50 via-white to-red-100 text-gray-800">

    <!-- NAV BAR -->
  <nav class="bg-red-600 shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 h-16 flex justify-between items-center">
      <div class="flex items-center text-white font-semibold text-lg">
        <i class="fas fa-user mr-2"></i>
        Bienvenido, <?= htmlspecialchars($adminNombre) ?>
      </div>
      <div class="flex space-x-6 text-sm">
        <a href="index.php" class="text-white hover:text-red-200 transition">Inicio</a>
        <a href="users.php" class="text-white hover:text-red-200 transition">Usuarios</a>
        <a href="dashboard.php" class="text-white hover:text-red-200 transition">Reservas</a>
        <a href="logout.php" class="text-white hover:text-red-200 transition">Cerrar Sesión</a>
      </div>
    </div>
  </nav>


  <!-- CONTENIDO -->
  <main class="max-w-6xl mx-auto p-8">

    <!-- Encabezado -->
    <div class="flex items-center justify-between mb-8">
      <h1 class="text-3xl font-bold text-red-600 flex items-center gap-2">
        <i class="fas fa-envelope-open-text"></i> Administrar Mensajes de Contacto
      </h1>
      <div class="space-x-3">
        <a href="?action=list"
           class="px-4 py-2 bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 rounded-lg text-sm font-medium transition">
          <i class="fas fa-list"></i> Lista
        </a>
        <a href="?action=create"
           class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition shadow-md">
          <i class="fas fa-plus"></i> Nuevo Mensaje
        </a>
      </div>
    </div>

    <!-- Mensaje -->
    <?php if ($message): ?>
      <div class="p-3 mb-6 rounded-lg text-sm font-medium shadow-md 
        <?= str_contains($message, '✅') 
          ? 'bg-green-100 text-green-700 border border-green-300' 
          : 'bg-yellow-100 border-yellow-300' ?>">
        <?= e($message) ?>
      </div>
    <?php endif; ?>

    <!-- LISTA DE MENSAJES -->
    <?php if ($action === 'list'): ?>
      <div class="overflow-x-auto bg-white/90 border border-red-100 shadow-lg rounded-2xl hover:shadow-xl transition">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
          <thead class="bg-red-600 text-white uppercase text-xs tracking-wider">
            <tr>
              <th class="px-4 py-3 text-left">ID</th>
              <th class="px-4 py-3 text-left">Nombre</th>
              <th class="px-4 py-3 text-left">Email</th>
              <th class="px-4 py-3 text-left">Teléfono</th>
              <th class="px-4 py-3 text-left">Asunto</th>
              <th class="px-4 py-3 text-left">Estado</th>
              <th class="px-4 py-3 text-left">Fecha</th>
              <th class="px-4 py-3 text-center">Acciones</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <?php foreach ($contactos as $c): ?>
              <tr class="hover:bg-red-50 transition">
                <td class="px-4 py-3"><?= e($c['id']) ?></td>
                <td class="px-4 py-3 font-medium"><?= e($c['nombre']) ?></td>
                <td class="px-4 py-3"><?= e($c['email']) ?></td>
                <td class="px-4 py-3"><?= e($c['telefono']) ?></td>
                <td class="px-4 py-3"><?= e($c['asunto']) ?></td>
                <td class="px-4 py-3">
                  <span class="px-2 py-1 rounded text-xs font-medium 
                    <?= $c['estado'] === 'respondido'
                      ? 'bg-green-100 text-green-700'
                      : 'bg-yellow-100 text-yellow-700' ?>">
                    <?= e(ucfirst($c['estado'])) ?>
                  </span>
                </td>
                <td class="px-4 py-3 text-xs text-gray-500"><?= e($c['fecha_envio']) ?></td>
                <td class="px-4 py-2 text-center">
                  <div class="flex justify-center gap-2">
                    <a href="?action=edit&id=<?= e($c['id']) ?>"
                       class="inline-flex items-center gap-1 px-3 py-1.5 bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 rounded-lg text-sm">
                      <i class="fas fa-edit"></i> Editar
                    </a>
                    <form class="inline" method="post" onsubmit="return confirm('¿Eliminar este mensaje?');">
                      <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
                      <input type="hidden" name="do" value="delete">
                      <input type="hidden" name="id" value="<?= e($c['id']) ?>">
                      <button type="submit"
                              class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm shadow-md">
                        <i class="fas fa-trash-alt"></i> Eliminar
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if (empty($contactos)): ?>
              <tr>
                <td colspan="8" class="px-4 py-6 text-center text-gray-500">
                  No hay mensajes registrados.
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

    <!-- CREAR / EDITAR -->
    <?php elseif ($action === 'create' || $action === 'edit'):
      $isEdit = ($action === 'edit' && $editingContacto);
    ?>
      <div class="bg-white/90 border border-red-100 rounded-2xl shadow-lg p-8 hover:shadow-xl transition">
        <h2 class="text-2xl font-semibold text-red-600 mb-6">
          <?= $isEdit ? 'Editar Mensaje' : 'Registrar Nuevo Mensaje' ?>
        </h2>

        <form method="post" class="space-y-5">
          <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
          <input type="hidden" name="do" value="<?= $isEdit ? 'update' : 'create' ?>">
          <?php if ($isEdit): ?>
            <input type="hidden" name="id" value="<?= e($editingContacto['id']) ?>">
          <?php endif; ?>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
              <label class="block text-sm font-semibold text-gray-700">Nombre</label>
              <input type="text" name="nombre" value="<?= e($editingContacto['nombre'] ?? '') ?>" required
                     class="w-full border border-gray-300 rounded-lg p-2 focus:border-red-600 focus:ring-red-600 outline-none">
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700">Email</label>
              <input type="email" name="email" value="<?= e($editingContacto['email'] ?? '') ?>" required
                     class="w-full border border-gray-300 rounded-lg p-2 focus:border-red-600 focus:ring-red-600 outline-none">
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
              <label class="block text-sm font-semibold text-gray-700">Teléfono</label>
              <input type="text" name="telefono" value="<?= e($editingContacto['telefono'] ?? '') ?>"
                     class="w-full border border-gray-300 rounded-lg p-2 focus:border-red-600 focus:ring-red-600 outline-none">
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700">Asunto</label>
              <input type="text" name="asunto" value="<?= e($editingContacto['asunto'] ?? '') ?>" required
                     class="w-full border border-gray-300 rounded-lg p-2 focus:border-red-600 focus:ring-red-600 outline-none">
            </div>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700">Mensaje</label>
            <textarea name="mensaje" rows="5" required
                      class="w-full border border-gray-300 rounded-lg p-2 focus:border-red-600 focus:ring-red-600 outline-none"><?= e($editingContacto['mensaje'] ?? '') ?></textarea>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700">Estado</label>
            <select name="estado"
                    class="w-full border border-gray-300 rounded-lg p-2 focus:border-red-600 focus:ring-red-600 outline-none">
              <option value="pendiente" <?= (isset($editingContacto['estado']) && $editingContacto['estado'] === 'pendiente') ? 'selected' : '' ?>>Pendiente</option>
              <option value="respondido" <?= (isset($editingContacto['estado']) && $editingContacto['estado'] === 'respondido') ? 'selected' : '' ?>>Respondido</option>
            </select>
          </div>

          <div class="flex justify-end space-x-3 pt-4">
            <a href="?action=list"
               class="px-4 py-2 bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 rounded-lg text-sm font-medium">
              <i class="fas fa-arrow-left"></i> Volver
            </a>
            <button type="submit"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium shadow-md">
              <?= $isEdit ? 'Actualizar' : 'Guardar' ?>
            </button>
          </div>
        </form>
      </div>
    <?php endif; ?>
  </main>
</body>
</html>
