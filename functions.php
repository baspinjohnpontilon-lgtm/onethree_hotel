<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/database.php';

function e($value): string {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}
function redirect(string $url): never {
    header("Location: {$url}");
    exit;
}
function logged_in(): bool {
    return isset($_SESSION['user']['id']);
}
function is_admin(): bool {
    return logged_in() && ($_SESSION['user']['role'] ?? '') === 'admin';
}
function require_login(): void {
    if (!logged_in()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'] ?? 'index.php';
        redirect('login.php');
    }
}
function require_admin(): void {
    if (!is_admin()) {
        redirect('../login.php');
    }
}
function nights_between(string $checkIn, string $checkOut): int {
    $a = new DateTime($checkIn);
    $b = new DateTime($checkOut);
    return max(1, (int)$a->diff($b)->days);
}
function room_is_available(PDO $pdo, int $roomId, string $checkIn, string $checkOut, ?int $ignoreReservation = null): bool {
    $sql = "SELECT COUNT(*) FROM reservations
            WHERE room_id = ? AND status IN ('pending','confirmed')
            AND check_in < ? AND check_out > ?";
    $params = [$roomId, $checkOut, $checkIn];
    if ($ignoreReservation !== null) {
        $sql .= " AND id <> ?";
        $params[] = $ignoreReservation;
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return (int)$stmt->fetchColumn() === 0;
}
