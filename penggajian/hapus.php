<?php
require_once '../config/koneksi.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM penggajian WHERE id_gaji = :id");
    $stmt->execute(['id' => $id]);
    $_SESSION['flash'] = ['type' => 'success', 'message' => 'Data penggajian berhasil dihapus.'];
}

header('Location: index.php');
exit;
