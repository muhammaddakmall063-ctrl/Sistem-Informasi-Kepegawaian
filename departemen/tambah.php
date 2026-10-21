<?php
require_once '../config/koneksi.php';
$page_title = 'Tambah Departemen';
$base = '../';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama_departemen'] ?? '');

    if ($nama === '') {
        $_SESSION['flash'] = ['type' => 'error', 'message' => 'Nama departemen wajib diisi.'];
    } else {
        $stmt = $pdo->prepare("INSERT INTO departemen (nama_departemen) VALUES (:nama)");
        $stmt->execute(['nama' => $nama]);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Departemen berhasil ditambahkan.'];
        header('Location: index.php');
        exit;
    }
}

require_once '../includes/header.php';
?>

<div class="card">
    <form class="form-box" method="post">
        <label>Nama Departemen</label>
        <input type="text" name="nama_departemen" value="<?= htmlspecialchars($_POST['nama_departemen'] ?? '') ?>" required autofocus>

        <div class="actions">
            <button class="btn btn-primary" type="submit">Simpan</button>
            <a class="btn btn-secondary" href="index.php">Batal</a>
        </div>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>
