<?php
require_once '../config/koneksi.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM departemen WHERE id_departemen = :id");
        $stmt->execute(['id' => $id]);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Departemen berhasil dihapus.'];
    } catch (PDOException $e) {
        $_SESSION['flash'] = ['type' => 'error', 'message' => 'Tidak bisa menghapus, data masih dipakai oleh pegawai lain.'];
    }
}

header('Location: index.php');
exit;
