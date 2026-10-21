<?php
require_once '../config/koneksi.php';
$page_title = 'Tambah Jabatan';
$base = '../';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama_jabatan'] ?? '');
    $gaji = $_POST['gaji_pokok'] ?? '';

    if ($nama === '' || $gaji === '' || !is_numeric($gaji)) {
        $_SESSION['flash'] = ['type' => 'error', 'message' => 'Nama jabatan dan gaji pokok wajib diisi dengan benar.'];
    } else {
        $stmt = $pdo->prepare("INSERT INTO jabatan (nama_jabatan, gaji_pokok) VALUES (:nama, :gaji)");
        $stmt->execute(['nama' => $nama, 'gaji' => $gaji]);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Jabatan berhasil ditambahkan.'];
        header('Location: index.php');
        exit;
    }
}

require_once '../includes/header.php';
?>

<div class="card">
    <form class="form-box" method="post">
        <label>Nama Jabatan</label>
        <input type="text" name="nama_jabatan" value="<?= htmlspecialchars($_POST['nama_jabatan'] ?? '') ?>" required autofocus>

        <label>Gaji Pokok (Rp)</label>
        <input type="number" step="0.01" name="gaji_pokok" value="<?= htmlspecialchars($_POST['gaji_pokok'] ?? '') ?>" required>

        <div class="actions">
            <button class="btn btn-primary" type="submit">Simpan</button>
            <a class="btn btn-secondary" href="index.php">Batal</a>
        </div>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>
