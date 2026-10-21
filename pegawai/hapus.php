<?php
require_once '../config/koneksi.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM pegawai WHERE id_pegawai = :id");
    $stmt->execute(['id' => $id]);
    $_SESSION['flash'] = ['type' => 'success', 'message' => 'Pegawai berhasil dihapus.'];
}

header('Location: index.php');
exit;
