<?php
// Application Router

define('BASE_PATH', __DIR__);

require_once BASE_PATH . '/src/helpers/db.php';
require_once BASE_PATH . '/src/helpers/functions.php';

session_start_safe();

// Transfer flashed old input and errors once
$_view_errors  = validation_errors();
$_view_success = get_flash('success');
$_view_error   = get_flash('error');
$_view_status  = get_flash('status');

// --- Simple Router ---
$request_uri    = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$request_method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

// Support _method override for PUT/DELETE from forms
$method_override = strtoupper($_POST['_method'] ?? '');
if (in_array($method_override, ['PUT', 'DELETE', 'PATCH']) && $request_method === 'POST') {
    $request_method = $method_override;
}

// Strip base subdirectory if needed
$base_path_prefix = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/');
if ($base_path_prefix && str_starts_with($request_uri, $base_path_prefix)) {
    $request_uri = substr($request_uri, strlen($base_path_prefix));
}
$request_uri = '/' . ltrim($request_uri, '/');

// ----- Route Matching -----
function match_route(string $pattern, string $uri): array|false {
    $regex = preg_replace('/\{([^}]+)\}/', '([^/]+)', $pattern);
    $regex = '#^' . $regex . '$#';
    if (preg_match($regex, $uri, $matches)) {
        array_shift($matches);
        return $matches;
    }
    return false;
}

global $routes_matched;
$routes_matched = false;

function route(string $method, string $pattern, callable $handler, bool $auth = false, bool $guest = false): void {
    global $request_method, $request_uri, $routes_matched;
    if ($routes_matched) return;

    $params = match_route($pattern, $request_uri);
    if ($params !== false && strtoupper($method) === $request_method) {
        $routes_matched = true;
        if ($auth) require_auth();
        if ($guest) require_guest();
        call_user_func_array($handler, $params);
        exit;
    }
}

// Pages / Controllers
require_once BASE_PATH . '/src/pages/siswa/index.php';
require_once BASE_PATH . '/src/pages/siswa/store.php';
require_once BASE_PATH . '/src/pages/siswa/history.php';
require_once BASE_PATH . '/src/pages/siswa/login.php';
require_once BASE_PATH . '/src/pages/siswa/logout.php';

require_once BASE_PATH . '/src/pages/auth/login.php';
require_once BASE_PATH . '/src/pages/auth/logout.php';
require_once BASE_PATH . '/src/pages/auth/register.php';
require_once BASE_PATH . '/src/pages/auth/profile.php';

require_once BASE_PATH . '/src/pages/admin/dashboard.php';
require_once BASE_PATH . '/src/pages/admin/action.php';
require_once BASE_PATH . '/src/pages/admin/users.php';
require_once BASE_PATH . '/src/pages/admin/siswas.php';
require_once BASE_PATH . '/src/pages/admin/export_pdf.php';
require_once BASE_PATH . '/src/pages/admin/kategori/index.php';
require_once BASE_PATH . '/src/pages/admin/kategori/create.php';
require_once BASE_PATH . '/src/pages/admin/kategori/store.php';
require_once BASE_PATH . '/src/pages/admin/kategori/edit.php';
require_once BASE_PATH . '/src/pages/admin/kategori/update.php';
require_once BASE_PATH . '/src/pages/admin/kategori/destroy.php';

// 404 handler
if (!$routes_matched) {
    http_response_code(404);
    echo '<!DOCTYPE html><html><head><title>404 Not Found</title><style>body{font-family:sans-serif;display:flex;align-items:center;justify-content:center;min-height:100vh;background:#f1f5f9;}.box{text-align:center;padding:3rem;border-radius:1rem;background:white;box-shadow:0 4px 24px rgba(0,0,0,0.08);}h1{color:#0F52BA;font-size:4rem;margin:0;}p{color:#64748b;}</style></head><body><div class="box"><h1>404</h1><p>Halaman tidak ditemukan.</p><a href="' . url('/') . '" style="color:#0F52BA;font-weight:bold;text-decoration:none;">← Kembali ke Beranda</a></div></body></html>';
}
