<?php
$base_dir = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\');
define('BASE_URL', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . $base_dir);
defined('BASE_PATH') || define('BASE_PATH', dirname(__DIR__, 2));
define('UPLOAD_DIR', BASE_PATH . '/public/lampiran/');
define('APP_VERSION', '1.0.0');

function session_start_safe(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function session_get(string $key, $default = null)
{
    session_start_safe();
    return $_SESSION[$key] ?? $default;
}

function session_set(string $key, $value): void
{
    session_start_safe();
    $_SESSION[$key] = $value;
}

function session_flash(string $key, $value): void
{
    session_start_safe();
    $_SESSION['_flash'][$key] = $value;
}

function get_flash(string $key, $default = null)
{
    session_start_safe();
    $val = $_SESSION['_flash'][$key] ?? $default;
    unset($_SESSION['_flash'][$key]);
    return $val;
}

function has_flash(string $key): bool
{
    return isset($_SESSION['_flash'][$key]);
}


function auth(): ?array
{
    session_start_safe();
    return $_SESSION['user'] ?? null;
}

function is_logged_in(): bool
{
    return auth() !== null;
}

function require_auth(): void
{
    if (!is_logged_in()) {
        session_flash('intended', current_url());
        redirect('/login');
    }

    $user = auth();
    if ($user && isset($user['status']) && $user['status'] !== 'Setujui') {

        session_destroy();
        session_start_safe();
        session_flash('error', 'Akun Anda belum disetujui atau telah ditolak oleh Administrator.');
        redirect('/login');
    }
}

function require_guest(): void
{
    if (is_logged_in()) {
        redirect('/admin/dashboard');
    }
}

function user_id(): ?int
{
    return auth()['id'] ?? null;
}

function username(): ?string
{
    return auth()['username'] ?? null;
}


function redirect(string $url): never
{
    header("Location: " . BASE_URL . $url);
    exit;
}

function redirect_back(): never
{
    $ref = $_SERVER['HTTP_REFERER'] ?? '/';
    header("Location: $ref");
    exit;
}

function current_url(): string
{
    $path = $_SERVER['REQUEST_URI'] ?? '/';
    $base = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\');
    if ($base !== '' && strpos($path, $base) === 0) {
        $path = substr($path, strlen($base));
    }
    return $path ?: '/';
}

function url(string $path = ''): string
{
    return BASE_URL . '/' . ltrim($path, '/');
}

function asset(string $path): string
{
    return BASE_URL . '/public/' . ltrim($path, '/');
}

function method_is(string $method): bool
{
    return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET') === strtoupper($method);
}

function request_method(): string
{
    return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
}

function post(string $key, $default = ''): string
{
    return trim($_POST[$key] ?? $default);
}

function get_param(string $key, $default = ''): string
{
    return trim($_GET[$key] ?? $default);
}

function old(string $key, $default = ''): string
{
    session_start_safe();
    $val = $_SESSION['_old'][$key] ?? $default;
    return htmlspecialchars($val ?? '');
}

function flash_old(): void
{
    session_start_safe();
    $_SESSION['_old'] = $_POST;
}

function clear_old(): void
{
    session_start_safe();
    unset($_SESSION['_old']);
}


function csrf_token(): string
{
    session_start_safe();
    if (empty($_SESSION['_csrf_token'])) {
        $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf_token" value="' . csrf_token() . '">';
}

function csrf_verify(): bool
{
    session_start_safe();
    $token = $_POST['_csrf_token'] ?? '';
    return hash_equals($_SESSION['_csrf_token'] ?? '', $token);
}

function csrf_abort(): void
{
    if (!csrf_verify()) {
        http_response_code(419);
        die('<h1>419 - Token Mismatch</h1><p>Halaman kadaluarsa. <a href="javascript:history.back()">Kembali</a></p>');
    }
}


function validate(array $data, array $rules): array
{
    $errors = [];
    foreach ($rules as $field => $ruleStr) {
        $rulesArr = explode('|', $ruleStr);
        $value = $data[$field] ?? '';

        foreach ($rulesArr as $rule) {
            if ($rule === 'required') {
                if ($value === '' || $value === null) {
                    $errors[$field][] = ucfirst(str_replace('_', ' ', $field)) . ' wajib diisi.';
                }
            } elseif (str_starts_with($rule, 'max:')) {
                $max = (int) substr($rule, 4);
                if (strlen($value) > $max) {
                    $errors[$field][] = ucfirst(str_replace('_', ' ', $field)) . " tidak boleh lebih dari $max karakter.";
                }
            } elseif (str_starts_with($rule, 'min:')) {
                $min = (int) substr($rule, 4);
                if (strlen($value) < $min) {
                    $errors[$field][] = ucfirst(str_replace('_', ' ', $field)) . " minimal $min karakter.";
                }
            } elseif ($rule === 'string') {
                // Accept (no-op here)
            } elseif (str_starts_with($rule, 'in:')) {
                $options = explode(',', substr($rule, 3));
                if ($value !== '' && !in_array($value, $options)) {
                    $errors[$field][] = ucfirst(str_replace('_', ' ', $field)) . ' tidak valid.';
                }
            } elseif ($rule === 'confirmed') {
                $confirm = $data[$field . '_confirmation'] ?? '';
                if ($value !== $confirm) {
                    $errors[$field][] = ucfirst(str_replace('_', ' ', $field)) . ' tidak cocok.';
                }
            } elseif (str_starts_with($rule, 'unique:')) {

                $parts = explode(',', substr($rule, 7));
                $table = $parts[0];
                $column = $parts[1] ?? $field;
                $exceptId = $parts[2] ?? null;
                $exceptColumn = $parts[3] ?? 'id';
                if ($value !== '') {
                    $sql = "SELECT COUNT(*) FROM `$table` WHERE `$column` = ?";
                    $params = [$value];
                    if ($exceptId !== null) {
                        $sql .= " AND `$exceptColumn` != ?";
                        $params[] = $exceptId;
                    }
                    $stmt = db()->prepare($sql);
                    $stmt->execute($params);
                    if ($stmt->fetchColumn() > 0) {
                        $errors[$field][] = ucfirst(str_replace('_', ' ', $field)) . ' sudah digunakan.';
                    }
                }
            } elseif (str_starts_with($rule, 'exists:')) {
                $parts = explode(',', substr($rule, 7));
                $table = $parts[0];
                $column = $parts[1] ?? $field;
                if ($value !== '') {
                    $stmt = db()->prepare("SELECT COUNT(*) FROM `$table` WHERE `$column` = ?");
                    $stmt->execute([$value]);
                    if ($stmt->fetchColumn() == 0) {
                        $errors[$field][] = ucfirst(str_replace('_', ' ', $field)) . ' tidak ditemukan.';
                    }
                }
            } elseif ($rule === 'nullable') {

                if ($value === '' || $value === null)
                    break;
            } elseif ($rule === 'current_password') {
                $user = auth();
                if ($user && !password_verify($value, $user['password'])) {
                    $errors[$field][] = 'Password saat ini tidak sesuai.';
                }
            }
        }
    }
    return $errors;
}

function validation_errors(): array
{
    session_start_safe();
    $errors = $_SESSION['_errors'] ?? [];
    unset($_SESSION['_errors']);
    return $errors;
}

function has_error(string $field, array $errors): bool
{
    return !empty($errors[$field]);
}

function error_msg(string $field, array $errors): string
{
    if (!empty($errors[$field])) {
        return '<span class="text-red-500 text-xs font-medium">' . implode(' ', $errors[$field]) . '</span>';
    }
    return '';
}

function flash_errors(array $errors): void
{
    session_start_safe();
    $_SESSION['_errors'] = $errors;
}


function compress_and_store_image(array $file): ?string
{
    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0777, true);
    }

    $imageData = getimagesize($file['tmp_name']);
    if (!$imageData) {

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = time() . '_' . uniqid() . '.' . $ext;
        move_uploaded_file($file['tmp_name'], UPLOAD_DIR . $filename);
        return 'lampiran/' . $filename;
    }

    [$width, $height, $type] = $imageData;
    $max_width = 1024;

    if ($width > $max_width) {
        $new_width = $max_width;
        $new_height = (int) round($height * $max_width / $width);
    } else {
        $new_width = $width;
        $new_height = $height;
    }

    $ext = match ($type) {
        IMAGETYPE_JPEG => 'jpg',
        IMAGETYPE_PNG => 'png',
        IMAGETYPE_WEBP => 'webp',
        IMAGETYPE_GIF => 'gif',
        default => pathinfo($file['name'], PATHINFO_EXTENSION),
    };

    $filename = time() . '_' . uniqid() . '.' . $ext;
    $path = UPLOAD_DIR . $filename;

    $image_p = imagecreatetruecolor($new_width, $new_height);

    switch ($type) {
        case IMAGETYPE_JPEG:
            $image = imagecreatefromjpeg($file['tmp_name']);
            imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            imagejpeg($image_p, $path, 75);
            imagedestroy($image);
            break;
        case IMAGETYPE_PNG:
            $image = imagecreatefrompng($file['tmp_name']);
            imagealphablending($image_p, false);
            imagesavealpha($image_p, true);
            imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            imagepng($image_p, $path, 6);
            imagedestroy($image);
            break;
        case IMAGETYPE_WEBP:
            if (function_exists('imagecreatefromwebp')) {
                $image = imagecreatefromwebp($file['tmp_name']);
                imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                imagewebp($image_p, $path, 75);
                imagedestroy($image);
            } else {
                imagedestroy($image_p);
                $filename = time() . '_' . uniqid() . '.webp';
                move_uploaded_file($file['tmp_name'], UPLOAD_DIR . $filename);
                return 'lampiran/' . $filename;
            }
            break;
        default:
            imagedestroy($image_p);
            move_uploaded_file($file['tmp_name'], $path);
            return 'lampiran/' . $filename;
    }

    imagedestroy($image_p);
    return 'lampiran/' . $filename;
}

function format_date(string $datetime, string $format = 'd M Y'): string
{
    return date($format, strtotime($datetime));
}

function format_datetime(string $datetime): string
{
    return date('d M Y - H:i', strtotime($datetime));
}

function diff_for_humans(string $datetime): string
{
    $now = time();
    $then = strtotime($datetime);
    $diff = $now - $then;

    if ($diff < 60)
        return 'baru saja';
    if ($diff < 3600)
        return floor($diff / 60) . ' menit lalu';
    if ($diff < 86400)
        return floor($diff / 3600) . ' jam lalu';
    if ($diff < 2592000)
        return floor($diff / 86400) . ' hari lalu';
    return date('d M Y', $then);
}

function paginate(string $sql, array $params, int $perPage = 10, string $pageParam = 'page'): array
{
    $page = max(1, (int) get_param($pageParam, '1'));
    $offset = ($page - 1) * $perPage;

    $countSql = preg_replace('/SELECT .+? FROM /si', 'SELECT COUNT(*) FROM ', $sql, 1);

    $countSql = preg_replace('/\s+ORDER BY .+$/si', '', $countSql);
    $stmtCount = db()->prepare($countSql);
    $stmtCount->execute($params);
    $total = (int) $stmtCount->fetchColumn();


    $stmtData = db()->prepare($sql . " LIMIT $perPage OFFSET $offset");
    $stmtData->execute($params);
    $data = $stmtData->fetchAll();

    $lastPage = (int) ceil($total / $perPage);

    return [
        'data' => $data,
        'total' => $total,
        'per_page' => $perPage,
        'current_page' => $page,
        'last_page' => max(1, $lastPage),
        'from' => $total > 0 ? $offset + 1 : 0,
        'to' => min($total, $offset + $perPage),
    ];
}

function render_pagination(array $paginator, array $extra_query = []): string
{
    if ($paginator['last_page'] <= 1)
        return '';

    $html = '<div class="flex items-center justify-center gap-1 flex-wrap">';
    $current = $paginator['current_page'];
    $last = $paginator['last_page'];
    $extra = http_build_query($extra_query);
    global $request_uri;
    $path = url($request_uri ?? '');

    for ($i = 1; $i <= $last; $i++) {
        $q = $extra ? "?page=$i&$extra" : "?page=$i";
        $q = rtrim($q, '&');
        $act = $i === $current ? 'bg-smk-blue text-white' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700';
        $html .= '<a href="' . htmlspecialchars($path . $q) . '" class="px-3 py-1.5 rounded-lg text-sm font-bold border border-slate-200 dark:border-slate-700 transition-colors ' . $act . '">' . $i . '</a>';
    }

    $html .= '</div>';
    return $html;
}


function hash_password(string $password): string
{
    return password_hash($password, PASSWORD_BCRYPT);
}

function verify_password(string $password, string $hash): bool
{
    return password_verify($password, $hash);
}


function str_limit(string $str, int $limit = 100): string
{
    if (mb_strlen($str) <= $limit)
        return $str;
    return mb_substr($str, 0, $limit) . '...';
}

function e(?string $str): string
{
    return htmlspecialchars((string) ($str ?? ''), ENT_QUOTES, 'UTF-8');
}


function rate_limit(string $key, int $maxAttempts, int $decaySeconds): bool
{
    $dir = sys_get_temp_dir();
    $file = $dir . '/rl_' . md5($key) . '.json';
    $now = time();
    $data = [];

    if (file_exists($file)) {
        $data = json_decode(file_get_contents($file), true) ?? [];
    }


    $data = array_filter($data, fn($t) => $t > $now - $decaySeconds);

    if (count($data) >= $maxAttempts) {
        return false;
    }

    $data[] = $now;
    file_put_contents($file, json_encode(array_values($data)));
    return true;
}

function auth_siswa(): ?array {
    session_start_safe();
    return $_SESSION['siswa'] ?? null;
}

function is_siswa_logged_in(): bool {
    return auth_siswa() !== null;
}

function require_siswa(): void {
    if (!is_siswa_logged_in()) {
        session_flash('intended', current_url());
        redirect('/siswa/login');
    }
}

function require_siswa_guest(): void {
    if (is_siswa_logged_in()) {
        redirect('/');
    }
}
