<?php

// --- Configuración de la base de datos ---
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); 
define('DB_NAME', 'reservas_eventos');

date_default_timezone_set('America/Mexico_City');

// Iniciar sesión PHP si no está iniciada 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/** 
 * @return PDO
 */

function conectarDB() {
    static $pdo = null; 
    if ($pdo) return $pdo;

    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $opciones = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $opciones);
        return $pdo;
    } catch (PDOException $e) {
        die("❌ Error al conectar con la base de datos: " . $e->getMessage());
    }
}

$pdo = conectarDB();


// Sanitiza
function sanitizar($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}


// Evita errores si no hay datos
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}


// Valida un email
function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}


// Seguridad de la contraseña
function csrf_token() {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}


// Validar contraseña
function check_csrf($token) {
    return isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token);
}


// Verifica si el admin está logueado
function ensure_admin() {
    if (empty($_SESSION['admin_id'])) {
        header('Location: /admin/login_admin.php');
        exit;
    }
}


// Mostrar un mensaje simple
function mostrarMensaje($mensaje, $tipo = 'info') {
    $colores = [
        'success' => 'bg-green-100 text-green-700',
        'error'   => 'bg-red-100 text-red-700',
        'warning' => 'bg-yellow-100 text-yellow-800',
        'info'    => 'bg-blue-100 text-blue-700',
    ];
    $color = $colores[$tipo] ?? $colores['info'];

    echo "<div class='p-3 mb-4 rounded-lg {$color}'>{$mensaje}</div>";
}

?>
